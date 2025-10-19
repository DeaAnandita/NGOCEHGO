<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabKualitasBayiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jawabkualitasbayi')->insert([
            ['kdjawabkualitasbayi' => 1, 'jawabkualitasbayi' => 'TIDAK DIISI'],
            ['kdjawabkualitasbayi' => 2, 'jawabkualitasbayi' => 'ADA'],
            ['kdjawabkualitasbayi' => 3, 'jawabkualitasbayi' => 'PERNAH ADA'],
            ['kdjawabkualitasbayi' => 4, 'jawabkualitasbayi' => 'TIDAK ADA'],
        ]);
    }
}
