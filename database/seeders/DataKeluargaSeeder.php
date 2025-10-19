<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_keluarga')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert data
        DB::table('data_keluarga')->insert([
            [
                'no_kk' => '3374123400000001',
                'kdmutasimasuk' => 1,
                'keluarga_tanggalmutasi' => now(),
                'keluarga_kepalakeluarga' => 'Budi Santoso',
                'kddusun' => 1,
                'keluarga_rw' => '001',
                'keluarga_rt' => '002',
                'keluarga_alamatlengkap' => 'Jl. Melati No. 10, Dusun Winong, Kaliwungu',
            ],
            [
                'no_kk' => '3374123400000002',
                'kdmutasimasuk' => 2,
                'keluarga_tanggalmutasi' => now(),
                'keluarga_kepalakeluarga' => 'Siti Aminah',
                'kddusun' => 2,
                'keluarga_rw' => '002',
                'keluarga_rt' => '003',
                'keluarga_alamatlengkap' => 'Jl. Kenanga No. 25, Dusun Krajan, Kaliwungu',
            ],
        ]);
    }
}
