<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaDesaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $nikList = DB::table('data_penduduk')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('nik');

        foreach ($nikList as $nik) {
            $lembagadesa = ['nik' => $nik];
            for ($i = 1; $i <= 9; $i++) {
                $lembagadesa["lemdes_$i"] = rand(1, 3);
            }

            DB::table('data_lembagadesa')->insert($lembagadesa);
        }
    }
}
