<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPenyakitKronisSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_penyakitkronis')->truncate();

        DB::table('master_penyakitkronis')->insert([
            ['kdpenyakitkronis' => 1, 'penyakitkronis' => 'TIDAK ADA'],
            ['kdpenyakitkronis' => 2, 'penyakitkronis' => 'HIPERTENSI (TEKANAN DARAH TINGGI)'],
            ['kdpenyakitkronis' => 3, 'penyakitkronis' => 'REMATIK'],
            ['kdpenyakitkronis' => 4, 'penyakitkronis' => 'ASMA'],
            ['kdpenyakitkronis' => 5, 'penyakitkronis' => 'MASALAH JANTUNG'],
            ['kdpenyakitkronis' => 6, 'penyakitkronis' => 'DIABETES (KENCING MANIS)'],
            ['kdpenyakitkronis' => 7, 'penyakitkronis' => 'TBC (TUBERCULLOSIS)'],
            ['kdpenyakitkronis' => 8, 'penyakitkronis' => 'STROKE'],
            ['kdpenyakitkronis' => 9, 'penyakitkronis' => 'KANKER ATAU TUMOR GANAS'],
            ['kdpenyakitkronis' => 10, 'penyakitkronis' => 'LEPRA/KUSTA'],
            ['kdpenyakitkronis' => 11, 'penyakitkronis' => 'LEVER'],
            ['kdpenyakitkronis' => 12, 'penyakitkronis' => 'MALARIA'],
            ['kdpenyakitkronis' => 13, 'penyakitkronis' => 'HIV/AIDS'],
            ['kdpenyakitkronis' => 14, 'penyakitkronis' => 'GAGAL GINJAL'],
            ['kdpenyakitkronis' => 15, 'penyakitkronis' => 'PARU-PARU FLEK & SEJENISNYA'],
            ['kdpenyakitkronis' => 16, 'penyakitkronis' => 'LAINNYA'],
        ]);
    }
}
