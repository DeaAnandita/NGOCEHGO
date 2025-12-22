<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetPerikananSeeder extends Seeder
{
    public function run(): void
    {
        $kkList = DB::table('data_keluarga')
            ->limit(15)
            ->pluck('no_kk')
            ->toArray();

        $data = [

            // Keluarga 1 – Kolam lele kecil
            [
                'no_kk' => $kkList[0],
                'asetperikanan_1' => 80, // Lele
            ],

            // Keluarga 2 – Tidak punya perikanan
            [
                'no_kk' => $kkList[1],
            ],

            // Keluarga 3 – Kolam nila
            [
                'no_kk' => $kkList[2],
                'asetperikanan_2' => 60, // Nila
            ],

            // Keluarga 4 – Kolam lele + patin
            [
                'no_kk' => $kkList[3],
                'asetperikanan_1' => 70,
                'asetperikanan_3' => 40, // Patin
            ],

            // Keluarga 5 – Tidak punya perikanan
            [
                'no_kk' => $kkList[4],
            ],

            // Keluarga 6 – Budidaya ikan mas
            [
                'no_kk' => $kkList[5],
                'asetperikanan_4' => 50, // Ikan mas
            ],

            // Keluarga 7 – Tidak punya perikanan
            [
                'no_kk' => $kkList[6],
            ],

            // Keluarga 8 – Kolam lele skala kecil
            [
                'no_kk' => $kkList[7],
                'asetperikanan_1' => 40,
            ],

            // Keluarga 9 – Kolam campuran nila + lele
            [
                'no_kk' => $kkList[8],
                'asetperikanan_1' => 30,
                'asetperikanan_2' => 30,
            ],

            // Keluarga 10 – Tidak punya perikanan
            [
                'no_kk' => $kkList[9],
            ],

            // Keluarga 11 – Kolam patin
            [
                'no_kk' => $kkList[10],
                'asetperikanan_3' => 55,
            ],

            // Keluarga 12 – Kolam nila besar
            [
                'no_kk' => $kkList[11],
                'asetperikanan_2' => 90,
            ],

            // Keluarga 13 – Tidak punya perikanan
            [
                'no_kk' => $kkList[12],
            ],

            // Keluarga 14 – Usaha kecil lele
            [
                'no_kk' => $kkList[13],
                'asetperikanan_1' => 60,
            ],

            // Keluarga 15 – Tidak punya perikanan
            [
                'no_kk' => $kkList[14],
            ],
        ];

        // Lengkapi asetperikanan_1 s/d 6 jadi 0
        foreach ($data as &$row) {
            for ($i = 1; $i <= 6; $i++) {
                if (!isset($row["asetperikanan_$i"])) {
                    $row["asetperikanan_$i"] = 0;
                }
            }
        }

        DB::table('data_asetperikanan')->insert($data);
    }
}
