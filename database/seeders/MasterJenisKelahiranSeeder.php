<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisKelahiranSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_jeniskelahiran')->truncate();

        DB::table('master_jeniskelahiran')->insert([
            ['kdjeniskelahiran' => 1, 'jeniskelahiran' => 'TUNGGAL'],
            ['kdjeniskelahiran' => 2, 'jeniskelahiran' => 'KEMBAR 2'],
            ['kdjeniskelahiran' => 3, 'jeniskelahiran' => 'KEMBAR 3'],
            ['kdjeniskelahiran' => 4, 'jeniskelahiran' => 'KEMBAR 4'],
            ['kdjeniskelahiran' => 5, 'jeniskelahiran' => 'LAINNYA'],
        ]);
    }
}
