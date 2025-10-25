<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetTernakSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $asetternak = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 24; $i++) {
                $asetternak["asetternak_$i"] = rand(0, 10);
            }

            DB::table('data_asetternak')->insert($asetternak);
        }
    }
}
