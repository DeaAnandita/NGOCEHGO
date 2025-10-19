<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAsetLahanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_asetlahan')->truncate();

        // Insert data master
        DB::table('master_asetlahan')->insert([
            ['kdasetlahan' => 1, 'asetlahan' => 'Memiliki lahan pertanian'],
            ['kdasetlahan' => 2, 'asetlahan' => 'Memiliki lahan perkebunan'],
            ['kdasetlahan' => 3, 'asetlahan' => 'Memiliki lahan hutan'],
            ['kdasetlahan' => 4, 'asetlahan' => 'Memiliki lahan peternakan'],
            ['kdasetlahan' => 5, 'asetlahan' => 'Memiliki lahan perikanan'],
            ['kdasetlahan' => 6, 'asetlahan' => 'Memiliki tanah sewa'],
            ['kdasetlahan' => 7, 'asetlahan' => 'Memiliki tanah pinjam pakai'],
            ['kdasetlahan' => 8, 'asetlahan' => 'Memiliki tanah kerjasama pemanfaatan'],
            ['kdasetlahan' => 9, 'asetlahan' => 'Memiliki tanah bangun serah guna'],
            ['kdasetlahan' => 10, 'asetlahan' => 'Memiliki tanah tidak terpakai'],
        ]);
    }
}
