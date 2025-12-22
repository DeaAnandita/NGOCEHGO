<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPendapatanPerbulanSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_pendapatanperbulan')->truncate();

        DB::table('master_pendapatanperbulan')->insert([
            ['kdpendapatanperbulan' => 1, 'pendapatanperbulan' => 'TIDAK BERPENDAPATAN'],
            ['kdpendapatanperbulan' => 2, 'pendapatanperbulan' => 'KURANG DARI/SAMA DENGAN RP. 1 JUTA'],
            ['kdpendapatanperbulan' => 3, 'pendapatanperbulan' => 'RP. 1 JUTA S/D RP. 1,5 JUTA'],
            ['kdpendapatanperbulan' => 4, 'pendapatanperbulan' => 'RP. 1,5 JUTA S/D RP. 2 JUTA'],
            ['kdpendapatanperbulan' => 5, 'pendapatanperbulan' => 'RP. 2 JUTA S/D RP. 3 JUTA'],
            ['kdpendapatanperbulan' => 6, 'pendapatanperbulan' => 'LEBIH DARI/SAMA DENGAN RP. 3 JUTA'],
        ]);
    }
}
