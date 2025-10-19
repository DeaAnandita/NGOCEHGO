<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusPemilikLahanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum seeding ulang
        //DB::table('master_statuspemiliklahan')->truncate();

        // Data master
        DB::table('master_statuspemiliklahan')->insert([
            ['kdstatuspemiliklahan' => 1, 'statuspemiliklahan' => 'MILIK SENDIRI'],
            ['kdstatuspemiliklahan' => 2, 'statuspemiliklahan' => 'MILIK ORANG LAIN'],
            ['kdstatuspemiliklahan' => 3, 'statuspemiliklahan' => 'TANAH NEGARA'],
            ['kdstatuspemiliklahan' => 4, 'statuspemiliklahan' => 'MILIK ORANG TUA'],
            ['kdstatuspemiliklahan' => 5, 'statuspemiliklahan' => 'MILIK KELUARGA'],
            ['kdstatuspemiliklahan' => 6, 'statuspemiliklahan' => 'PINJAM PAKAI'],
            ['kdstatuspemiliklahan' => 7, 'statuspemiliklahan' => 'LAINNYA'],
        ]);
    }
}
