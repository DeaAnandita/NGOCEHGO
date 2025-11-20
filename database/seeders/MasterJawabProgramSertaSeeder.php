<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabProgramSertaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawabprogramserta')->truncate();

        DB::table('master_jawabprogramserta')->insert([

            ['kdjawabprogramserta' => 1, 'jawabprogramserta' => 'Ya'],
            ['kdjawabprogramserta' => 2, 'jawabprogramserta' => 'Pernah Mendapatkan'],
            ['kdjawabprogramserta' => 3, 'jawabprogramserta' => 'Tidak'],
  	]);
    }
}
