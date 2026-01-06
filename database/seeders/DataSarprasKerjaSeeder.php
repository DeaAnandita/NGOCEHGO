<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSarprasKerjaSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data sarpras kerja yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data sarpras kerja untuk {$totalKK} keluarga...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan tingkat ekonomi keluarga (mirip seeder aset/bangun keluarga)
            // 1 = Rentan/Miskin, 2 = Berkembang, 3 = Sejahtera
            $rand = mt_rand(1, 100);
            if ($rand <= 20) {
                $tingkat = 1; // 20% Rentan
            } elseif ($rand <= 70) {
                $tingkat = 2; // 50% Berkembang
            } else {
                $tingkat = 3; // 30% Sejahtera
            }

            // Tentukan jenis pekerjaan utama secara acak dengan distribusi realistis desa
            $pekerjaan = $this->tentukanPekerjaan($tingkat);

            // Bangun data sarpras berdasarkan pekerjaan dan tingkat ekonomi
            $sarpras = $this->generateSarpras($pekerjaan, $tingkat);

            $row = ['no_kk' => $no_kk];
            for ($i = 1; $i <= 25; $i++) {
                $row["sarpraskerja_$i"] = $sarpras[$i] ?? 6; // default TIDAK MEMILIKI
            }

            $data[] = $row;

            // Batch insert
            if (count($data) >= $batchSize) {
                DB::table('data_sarpraskerja')->insert($data);
                $this->command->info("Inserted " . count($data) . " data sarpras kerja.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_sarpraskerja')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data sarpras kerja.");
        }

        $this->command->info("Seeder DataSarprasKerja selesai! Total {$totalKK} keluarga memiliki data sarpras kerja yang realistis untuk desa.");
    }

    /**
     * Tentukan jenis pekerjaan utama keluarga berdasarkan tingkat ekonomi
     */
    private function tentukanPekerjaan(int $tingkat): string
    {
        $rand = mt_rand(1, 100);

        // Distribusi pekerjaan umum di desa Indonesia
        if ($tingkat == 1) { // Rentan
            if ($rand <= 40) return 'buruh_harian';
            if ($rand <= 65) return 'petani_penggarap';
            if ($rand <= 80) return 'nelayan_tradisional';
            if ($rand <= 90) return 'ojek';
            return 'pedagang_kecil';
        }

        if ($tingkat == 2) { // Berkembang
            if ($rand <= 30) return 'petani_milik';
            if ($rand <= 50) return 'pedagang_kecil';
            if ($rand <= 65) return 'tukang_bengkel';
            if ($rand <= 75) return 'penjahit';
            if ($rand <= 85) return 'ojek';
            if ($rand <= 95) return 'meubel';
            return 'nelayan_tradisional';
        }

        // Sejahtera
        if ($rand <= 35) return 'petani_milik';
        if ($rand <= 55) return 'pedagang_kecil';
        if ($rand <= 70) return 'toko_kecil';
        if ($rand <= 80) return 'konveksi';
        if ($rand <= 90) return 'meubel';
        return 'bengkel';
    }

    /**
     * Generate sarpras berdasarkan pekerjaan dan tingkat ekonomi
     * Return array [1=>value, 3=>value, ...]
     */
    private function generateSarpras(string $pekerjaan, int $tingkat): array
    {
        $sarpras = [];

        // Semua keluarga punya kemungkinan punya motor (sangat umum di desa)
        if (mt_rand(1, 100) <= (60 + 20 * $tingkat)) {
            $kondisi = ($tingkat >= 2 && mt_rand(1, 100) <= 70) ? 1 : 2; // Bagus jika ekonomi baik
            $sarpras[3] = $kondisi; // SEPEDA MOTOR
        }

        switch ($pekerjaan) {
            case 'buruh_harian':
                // Hampir tidak punya alat produktif besar
                break;

            case 'petani_penggarap':
            case 'petani_milik':
                // Petani biasanya punya alat bangunan sederhana
                if (mt_rand(1, 100) <= 70) {
                    $sarpras[13] = (mt_rand(1, 100) <= 60) ? 1 : 2; // Alat bangunan
                }
                break;

            case 'nelayan_tradisional':
                if (mt_rand(1, 100) <= 80) $sarpras[1] = 1; // Jaring udang
                if (mt_rand(1, 100) <= 70) $sarpras[8] = 1; // Jala ikan
                if (mt_rand(1, 100) <= 60) $sarpras[9] = (mt_rand(1, 100) <= 40) ? 1 : 2; // Bubu
                break;

            case 'pedagang_kecil':
            case 'toko_kecil':
                if (mt_rand(1, 100) <= 80) $sarpras[21] = 1; // Alat PKL/pasar
                if (mt_rand(1, 100) <= 70) $sarpras[6] = 1;  // Gerobak
                if (mt_rand(1, 100) <= 60) $sarpras[14] = ($tingkat == 3) ? 1 : 2; // Etalase
                if ($pekerjaan == 'toko_kecil') {
                    $sarpras[7] = 1; // Toko fashion / toko kecil
                }
                if (mt_rand(1, 100) <= 40) $sarpras[10] = 1; // Alat kuliner
                break;

            case 'ojek':
                $sarpras[3] = ($tingkat >= 2) ? 1 : 2; // Pasti punya motor
                break;

            case 'tukang_bengkel':
            case 'bengkel':
                $sarpras[17] = 1; // Alat bengkel motor/mobil
                if (mt_rand(1, 100) <= 70) $sarpras[11] = 1; // Kompresor
                if ($tingkat == 3 && mt_rand(1, 100) <= 50) $sarpras[16] = 1; // Alat las
                break;

            case 'penjahit':
            case 'konveksi':
                $sarpras[18] = 1; // Alat konveksi
                if (mt_rand(1, 100) <= 60) $sarpras[14] = ($tingkat >= 2) ? 1 : 4; // Etalase (milik/sewa)
                break;

            case 'meubel':
                $sarpras[15] = 1; // Alat meubel
                if (mt_rand(1, 100) <= 80) $sarpras[12] = 1; // Mesin belah
                break;
        }

        // Tambahan kecil acak (jarang, tapi mungkin)
        if (mt_rand(1, 100) <= 5) $sarpras[25] = 2; // Becak (jarang)
        if (mt_rand(1, 100) <= 8 && $tingkat == 3) $sarpras[4] = 1; // Mobil (hanya sejahtera)

        return $sarpras;
    }
}