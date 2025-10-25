<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBangunKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $bangunkeluarga = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 51; $i++) {
                $bangunkeluarga["bangunkeluarga_$i"] = rand(0, 3);
            }

            DB::table('data_bangunkeluarga')->insert($bangunkeluarga);
        }
    }

}
