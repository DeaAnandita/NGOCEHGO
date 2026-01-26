<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProgramSertaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_programserta')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil SEMUA NIK dari data_penduduk
        $nikList = DB::table('data_penduduk')->pluck('nik');
        
        $totalPenduduk = count($nikList);
        $records = [];

        foreach ($nikList as $nik) {
            $programserta = ['nik' => $nik];
            for ($i = 1; $i <= 8; $i++) {
                $programserta["programserta_$i"] = rand(1, 3);
            }
            $records[] = $programserta;
        }

        // Insert dengan batch untuk performa lebih baik
        $batchSize = 500;
        for ($i = 0; $i < count($records); $i += $batchSize) {
            DB::table('data_programserta')->insert(array_slice($records, $i, $batchSize));
        }
        
        $this->command->info("Data program serta berhasil ditambahkan untuk $totalPenduduk penduduk.");
    }
}
