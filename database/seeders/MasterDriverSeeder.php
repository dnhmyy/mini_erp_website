<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = ['Jojo', 'Iki', 'Rendi'];
        foreach ($drivers as $driver) {
            \App\Models\MasterDriver::create(['nama' => $driver]);
        }
    }
}
