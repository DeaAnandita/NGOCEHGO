<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTercantumDalamKKSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_tercantumdalamkk')->truncate();

        DB::table('master_tercantumdalamkk')->insert([
            ['kdtercantumdalamkk' => 1, 'tercantumdalamkk' => 'TIDAK'],
            ['kdtercantumdalamkk' => 2, 'tercantumdalamkk' => 'YA'],
        ]);
    }
}

