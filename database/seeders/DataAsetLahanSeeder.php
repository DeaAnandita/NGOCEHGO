<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetLahanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $asetlahan = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 10; $i++) {
                $asetlahan["asetlahan_$i"] = rand(0, 11);
            }

            DB::table('data_asetlahan')->insert($asetlahan);
        }
    }
}
