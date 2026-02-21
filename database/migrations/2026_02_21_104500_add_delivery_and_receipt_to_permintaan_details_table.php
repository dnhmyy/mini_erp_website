<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permintaan_details', function (Blueprint $table) {
            $table->integer('qty_dikirim')->nullable()->after('qty');
            $table->integer('qty_terima')->nullable()->after('qty_dikirim');
        });

        // Update enum for status in permintaans
        // Using DB::statement for MySQL compatible enum change if needed, 
        // or just re-declaring with Schema if supported by the driver.
        DB::statement("ALTER TABLE permintaans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'shipped', 'received', 'received_complete', 'received_partial') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_details', function (Blueprint $table) {
            $table->dropColumn(['qty_dikirim', 'qty_terima']);
        });

        DB::statement("ALTER TABLE permintaans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'shipped', 'received') DEFAULT 'pending'");
    }
};
