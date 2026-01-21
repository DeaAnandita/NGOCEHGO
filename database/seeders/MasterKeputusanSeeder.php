<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKeputusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jenis_keputusan')->insert([
            ['kdjenis' => 1, 'jenis_keputusan' => 'Penetapan Perangkat Desa'],
            ['kdjenis' => 2, 'jenis_keputusan' => 'Pemberhentian Perangkat Desa'],
            ['kdjenis' => 3, 'jenis_keputusan' => 'Penetapan Penerima BLT'],
            ['kdjenis' => 4, 'jenis_keputusan' => 'Penetapan Penerima Bantuan Sosial'],
            ['kdjenis' => 5, 'jenis_keputusan' => 'Penetapan Pengelola BUMDes'],
            ['kdjenis' => 6, 'jenis_keputusan' => 'Penetapan Kelompok Tani'],
            ['kdjenis' => 7, 'jenis_keputusan' => 'Penetapan Tim Kegiatan Desa'],
            ['kdjenis' => 8, 'jenis_keputusan' => 'Penetapan Penerima Bantuan UMKM'],
        ]);

        // ===============================
        // MASTER KRITERIA KEPUTUSAN
        // ===============================
        DB::table('master_kriteria_keputusan')->insert([
            ['kdkriteria' => 1, 'kriteria' => 'Penghasilan'],
            ['kdkriteria' => 2, 'kriteria' => 'Jumlah Tanggungan'],
            ['kdkriteria' => 3, 'kriteria' => 'Kondisi Rumah'],
            ['kdkriteria' => 4, 'kriteria' => 'Kepemilikan Aset'],
            ['kdkriteria' => 5, 'kriteria' => 'Status Pekerjaan'],
            ['kdkriteria' => 6, 'kriteria' => 'Pendidikan'],
            ['kdkriteria' => 7, 'kriteria' => 'Usia'],
            ['kdkriteria' => 8, 'kriteria' => 'Kinerja'],
            ['kdkriteria' => 9, 'kriteria' => 'Kehadiran'],
            ['kdkriteria' => 10, 'kriteria' => 'Pengalaman'],
        ]);

        // ===============================
        // MASTER METODE KEPUTUSAN
        // ===============================
        DB::table('master_metode_keputusan')->insert([
            ['kdmetode' => 1, 'metode' => 'SAW'],
            ['kdmetode' => 2, 'metode' => 'TOPSIS'],
            ['kdmetode' => 3, 'metode' => 'MOORA'],
        ]);

        // ===============================
        // MASTER UNIT KEPUTUSAN
        // ===============================
        DB::table('master_unit_keputusan')->insert([
            ['kdunit' => 1, 'unit_keputusan' => 'Pemerintah Desa'],
            ['kdunit' => 2, 'unit_keputusan' => 'BPD'],
            ['kdunit' => 3, 'unit_keputusan' => 'BUMDes'],
            ['kdunit' => 4, 'unit_keputusan' => 'LPM'],
            ['kdunit' => 5, 'unit_keputusan' => 'Karang Taruna'],
            ['kdunit' => 6, 'unit_keputusan' => 'PKK'],
            ['kdunit' => 7, 'unit_keputusan' => 'Linmas'],
            ['kdunit' => 8, 'unit_keputusan' => 'Tim Pelaksana Kegiatan'],
        ]);

        // ===============================
        // MASTER STATUS KEPUTUSAN
        // ===============================
        DB::table('master_status_keputusan')->insert([
            ['kdstatus' => 1, 'status_keputusan' => 'Draft'],
            ['kdstatus' => 2, 'status_keputusan' => 'Dibahas'],
            ['kdstatus' => 3, 'status_keputusan' => 'Disetujui'],
            ['kdstatus' => 4, 'status_keputusan' => 'Ditetapkan'],
            ['kdstatus' => 5, 'status_keputusan' => 'Dibatalkan'],
        ]);
        // ===============================
        // MASTER STATUS ANGGARAN
        // ===============================
        DB::table('master_status_anggaran')->insert([
            ['kdstatus' => 1, 'status_anggaran' => 'Direncanakan'],
            ['kdstatus' => 2, 'status_anggaran' => 'Disetujui'],
            ['kdstatus' => 3, 'status_anggaran' => 'Direvisi'],
            ['kdstatus' => 4, 'status_anggaran' => 'Dibatalkan'],
        ]);

        // ===============================
        // MASTER STATUS PENCAIRAN
        // ===============================
        DB::table('master_status_pencairan')->insert([
            ['kdstatus' => 1, 'status_pencairan' => 'Diajukan'],
            ['kdstatus' => 2, 'status_pencairan' => 'Disetujui'],
            ['kdstatus' => 3, 'status_pencairan' => 'Cair'],
            ['kdstatus' => 4, 'status_pencairan' => 'Ditolak'],
            ['kdstatus' => 5, 'status_pencairan' => 'Dibatalkan'],
        ]);

        // ===============================
        // MASTER STATUS LPJ
        // ===============================
        DB::table('master_status_lpj')->insert([
            ['kdstatus' => 1, 'status_lpj' => 'Draft'],
            ['kdstatus' => 2, 'status_lpj' => 'Diajukan'],
            ['kdstatus' => 3, 'status_lpj' => 'Disahkan'],
            ['kdstatus' => 4, 'status_lpj' => 'Diperiksa'],
            ['kdstatus' => 5, 'status_lpj' => 'Ditolak'],
        ]);
    }
}
