<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetLahanSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data aset lahan yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data aset lahan untuk {$totalKK} keluarga...");

        $batchSize = 500;
        $dataBatch = [];

        foreach ($kkList as $no_kk) {
            // Tentukan kategori petani berdasarkan probabilitas realistis
            $rand = mt_rand(1, 100);

            if ($rand <= 40) {
                // 40% - Buruh tani / tidak punya lahan produktif
                $lahan = $this->generateBuruhTani();
            } elseif ($rand <= 80) {
                // 40% - Petani kecil
                $lahan = $this->generatePetaniKecil();
            } elseif ($rand <= 95) {
                // 15% - Petani sedang
                $lahan = $this->generatePetaniSedang();
            } else {
                // 5% - Petani besar / pengusaha lahan
                $lahan = $this->generatePetaniBesar();
            }

            $dataBatch[] = [
                'no_kk'        => $no_kk,
                'asetlahan_1'  => $lahan[0],  // Lahan pertanian (sawah)
                'asetlahan_2'  => $lahan[1],  // Lahan perkebunan
                'asetlahan_3'  => $lahan[2],  // Lahan hutan
                'asetlahan_4'  => $lahan[3],  // Lahan peternakan
                'asetlahan_5'  => $lahan[4],  // Lahan perikanan (tambak/kolam)
                'asetlahan_6'  => $lahan[5],  // Tanah sewa
                'asetlahan_7'  => $lahan[6],  // Tanah pinjam pakai
                'asetlahan_8'  => $lahan[7],  // Tanah kerjasama
                'asetlahan_9'  => $lahan[8],  // Tanah bangun serah guna
                'asetlahan_10' => $lahan[9],  // Tanah tidur / tidak terpakai
            ];

            // Insert per batch 500
            if (count($dataBatch) >= $batchSize) {
                DB::table('data_asetlahan')->insert($dataBatch);
                $this->command->info("Inserted " . count($dataBatch) . " data aset lahan.");
                $dataBatch = [];
            }
        }

        // Insert sisa data
        if (!empty($dataBatch)) {
            DB::table('data_asetlahan')->insert($dataBatch);
            $this->command->info("Inserted sisa " . count($dataBatch) . " data aset lahan.");
        }

        $this->command->info("Seeder DataAsetLahan selesai! {$totalKK} keluarga memiliki data aset lahan yang realistis dan bervariasi.");
    }

    // ==================== GENERATOR REALISTIS ====================

    private function generateBuruhTani(): array
    {
        // Tidak punya lahan produktif, tapi mungkin sewa atau punya tanah tidur
        return [
            0, // pertanian
            0, // perkebunan
            0, // hutan
            0, // peternakan
            0, // perikanan
            mt_rand(0, 1) ? 1 : 0, // sewa (kadang sewa sawah)
            mt_rand(0, 3) === 0 ? 1 : 0, // pinjam pakai (jarang)
            0, // kerjasama
            0, // bangun serah guna
            mt_rand(0, 1) ? 1 : 0, // tanah tidur (pekarangan tidak dipakai)
        ];
    }

    private function generatePetaniKecil(): array
    {
        $punyaSawah = mt_rand(1, 100) <= 80; // 80% punya sawah kecil
        $punyaKebun = mt_rand(1, 100) <= 40; // 40% punya kebun

        return [
            $punyaSawah ? 1 : 0,
            $punyaKebun ? 1 : 0,
            mt_rand(1, 100) <= 10 ? 1 : 0, // hutan kecil jarang
            mt_rand(1, 100) <= 30 ? 1 : 0, // peternakan kecil (kandang ayam)
            mt_rand(1, 100) <= 10 ? 1 : 0, // perikanan kecil
            mt_rand(1, 100) <= 30 ? 1 : 0, // sewa tambahan
            mt_rand(1, 100) <= 15 ? 1 : 0, // pinjam pakai
            mt_rand(1, 100) <= 10 ? 1 : 0, // kerjasama
            0, // bangun serah guna jarang
            mt_rand(1, 100) <= 40 ? 1 : 0, // tanah tidur biasa ada
        ];
    }

    private function generatePetaniSedang(): array
    {
        return [
            mt_rand(1, 100) <= 90 ? 1 : 0, // hampir pasti punya sawah
            mt_rand(1, 100) <= 70 ? 1 : 0, // sering punya kebun
            mt_rand(1, 100) <= 30 ? 1 : 0, // hutan rakyat
            mt_rand(1, 100) <= 60 ? 1 : 0, // peternakan sedang
            mt_rand(1, 100) <= 25 ? 1 : 0, // tambak/kolam
            mt_rand(1, 100) <= 40 ? 1 : 0, // sewa lahan tambahan
            mt_rand(1, 100) <= 30 ? 1 : 0, // pinjam pakai
            mt_rand(1, 100) <= 25 ? 1 : 0, // kerjasama usaha tani
            mt_rand(1, 100) <= 10 ? 1 : 0, // bangun serah guna
            mt_rand(1, 100) <= 50 ? 1 : 0, // tanah tidur
        ];
    }

    private function generatePetaniBesar(): array
    {
        // Punya banyak jenis lahan
        return [
            1, // pertanian besar
            mt_rand(1, 100) <= 90 ? 1 : 0,
            mt_rand(1, 100) <= 60 ? 1 : 0,
            mt_rand(1, 100) <= 80 ? 1 : 0,
            mt_rand(1, 100) <= 50 ? 1 : 0,
            mt_rand(1, 100) <= 60 ? 1 : 0,
            mt_rand(1, 100) <= 50 ? 1 : 0,
            mt_rand(1, 100) <= 40 ? 1 : 0,
            mt_rand(1, 100) <= 30 ? 1 : 0,
            mt_rand(1, 100) <= 40 ? 1 : 0,
        ];
    }
}