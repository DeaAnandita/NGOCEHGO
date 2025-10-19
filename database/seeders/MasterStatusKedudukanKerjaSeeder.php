<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusKedudukanKerjaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_statuskedudukankerja')->truncate();

        DB::table('master_statuskedudukankerja')->insert([
            ['kdstatuskedudukankerja' => 1, 'statuskedudukankerja' => 'BERUSAHA SENDIRI'],
            ['kdstatuskedudukankerja' => 2, 'statuskedudukankerja' => 'BERUSAHA DIBANTU BURUH TIDAK TETAP/BAYAR'],
            ['kdstatuskedudukankerja' => 3, 'statuskedudukankerja' => 'BERUSAHA DIBANTU BURUH TETAP/DIBAYAR'],
            ['kdstatuskedudukankerja' => 4, 'statuskedudukankerja' => 'BURUH/KARYAWAN/PEGAWAI SWASTA'],
            ['kdstatuskedudukankerja' => 5, 'statuskedudukankerja' => 'PNS/TNI/POLRI/BUMN/BUMD/ANGGOTA LEGISLATIF'],
            ['kdstatuskedudukankerja' => 6, 'statuskedudukankerja' => 'PEKERJA BEBAS PERTANIAN'],
            ['kdstatuskedudukankerja' => 7, 'statuskedudukankerja' => 'PEKERJA BEBAS NON PERTANIAN'],
            ['kdstatuskedudukankerja' => 8, 'statuskedudukankerja' => 'PEKERJA KELUARGA/TIDAK DIBAYAR'],
        ]);
    }
}
