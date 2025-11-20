<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaEkonomiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $nikList = DB::table('data_penduduk')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('nik');

        foreach ($nikList as $nik) {
            $lembagaekonomi = ['nik' => $nik];
            for ($i = 1; $i <= 75; $i++) {
                $lembagaekonomi["lemek_$i"] = rand(1, 3);
            }

            DB::table('data_lembagaekonomi')->insert($lembagaekonomi);
        }
    }
}
