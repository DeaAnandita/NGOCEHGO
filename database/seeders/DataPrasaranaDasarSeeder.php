<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPrasaranaDasarSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_prasaranadasar')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $noKKList = DB::table('data_keluarga')->pluck('no_kk')->toArray();

        if (empty($noKKList)) {
            $this->command->warn('Tabel data_keluarga kosong!');
            return;
        }

        $totalKK = count($noKKList);
        $this->command->info("Membuat data prasarana dasar untuk {$totalKK} keluarga tahun 2026...");

        $records = [];
        foreach ($noKKList as $noKK) {
            // Logika Ekonomi (1: Miskin, 2: Menengah Bawah, 3: Menengah Atas, 4: Mapan)
            $rand = mt_rand(1, 100);
            $ekonomi = ($rand <= 20) ? 1 : (($rand <= 70) ? 2 : (($rand <= 90) ? 3 : 4));

            $records[] = [
                'no_kk' => $noKK,

                // Sesuai Master Data
                'kdstatuspemilikbangunan' => $this->weightedChoice([1=>70, 2=>15, 3=>5, 5=>5, 6=>5], $ekonomi),
                'kdstatuspemiliklahan'    => $this->weightedChoice([1=>60, 2=>10, 3=>5, 4=>10, 5=>10, 6=>5], $ekonomi),
                'kdjenisfisikbangunan'    => $this->weightedChoice([1=>90, 2=>8, 3=>2], $ekonomi),
                
                // Atap
                'kdjenisatapbangunan'     => $this->weightedChoice([1=>10, 2=>20, 3=>20, 4=>30, 5=>10, 6=>10], $ekonomi),
                'kdkondisiatapbangunan'   => ($ekonomi == 1 && mt_rand(1,100) > 60) ? 2 : 1,

                // Dinding
                'kdjenisdindingbangunan'  => $this->weightedChoice([1=>70, 2=>15, 5=>10, 3=>5], $ekonomi),
                'kdkondisidindingbangunan' => ($ekonomi == 1 && mt_rand(1,100) > 70) ? 2 : 1,

                // Lantai
                'kdjenislantaibangunan'   => $this->weightedChoice([1=>5, 2=>60, 4=>15, 6=>10, 8=>10], $ekonomi),
                'kdkondisilantaibangunan' => ($ekonomi == 1 && mt_rand(1,100) > 70) ? 2 : 1,

                // Air
                'kdsumberairminum'        => $this->weightedChoice([1=>10, 2=>30, 3=>30, 5=>15, 7=>10, 10=>5], $ekonomi),
                'kdkondisisumberair'      => ($ekonomi == 1) ? mt_rand(1,4) : 1,
                'kdcaraperolehanair'      => $this->weightedChoice([1=>30, 2=>40, 3=>30], $ekonomi),
                'kdmanfaatmataair'        => mt_rand(2, 3),

                // Listrik (Perbaikan Nama Kolom)
                'kdsumberpeneranganutama'   => ($ekonomi == 1 && mt_rand(1,100) > 90) ? 3 : 1, 
                'kdsumberdayaterpasang'   => $this->weightedChoice([1=>40, 2=>40, 3=>10, 6=>10], $ekonomi),
                
                // Memasak
                'kdbahanbakarmemasak'     => $this->weightedChoice([3=>70, 2=>10, 8=>15, 1=>5], $ekonomi),

                // Sanitasi & Sampah
                'kdfasilitastempatbab'    => $this->weightedChoice([1=>80, 2=>15, 3=>5], $ekonomi),
                'kdpembuanganakhirtinja'  => $this->weightedChoice([1=>70, 3=>20, 4=>10], $ekonomi),
                'kdcarapembuangansampah'  => $this->weightedChoice([1=>40, 3=>20, 4=>30, 5=>10], $ekonomi),

                // Numerik
                'prasdas_luaslantai'      => ($ekonomi == 1) ? mt_rand(18, 36) : (($ekonomi == 4) ? mt_rand(100, 300) : mt_rand(36, 90)),
                'prasdas_jumlahkamar'     => ($ekonomi == 1) ? mt_rand(1, 2) : mt_rand(2, 5),
            ];

            if (count($records) >= 500) {
                DB::table('data_prasaranadasar')->insert($records);
                $records = [];
            }
        }

        if (!empty($records)) {
            DB::table('data_prasaranadasar')->insert($records);
        }
    }

    private function weightedChoice(array $options, int $ekonomi): int
    {
        $totalWeight = array_sum($options);
        $rand = mt_rand(1, $totalWeight);
        foreach ($options as $id => $weight) {
            if ($rand <= $weight) return $id;
            $rand -= $weight;
        }
        return array_key_first($options);
    }
}
