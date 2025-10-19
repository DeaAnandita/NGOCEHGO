<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBahanBakarMemasakSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_bahanbakarmemasak')->truncate();

        // Insert data master
        DB::table('master_bahanbakarmemasak')->insert([
            ['kdbahanbakarmemasak' => 1, 'bahanbakarmemasak' => 'LISTRIK'],
            ['kdbahanbakarmemasak' => 2, 'bahanbakarmemasak' => 'GAS LEBIH DARI 3 KG'],
            ['kdbahanbakarmemasak' => 3, 'bahanbakarmemasak' => 'GAS 3 KG'],
            ['kdbahanbakarmemasak' => 4, 'bahanbakarmemasak' => 'GAS KOTA/BIOGAS'],
            ['kdbahanbakarmemasak' => 5, 'bahanbakarmemasak' => 'MINYAK TANAH'],
            ['kdbahanbakarmemasak' => 6, 'bahanbakarmemasak' => 'BRIKET'],
            ['kdbahanbakarmemasak' => 7, 'bahanbakarmemasak' => 'ARANG'],
            ['kdbahanbakarmemasak' => 8, 'bahanbakarmemasak' => 'KAYU BAKAR'],
            ['kdbahanbakarmemasak' => 9, 'bahanbakarmemasak' => 'TIDAK MEMASAK DIRUMAH'],
        ]);
    }
}