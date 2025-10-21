<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabSarprasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawabsarpras')->truncate();

        DB::table('master_jawabsarpras')->insert([

	        ['kdjawabsarpras' => 1, 'jawabsarpras' => 'TIDAK DIISI'],
            ['kdjawabsarpras' => 2, 'jawabsarpras' => 'MILIK SENDIRI(BAGUS/ KONDISI BAIK)'],
            ['kdjawabsarpras' => 3, 'jawabsarpras' => 'MILIK SENDIRI(JELEK/ KONDISI TIDAK BAIK)'],
            ['kdjawabsarpras' => 4, 'jawabsarpras' => 'MILIK KELOMPOK(SEWA TIDAK BAYAR)'],
			['kdjawabsarpras' => 5, 'jawabsarpras' => 'MILIK ORANG LAIN(SEWA BAYAR)'],
			['kdjawabsarpras' => 6, 'jawabsarpras' => 'MILIK ORANG LAIN(SEWA TIDAK BAYAR)'],
			['kdjawabsarpras' => 7, 'jawabsarpras' => 'TIDAK MEMILIKI'],
  	]);
    }
}