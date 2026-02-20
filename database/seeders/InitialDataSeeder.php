<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Branches
        $cabang1 = \App\Models\Cabang::create([
            'nama' => 'Cabang Sudirman',
            'alamat' => 'Jl. Sudirman No. 123, Jakarta'
        ]);

        $cabang2 = \App\Models\Cabang::create([
            'nama' => 'Cabang Thamrin',
            'alamat' => 'Jl. Thamrin No. 45, Jakarta'
        ]);

        // Products
        \App\Models\MasterProduk::create([
            'kode_produk' => 'ROTI001',
            'nama_produk' => 'Roti Tawar Kebanggaan',
            'satuan' => 'Pcs'
        ]);

        \App\Models\MasterProduk::create([
            'kode_produk' => 'ROTI002',
            'nama_produk' => 'Roti Coklat Lumer',
            'satuan' => 'Pcs'
        ]);

        \App\Models\MasterProduk::create([
            'kode_produk' => 'ROTI003',
            'nama_produk' => 'Roti Keju Spesial',
            'satuan' => 'Pcs'
        ]);

        // Users
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@rotikebanggaan.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'superadmin',
        ]);

        \App\Models\User::create([
            'name' => 'Admin Gudang',
            'email' => 'gudang@rotikebanggaan.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'warehouse_admin',
        ]);

        \App\Models\User::create([
            'name' => 'Admin Cabang Sudirman',
            'email' => 'sudirman@rotikebanggaan.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'branch_admin',
            'cabang_id' => $cabang1->id
        ]);

        \App\Models\User::create([
            'name' => 'Admin Cabang Thamrin',
            'email' => 'thamrin@rotikebanggaan.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'branch_admin',
            'cabang_id' => $cabang2->id
        ]);
    }
}
