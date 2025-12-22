<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKonflikSosialSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 15 KK
        $kkList = DB::table('data_keluarga')
            ->limit(15)
            ->pluck('no_kk');

        $dataKonflik = [
            // KELUARGA 1 - Aman
            [
                'konfliksosial_1' => 2, 'konfliksosial_2' => 2, 'konfliksosial_3' => 2,
                'konfliksosial_4' => 2, 'konfliksosial_5' => 2, 'konfliksosial_6' => 2,
                'konfliksosial_7' => 2, 'konfliksosial_8' => 2, 'konfliksosial_9' => 2,
                'konfliksosial_10' => 2, 'konfliksosial_11' => 2, 'konfliksosial_12' => 2,
                'konfliksosial_13' => 2, 'konfliksosial_14' => 2, 'konfliksosial_15' => 2,
                'konfliksosial_16' => 2, 'konfliksosial_17' => 2, 'konfliksosial_18' => 2,
                'konfliksosial_19' => 2, 'konfliksosial_20' => 2, 'konfliksosial_21' => 2,
                'konfliksosial_22' => 2, 'konfliksosial_23' => 2, 'konfliksosial_24' => 2,
                'konfliksosial_25' => 2, 'konfliksosial_26' => 2, 'konfliksosial_27' => 2,
                'konfliksosial_28' => 2, 'konfliksosial_29' => 2, 'konfliksosial_30' => 2,
                'konfliksosial_31' => 2, 'konfliksosial_32' => 2,
            ],

            // KELUARGA 2 - Pertengkaran ringan
            [
                'konfliksosial_22' => 1, // anak vs orang tua
                'konfliksosial_24' => 1, // ayah vs ibu
            ],

            // KELUARGA 3 - Masalah sosial
            [
                'konfliksosial_10' => 1, // judi
                'konfliksosial_11' => 1, // miras
            ],

            // KELUARGA 4 - Konflik anak
            [
                'konfliksosial_23' => 1,
                'konfliksosial_26' => 1,
            ],

            // KELUARGA 5 - Kekerasan ringan
            [
                'konfliksosial_27' => 1,
                'konfliksosial_28' => 1,
            ],

            // KELUARGA 6 - Kehamilan bermasalah
            [
                'konfliksosial_20' => 1,
                'konfliksosial_21' => 1,
            ],
        ];

        foreach ($kkList as $index => $no_kk) {

            // Default semua TIDAK ADA
            $row = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 32; $i++) {
                $row["konfliksosial_$i"] = 2;
            }

            // Jika ada skenario konflik
            if (isset($dataKonflik[$index])) {
                foreach ($dataKonflik[$index] as $key => $value) {
                    $row[$key] = $value;
                }
            }

            DB::table('data_konfliksosial')->insert($row);
        }
    }
}
