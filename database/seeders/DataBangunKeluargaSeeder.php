<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBangunKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $kkList = DB::table('data_keluarga')
            ->limit(15)
            ->pluck('no_kk');

        foreach ($kkList as $index => $no_kk) {

            /*
             |--------------------------------------------------------------------------
             | KLASIFIKASI KELUARGA
             |--------------------------------------------------------------------------
             | 0–4   : Sejahtera
             | 5–10  : Berkembang
             | 11–14 : Rentan
             */
            if ($index <= 4) {
                $tipe = 'SEJAHTERA';
            } elseif ($index <= 10) {
                $tipe = 'BERKEMBANG';
            } else {
                $tipe = 'RENTAN';
            }

            DB::table('data_bangunkeluarga')->insert([
                'no_kk' => $no_kk,

                /* =====================
                   KEBUTUHAN DASAR
                ===================== */
                'bangunkeluarga_1' => 1,
                'bangunkeluarga_2' => 1,
                'bangunkeluarga_3' => 1,
                'bangunkeluarga_4' => 1,
                'bangunkeluarga_5' => ($tipe === 'RENTAN') ? 2 : 1,
                'bangunkeluarga_6' => 1,

                /* =====================
                   PERENCANAAN & EKONOMI
                ===================== */
                'bangunkeluarga_7'  => ($tipe === 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_8'  => ($tipe === 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_9'  => 1,
                'bangunkeluarga_10' => ($tipe !== 'RENTAN') ? 1 : 2,
                'bangunkeluarga_11' => ($tipe !== 'RENTAN') ? 1 : 2,
                'bangunkeluarga_12' => ($tipe === 'SEJAHTERA') ? 1 : 2,

                /* =====================
                   KEGIATAN KELUARGA
                ===================== */
                'bangunkeluarga_13' => ($tipe === 'RENTAN') ? 2 : 1,
                'bangunkeluarga_14' => ($tipe === 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_15' => ($tipe === 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_16' => 2,
                'bangunkeluarga_17' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_18' => ($tipe === 'SEJAHTERA') ? 1 : 2,

                /* =====================
                   MASALAH SOSIAL
                ===================== */
                'bangunkeluarga_19' => 2,
                'bangunkeluarga_20' => 2,
                'bangunkeluarga_21' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_22' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_23' => 2,
                'bangunkeluarga_24' => 2,
                'bangunkeluarga_25' => 2,
                'bangunkeluarga_26' => 2,
                'bangunkeluarga_27' => 2,
                'bangunkeluarga_28' => 2,
                'bangunkeluarga_29' => ($tipe !== 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_30' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_31' => 2,

                /* =====================
                   LINGKUNGAN TINGGAL
                ===================== */
                'bangunkeluarga_32' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_33' => 2,
                'bangunkeluarga_34' => 2,
                'bangunkeluarga_35' => 2,
                'bangunkeluarga_36' => ($tipe === 'RENTAN') ? 1 : 2,

                /* =====================
                   EKONOMI & KERJA
                ===================== */
                'bangunkeluarga_37' => ($tipe !== 'SEJAHTERA') ? 1 : 2,
                'bangunkeluarga_38' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_39' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_40' => 2,

                /* =====================
                   KERAWANAN WILAYAH
                ===================== */
                'bangunkeluarga_41' => 1,
                'bangunkeluarga_42' => 2,
                'bangunkeluarga_43' => 2,
                'bangunkeluarga_44' => 1,
                'bangunkeluarga_45' => 2,
                'bangunkeluarga_46' => 2,
                'bangunkeluarga_47' => ($tipe === 'RENTAN') ? 1 : 2,
                'bangunkeluarga_48' => 1,
                'bangunkeluarga_49' => 2,
                'bangunkeluarga_50' => 2,
                'bangunkeluarga_51' => 2,
            ]);
        }
    }
}
