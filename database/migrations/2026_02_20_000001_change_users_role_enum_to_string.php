<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom role dari ENUM ke string agar bisa menampung semua nilai role baru
        // (superuser, staff_admin, staff_gudang, staff_produksi)
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'staff_admin'");
    }

    public function down(): void
    {
        // Kembalikan ke enum lama (hanya nilai standar)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','branch_admin','warehouse_admin') NOT NULL DEFAULT 'branch_admin'");
    }
};
