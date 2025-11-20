<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabLemmasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawablemmas')->truncate();

        DB::table('master_jawablemmas')->insert([

            ['kdjawablemmas' => 1, 'jawablemmas' => 'Ya'],
            ['kdjawablemmas' => 2, 'jawablemmas' => 'Pernah'],
            ['kdjawablemmas' => 3, 'jawablemmas' => 'Tidak'],
  ]);
    }
}
