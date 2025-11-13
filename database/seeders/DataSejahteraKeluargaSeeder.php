<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSejahteraKeluargaSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel data_sejahterakeluarga.
     */
    public function run(): void
    {
        $data = [];

        // Helper untuk buat angka kelipatan 500.000 agar terlihat realistis
        $randRupiah = function ($min, $max) {
            $kelipatan = 500000;
            $nilai = rand($min / $kelipatan, $max / $kelipatan) * $kelipatan;
            return (int) $nilai;
        };

        $randRupiahsaku = function ($min, $max) {
            $kelipatansaku = 5000;
            $nilaisaku = rand($min / $kelipatansaku, $max / $kelipatansaku) * $kelipatansaku;
            return (int) $nilaisaku;
        };

        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                'no_kk' => '33741234000000' . str_pad($i, 2, '0', STR_PAD_LEFT),

                // Nilai lebih masuk akal dan seragam
                'sejahterakeluarga_61' => $randRupiahsaku(10000, 50000),              // uang saku per hari
                'sejahterakeluarga_62' => rand(1, 3) . ' bungkus',                // rokok per hari
                'sejahterakeluarga_63' => rand(1, 5) . ' kali',                   // minum kopi kali
                'sejahterakeluarga_64' => rand(1, 4) . ' jam',                    // minum kopi jam
                'sejahterakeluarga_65' => $randRupiah(2000000, 6000000),          // pulsa per minggu
                'sejahterakeluarga_66' => $randRupiah(1500000, 3500000),          // pendapatan per bulan
                'sejahterakeluarga_67' => $randRupiah(800000, 2000000),           // pengeluaran per bulan
                'sejahterakeluarga_68' => $randRupiah(500000, 5000000),           // uang belanja per bulan
            ];
        }

        DB::table('data_sejahterakeluarga')->insert($data);
    }
}
