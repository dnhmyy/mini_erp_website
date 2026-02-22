<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterProduk;
use Illuminate\Support\Facades\DB;

class ImportProdukCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-produk-csv {file : Path ke file CSV} {kategori? : BB, ISIAN, atau GA (Opsional jika ada di nama file)} {roles?* : Target roles (Opsional jika ada di nama file)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Master Produk dari file CSV (Auto-detect Kategori & Role dari nama file)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $filename = pathinfo($filePath, PATHINFO_FILENAME); // misal: GA_staff_admin
        
        // 1. Ambil Kategori (prioritas: Argumen > Nama File)
        $kategoriArg = $this->argument('kategori');
        $rolesArg = $this->argument('roles');

        $inferredKategori = null;
        $inferredRoles = [];

        // Parsing Nama File (Contoh: GA_staff_admin_staff_produksi.csv)
        $filenameLower = strtolower($filename);
        $possibleCategories = ['bb', 'isian', 'ga'];
        $possibleRoles = ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry', 'mixing', 'all'];

        foreach ($possibleCategories as $cat) {
            if (str_contains($filenameLower, $cat)) {
                $inferredKategori = strtoupper($cat);
            }
        }

        foreach ($possibleRoles as $role) {
            if (str_contains($filenameLower, $role)) {
                $inferredRoles[] = $role;
            }
        }

        $finalKategori = $kategoriArg ?: $inferredKategori;
        $finalRoles = !empty($rolesArg) ? $rolesArg : $inferredRoles;

        if (!$finalKategori && !empty($finalKategori)) {
            // Kita akan cek nanti di dalam loop apakah ada kolom kategori
        }

        if (empty($finalRoles)) {
            $this->error("Role tidak ditemukan di argumen maupun nama file!");
            $this->info("Contoh nama file: GA_staff_admin.csv atau BB_staff_produksi_mixing.csv");
            return 1;
        }

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $handle = fopen($filePath, "r");
        $firstRow = fgetcsv($handle, 1000, ","); 
        
        // Cek apakah ini mode "Vertical List" (Spreadsheet style)
        // Ciri: Header Isian, BB, atau GA ada di baris pertama
        $isVerticalMode = false;
        $columnMap = []; // index -> kategori
        foreach ($firstRow as $idx => $val) {
            $cleanVal = strtoupper(trim($val));
            if (in_array($cleanVal, ['ISIAN', 'BB', 'GA'])) {
                $isVerticalMode = true;
                $columnMap[$idx] = $cleanVal;
            }
        }

        if ($isVerticalMode) {
            $this->info("Mode List Vertikal Terdeteksi...");
            $handle = fopen($filePath, "r");
            fgetcsv($handle); // Skip header baris 1
        } else {
            // Mode CSV Standar (maju ke baris data jika ada header)
            $header = $firstRow;
            $requiredCols = ['nama'];
            if (!$finalKategori) { $requiredCols[] = 'kategori'; }
            foreach ($requiredCols as $col) {
                if (!in_array($col, $header)) {
                    $this->error("CSV salah! Header harus memiliki kolom: " . implode(', ', $requiredCols));
                    return 1;
                }
            }
        }

        $count = 0;
        $skipped = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $itemsToProcess = [];

                if ($isVerticalMode) {
                    // Ambil setiap kolom yang terpetai
                    foreach ($columnMap as $idx => $kat) {
                        if (!empty(trim($data[$idx] ?? ''))) {
                            $itemsToProcess[] = [
                                'nama' => trim($data[$idx]),
                                'kategori' => $kat
                            ];
                        }
                    }
                } else {
                    $row = array_combine($header, $data);
                    $itemsToProcess[] = [
                        'nama' => trim($row['nama']),
                        'kategori' => isset($row['kategori']) ? strtoupper($row['kategori']) : $finalKategori
                    ];
                }

                foreach ($itemsToProcess as $item) {
                    $rawName = $item['nama'];
                    $kat = $item['kategori'];

                    // CLEANING NAMA: Lepas prefix kode (misal "AT - ", "PG - ")
                    // Biasanya polanya adalah [TEXT] - [NAMA]
                    $cleanName = $rawName;
                    if (preg_match('/^[A-Z]{2,3}\s?-\s?(.*)$/', $rawName, $matches)) {
                        $cleanName = trim($matches[1]);
                    }

                    // Cari di KATALOG (Coba nama asli dulu, lalu nama bersih)
                    $catalogItem = \App\Models\ProductCatalog::where('nama', $rawName)
                                    ->orWhere('nama', $cleanName)
                                    ->first();

                    if (!$catalogItem) {
                        $this->warn("Produk '{$rawName}' tidak ditemukan di Katalog. Dilewati.");
                        $skipped++;
                        continue;
                    }

                    $produk = MasterProduk::where('kode_produk', $catalogItem->kode)->first();

                    if ($produk) {
                        $existingRoles = is_array($produk->target_role) ? $produk->target_role : [];
                        $mergedRoles = array_unique(array_merge($existingRoles, $finalRoles));
                        
                        $produk->update([
                            'nama_produk' => $catalogItem->nama,
                            'satuan'      => $catalogItem->satuan,
                            'kategori'    => $kat,
                            'target_role' => $mergedRoles,
                        ]);
                    } else {
                        MasterProduk::create([
                            'kode_produk' => $catalogItem->kode,
                            'nama_produk' => $catalogItem->nama,
                            'satuan'      => $catalogItem->satuan,
                            'kategori'    => $kat,
                            'target_role' => $finalRoles,
                        ]);
                    }
                    $count++;
                }
            }
            DB::commit();
            $this->info("BERHASIL! Memproses {$count} produk.");
            if ($skipped > 0) {
                $this->warn("{$skipped} produk dilewati karena tidak ada di Katalog.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("ERR: " . $e->getMessage());
        }

        fclose($handle);
    }
}
