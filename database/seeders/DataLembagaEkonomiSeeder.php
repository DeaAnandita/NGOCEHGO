<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaEkonomiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua NIK dari data_penduduk (asumsi data ini adalah per individu yang memiliki/mengelola usaha)
        $nikList = DB::table('data_penduduk')
            ->pluck('nik')
            ->toArray();

        if (empty($nikList)) {
            $this->command->warn('Tabel data_penduduk kosong! Tidak ada data lembaga ekonomi yang bisa dibuat.');
            return;
        }

        $totalPenduduk = count($nikList);
        $this->command->info("Membuat data lembaga ekonomi realistis untuk {$totalPenduduk} penduduk di desa...");

        $batchSize = 500;
        $data = [];

        foreach ($nikList as $nik) {
            // Tingkat ekonomi/aktivitas usaha (1: rendah/miskin, 2: menengah, 3: tinggi)
            $tingkatRand = mt_rand(1, 100);
            if ($tingkatRand <= 40) {
                $tingkat = 1; // 40% rendah (kurang aktif usaha)
            } elseif ($tingkatRand <= 85) {
                $tingkat = 2; // 45% menengah
            } else {
                $tingkat = 3; // 15% tinggi (lebih banyak usaha)
            }

            // Default semua Tidak (3)
            $row = ['nik' => $nik];
            for ($i = 1; $i <= 75; $i++) {
                $row["lemek_$i"] = 3;
            }

            // Kelompok umum di desa (probabilitas tinggi)
            $row['lemek_11'] = $this->prob(45 + 15 * $tingkat, $tingkat); // Warung kelontongan/kios (sangat umum ~50-70%)
            $row['lemek_10'] = $this->prob(20 + 15 * $tingkat, $tingkat); // Toko/swalayan kecil
            $row['lemek_14'] = $this->prob(15 + 10 * $tingkat, $tingkat); // Angkutan darat (ojek/mobil angkut)
            $row['lemek_24'] = $this->prob(20 + 15 * $tingkat, $tingkat); // Usaha peternakan
            $row['lemek_25'] = $this->prob(15 + 10 * $tingkat, $tingkat); // Usaha perikanan (jika relevan)
            $row['lemek_26'] = $this->prob(30 + 20 * $tingkat, $tingkat); // Usaha perkebunan/pertanian kecil

            // Jasa tukang & kecil (umum di desa)
            $this->applyCommon($row, [35,36,37,38,39,40,41], $tingkat, 10 + 10 * $tingkat); // Tukang kayu, batu, jahit, cukur, dll.

            // Koperasi & simpan pinjam (sedang ~10-20%)
            $row['lemek_1']  = $this->prob(8 + 8 * $tingkat, $tingkat);  // Koperasi
            $row['lemek_17'] = $this->prob(12 + 10 * $tingkat, $tingkat); // Kelompok simpan pinjam

            // Industri kecil (makanan, kerajinan) ~15-30%
            $this->applyCommon($row, [2,3,4,5], $tingkat, 15 + 12 * $tingkat);

            // Usaha besar/modern (hotel, bank, industri besar) sangat jarang di desa (<2%)
            $this->applyRare($row, [54,56,59,62,64], $tingkat, 0.5); // Hotel, villa, bank, dll.

            // Hiburan tradisional & lain (jarang)
            $this->applyRare($row, [24,25,26,27,28,29,30,31], $tingkat, 2 + 2 * $tingkat); // Bioskop, grup musik, dll.

            $data[] = $row;

            if (count($data) >= $batchSize) {
                DB::table('data_lembagaekonomi')->insert($data);
                $this->command->info("Inserted " . count($data) . " data lembaga ekonomi.");
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('data_lembagaekonomi')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data lembaga ekonomi.");
        }

        $this->command->info("Seeder DataLembagaEkonomi selesai! Data realistis desa: mayoritas warung kecil, peternakan, tukang; koperasi sedang; usaha besar jarang.");
    }

    private function prob(float $baseChance, int $tingkat): int
    {
        $chance = $baseChance + ($tingkat - 1) * 10;
        $chance = min($chance, 95);
        return mt_rand(1, 100) <= $chance ? 1 : 3; // 1=Ya, 3=Tidak (2=Pernah jarang digunakan)
    }

    private function applyCommon(array &$row, array $fields, int $tingkat, float $base): void
    {
        foreach ($fields as $f) {
            $row["lemek_$f"] = $this->prob($base, $tingkat);
        }
    }

    private function applyRare(array &$row, array $fields, int $tingkat, float $base): void
    {
        foreach ($fields as $f) {
            $row["lemek_$f"] = $this->prob($base, $tingkat);
        }
    }
}