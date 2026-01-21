<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // =========================
        // MASTER JENIS AGENDA
        // =========================
        DB::table('master_jenis_agenda')->insert([
            ['kdjenis' => 1, 'jenis_agenda' => 'Rapat'],
            ['kdjenis' => 2, 'jenis_agenda' => 'Musyawarah Desa'],
            ['kdjenis' => 3, 'jenis_agenda' => 'Sosialisasi'],
            ['kdjenis' => 4, 'jenis_agenda' => 'Monitoring'],
            ['kdjenis' => 5, 'jenis_agenda' => 'Evaluasi'],
            ['kdjenis' => 6, 'jenis_agenda' => 'Pelatihan'],
            ['kdjenis' => 7, 'jenis_agenda' => 'Kegiatan Lapangan'],
        ]);

        // =========================
        // MASTER STATUS AGENDA
        // =========================
        DB::table('master_status_agenda')->insert([
            ['kdstatus' => 1, 'status_agenda' => 'Direncanakan'],
            ['kdstatus' => 2, 'status_agenda' => 'Dilaksanakan'],
            ['kdstatus' => 3, 'status_agenda' => 'Selesai'],
            ['kdstatus' => 4, 'status_agenda' => 'Ditunda'],
            ['kdstatus' => 5, 'status_agenda' => 'Dibatalkan'],
        ]);

        // =========================
        // MASTER TEMPAT AGENDA
        // =========================
        DB::table('master_tempat_agenda')->insert([
            ['kdtempat' => 1, 'tempat_agenda' => 'Balai Desa'],
            ['kdtempat' => 2, 'tempat_agenda' => 'Aula BPD'],
            ['kdtempat' => 3, 'tempat_agenda' => 'Kantor BUMDes'],
            ['kdtempat' => 4, 'tempat_agenda' => 'Lapangan Desa'],
            ['kdtempat' => 5, 'tempat_agenda' => 'Rumah Warga'],
        ]);
    }
}
