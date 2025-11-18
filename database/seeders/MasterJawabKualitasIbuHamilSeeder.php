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
            ['kdjawabkualitasibuhamil' => 1, 'jawabkualitasibuhamil' => 'ADA'],
            ['kdjawabkualitasibuhamil' => 2, 'jawabkualitasibuhamil' => 'PERNAH ADA'],
            ['kdjawabkualitasibuhamil' => 3, 'jawabkualitasibuhamil' => 'TIDAK ADA'],
        ]);
    }
}
