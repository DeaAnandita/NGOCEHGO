<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTingkatSulitDisabilitasSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_tingkatsulitdisabilitas')->truncate();

        DB::table('master_tingkatsulitdisabilitas')->insert([
            ['kdtingkatsulitdisabilitas' => 1, 'tingkatsulitdisabilitas' => 'SEDIKIT KESULITAN'],
            ['kdtingkatsulitdisabilitas' => 2, 'tingkatsulitdisabilitas' => 'BANYAK KESULITAN'],
            ['kdtingkatsulitdisabilitas' => 3, 'tingkatsulitdisabilitas' => 'TIDAK BISA SAMA SEKALI'],
            ['kdtingkatsulitdisabilitas' => 4, 'tingkatsulitdisabilitas' => 'TIDAK MENGALAMI KESULITAN'],
        ]);
    }
}

