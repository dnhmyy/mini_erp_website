<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Cabang;
use App\Models\MasterProduk;
use App\Models\User;
use App\Models\MasterDriver;
use App\Models\Permintaan;
use App\Models\PermintaanDetail;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CABANGS (Historical)
        $cabangs = [
            'Downtown'        => 'Jl. Ruko Downtown',
            'Sorrento'        => 'Jl. Ruko Sorrento',
            'Beryl'           => 'Jl. Ruko Beryl',
            'Greenlake'       => 'Jl. Ruko Greenlake',
            'Kelapa Gading'   => 'GAFOY',
            'Grand Indonesia' => 'Jl. Grand Indonesia',
            'Dapur Solvang'   => 'Solvang Dapur Area',
            'Pastry Solvang'  => 'Solvang Pastry Area',
        ];

        foreach ($cabangs as $nama => $alamat) {
            Cabang::updateOrCreate(['nama' => $nama], ['alamat' => $alamat]);
        }

        // 2. MASTER_DRIVERS (Historical)
        $drivers = ['Jojo', 'Iki', 'Rendi'];
        foreach ($drivers as $driver) {
            MasterDriver::firstOrCreate(['nama' => $driver]);
        }

        // 3. MASTER_PRODUKS (Historical)
        $produks = [
            ['kode' => 'SR0002', 'nama' => 'SR - CABE RAWIT', 'satuan' => 'BKS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'CK0003', 'nama' => 'LAPIS LEGIT', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'SG00016', 'nama' => 'SG - TISSU TOWEL', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'BB0006', 'nama' => 'BB - TELUR AYAM', 'satuan' => 'IKAT', 'kat' => 'BB', 'role' => 'staff_produksi'],
            ['kode' => 'IN0001', 'nama' => 'IN - BA', 'satuan' => 'KLNG', 'kat' => 'ISIAN', 'role' => 'staff_produksi'],
            ['kode' => 'SN0009', 'nama' => 'SN - RINSO BUBUK', 'satuan' => 'BKS', 'kat' => 'GA', 'role' => 'staff_produksi'],
        ];

        foreach ($produks as $p) {
            $masterProduk = MasterProduk::updateOrCreate(
                ['kode_produk' => $p['kode']],
                [
                    'nama_produk' => $p['nama'],
                    'satuan'      => $p['satuan'],
                    'kategori'    => $p['kat'],
                    'target_role' => $p['role']
                ]
            );

            // Mapping to all branches (Historical relation)
            $allCabangIds = Cabang::pluck('id')->toArray();
            $masterProduk->cabangs()->sync($allCabangIds);
        }

        // 4. USERS (Historical)
        $users = [
            [
                'email' => 'dev',
                'name'  => 'Akhdan IT',
                'role'  => 'superuser',
                'cabang'=> null
            ],
            [
                'email' => 'icha',
                'name'  => 'Icha',
                'role'  => 'staff_gudang',
                'cabang'=> null
            ],
            [
                'email' => 'kalyca',
                'name'  => 'Kalyca',
                'role'  => 'staff_admin',
                'cabang'=> 'Downtown'
            ],
            [
                'email' => 'ziyad',
                'name'  => 'Ziyad',
                'role'  => 'staff_produksi',
                'cabang'=> 'Sorrento'
            ],
            [
                'email' => 'dapur',
                'name'  => 'Staff Dapur',
                'role'  => 'staff_dapur',
                'cabang'=> 'Dapur Solvang'
            ],
            [
                'email' => 'pastry',
                'name'  => 'Staff Pastry',
                'role'  => 'staff_pastry',
                'cabang'=> 'Pastry Solvang'
            ],
        ];

        foreach ($users as $u) {
            $cabangId = null;
            if ($u['cabang']) {
                $c = Cabang::where('nama', $u['cabang'])->first();
                $cabangId = $c ? $c->id : null;
            }

            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'      => $u['name'],
                    'password'  => Hash::make('password'),
                    'role'      => $u['role'],
                    'cabang_id' => $cabangId
                ]
            );
        }

        // 5. PERMINTAANS (Historical)
        // Extracting IDs carefully
        $dev = User::where('email', 'dev')->first();
        $beryl = Cabang::where('nama', 'Beryl')->first();

        if ($dev && $beryl) {
            // REQ-BB-20260220-001
            $req1 = Permintaan::updateOrCreate(
                ['nomor_permintaan' => 'REQ-BB-20260220-001'],
                [
                    'user_id'   => $dev->id,
                    'cabang_id' => $beryl->id,
                    'status'    => 'pending',
                    'kategori'  => 'BB',
                    'tanggal'   => '2026-02-20 10:04:38'
                ]
            );
            $p4 = MasterProduk::where('kode_produk', 'SR0002')->first();
            if ($req1 && $p4) {
                PermintaanDetail::updateOrCreate(
                    ['permintaan_id' => $req1->id, 'produk_id' => $p4->id],
                    ['jumlah' => 2]
                );
            }

            // REQ-ISIAN-20260220-001
            $req2 = Permintaan::updateOrCreate(
                ['nomor_permintaan' => 'REQ-ISIAN-20260220-001'],
                [
                    'user_id'   => $dev->id,
                    'cabang_id' => $beryl->id,
                    'status'    => 'pending',
                    'kategori'  => 'ISIAN',
                    'tanggal'   => '2026-02-20 10:04:48'
                ]
            );
            $p5 = MasterProduk::where('kode_produk', 'CK0003')->first();
            if ($req2 && $p5) {
                PermintaanDetail::updateOrCreate(
                    ['permintaan_id' => $req2->id, 'produk_id' => $p5->id],
                    ['jumlah' => 2]
                );
            }

            // REQ-GA-20260220-001 (received)
            $req3 = Permintaan::updateOrCreate(
                ['nomor_permintaan' => 'REQ-GA-20260220-001'],
                [
                    'user_id'   => $dev->id,
                    'cabang_id' => $beryl->id,
                    'status'    => 'received',
                    'kategori'  => 'GA',
                    'tanggal'   => '2026-02-20 10:05:04',
                    'driver'    => 'Jojo'
                ]
            );
            $p6 = MasterProduk::where('kode_produk', 'SG00016')->first();
            if ($req3 && $p6) {
                PermintaanDetail::updateOrCreate(
                    ['permintaan_id' => $req3->id, 'produk_id' => $p6->id],
                    ['jumlah' => 2]
                );
            }
        }
    }
}
