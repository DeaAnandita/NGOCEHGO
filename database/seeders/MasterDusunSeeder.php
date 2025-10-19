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
            ['kddusun' => 1, 'dusun' => 'NALUMSARI'],
            ['kddusun' => 2, 'dusun' => 'DESA'],
            ['kddusun' => 3, 'dusun' => 'BLABLA'],
        ]);
    }
}
