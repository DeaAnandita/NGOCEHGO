<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSumberPeneranganUtamaSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_sumberpeneranganutama')->truncate();

        // Insert data master
        DB::table('master_sumberpeneranganutama')->insert([
            ['kdsumberpeneranganutama' => 1, 'sumberpeneranganutama' => 'LISTRIK PLN'],
            ['kdsumberpeneranganutama' => 2, 'sumberpeneranganutama' => 'LISTRIK NON PLN'],
            ['kdsumberpeneranganutama' => 3, 'sumberpeneranganutama' => 'BUKAN LISTRIK'],
        ]);
    }
}
