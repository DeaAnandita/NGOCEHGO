<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProgramSertaSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh NIK penduduk yang akan diisi datanya
        $records = [
            '3374123400000001',
            '3374123400000002',
            '3374123400000003',

        ];

        foreach ($records as $nik) {
            $programserta = ['nik' => $nik];
            for ($i = 1; $i <= 8; $i++) {
                // Nilai random antara 1–4 sesuai master_jawablemdes (1=TIDAK DIISI, 2=YA, 3=PERNAH MENDAPATKAN, 4=TIDAK)
                $programserta["programserta_$i"] = rand(1, 4);
            }

            DB::table('data_programserta')->insert($programserta);
        }
    }
}
