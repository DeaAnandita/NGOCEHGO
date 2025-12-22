<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSarprasKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $kk = DB::table('data_keluarga')
            ->orderBy('no_kk')
            ->limit(8)
            ->pluck('no_kk')
            ->toArray();

        /* ==========================
           KK 1 – Nelayan Tradisional
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[0], [
                1  => 1, // Jaring Udang
                8  => 1, // Jala Ikan
                9  => 2, // Bubu (jelek)
            ])
        );

        /* ==========================
           KK 2 – Pedagang Kaki Lima
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[1], [
                6  => 1, // Gerobak
                10 => 1, // Alat Kuliner
                21 => 1, // PKL
                3  => 2, // Motor jelek
            ])
        );

        /* ==========================
           KK 3 – Bengkel Motor
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[2], [
                3  => 1, // Motor
                11 => 1, // Kompresor
                17 => 1, // Alat Bengkel
                14 => 2, // Etalase jelek
            ])
        );

        /* ==========================
           KK 4 – Penjahit / Konveksi
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[3], [
                18 => 1, // Alat Konveksi
                3  => 1, // Motor
                14 => 4, // Etalase sewa
            ])
        );

        /* ==========================
           KK 5 – Usaha Meubel
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[4], [
                12 => 1, // Mesin Belah
                15 => 1, // Alat Meubel
                13 => 2, // Alat Bangunan jelek
                3  => 2, // Motor jelek
            ])
        );

        /* ==========================
           KK 6 – Ojek Motor
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[5], [
                3 => 1, // Motor
            ])
        );

        /* ==========================
           KK 7 – Toko Fashion Kecil
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[6], [
                7  => 1, // Toko Fashion
                14 => 1, // Etalase
            ])
        );

        /* ==========================
           KK 8 – Buruh Harian
        =========================== */
        DB::table('data_sarpraskerja')->insert(
            $this->base($kk[7], [])
        );
    }

    /**
     * Base data: default TIDAK MEMILIKI (6)
     */
    private function base($no_kk, array $isi)
    {
        $data = ['no_kk' => $no_kk];

        for ($i = 1; $i <= 25; $i++) {
            $data["sarpraskerja_$i"] = $isi[$i] ?? 6;
        }

        return $data;
    }
}
