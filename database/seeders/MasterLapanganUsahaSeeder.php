<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterLapanganUsahaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_lapanganusaha')->truncate();

        DB::table('master_lapanganusaha')->insert([
            ['kdlapanganusaha' => 1, 'lapanganusaha' => 'PERTANIAN TANAMAN PADI & PALAWIJA'],
            ['kdlapanganusaha' => 2, 'lapanganusaha' => 'HORTIKULTURA'],
            ['kdlapanganusaha' => 3, 'lapanganusaha' => 'PERKEBUNAN'],
            ['kdlapanganusaha' => 4, 'lapanganusaha' => 'PERIKANAN TANGKAP'],
            ['kdlapanganusaha' => 5, 'lapanganusaha' => 'PERIKANAN BUDIDAYA'],
            ['kdlapanganusaha' => 6, 'lapanganusaha' => 'PETERNAKAN'],
            ['kdlapanganusaha' => 7, 'lapanganusaha' => 'KEHUTANAN & PERTANIAN LAINNYA'],
            ['kdlapanganusaha' => 8, 'lapanganusaha' => 'PERTAMBANGAN/PENGGALIAN'],
            ['kdlapanganusaha' => 9, 'lapanganusaha' => 'INDUSTRI PENGOLAHAN'],
            ['kdlapanganusaha' => 10, 'lapanganusaha' => 'LISTRIK, GAS & AIR'],
            ['kdlapanganusaha' => 11, 'lapanganusaha' => 'BANGUNAN/KONSTRUKSI'],
            ['kdlapanganusaha' => 12, 'lapanganusaha' => 'PERDAGANGAN'],
            ['kdlapanganusaha' => 13, 'lapanganusaha' => 'HOTEL & RUMAH MAKAN'],
            ['kdlapanganusaha' => 14, 'lapanganusaha' => 'TRANSPORTASI & PERGUDANGAN'],
            ['kdlapanganusaha' => 15, 'lapanganusaha' => 'INFORMASI DAN KOMUNIKASI'],
            ['kdlapanganusaha' => 16, 'lapanganusaha' => 'KEUANGAN DAN ASURANSI'],
            ['kdlapanganusaha' => 17, 'lapanganusaha' => 'JASA PENDIDIKAN'],
            ['kdlapanganusaha' => 18, 'lapanganusaha' => 'JASA KESEHATAN'],
            ['kdlapanganusaha' => 19, 'lapanganusaha' => 'JASA KEMASYARAKATAN, PEMERINTAH & PERORANGAN'],
            ['kdlapanganusaha' => 20, 'lapanganusaha' => 'PEMULUNG'],
            ['kdlapanganusaha' => 21, 'lapanganusaha' => 'LAINNYA'],
            ['kdlapanganusaha' => 22, 'lapanganusaha' => 'TIDAK MEMILIKI USAHA'],
        ]);
    }
}
