<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisLantaiBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum diisi ulang
        //DB::table('master_jenislantaibangunan')->truncate();

        // Insert data master
        DB::table('master_jenislantaibangunan')->insert([
            ['kdjenislantaibangunan' => 1, 'jenislantaibangunan' => 'MARMER/GRANIT'],
            ['kdjenislantaibangunan' => 2, 'jenislantaibangunan' => 'KERAMIK'],
            ['kdjenislantaibangunan' => 3, 'jenislantaibangunan' => 'PARKET/VINIL/PERMADANI'],
            ['kdjenislantaibangunan' => 4, 'jenislantaibangunan' => 'UBIN/TEGEL/TERASO'],
            ['kdjenislantaibangunan' => 5, 'jenislantaibangunan' => 'KAYU/PAPAN'],
            ['kdjenislantaibangunan' => 6, 'jenislantaibangunan' => 'SEMEN/BATA MERAH'],
            ['kdjenislantaibangunan' => 7, 'jenislantaibangunan' => 'BAMBU'],
            ['kdjenislantaibangunan' => 8, 'jenislantaibangunan' => 'TANAH'],
            ['kdjenislantaibangunan' => 9, 'jenislantaibangunan' => 'LAINNYA'],
        ]);
    }
}
