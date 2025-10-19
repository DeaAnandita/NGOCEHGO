<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_jawab')->truncate();

        // Insert data master
        DB::table('master_jawab')->insert([
            ['kdjawab' => 0, 'jawab' => 'TIDAK DIISI'],
            ['kdjawab' => 1, 'jawab' => 'YA'],
            ['kdjawab' => 2, 'jawab' => 'TIDAK'],
        ]);
    }
}
