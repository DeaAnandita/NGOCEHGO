<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $asetkeluarga = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 42; $i++) {
                $asetkeluarga["asetkeluarga_$i"] = rand(1, 2);
            }

            DB::table('data_asetkeluarga')->insert($asetkeluarga);
        }
    }
}
