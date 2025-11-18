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
            ['kdjawabkualitasbayi' => 1, 'jawabkualitasbayi' => 'ADA'],
            ['kdjawabkualitasbayi' => 2, 'jawabkualitasbayi' => 'PERNAH ADA'],
            ['kdjawabkualitasbayi' => 3, 'jawabkualitasbayi' => 'TIDAK ADA'],
        ]);
    }
}
