<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterOmsetUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_omsetusaha')->truncate();

        DB::table('master_omsetusaha')->insert([
            ['kdomsetusaha' => 1, 'omsetusaha' => 'KURANG DARI/SAMA DENGAN RP. 1 JUTA'],
            ['kdomsetusaha' => 2, 'omsetusaha' => 'RP. 1 JUTA S/D RP. 5 JUTA'],
            ['kdomsetusaha' => 3, 'omsetusaha' => 'RP. 5 JUTA S/D RP. 10 JUTA'],
            ['kdomsetusaha' => 4, 'omsetusaha' => 'LEBIH DARI/SAMA DENGAN RP. 10 JUTA'],
        ]);
    }
}
