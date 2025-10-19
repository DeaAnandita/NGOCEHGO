<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabLemekSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawablemek')->truncate();

        DB::table('master_jawablemek')->insert([

	    ['kdjawablemek' => 1, 'jawablemek' => 'Tidak Diisi'],
            ['kdjawablemek' => 2, 'jawablemek' => 'Ya'],
            ['kdjawablemek' => 3, 'jawablemek' => 'Pernah'],
            ['kdjawablemek' => 4, 'jawablemek' => 'Tidak'],
        ]);
    }
}