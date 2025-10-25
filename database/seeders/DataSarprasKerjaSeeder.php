<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSarprasKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $sarpraskerja = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 25; $i++) {
                $sarpraskerja["sarpraskerja_$i"] = rand(1, 7);
            }

            DB::table('data_sarpraskerja')->insert($sarpraskerja);
        }
    }
}
