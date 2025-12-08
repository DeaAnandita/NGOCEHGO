<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterHubunganKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_hubungankeluarga')->truncate();

        DB::table('master_hubungankeluarga')->insert([
            ['kdhubungankeluarga' => 1, 'hubungankeluarga' => 'KEPALA RUMAH TANGGA'],
            ['kdhubungankeluarga' => 2, 'hubungankeluarga' => 'ISTRI/SUAMI'],
            ['kdhubungankeluarga' => 3, 'hubungankeluarga' => 'ANAK'],
            ['kdhubungankeluarga' => 4, 'hubungankeluarga' => 'MENANTU'],
            ['kdhubungankeluarga' => 5, 'hubungankeluarga' => 'CUCU'],
            ['kdhubungankeluarga' => 6, 'hubungankeluarga' => 'ORANG TUA/MERTUA'],
            ['kdhubungankeluarga' => 7, 'hubungankeluarga' => 'KELUARGA LAIN'],
            ['kdhubungankeluarga' => 8, 'hubungankeluarga' => 'PEMBANTU RUMAH TANGGA'],
            ['kdhubungankeluarga' => 9, 'hubungankeluarga' => 'LAINNYA'],
        ]);
    }
}