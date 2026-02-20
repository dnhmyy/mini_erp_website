<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Cabang;
use App\Models\MasterProduk;
use App\Models\User;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Branches
        $cabang1 = Cabang::firstOrCreate(
            ['nama' => 'Cabang Sudirman'],
            ['alamat' => 'Jl. Sudirman No. 123, Jakarta']
        );

        $cabang2 = Cabang::firstOrCreate(
            ['nama' => 'Cabang Thamrin'],
            ['alamat' => 'Jl. Thamrin No. 45, Jakarta']
        );

        $cabang3 = Cabang::firstOrCreate(
            ['nama' => 'Dapur Solvang'],
            ['alamat' => '']
        );

        $cabang4 = Cabang::firstOrCreate(
            ['nama' => 'Pastry Solvang'],
            ['alamat' => '']
        );

        // Products
        MasterProduk::firstOrCreate(
            ['kode_produk' => 'ROTI001'],
            ['nama_produk' => 'Roti Tawar Kebanggaan', 'satuan' => 'Pcs']
        );

        MasterProduk::firstOrCreate(
            ['kode_produk' => 'ROTI002'],
            ['nama_produk' => 'Roti Coklat Lumer', 'satuan' => 'Pcs']
        );

        MasterProduk::firstOrCreate(
            ['kode_produk' => 'ROTI003'],
            ['nama_produk' => 'Roti Keju Spesial', 'satuan' => 'Pcs']
        );

        // Users
        User::firstOrCreate(
            ['email' => 'admin@rotikebanggaan.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'role'     => 'superuser',
            ]
        );

        User::firstOrCreate(
            ['email' => 'gudang@rotikebanggaan.com'],
            [
                'name'     => 'Admin Gudang',
                'password' => Hash::make('password'),
                'role'     => 'staff_gudang',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sudirman@rotikebanggaan.com'],
            [
                'name'      => 'Admin Cabang Sudirman',
                'password'  => Hash::make('password'),
                'role'      => 'staff_admin',
                'cabang_id' => $cabang1->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'thamrin@rotikebanggaan.com'],
            [
                'name'      => 'Admin Cabang Thamrin',
                'password'  => Hash::make('password'),
                'role'      => 'staff_admin',
                'cabang_id' => $cabang2->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'produksi@rotikebanggaan.com'],
            [
                'name'     => 'Staff Produksi',
                'password' => Hash::make('password'),
                'role'     => 'staff_produksi',
            ]
        );
    }
}
