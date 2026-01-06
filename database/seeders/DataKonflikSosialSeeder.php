<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKonflikSosialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua no_kk dari data_keluarga
        $kkList = DB::table('data_keluarga')
            ->pluck('no_kk')
            ->toArray();

        if (empty($kkList)) {
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data konflik sosial yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data konflik sosial untuk {$totalKK} keluarga di lingkungan desa...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan "tingkat risiko sosial" keluarga secara acak untuk variasi realistis di desa
            // 1 = risiko tinggi (miskin/stres tinggi), 2 = menengah, 3 = rendah (stabil)
            $risikoRand = mt_rand(1, 100);
            if ($risikoRand <= 30) {
                $risiko = 1; // 30% risiko tinggi (lebih rentan konflik, miras, judi)
            } elseif ($risikoRand <= 70) {
                $risiko = 2; // 40% menengah
            } else {
                $risiko = 3; // 30% rendah (hampir aman)
            }

            // Default semua TIDAK ADA (2)
            $row = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 32; $i++) {
                $row["konfliksosial_$i"] = 2;
            }

            // Konflik SARA & kekerasan berat (1-9,13-18): sangat jarang di desa biasa (<1%)
            $this->applyRare($row, [1,2,3,4,5,6,7,8,9,13,14,15,16,17,18], $risiko, 0.5);

            // Judi (10): ~8-15% di desa, lebih tinggi jika risiko tinggi
            $row['konfliksosial_10'] = $this->prob(5 + 8 * $risiko, $risiko);

            // Miras (11): ~15-25%, umum di desa
            $row['konfliksosial_11'] = $this->prob(10 + 10 * $risiko, $risiko);

            // Narkoba (12): sangat jarang ~1-2%
            $row['konfliksosial_12'] = $this->prob(0.5 + 1 * $risiko, $risiko);

            // Kehamilan bermasalah (19-21): ~3-8%, lebih tinggi risiko tinggi
            $this->applyRare($row, [19,20,21], $risiko, 3 + 4 * $risiko);

            // Pertengkaran ringan (22-26): cukup umum ~20-40%
            $row['konfliksosial_22'] = $this->prob(15 + 15 * $risiko, $risiko); // anak vs ortu
            $row['konfliksosial_23'] = $this->prob(10 + 10 * $risiko, $risiko); // anak vs anak
            $row['konfliksosial_24'] = $this->prob(20 + 15 * $risiko, $risiko); // ayah vs ibu (paling umum)
            $row['konfliksosial_25'] = $this->prob(5 + 5 * $risiko, $risiko);  // vs pembantu (jarang)
            $row['konfliksosial_26'] = $this->prob(8 + 8 * $risiko, $risiko);   // vs keluarga lain

            // Kekerasan fisik/KDRT ringan-berat (27-32): ~10-25%, terkait KDRT
            $row['konfliksosial_27'] = $this->prob(8 + 10 * $risiko, $risiko);  // anak mukul ortu (jarang)
            $row['konfliksosial_28'] = $this->prob(15 + 15 * $risiko, $risiko); // ortu mukul anak (umum)
            $row['konfliksosial_29'] = $this->prob(5 + 8 * $risiko, $risiko);
            $row['konfliksosial_30'] = $this->prob(10 + 12 * $risiko, $risiko); // ortu vs ortu
            $row['konfliksosial_31'] = $this->prob(3 + 5 * $risiko, $risiko);   // vs pembantu
            $row['konfliksosial_32'] = $this->prob(4 + 6 * $risiko, $risiko);

            $data[] = $row;

            // Insert per batch
            if (count($data) >= $batchSize) {
                DB::table('data_konfliksosial')->insert($data);
                $this->command->info("Inserted " . count($data) . " data konflik sosial.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_konfliksosial')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data konflik sosial.");
        }

        $this->command->info("Seeder DataKonflikSosial selesai! Total {$totalKK} keluarga memiliki data konflik sosial yang realistis untuk lingkungan desa Indonesia (mayoritas aman, konflik ringan sesekali, masalah berat jarang).");
    }

    /**
     * Helper: Mengembalikan 1 (ADA) jika lolos probabilitas, 2 (TIDAK ADA) jika tidak
     * Chance lebih tinggi jika risiko tinggi
     */
    private function prob(float $baseChance, int $risikoLevel): int
    {
        $chance = $baseChance + ($risikoLevel - 1) * 8; // Bonus per level risiko
        $chance = min($chance, 98); // Maksimal agar ada variasi acak
        return mt_rand(1, 100) <= $chance ? 1 : 2;
    }

    /**
     * Helper untuk apply prob ke multiple fields yang sangat jarang
     */
    private function applyRare(array &$row, array $fields, int $risiko, float $base): void
    {
        foreach ($fields as $f) {
            $row["konfliksosial_$f"] = $this->prob($base, $risiko);
        }
    }
}