<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabKualitasIbuHamilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jawabkualitasibuhamil')->insert([
            ['kdjawabkualitasibuhamil' => 1, 'jawabkualitasibuhamil' => 'TIDAK DIISI'],
            ['kdjawabkualitasibuhamil' => 2, 'jawabkualitasibuhamil' => 'ADA'],
            ['kdjawabkualitasibuhamil' => 3, 'jawabkualitasibuhamil' => 'PERNAH ADA'],
            ['kdjawabkualitasibuhamil' => 4, 'jawabkualitasibuhamil' => 'TIDAK ADA'],
        ]);
    }
}
