<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPembuanganAkhirTinjaSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_pembuanganakhirtinja')->truncate();

        // Insert data master
        DB::table('master_pembuanganakhirtinja')->insert([
            ['kdpembuanganakhirtinja' => 1, 'pembuanganakhirtinja' => 'TANGKI'],
            ['kdpembuanganakhirtinja' => 2, 'pembuanganakhirtinja' => 'SPAL'],
            ['kdpembuanganakhirtinja' => 3, 'pembuanganakhirtinja' => 'LUBANG TANAH'],
            ['kdpembuanganakhirtinja' => 4, 'pembuanganakhirtinja' => 'KOLAM/ SAWAH/ SUNGAI/ DANAU/ LAUT'],
            ['kdpembuanganakhirtinja' => 5, 'pembuanganakhirtinja' => 'PANTAI/ TANAH LAPANG/ KEBUN'],
            ['kdpembuanganakhirtinja' => 6, 'pembuanganakhirtinja' => 'LAINNYA'],
        ]);
    }
}