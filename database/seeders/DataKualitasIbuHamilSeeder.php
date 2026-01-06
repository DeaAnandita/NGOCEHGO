<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKualitasIbuHamilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kkList = DB::table('data_keluarga')
            ->pluck('no_kk')
            ->toArray();

        if (empty($kkList)) {
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data kualitas ibu hamil yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data kualitas ibu hamil realistis untuk {$totalKK} keluarga desa...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            $risikoRand = mt_rand(1, 100);
            if ($risikoRand <= 35) {
                $risiko = 1;
            } elseif ($risikoRand <= 75) {
                $risiko = 2;
            } else {
                $risiko = 3;
            }

            $row = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 13; $i++) {
                $row["kualitasibuhamil_$i"] = 3; // Default TIDAK ADA
            }

            // Asumsi hanya keluarga dengan ibu hamil/anak kecil yang punya data ini, tapi seeder untuk semua KK → mayoritas TIDAK ADA

            // Probabilitas ada kehamilan baru-baru ini ~20-30% di desa (fertilitas tinggi)
            if (mt_rand(1, 100) > 25 + 10 * (4 - $risiko)) { // Lebih tinggi di risiko tinggi
                // Tidak ada kehamilan → semua 3
            } else {
                // Ada kehamilan: set tempat periksa (mutual, pilih satu utama)
                $periksaRand = mt_rand(1, 100);
                if ($periksaRand <= 40) {
                    $row['kualitasibuhamil_1'] = 1; // Posyandu (umum di desa)
                } elseif ($periksaRand <= 70) {
                    $row['kualitasibuhamil_5'] = 1; // Bidan praktek (sangat umum)
                } elseif ($periksaRand <= 90) {
                    $row['kualitasibuhamil_2'] = 1; // Puskesmas
                } elseif ($periksaRand <= 95) {
                    $row['kualitasibuhamil_4'] = 1; // Dokter
                } else {
                    $row['kualitasibuhamil_3'] = 1; // RS (jarang)
                }

                // Tidak periksa (7) → jarang sekarang (~5%)
                if (mt_rand(1, 100) <= 3 + 2 * $risiko) {
                    $row['kualitasibuhamil_7'] = 1;
                }

                // Melahirkan (9) → ya jika ada kehamilan
                $row['kualitasibuhamil_9'] = 1;

                // Ibu nifas sehat (12) → mayoritas
                $row['kualitasibuhamil_12'] = 1;

                // Sakit nifas (10) → ~5-10%
                if ($this->prob(4 + 4 * $risiko, $risiko) == 1) {
                    $row['kualitasibuhamil_10'] = 1;
                }

                // Kematian ibu saat melahirkan/nifas/hamil (8,11,13) → sangat jarang (<0.5%)
                $this->applyRare($row, [8,11,13], $risiko, 0.2);
            }

            $data[] = $row;

            if (count($data) >= $batchSize) {
                DB::table('data_kualitasibuhamil')->insert($data);
                $this->command->info("Inserted " . count($data) . " data kualitas ibu hamil.");
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('data_kualitasibuhamil')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data kualitas ibu hamil.");
        }

        $this->command->info("Seeder DataKualitasIbuHamil selesai! Mayoritas TIDAK ADA (no recent pregnancy), jika ada: periksa di posyandu/bidan/puskesmas, kematian sangat jarang.");
    }

    private function prob(float $baseChance, int $risikoLevel): int
    {
        $chance = $baseChance + ($risikoLevel - 1) * 3;
        $chance = min($chance, 98);
        return mt_rand(1, 100) <= $chance ? 1 : 3;
    }

    private function applyRare(array &$row, array $fields, int $risiko, float $base): void
    {
        foreach ($fields as $f) {
            $row["kualitasibuhamil_$f"] = $this->prob($base, $risiko);
        }
    }
}