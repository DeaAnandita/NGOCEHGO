<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisAtapBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_jenisatapbangunan')->truncate();

        // Insert data master
        DB::table('master_jenisatapbangunan')->insert([
            ['kdjenisatapbangunan' => 1, 'jenisatapbangunan' => 'BETON/ GENTENG BETON'],
            ['kdjenisatapbangunan' => 2, 'jenisatapbangunan' => 'GENTENG KERAMIK'],
            ['kdjenisatapbangunan' => 3, 'jenisatapbangunan' => 'GENTENG METAL'],
            ['kdjenisatapbangunan' => 4, 'jenisatapbangunan' => 'GENTENG TANAH LIAT'],
            ['kdjenisatapbangunan' => 5, 'jenisatapbangunan' => 'ASBES'],
            ['kdjenisatapbangunan' => 6, 'jenisatapbangunan' => 'SENG'],
            ['kdjenisatapbangunan' => 7, 'jenisatapbangunan' => 'SIRAP'],
            ['kdjenisatapbangunan' => 8, 'jenisatapbangunan' => 'BAMBU'],
            ['kdjenisatapbangunan' => 9, 'jenisatapbangunan' => 'JERAMI/ IJUK/ DAUN/ RUMBIA'],
            ['kdjenisatapbangunan' => 10, 'jenisatapbangunan' => 'LAINNYA'],
        ]);
    }
}
