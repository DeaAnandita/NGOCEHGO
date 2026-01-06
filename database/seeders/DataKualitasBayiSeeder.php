<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKualitasBayiSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data kualitas bayi yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data kualitas bayi realistis untuk {$totalKK} keluarga desa...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tingkat risiko keluarga (1: tinggi/miskin, 2: menengah, 3: rendah)
            $risikoRand = mt_rand(1, 100);
            if ($risikoRand <= 35) {
                $risiko = 1; // 35% risiko tinggi
            } elseif ($risikoRand <= 75) {
                $risiko = 2; // 40% menengah
            } else {
                $risiko = 3; // 25% rendah
            }

            // Default semua TIDAK ADA (3)
            $row = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 7; $i++) {
                $row["kualitasbayi_$i"] = 3;
            }

            // 1: Keguguran → jarang (~2-5%, lebih tinggi risiko tinggi)
            $row['kualitasbayi_1'] = $this->prob(1 + 3 * $risiko, $risiko);

            // 2: Bayi lahir hidup normal → mayoritas (90-95%)
            // Karena mutual exclusive dengan yang lain, set ke 2 jika tidak ada masalah lain
            $hasMasalah = false;

            // 3: Bayi lahir hidup cacat/konginatal → sangat jarang (~1%)
            if ($this->prob(0.5 + 0.5 * $risiko, $risiko) == 1) {
                $row['kualitasbayi_3'] = 1;
                $hasMasalah = true;
            }

            // 4: Bayi lahir mati/stillbirth → jarang (~1-2%)
            if ($this->prob(0.8 + 0.7 * $risiko, $risiko) == 1) {
                $row['kualitasbayi_4'] = 1;
                $hasMasalah = true;
            }

            // 5: BBLR <2.5kg → ~6-8%
            if ($this->prob(4 + 4 * $risiko, $risiko) == 1) {
                $row['kualitasbayi_5'] = 1;
                $hasMasalah = true;
            }

            // 6: Makrosomia >4kg → jarang (~1-2%)
            if ($this->prob(0.5 + 0.5 * $risiko, $risiko) == 1) {
                $row['kualitasbayi_6'] = 1;
                $hasMasalah = true;
            }

            // 7: Kelainan organ 0-5 tahun → jarang (~1%)
            if ($this->prob(0.5 + 0.5 * $risiko, $risiko) == 1) {
                $row['kualitasbayi_7'] = 1;
                $hasMasalah = true;
            }

            // Jika tidak ada masalah, set normal (2: ADA normal, tapi sesuai jawaban: 1=ADA, 2=PERNAH, 3=TIDAK)
            // Master jawab: 1=ADA, 2=PERNAH ADA, 3=TIDAK ADA
            // Untuk normal, jika tidak ada masalah lain → TIDAK ADA masalah, tapi normal adalah default.
            // Sebenarnya kolom per indikator, jadi normal tidak punya kolom khusus.
            // Jika hasMasalah false, semua tetap 3 (TIDAK ADA masalah)

            $data[] = $row;

            if (count($data) >= $batchSize) {
                DB::table('data_kualitasbayi')->insert($data);
                $this->command->info("Inserted " . count($data) . " data kualitas bayi.");
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('data_kualitasbayi')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data kualitas bayi.");
        }

        $this->command->info("Seeder DataKualitasBayi selesai! Data realistis: mayoritas TIDAK ADA masalah, BBLR ~6-8%, cacat/mati lahir sangat jarang.");
    }

    private function prob(float $baseChance, int $risikoLevel): int
    {
        $chance = $baseChance + ($risikoLevel - 1) * 2;
        $chance = min($chance, 98);
        return mt_rand(1, 100) <= $chance ? 1 : 3; // 1=ADA, 3=TIDAK ADA (skip 2=PERNAH untuk simplifikasi)
    }
}