<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaEkonomiSeeder extends Seeder
{
    public function run(): void
    {
        $nikList = DB::table('data_penduduk')
            ->pluck('nik')
            ->toArray();

        if (empty($nikList)) {
            $this->command->warn('Tabel data_penduduk kosong!');
            return;
        }

        $this->command->info("Membuat data lembaga ekonomi untuk " . count($nikList) . " penduduk");

        $batchSize = 500;
        $data = [];

        foreach ($nikList as $nik) {

            // Tingkat ekonomi
            $rand = mt_rand(1, 100);
            if ($rand <= 40) {
                $tingkat = 1; // rendah
            } elseif ($rand <= 85) {
                $tingkat = 2; // menengah
            } else {
                $tingkat = 3; // tinggi
            }

            // Default semua Tidak (3)
            $row = ['nik' => $nik];
            for ($i = 1; $i <= 75; $i++) {
                $row["lemek_$i"] = 3;
            }

            // Usaha umum desa
            $row['lemek_11'] = $this->prob3(45, 10, $tingkat); // Warung
            $row['lemek_10'] = $this->prob3(25, 10, $tingkat); // Toko kecil
            $row['lemek_14'] = $this->prob3(20, 10, $tingkat); // Angkutan
            $row['lemek_24'] = $this->prob3(25, 10, $tingkat); // Peternakan
            $row['lemek_25'] = $this->prob3(20, 10, $tingkat); // Perikanan
            $row['lemek_26'] = $this->prob3(35, 15, $tingkat); // Pertanian

            // Tukang & jasa kecil
            $this->applyCommon($row, [35,36,37,38,39,40,41], $tingkat, 20, 10);

            // Koperasi & simpan pinjam
            $row['lemek_1']  = $this->prob3(10, 8, $tingkat);
            $row['lemek_17'] = $this->prob3(15, 10, $tingkat);

            // Industri rumahan
            $this->applyCommon($row, [2,3,4,5], $tingkat, 18, 10);

            // Usaha besar (sangat jarang)
            $this->applyRare($row, [54,56,59,62,64], $tingkat, 1, 1);

            // Hiburan & lain-lain
            $this->applyRare($row, [24,25,26,27,28,29,30,31], $tingkat, 3, 3);

            $data[] = $row;

            if (count($data) >= $batchSize) {
                DB::table('data_lembagaekonomi')->insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('data_lembagaekonomi')->insert($data);
        }

        $this->command->info('Seeder DataLembagaEkonomi selesai (Ya / Pernah / Tidak)');
    }

    /**
     * Probabilitas 3 jawaban:
     * - Ya
     * - Pernah
     * - Tidak
     */
    private function prob3(float $yaBase, float $pernahBase, int $tingkat): int
    {
        $yaChance     = min($yaBase + ($tingkat - 1) * 10, 90);
        $pernahChance = min($pernahBase + ($tingkat - 1) * 5, 30);

        $rand = mt_rand(1, 100);

        if ($rand <= $yaChance) {
            return 1; // Ya
        } elseif ($rand <= ($yaChance + $pernahChance)) {
            return 2; // Pernah
        }

        return 3; // Tidak
    }

    private function applyCommon(array &$row, array $fields, int $tingkat, float $ya, float $pernah): void
    {
        foreach ($fields as $f) {
            $row["lemek_$f"] = $this->prob3($ya, $pernah, $tingkat);
        }
    }

    private function applyRare(array &$row, array $fields, int $tingkat, float $ya, float $pernah): void
    {
        foreach ($fields as $f) {
            $row["lemek_$f"] = $this->prob3($ya, $pernah, $tingkat);
        }
    }
}
