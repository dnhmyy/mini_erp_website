<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add target_role to master_produks if it doesn't exist
        if (!Schema::hasColumn('master_produks', 'target_role')) {
            Schema::table('master_produks', function (Blueprint $table) {
                $table->string('target_role')->nullable()->after('kategori');
            });
        }

        // 2. Rename existing roles to new names
        DB::table('users')->where('role', 'superadmin')->update(['role' => 'superuser']);
        DB::table('users')->where('role', 'warehouse_admin')->update(['role' => 'staff_gudang']);
        DB::table('users')->where('role', 'branch_admin')->update(['role' => 'staff_admin']);
    }

    public function down(): void
    {
        // Revert target_role
        if (Schema::hasColumn('master_produks', 'target_role')) {
            Schema::table('master_produks', function (Blueprint $table) {
                $table->dropColumn('target_role');
            });
        }

        // Revert user roles
        DB::table('users')->where('role', 'superuser')->update(['role' => 'superadmin']);
        DB::table('users')->where('role', 'staff_gudang')->update(['role' => 'warehouse_admin']);
        DB::table('users')->where('role', 'staff_admin')->update(['role' => 'branch_admin']);
    }
};
