<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaDesaSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh NIK penduduk yang akan diisi datanya
        $records = [
            '3374123400000001',
            '3374123400000002',
            '3374123400000003',
        ];

        foreach ($records as $nik) {
            $lembagadesa = ['nik' => $nik];
            for ($i = 1; $i <= 9; $i++) {
                // Nilai random antara 1–4 sesuai master_jawablemdes
                $lembagadesa["lemdes_$i"] = rand(1, 4);
            }

            DB::table('data_lembagadesa')->insert($lembagadesa);
        }
    }
}
