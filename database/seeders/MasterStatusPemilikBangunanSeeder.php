<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusPemilikBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel dulu biar gak dobel data
        //DB::table('master_statuspemilikbangunan')->truncate();

        // Insert data master
        DB::table('master_statuspemilikbangunan')->insert([
            ['kdstatuspemilikbangunan' => 1, 'statuspemilikbangunan' => 'MILIK SENDIRI'],
            ['kdstatuspemilikbangunan' => 2, 'statuspemilikbangunan' => 'KONTRAK/SEWA'],
            ['kdstatuspemilikbangunan' => 3, 'statuspemilikbangunan' => 'BEBAS SEWA'],
            ['kdstatuspemilikbangunan' => 4, 'statuspemilikbangunan' => 'DINAS'],
            ['kdstatuspemilikbangunan' => 5, 'statuspemilikbangunan' => 'MILIK ORANG TUA'],
            ['kdstatuspemilikbangunan' => 6, 'statuspemilikbangunan' => 'MILIK KELUARGA'],
            ['kdstatuspemilikbangunan' => 7, 'statuspemilikbangunan' => 'PINJAM PAKAI'],
            ['kdstatuspemilikbangunan' => 8, 'statuspemilikbangunan' => 'LAINNYA'],
        ]);
    }
}