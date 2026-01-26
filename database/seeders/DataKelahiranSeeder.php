<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKelahiranSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar bisa truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_kelahiran')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil SEMUA NIK dari data_penduduk
        $nikList = DB::table('data_penduduk')->pluck('nik');
        $allNik = $nikList->toArray(); // Untuk random selection ibu dan ayah
        
        $totalPenduduk = count($nikList);
        $records = [];

        foreach ($nikList as $index => $nik) {
            $records[] = [
                'nik' => $nik,
                'kdtempatpersalinan' => fake()->numberBetween(1, 3), // 1=RS, 2=Puskesmas, 3=Rumah
                'kdjeniskelahiran' => fake()->numberBetween(1, 2),   // 1=Tunggal, 2=Kembar
                'kdpertolonganpersalinan' => fake()->numberBetween(1, 3), // 1=Dokter, 2=Bidan, 3=Tenaga Medis
                'kelahiran_jamkelahiran' => fake()->time('H:i:s'),
                'kelahiran_kelahiranke' => fake()->numberBetween(1, 4),
                'kelahiran_berat' => fake()->numberBetween(2700, 4000), // gram
                'kelahiran_panjang' => fake()->numberBetween(45, 55),   // cm
                'kelahiran_nikibu' => $allNik[array_rand($allNik)],
                'kelahiran_nikayah' => $allNik[array_rand($allNik)],
                'created_by' => 1,
            ];
        }

        // Insert dengan batch untuk performa lebih baik
        $batchSize = 500;
        for ($i = 0; $i < count($records); $i += $batchSize) {
            DB::table('data_kelahiran')->insert(array_slice($records, $i, $batchSize));
        }
        
        $this->command->info("Data kelahiran berhasil ditambahkan untuk $totalPenduduk penduduk.");
    }
}
