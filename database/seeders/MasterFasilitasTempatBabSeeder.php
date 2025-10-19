<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterFasilitasTempatBabSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_fasilitastempatbab')->truncate();

        // Insert data master
        DB::table('master_fasilitastempatbab')->insert([
            ['kdfasilitastempatbab' => 0, 'fasilitastempatbab' => 'TIDAK ADA'],
            ['kdfasilitastempatbab' => 1, 'fasilitastempatbab' => 'SENDIRI'],
            ['kdfasilitastempatbab' => 2, 'fasilitastempatbab' => 'BERSAMA'],
            ['kdfasilitastempatbab' => 3, 'fasilitastempatbab' => 'UMUM'],
        ]);
    }
}
