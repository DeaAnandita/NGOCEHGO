<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDusunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_dusun')->insert([
            ['kddusun' => 1, 'dusun' => 'KALIWUNGU'],
            ['kddusun' => 2, 'dusun' => 'GERUNG'],
            ['kddusun' => 3, 'dusun' => 'TEGUHAN'],
            ['kddusun' => 4, 'dusun' => 'JETIS'],
            ['kddusun' => 5, 'dusun' => 'PROKO WINONG'],
        ]);
    }
}
