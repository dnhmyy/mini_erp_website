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
    protected $signature = 'app:import-produk-csv {file : Path ke file CSV} {kategori? : BB, ISIAN, atau GA (Opsional jika ada di nama file)} {roles* : Target roles (Opsional jika ada di nama file)}';

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
        $parts = explode('_', strtolower($filename));
        $possibleCategories = ['bb', 'isian', 'ga'];
        $possibleRoles = ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry', 'mixing', 'all'];

        foreach ($parts as $part) {
            if (in_array($part, $possibleCategories)) {
                $inferredKategori = strtoupper($part);
            } elseif (in_array($part, $possibleRoles)) {
                $inferredRoles[] = $part;
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
        $header = fgetcsv($handle, 1000, ","); // Ambil header

        // Pastikan kolom Minimal: kode, nama, satuan.
        // Jika Kategori TIDAK ada di nama file, maka WAJIB ada kolom 'kategori' di CSV.
        $requiredCols = ['kode', 'nama', 'satuan'];
        if (!$finalKategori) {
            $requiredCols[] = 'kategori';
        }

        foreach ($requiredCols as $col) {
            if (!in_array($col, $header)) {
                $this->error("CSV salah! Header harus memiliki kolom: " . implode(', ', $requiredCols));
                $this->info("Tips: Jika kategori tidak tertulis di nama file, buat kolom 'kategori' di dalam CSV.");
                return 1;
            }
        }

        $count = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip if empty row
                if (empty($data[0])) continue;

                $row = array_combine($header, $data);
                
                // Prioritas Kategori: Kolom CSV > Argumen/Nama File
                $rowKategori = isset($row['kategori']) ? strtoupper($row['kategori']) : $finalKategori;

                if (!$rowKategori) {
                    throw new \Exception("Kategori tidak ditemukan untuk baris: " . ($count + 1) . ". Pastikan ada di Nama File atau Kolom CSV.");
                }

                $produk = MasterProduk::where('kode_produk', $row['kode'])->first();

                if ($produk) {
                    // Update existing: MERGE roles
                    $existingRoles = is_array($produk->target_role) ? $produk->target_role : [];
                    $mergedRoles = array_unique(array_merge($existingRoles, $finalRoles));
                    
                    $produk->update([
                        'nama_produk' => $row['nama'],
                        'satuan'      => $row['satuan'],
                        'kategori'    => $rowKategori,
                        'target_role' => $mergedRoles,
                    ]);
                } else {
                    // Create new
                    MasterProduk::create([
                        'kode_produk' => $row['kode'],
                        'nama_produk' => $row['nama'],
                        'satuan'      => $row['satuan'],
                        'kategori'    => $rowKategori,
                        'target_role' => $finalRoles,
                    ]);
                }
                $count++;
            }
            DB::commit();
            $this->info("Berhasil memproses {$count} produk.");
            $this->info("Kategori: " . ($finalKategori ?: 'Variatif (cek kolom CSV)'));
            $this->info("Target Role (Baru/Update): " . implode(', ', $finalRoles));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Terjadi kesalahan pada baris {$count}: " . $e->getMessage());
        }

        fclose($handle);
    }
}
