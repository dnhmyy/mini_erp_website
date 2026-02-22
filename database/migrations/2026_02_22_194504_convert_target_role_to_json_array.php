<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $produks = DB::table('master_produks')->get();
        foreach ($produks as $produk) {
            $current = $produk->target_role;
            if ($current) {
                // Check if it's already a valid JSON array
                $decoded = json_decode($current, true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                    // It's a plain string, wrap it in a JSON array
                    DB::table('master_produks')
                        ->where('id', $produk->id)
                        ->update(['target_role' => json_encode([$current])]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $produks = DB::table('master_produks')->get();
        foreach ($produks as $produk) {
            $current = $produk->target_role;
            if ($current) {
                $decoded = json_decode($current, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    // Take the first one back as a plain string
                    DB::table('master_produks')
                        ->where('id', $produk->id)
                        ->update(['target_role' => $decoded[0]]);
                }
            }
        }
    }
};
