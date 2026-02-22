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
    protected $signature = 'app:import-produk-csv {file : Path ke file CSV} {kategori : BB, ISIAN, atau GA} {roles* : Target roles (staff_admin, staff_produksi, dll)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Master Produk dari file CSV secara cepat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $kategori = strtoupper($this->argument('kategori'));
        $roles = $this->argument('roles');

        if (!in_array($kategori, ['BB', 'ISIAN', 'GA'])) {
            $this->error("Kategori tidak valid! Gunakan: BB, ISIAN, atau GA.");
            return 1;
        }

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $handle = fopen($filePath, "r");
        $header = fgetcsv($handle, 1000, ","); // Ambil header

        // Pastikan kolom Minimal: kode, nama, satuan
        $expected = ['kode', 'nama', 'satuan'];
        foreach ($expected as $col) {
            if (!in_array($col, $header)) {
                $this->error("CSV harus memiliki kolom: " . implode(', ', $expected));
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
                
                $produk = MasterProduk::where('kode_produk', $row['kode'])->first();

                if ($produk) {
                    // Update existing: MERGE roles
                    $existingRoles = is_array($produk->target_role) ? $produk->target_role : [];
                    $newRoles = array_unique(array_merge($existingRoles, $roles));
                    
                    $produk->update([
                        'nama_produk' => $row['nama'],
                        'satuan'      => $row['satuan'],
                        'kategori'    => $kategori,
                        'target_role' => $newRoles,
                    ]);
                } else {
                    // Create new
                    MasterProduk::create([
                        'kode_produk' => $row['kode'],
                        'nama_produk' => $row['nama'],
                        'satuan'      => $row['satuan'],
                        'kategori'    => $kategori,
                        'target_role' => $roles,
                    ]);
                }
                $count++;
            }
            DB::commit();
            $this->info("Berhasil mengproses {$count} produk ke kategori {$kategori}.");
            $this->info("Target Role (Baru/Update): " . implode(', ', $roles));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Terjadi kesalahan pada baris {$count}: " . $e->getMessage());
        }

        fclose($handle);
    }
}
