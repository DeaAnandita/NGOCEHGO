<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPrasaranaDasarSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_prasaranadasar')->insert([
            [
                'no_kk' => '3374123400000001',
                'kdstatuspemilikbangunan' => 1,
                'kdstatuspemiliklahan' => 1,
                'kdjenisfisikbangunan' => 1,
                'kdjenislantaibangunan' => 1,
                'kdkondisilantaibangunan' => 1,
                'kdjenisdindingbangunan' => 1,
                'kdkondisidindingbangunan' => 1,
                'kdjenisatapbangunan' => 1,
                'kdkondisiatapbangunan' => 1,
                'kdsumberairminum' => 1,
                'kdkondisisumberair' => 1,
                'kdcaraperolehanair' => 1,
                'kdsumberpeneranganutama' => 1,
                'kdsumberdayaterpasang' => 1,
                'kdbahanbakarmemasak' => 1,
                'kdfasilitastempatbab' => 1,
                'kdpembuanganakhirtinja' => 1,
                'kdcarapembuangansampah' => 1,
                'kdmanfaatmataair' => 1,
                'prasdas_luaslantai' => 72.5,
                'prasdas_jumlahkamar' => 3,
            ],
            [
                'no_kk' => '3374123400000002',
                'kdstatuspemilikbangunan' => 2,
                'kdstatuspemiliklahan' => 1,
                'kdjenisfisikbangunan' => 2,
                'kdjenislantaibangunan' => 2,
                'kdkondisilantaibangunan' => 2,
                'kdjenisdindingbangunan' => 2,
                'kdkondisidindingbangunan' => 2,
                'kdjenisatapbangunan' => 2,
                'kdkondisiatapbangunan' => 2,
                'kdsumberairminum' => 2,
                'kdkondisisumberair' => 2,
                'kdcaraperolehanair' => 2,
                'kdsumberpeneranganutama' => 2,
                'kdsumberdayaterpasang' => 2,
                'kdbahanbakarmemasak' => 2,
                'kdfasilitastempatbab' => 2,
                'kdpembuanganakhirtinja' => 2,
                'kdcarapembuangansampah' => 2,
                'kdmanfaatmataair' => 2,
                'prasdas_luaslantai' => 54.0,
                'prasdas_jumlahkamar' => 2,
            ],
        ]);
    }
}
