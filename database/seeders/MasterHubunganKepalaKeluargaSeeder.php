<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterHubunganKepalaKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_hubungankepalakeluarga')->truncate();

        DB::table('master_hubungankepalakeluarga')->insert([
            ['kdhubungankepalakeluarga' => 1, 'hubungankepalakeluarga' => 'KEPALA KELUARGA'],
            ['kdhubungankepalakeluarga' => 2, 'hubungankepalakeluarga' => 'SUAMI'],
            ['kdhubungankepalakeluarga' => 3, 'hubungankepalakeluarga' => 'ISTRI'],
            ['kdhubungankepalakeluarga' => 4, 'hubungankepalakeluarga' => 'ANAK'],
            ['kdhubungankepalakeluarga' => 5, 'hubungankepalakeluarga' => 'MENANTU'],
            ['kdhubungankepalakeluarga' => 6, 'hubungankepalakeluarga' => 'CUCU'],
            ['kdhubungankepalakeluarga' => 7, 'hubungankepalakeluarga' => 'ORANG TUA'],
            ['kdhubungankepalakeluarga' => 8, 'hubungankepalakeluarga' => 'MERTUA'],
            ['kdhubungankepalakeluarga' => 9, 'hubungankepalakeluarga' => 'FAMILI LAIN'],
            ['kdhubungankepalakeluarga' => 10, 'hubungankepalakeluarga' => 'PEMBANTU RUMAH TANGGA'],
            ['kdhubungankepalakeluarga' => 11, 'hubungankepalakeluarga' => 'LAINNYA'],
        ]);
    }
}
