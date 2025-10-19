<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_pekerjaan')->truncate();

        DB::table('master_pekerjaan')->insert([
            ['kdpekerjaan' => 1, 'pekerjaan' => 'BELUM/ TIDAK BEKERJA'],
            ['kdpekerjaan' => 2, 'pekerjaan' => 'MENGURUS RUMAH TANGGA'],
            ['kdpekerjaan' => 3, 'pekerjaan' => 'PELAJAR/ MAHASISWA'],
            ['kdpekerjaan' => 4, 'pekerjaan' => 'PENSIUNAN'],
            ['kdpekerjaan' => 5, 'pekerjaan' => 'PEGAWAI NEGERI SIPIL'],
            ['kdpekerjaan' => 6, 'pekerjaan' => 'TENTARA NASIONAL INDONESIA'],
            ['kdpekerjaan' => 7, 'pekerjaan' => 'KEPOLISIAN RI'],
            ['kdpekerjaan' => 8, 'pekerjaan' => 'PERDAGANGAN'],
            ['kdpekerjaan' => 9, 'pekerjaan' => 'PETANI/ PEKEBUN'],
            ['kdpekerjaan' => 10, 'pekerjaan' => 'PETERNAK'],
            ['kdpekerjaan' => 11, 'pekerjaan' => 'NELAYAN/ PERIKANAN'],
            ['kdpekerjaan' => 12, 'pekerjaan' => 'INDUSTRI'],
            ['kdpekerjaan' => 13, 'pekerjaan' => 'KONSTRUKSI'],
            ['kdpekerjaan' => 14, 'pekerjaan' => 'TRANSPORTASI'],
            ['kdpekerjaan' => 15, 'pekerjaan' => 'KARYAWAN SWASTA'],
            ['kdpekerjaan' => 16, 'pekerjaan' => 'KARYAWAN BUMN'],
            ['kdpekerjaan' => 17, 'pekerjaan' => 'KARYAWAN BUMD'],
            ['kdpekerjaan' => 18, 'pekerjaan' => 'KARYAWAN HONORER'],
            ['kdpekerjaan' => 19, 'pekerjaan' => 'BURUH HARIAN LEPAS'],
            ['kdpekerjaan' => 20, 'pekerjaan' => 'BURUH TANI/ PERKEBUNAN'],
            ['kdpekerjaan' => 21, 'pekerjaan' => 'BURUH NELAYAN/ PERIKANAN'],
            ['kdpekerjaan' => 22, 'pekerjaan' => 'BURUH PETERNAKAN'],
            ['kdpekerjaan' => 23, 'pekerjaan' => 'PEMBANTU RUMAH TANGGA'],
            ['kdpekerjaan' => 24, 'pekerjaan' => 'TUKANG CUKUR'],
            ['kdpekerjaan' => 25, 'pekerjaan' => 'TUKANG LISTRIK'],
        ]);
    }
}
