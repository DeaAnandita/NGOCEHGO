<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProgramSertaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $nikList = DB::table('data_penduduk')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('nik');

        foreach ($nikList as $nik) {
            $programserta = ['nik' => $nik];
            for ($i = 1; $i <= 8; $i++) {
                $programserta["programserta_$i"] = rand(1, 4);
            }

            DB::table('data_programserta')->insert($programserta);
        }
    }
}
