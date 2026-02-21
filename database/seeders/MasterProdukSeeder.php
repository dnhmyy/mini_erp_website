<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterProduk;
use App\Models\Cabang;

class MasterProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Data hasil ekstraksi dari file CSV Master dan List
        $data = [
            ['kode' => 'CK0002', 'nama' => 'LAPIS LEGIT (1/2 LOYANG)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'KS0003', 'nama' => 'KS - THERMAL 57 X 40', 'satuan' => 'ROLL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0001', 'nama' => 'AT - AKRILIK HITAM MENU & HARGA', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0001', 'nama' => 'LAPIS LEGIT (1LOYANG)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'KS0004', 'nama' => 'KS - THERMAL 80 X 80', 'satuan' => 'ROLL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0002', 'nama' => 'AT - AKRILIK KOTAK KARTU UCAPAN', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0003', 'nama' => 'LAPIS LEGIT (PCS)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0001', 'nama' => 'PG - BOTOL KOPI', 'satuan' => 'BTL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0003', 'nama' => 'AT - AKRILIK KULKAS', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0005', 'nama' => 'LAPIS SURABAYA (1/2 LOYANG)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0002', 'nama' => 'PG - BOX L', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0005', 'nama' => 'AT - ALAT PEMBERSIH KACA IKEA', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0004', 'nama' => 'LAPIS SURABAYA (1LOYANG)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0003', 'nama' => 'PG - BOX XL', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0009', 'nama' => 'AT - BANGKU PLASTIK', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0006', 'nama' => 'LAPIS SURABAYA (PCS)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0004', 'nama' => 'PG - BOX XL (EVENT)', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0013', 'nama' => 'AT - BOTOL PUMP', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0008', 'nama' => 'ORIGINAL BOLU PISANG (1 LOYANG)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0005', 'nama' => 'PG - BOX XXL', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0014', 'nama' => 'AT - BOTOL SEMPROTAN', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'CK0007', 'nama' => 'ORIGINAL BOLU PISANG (PCS)', 'satuan' => 'PCS', 'kat' => 'ISIAN', 'role' => 'staff_admin'],
            ['kode' => 'PG0006', 'nama' => 'PG - BUBBLE WRAP', 'satuan' => 'ROLL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0017', 'nama' => 'AT - BOX CONTAINER 2011 BIRU', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0007', 'nama' => 'PG - CUP PET 14 OZ', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0018', 'nama' => 'AT - BOX CONTAINER 2011 HIJAU', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0008', 'nama' => 'PG - HOT CUP 8 OZ', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0019', 'nama' => 'AT - BOX CONTAINER 2011 KUNING', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0009', 'nama' => 'PG - KABEL TIES', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0020', 'nama' => 'AT - BOX CONTAINER 2022 BIRU', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0010', 'nama' => 'PG - KABEL TIES NAMETAG', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0021', 'nama' => 'AT - BOX CONTAINER 2022 HIJAU', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0011', 'nama' => 'PG - KANTONG PLASTIK LOCO 24', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0022', 'nama' => 'AT - BOX CONTAINER 2022 KUNING', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0012', 'nama' => 'PG - KANTONG PLASTIK LOCO 40', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0034', 'nama' => 'AT - CAPITAN ROTI', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0013', 'nama' => 'PG - KANTONG PLASTIK UK. 56', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0104', 'nama' => 'AT - PAYUNG', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0025', 'nama' => 'PG - PAPERBAG L', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0108', 'nama' => 'AT - PENGKI NAGATA', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0026', 'nama' => 'PG - PAPERBAG M', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0113', 'nama' => 'AT - PRINTER PORTABEL PANDA 58MM', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0027', 'nama' => 'PG - PAPERBAG XL', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0114', 'nama' => 'AT - RAKET NYAMUK KRISBOW', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'KS0011', 'nama' => 'PG - REFILL LABEL EXP TAWAR', 'satuan' => 'ROLL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0117', 'nama' => 'AT - ROLL BAJU', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0041', 'nama' => 'PG - SNACK BOX M', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0120', 'nama' => 'AT - SAPU NAGATA', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0042', 'nama' => 'PG - SNACK BOX S', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0121', 'nama' => 'AT - SAPU SARANG LABA-LABA', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'PG0043', 'nama' => 'PG - SPUNBOND L', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0135', 'nama' => 'AT - SIGN BOARD POTRAIT', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG0064', 'nama' => 'PG - STIKER ABON PEDAS', 'satuan' => 'LMBR', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0136', 'nama' => 'AT - SIGN HATI-HATI LANTAI LICIN', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG0015', 'nama' => 'PG - STIKER LOGO UK. M', 'satuan' => 'LMBR', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0137', 'nama' => 'AT - SIGN OPEN & CLOSE', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'AR0001', 'nama' => 'PRISTINE 400ML', 'satuan' => 'DUS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0142', 'nama' => 'AT - SOAP DISPENSER KRISBOW', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00003', 'nama' => 'SG - KAIN LAP', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0157', 'nama' => 'AT - TEMPAT PULPEN', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00009', 'nama' => 'SG - REFILL ROLL BAJU', 'satuan' => 'ROLL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0164', 'nama' => 'AT - TIMBANGAN BUMBU ACIS 5 KG', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00014', 'nama' => 'SG - SARUNG TANGAN LATEX PUTIH UK. S', 'satuan' => 'DUS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'AT0183', 'nama' => 'AT - WALL CLOCK', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'BB0001', 'nama' => 'BB - ES BATU', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0001', 'nama' => 'ATK - AMPLOP COKELAT', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SR0002', 'nama' => 'SR - CABE RAWIT', 'satuan' => 'BKS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0002', 'nama' => 'ATK - AMPLOP PUTIH', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SP0002', 'nama' => 'SP - GULA AREN', 'satuan' => 'BTL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0008', 'nama' => 'ATK - CUTTER JOYKO', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SP0003', 'nama' => 'SP - SIRUP MONIN HAZELNUT', 'satuan' => 'BTL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0010', 'nama' => 'ATK - GUNTING KASIR', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SP0004', 'nama' => 'SP - SIRUP MONIN SALTED CARAMEL', 'satuan' => 'BTL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0012', 'nama' => 'ATK - ISI CUTTER', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SP0005', 'nama' => 'SP - SIRUP MONIN VANILLA', 'satuan' => 'BTL', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0017', 'nama' => 'ATK - LAKBAN KECIL', 'satuan' => 'ROLL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SU0002', 'nama' => 'SU - FRESH MILK GREENFIELDS', 'satuan' => 'PCS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0020', 'nama' => 'ATK - MAGNET PAPAN TULIS BULAT WARNA', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00007', 'nama' => 'SG - PLASTIK SAMPAH 50X60', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0023', 'nama' => 'ATK - NOTA BON KOSONG', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00008', 'nama' => 'SG - PLASTIK SAMPAH 80X100', 'satuan' => 'PACK', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0027', 'nama' => 'ATK - PULPEN', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'KI0001', 'nama' => 'KI -  EAST JAVA SILHOUETTE 1KG', 'satuan' => 'BKS', 'kat' => 'BB', 'role' => 'staff_admin'],
            ['kode' => 'ATK0028', 'nama' => 'ATK - SPIDOL PERMANENT G-12', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0030', 'nama' => 'ATK - SPIDOL WHITEBOARD ABG-12', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0039', 'nama' => 'ATK - STICKY NOTES', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0042', 'nama' => 'ATK - TAPE DISPENSER SOLATIP', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0048', 'nama' => 'ATK - TINTA SPIDOL WHITEBOARD', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0049', 'nama' => 'ATK - TUSUKAN KERTAS', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0016', 'nama' => 'ATK - LAKBAN BESAR', 'satuan' => 'ROLL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0018', 'nama' => 'ATK - LAKBAN KERTAS BESAR', 'satuan' => 'ROLL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0019', 'nama' => 'ATK - LAKBAN KERTAS KECIL', 'satuan' => 'ROLL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0003', 'nama' => 'SN - HAND SANITIZER GEL', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0004', 'nama' => 'SN - HAND SANITIZER LIQUID', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0005', 'nama' => 'SN - KAMPER', 'satuan' => 'BKS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0006', 'nama' => 'SN - KAPORIT', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0007', 'nama' => 'SN - PEL LANTAI LEMON TOPPAS 5L', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0008', 'nama' => 'SN - PLEDGE REFIL', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0009', 'nama' => 'SN - RINSO BUBUK', 'satuan' => 'BKS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0010', 'nama' => 'SN - SABUN COLEK EKONOMI', 'satuan' => 'DUS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0011', 'nama' => 'SN - SABUN CUCI MOBIL', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0012', 'nama' => 'SN - SABUN CUCI TANGAN', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0013', 'nama' => 'SN - SABUT CUCI PIRING', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0014', 'nama' => 'SN - SPONS CUCI PIRING 3M', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0015', 'nama' => 'SN - VANISH REFILL', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0016', 'nama' => 'SN - VIXAL', 'satuan' => 'BTL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00016', 'nama' => 'SG - TISSU TOWEL', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00017', 'nama' => 'SG - TISSUE KOPI', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00003', 'nama' => 'SG - KAIN LAP', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00005', 'nama' => 'SG - MASKER KN95', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SG00015', 'nama' => 'SG - TALI RAFIA', 'satuan' => 'ROLL', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0001', 'nama' => 'SN - CLING REFIL', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0003', 'nama' => 'SN - HAND SANITIZER GEL', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0007', 'nama' => 'SN - PEL LANTAI LEMON TOPPAS 5L', 'satuan' => 'DRIGENT', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'SN0008', 'nama' => 'SN - PLEDGE REFIL', 'satuan' => 'PCS', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0014', 'nama' => 'ATK - LABEL TJ - 103', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
            ['kode' => 'ATK0015', 'nama' => 'ATK - LABEL TJ - 98', 'satuan' => 'PACK', 'kat' => 'GA', 'role' => 'staff_admin'],
        ];

        // Ambil semua ID Cabang untuk mapping otomatis
        $allCabangIds = Cabang::pluck('id')->toArray();

        foreach ($data as $item) {
            // updateOrCreate: Jika Kode sudah ada, dia UPDATE (mencegah duplikat)
            $produk = MasterProduk::updateOrCreate(
                ['kode_produk' => $item['kode']], 
                [
                    'nama_produk' => $item['nama'],
                    'satuan'      => $item['satuan'],
                    'kategori'    => $item['kat'],
                    'target_role' => $item['role']
                ]
            );

            // Hubungkan produk ke semua cabang agar muncul di form request
            $produk->cabangs()->sync($allCabangIds);
        }

        $this->command->info('MasterProdukSeeder: Berhasil memproses ' . count($data) . ' produk.');
    }
}
