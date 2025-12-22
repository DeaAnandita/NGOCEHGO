<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetTernakSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 15 nomor KK pertama
        $kkList = DB::table('data_keluarga')
            ->limit(15)
            ->pluck('no_kk')
            ->toArray();

        $data = [

            // 1. Keluarga 1 – Peternak ayam rumahan
            [
                'no_kk' => $kkList[0],
                'asetternak_1' => 12, // Ayam
            ],

            // 2. Keluarga 2 – Ayam + bebek
            [
                'no_kk' => $kkList[1],
                'asetternak_1' => 8,  // Ayam
                'asetternak_2' => 5,  // Bebek
            ],

            // 3. Keluarga 3 – Kambing kecil
            [
                'no_kk' => $kkList[2],
                'asetternak_1' => 4,
                'asetternak_3' => 2,  // Kambing
            ],

            // 4. Keluarga 4 – Sapi 1 ekor
            [
                'no_kk' => $kkList[3],
                'asetternak_1' => 3,
                'asetternak_4' => 1,  // Sapi
            ],

            // 5. Keluarga 5 – Kolam ikan lele
            [
                'no_kk' => $kkList[4],
                'asetternak_5' => 80, // Ikan lele
            ],

            // 6. Keluarga 6 – Ayam + kambing
            [
                'no_kk' => $kkList[5],
                'asetternak_1' => 6,
                'asetternak_3' => 1,
            ],

            // 7. Keluarga 7 – Tidak punya ternak
            [
                'no_kk' => $kkList[6],
            ],

            // 8. Keluarga 8 – Peternak kambing
            [
                'no_kk' => $kkList[7],
                'asetternak_3' => 5,
            ],

            // 9. Keluarga 9 – Ayam skala kecil
            [
                'no_kk' => $kkList[8],
                'asetternak_1' => 3,
            ],

            // 10. Keluarga 10 – Bebek + ayam
            [
                'no_kk' => $kkList[9],
                'asetternak_1' => 5,
                'asetternak_2' => 7,
            ],

            // 11. Keluarga 11 – Sapi + kambing
            [
                'no_kk' => $kkList[10],
                'asetternak_3' => 2,
                'asetternak_4' => 1,
            ],

            // 12. Keluarga 12 – Kolam ikan nila
            [
                'no_kk' => $kkList[11],
                'asetternak_6' => 60, // Ikan nila
            ],

            // 13. Keluarga 13 – Ayam saja
            [
                'no_kk' => $kkList[12],
                'asetternak_1' => 10,
            ],

            // 14. Keluarga 14 – Usaha kecil ternak campur
            [
                'no_kk' => $kkList[13],
                'asetternak_1' => 7,
                'asetternak_3' => 2,
            ],

            // 15. Keluarga 15 – Tidak punya ternak
            [
                'no_kk' => $kkList[14],
            ],
        ];

        // Lengkapi asetternak_1 s/d asetternak_24 dengan nilai 0
        foreach ($data as &$row) {
            for ($i = 1; $i <= 24; $i++) {
                $key = "asetternak_$i";
                if (!isset($row[$key])) {
                    $row[$key] = 0;
                }
            }
        }

        DB::table('data_asetternak')->insert($data);
    }
}
