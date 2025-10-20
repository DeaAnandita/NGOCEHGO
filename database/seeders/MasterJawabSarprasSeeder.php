<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabSarprasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawabsarpas')->truncate();

        DB::table('master_jawabsarpas')->insert([

	    ['kdjawabsarpas' => 1, 'jawabsarpas' => 'TIDAK DIISI'],
            ['kdjawabsarpas' => 2, 'jawabsarpas' => 'MILIK SENDIRI(BAGUS/ KONDISI BAIK)'],
            ['kdjawabsarpas' => 3, 'jawabsarpas' => 'MILIK SENDIRI(JELEK/ KONDISI TIDAK BAIK)'],
            ['kdjawabsarpas' => 4, 'jawabsarpas' => 'MILIK KELOMPOK(SEWA TIDAK BAYAR)'],
			['kdjawabsarpas' => 5, 'jawabsarpas' => 'MILIK ORANG LAIN(SEWA BAYAR)'],
			['kdjawabsarpas' => 6, 'jawabsarpas' => 'MILIK ORANG LAIN(SEWA TIDAK BAYAR)'],
			['kdjawabsarpas' => 6, 'jawabsarpas' => 'TIDAK MEMILIKI'],
  	]);
    }
}