<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKondisiDindingBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_kondisidindingbangunan')->truncate();

        // Insert data master
        DB::table('master_kondisidindingbangunan')->insert([
            ['kdkondisidindingbangunan' => 1, 'kondisidindingbangunan' => 'BAGUS/KUALITAS TINGGI'],
            ['kdkondisidindingbangunan' => 2, 'kondisidindingbangunan' => 'JELEK/KUALITAS RENDAH'],
        ]);
    }
}
