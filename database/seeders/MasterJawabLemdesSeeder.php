<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterjawablemdesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawablemdes')->truncate();

        DB::table('master_jawablemdes')->insert([

            ['kdjawablemdes' => 1, 'jawablemdes' => 'Ya'],
            ['kdjawablemdes' => 2, 'jawablemdes' => 'Pernah'],
            ['kdjawablemdes' => 3, 'jawablemdes' => 'Tidak'],
        ]);
    }
}