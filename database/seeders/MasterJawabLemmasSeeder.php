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

	    ['kdjawablemmas' => 1, 'jawablemmas' => 'Tidak Diisi'],
            ['kdjawablemmas' => 2, 'jawablemmas' => 'Ya'],
            ['kdjawablemmas' => 3, 'jawablemmas' => 'Pernah'],
            ['kdjawablemmas' => 4, 'jawablemmas' => 'Tidak'],
  ]);
    }
}
