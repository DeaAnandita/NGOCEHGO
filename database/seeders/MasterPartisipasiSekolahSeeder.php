<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPartisipasiSekolahSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_partisipasisekolah')->truncate();

        DB::table('master_partisipasisekolah')->insert([
            ['kdpartisipasisekolah' => 1, 'partisipasisekolah' => 'SD/SDLB/PAKET A/MI'],
            ['kdpartisipasisekolah' => 2, 'partisipasisekolah' => 'SMP/SMPLB/PAKET B/MTS'],
            ['kdpartisipasisekolah' => 3, 'partisipasisekolah' => 'SMA/SMK/SMALB/PAKET C/MA'],
            ['kdpartisipasisekolah' => 4, 'partisipasisekolah' => 'PERGURUAN TINGGI'],
            ['kdpartisipasisekolah' => 5, 'partisipasisekolah' => 'TIDAK BERSEKOLAH LAGI'],
            ['kdpartisipasisekolah' => 6, 'partisipasisekolah' => 'TIDAK/BELUM PERNAH'],
        ]);
    }
}
