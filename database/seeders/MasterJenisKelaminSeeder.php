<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisKelaminSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_jeniskelamin')->truncate();

        DB::table('master_jeniskelamin')->insert([
            ['kdjeniskelamin' => 1, 'jeniskelamin' => 'LAKI-LAKI'],
            ['kdjeniskelamin' => 2, 'jeniskelamin' => 'PEREMPUAN'],
        ]);
    }
}
