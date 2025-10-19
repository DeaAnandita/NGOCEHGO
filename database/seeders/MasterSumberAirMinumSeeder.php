<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSumberAirMinumSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_sumberairminum')->truncate();

        // Insert data master
        DB::table('master_sumberairminum')->insert([
            ['kdsumberairminum' => 1, 'sumberairminum' => 'AIR KEMASAN BERMERK'],
            ['kdsumberairminum' => 2, 'sumberairminum' => 'AIR ISI ULANG'],
            ['kdsumberairminum' => 3, 'sumberairminum' => 'LEDING METERAN'],
            ['kdsumberairminum' => 4, 'sumberairminum' => 'LEDING ECERAN'],
            ['kdsumberairminum' => 5, 'sumberairminum' => 'SUMUR BOR/ POMPA'],
            ['kdsumberairminum' => 6, 'sumberairminum' => 'SUMUR TERLINDUNG'],
            ['kdsumberairminum' => 7, 'sumberairminum' => 'SUMUR TAK TERLINDUNG'],
            ['kdsumberairminum' => 8, 'sumberairminum' => 'MATA AIR TERLINDUNG'],
            ['kdsumberairminum' => 9, 'sumberairminum' => 'MATA AIR TAK TERLINDUNG'],
            ['kdsumberairminum' => 10, 'sumberairminum' => 'AIR SUNGAI/ DANAU/ WADUK'],
            ['kdsumberairminum' => 11, 'sumberairminum' => 'AIR HUJAN'],
            ['kdsumberairminum' => 12, 'sumberairminum' => 'LAINNYA'],
        ]);
    }
}
