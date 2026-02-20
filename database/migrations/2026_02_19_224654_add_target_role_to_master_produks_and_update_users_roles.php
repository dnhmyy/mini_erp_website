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
        $columns = collect(DB::select("PRAGMA table_info(master_produks)"))->pluck('name')->toArray();
        if (!in_array('target_role', $columns)) {
            Schema::table('master_produks', function (Blueprint $table) {
                $table->string('target_role')->nullable()->after('kategori');
            });
        }

        // 2. Reconstruct users table to remove old CHECK constraint on role
        //    (The old constraint was: role IN ('superadmin', 'branch_admin', 'warehouse_admin'))
        $usersTableDef = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'")?->sql ?? '';
        if (str_contains($usersTableDef, 'CHECK')) {
            DB::statement('PRAGMA foreign_keys=OFF;');
            DB::statement('
                CREATE TABLE users_new (
                    "id" integer primary key autoincrement not null,
                    "name" varchar not null,
                    "email" varchar not null,
                    "email_verified_at" datetime,
                    "password" varchar not null,
                    "remember_token" varchar,
                    "created_at" datetime,
                    "updated_at" datetime,
                    "role" varchar not null default \'staff_admin\',
                    "cabang_id" integer,
                    foreign key("cabang_id") references "cabangs"("id") on delete set null
                )
            ');
            DB::statement('INSERT INTO users_new SELECT * FROM users');
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
            DB::statement('PRAGMA foreign_keys=ON;');
        }

        // 3. Rename existing roles to new names
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
