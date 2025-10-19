<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKondisiAtapBangunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_kondisiatapbangunan')->insert([
            ['kdkondisiatapbangunan' => 1, 'kondisiatapbangunan' => 'BAGUS/KUALITAS TINGGI'],
            ['kdkondisiatapbangunan' => 2, 'kondisiatapbangunan' => 'JELEK/KUALITAS RENDAH'],
        ]);
    }
}
