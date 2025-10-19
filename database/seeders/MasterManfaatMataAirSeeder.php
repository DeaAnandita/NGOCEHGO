<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterManfaatMataAirSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_manfaatmataair')->truncate();

        // Insert data master
        DB::table('master_manfaatmataair')->insert([
            ['kdmanfaatmataair' => 1, 'manfaatmataair' => 'USAHA PERIKANAN'],
            ['kdmanfaatmataair' => 2, 'manfaatmataair' => 'AIR MINUM/AIR BAKU'],
            ['kdmanfaatmataair' => 3, 'manfaatmataair' => 'CUCI DAN MANDI'],
            ['kdmanfaatmataair' => 4, 'manfaatmataair' => 'IRIGASI'],
            ['kdmanfaatmataair' => 5, 'manfaatmataair' => 'BUANG AIR BESAR'],
            ['kdmanfaatmataair' => 6, 'manfaatmataair' => 'PEMBANGKIT LISTRIK'],
            ['kdmanfaatmataair' => 7, 'manfaatmataair' => 'PRASARANA TRANSPORTASI'],
            ['kdmanfaatmataair' => 8, 'manfaatmataair' => 'SUMBER AIR PANAS'],
        ]);
    }
}
