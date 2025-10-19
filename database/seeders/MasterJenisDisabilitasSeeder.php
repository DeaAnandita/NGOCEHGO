<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisDisabilitasSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_jenisdisabilitas')->truncate();

        DB::table('master_jenisdisabilitas')->insert([
            ['kdjenisdisabilitas' => 1, 'jenisdisabilitas' => 'PENGLIHATAN'],
            ['kdjenisdisabilitas' => 2, 'jenisdisabilitas' => 'PENDENGARAN'],
            ['kdjenisdisabilitas' => 3, 'jenisdisabilitas' => 'BERJALAN/NAIK TANGGA'],
            ['kdjenisdisabilitas' => 4, 'jenisdisabilitas' => 'MENGINGAT/KONSENTRASI (PIKUN)'],
            ['kdjenisdisabilitas' => 5, 'jenisdisabilitas' => 'MENGURUS DIRI SENDIRI'],
            ['kdjenisdisabilitas' => 6, 'jenisdisabilitas' => 'KOMUNIKASI'],
            ['kdjenisdisabilitas' => 7, 'jenisdisabilitas' => 'EMOSI/PERILAKU (DEPRESI/AUTIS)'],
            ['kdjenisdisabilitas' => 8, 'jenisdisabilitas' => 'LUMPUH'],
            ['kdjenisdisabilitas' => 9, 'jenisdisabilitas' => 'SUMBING'],
            ['kdjenisdisabilitas' => 10, 'jenisdisabilitas' => 'GILA'],
            ['kdjenisdisabilitas' => 11, 'jenisdisabilitas' => 'STRES'],
            ['kdjenisdisabilitas' => 12, 'jenisdisabilitas' => 'TIDAK MENGALAMI'],
        ]);
    }
}
