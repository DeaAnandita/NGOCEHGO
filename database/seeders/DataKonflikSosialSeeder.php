<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DataKeluarga;

class DataKonflikSosialSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 NIK pertama dari data_penduduk (otomatis sinkron)
        $kkList = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $no_kk) {
            $konfliksosial = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 32; $i++) {
                $konfliksosial["konfliksosial_$i"] = rand(1, 2);
            }

            DB::table('data_konfliksosial')->insert($konfliksosial);
        }
    }
}
