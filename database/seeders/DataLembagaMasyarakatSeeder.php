<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaMasyarakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nikList = DB::table('data_penduduk')
            ->pluck('nik')
            ->toArray();

        if (empty($nikList)) {
            $this->command->warn('Tabel data_penduduk kosong! Tidak ada data lembaga masyarakat yang bisa dibuat.');
            return;
        }

        $totalPenduduk = count($nikList);
        $this->command->info("Membuat data lembaga masyarakat realistis untuk {$totalPenduduk} penduduk di desa...");

        $batchSize = 500;
        $data = [];

        foreach ($nikList as $nik) {
            // Tingkat partisipasi sosial (1: rendah, 2: menengah, 3: tinggi)
            $partisipasiRand = mt_rand(1, 100);
            if ($partisipasiRand <= 30) {
                $partisipasi = 1;
            } elseif ($partisipasiRand <= 70) {
                $partisipasi = 2;
            } else {
                $partisipasi = 3;
            }

            $row = ['nik' => $nik];
            for ($i = 1; $i <= 48; $i++) {
                $row["lemmas_$i"] = 3; // Default Tidak
            }

            // Sangat umum di desa (banyak yang terlibat sebagai anggota/pengurus)
            $row['lemmas_4']  = $this->prob(50 + 20 * $partisipasi, $partisipasi); // Pengurus PKK (khususnya ibu-ibu)
            $row['lemmas_13'] = $this->prob(60 + 15 * $partisipasi, $partisipasi); // Pengurus Posyandu
            $row['lemmas_6']  = $this->prob(30 + 20 * $partisipasi, $partisipasi); // Pengurus Karang Taruna (pemuda)
            $row['lemmas_11'] = $this->prob(40 + 20 * $partisipasi, $partisipasi); // Pengurus organisasi keagamaan

            // Umum (RT/RW, kelompok tani, gotong royong)
            $this->applyCommon($row, [1,2,15,16], $partisipasi, 25 + 15 * $partisipasi); // RT, RW, kelompok tani, gotong royong

            // Sedang (LPM, adat, linmas, poskamling)
            $this->applyCommon($row, [3,5,7,8], $partisipasi, 15 + 12 * $partisipasi);

            // Jarang (organisasi profesi, pensiunan, pencinta alam, yayasan)
            $this->applyRare($row, [17,18,19,20,21,22,23,24,25], $partisipasi, 5 + 5 * $partisipasi);

            // Satgas (kebersihan, kebakaran, bencana) sedang
            $this->applyCommon($row, [25,26,27], $partisipasi, 10 + 10 * $partisipasi);

            $data[] = $row;

            if (count($data) >= $batchSize) {
                DB::table('data_lembagamasyarakat')->insert($data);
                $this->command->info("Inserted " . count($data) . " data lembaga masyarakat.");
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('data_lembagamasyarakat')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data lembaga masyarakat.");
        }

        $this->command->info("Seeder DataLembagaMasyarakat selesai! Data realistis: tinggi partisipasi di PKK, Posyandu, keagamaan; sedang di RT/RW/Karang Taruna; profesi/yayasan jarang.");
    }

    private function prob(float $baseChance, int $partisipasi): int
    {
        $chance = $baseChance + ($partisipasi - 1) * 12;
        $chance = min($chance, 95);
        return mt_rand(1, 100) <= $chance ? 1 : 3;
    }

    private function applyCommon(array &$row, array $fields, int $partisipasi, float $base): void
    {
        foreach ($fields as $f) {
            $row["lemmas_$f"] = $this->prob($base, $partisipasi);
        }
    }

    private function applyRare(array &$row, array $fields, int $partisipasi, float $base): void
    {
        foreach ($fields as $f) {
            $row["lemmas_$f"] = $this->prob($base, $partisipasi);
        }
    }
}