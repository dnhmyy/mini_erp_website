<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $file = base_path('file_csv/Permintaan BRG - Outlet Penj.DOWNTOWN - Master.csv');
        
        if (!file_exists($file)) {
            $this->command->error("File tidak ditemukan: $file");
            return;
        }

        $handle = fopen($file, 'r');
        fgetcsv($handle); // Skip header

        $count = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 3) continue;

                ProductCatalog::updateOrCreate(
                    ['kode' => trim($data[1])],
                    [
                        'nama' => trim($data[0]),
                        'satuan' => trim($data[2]),
                    ]
                );
                $count++;
            }
            DB::commit();
            $this->command->info("ProductCatalogSeeder: Berhasil mengimpor $count produk ke katalog.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error: " . $e->getMessage());
        }
        fclose($handle);
    }
}
