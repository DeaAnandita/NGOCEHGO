<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCaraPembuanganSampahSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_carapembuangansampah')->truncate();

        // Insert data master
        DB::table('master_carapembuangansampah')->insert([
            ['kdcarapembuangansampah' => 1, 'carapembuangansampah' => 'DIBAKAR'],
            ['kdcarapembuangansampah' => 2, 'carapembuangansampah' => 'DIPENDAM DI LUBANG TANAH'],
            ['kdcarapembuangansampah' => 3, 'carapembuangansampah' => 'DIBUANG DI TPA SAMPAH'],
            ['kdcarapembuangansampah' => 4, 'carapembuangansampah' => 'DIANGKUT TRUCK SAMPAH'],
            ['kdcarapembuangansampah' => 5, 'carapembuangansampah' => 'DIBUANG DI KOLAM/ SAWAH/ SUNGAI/ DANAU/ LAUT'],
            ['kdcarapembuangansampah' => 6, 'carapembuangansampah' => 'DIBUANG DI PANTAI/ TANAH LAPANG/ KEBUN'],
            ['kdcarapembuangansampah' => 7, 'carapembuangansampah' => 'LAINNYA'],
        ]);
    }
}