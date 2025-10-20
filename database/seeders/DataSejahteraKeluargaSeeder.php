<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSejahteraKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_sejahterakeluarga')->insert([
            [
                'no_kk' => '3374123400000001',
                'sejahterakeluarga_61' => '1 bungkus',
                'sejahterakeluarga_62' => '3 kali',
                'sejahterakeluarga_63' => '2 jam',
                'sejahterakeluarga_64' => '20000',
                'sejahterakeluarga_65' => '3500000',
                'sejahterakeluarga_66' => '2500000',
                'sejahterakeluarga_67' => '1500000',
                'sejahterakeluarga_68' => '1 motor',
            ],
            [
                'no_kk' => '3374123400000002',
                'sejahterakeluarga_61' => '2 bungkus',
                'sejahterakeluarga_62' => '5 kali',
                'sejahterakeluarga_63' => '3 jam',
                'sejahterakeluarga_64' => '30000',
                'sejahterakeluarga_65' => '4000000',
                'sejahterakeluarga_66' => '2800000',
                'sejahterakeluarga_67' => '2000000',
                'sejahterakeluarga_68' => '2 motor',
            ],
        ]);
    }
}
