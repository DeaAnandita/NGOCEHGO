<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterProgramSertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_programserta')->truncate();

        DB::table('master_programserta')->insert([
		['kdprogramserta' => 1, 'programserta' => 'KARTU KELUARGA SEJAHTERA(KKS)/KARTU PERLINDUNGAN SOSIAL(KPS)'],
		['kdprogramserta' => 2, 'programserta' => 'KARTU INDONESIA PINTAR (KIP)'],
		['kdprogramserta' => 3, 'programserta' => 'KARTU INDONESIA SEHAT (KIS)'],
		['kdprogramserta' => 4, 'programserta' => 'BPJS KESEHATAN NON PBI (PESERTA MANDIRI)'],
		['kdprogramserta' => 5, 'programserta' => 'JAMINAN SOSIAL TENAGA KERJA (JAMSOSTEK)'],
		['kdprogramserta' => 6, 'programserta' => 'ASURANSI KESEHATAN LAINNYA'],
		['kdprogramserta' => 7, 'programserta' => 'PROGRAM KELUARGA HARAPAN (PKH)'],
		['kdprogramserta' => 8, 'programserta' => 'BERAS UNTUK MASYARAKAT MISKIN (RASKIN)'],
							
      ]);
    }
}
