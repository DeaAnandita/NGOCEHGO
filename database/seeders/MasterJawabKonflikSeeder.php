<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabKonflikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jawabkonflik')->insert([
            ['kdjawabkonflik' => 1, 'jawabkonflik' => 'ADA'],
            ['kdjawabkonflik' => 2, 'jawabkonflik' => 'TIDAK ADA']
        ]);
    }
}
