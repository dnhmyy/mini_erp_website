<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCatalog;

class ImportKatalogCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-katalog-csv {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Data Katalog Barang dari CSV (Upsert / Sinkronisasi)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file') ?? 'app/Console/Commands/file_csv/katalog_barang.csv';
        $path = base_path($file);

        if (!file_exists($path)) {
            $this->error("File CSV tidak ditemukan: {$path}");
            return;
        }

        $this->info("Memulai proses sinkronisasi katalog dari: {$file}");

        // Read CSV
        $rows = array_map('str_getcsv', file($path));
        
        if (empty($rows)) {
            $this->error("File CSV kosong.");
            return;
        }

        $header = array_shift($rows);
        if (empty($header) || count($header) < 3) {
            $this->error("Format header CSV tidak valid. Harus ada Name, ProductCode, Unit.");
            return;
        }

        // Clean headers
        $header = array_map(function($h) {
            return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $h));
        }, $header);

        // Find index
        $nameIndex = array_search('name', $header);
        $codeIndex = array_search('productcode', $header);
        $unitIndex = array_search('unit', $header);

        if ($nameIndex === false || $unitIndex === false) {
            $this->error("Kolom 'Name' dan 'Unit' wajib ada di CSV.");
            return;
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows

                $nama = trim($row[$nameIndex] ?? '');
                $satuan = isset($row[$unitIndex]) ? trim($row[$unitIndex]) : 'PCS'; // Default PCS
                $kode = ($codeIndex !== false && isset($row[$codeIndex])) ? trim($row[$codeIndex]) : '';

                if (empty($nama)) continue;

                // Ensure Satuan is uppercase
                $satuan = strtoupper($satuan);
                if (empty($satuan)) $satuan = 'PCS';

                // Check by Name first to avoid duplicate names
                $existingByName = ProductCatalog::where('nama', $nama)->first();

                if ($existingByName) {
                    // Update existing
                    $updates = ['satuan' => $satuan];
                    if (!empty($kode) && $existingByName->kode !== $kode) {
                        // Cek apakah kode baru bentrok dengan produk lain
                        $collision = ProductCatalog::where('kode', $kode)->where('id', '!=', $existingByName->id)->first();
                        if (!$collision) {
                            $updates['kode'] = $kode;
                        }
                    }
                    $existingByName->update($updates);
                    $updatedCount++;
                } else {
                    // Cek by Kode
                    if (!empty($kode)) {
                        $existingByKode = ProductCatalog::where('kode', $kode)->first();
                        if ($existingByKode) {
                            // Code exist but name is different, we just update the name and unit
                            $existingByKode->update([
                                'nama' => $nama,
                                'satuan' => $satuan
                            ]);
                            $updatedCount++;
                        } else {
                            // Insert
                            ProductCatalog::create([
                                'kode' => $kode,
                                'nama' => $nama,
                                'satuan' => $satuan
                            ]);
                            $insertedCount++;
                        }
                    } else {
                        // Generate Kode
                        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nama), 0, 4));
                        $newKode = 'AU-' . $prefix . rand(1000, 9999);
                        
                        ProductCatalog::create([
                            'kode' => $newKode,
                            'nama' => $nama,
                            'satuan' => $satuan
                        ]);
                        $insertedCount++;
                    }
                }
            }
            DB::commit();

            $this->info("Sinkronisasi Katalog Berhasil!");
            $this->line("- Produk Ditambah : {$insertedCount}");
            $this->line("- Produk Diupdate : {$updatedCount}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal sinkronisasi data: " . $e->getMessage());
        }
    }
}
