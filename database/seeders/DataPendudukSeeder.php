<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DataPendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_penduduk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('data_penduduk')->insert([
            [
                'nik' => '3374123400000001',
                'no_kk' => '3374123400000001',
                'kdmutasimasuk' => 1,
                'penduduk_tanggalmutasi' => Carbon::now(),
                'penduduk_kewarganegaraan' => 'INDONESIA',
                'penduduk_nourutkk' => '1',
                'penduduk_goldarah' => 'O',
                'penduduk_noaktalahir' => 'AKT-00001',
                'penduduk_namalengkap' => 'Budi Santoso',
                'penduduk_tempatlahir' => 'Kudus',
                'penduduk_tanggallahir' => '1985-03-12',
                'kdjeniskelamin' => 1,
                'kdagama' => 1,
                'kdhubungankeluarga' => 1,
                'kdhubungankepalakeluarga' => 1,
                'kdstatuskawin' => 2,
                'kdaktanikah' => 1,
                'kdtercantumdalamkk' => 1,
                'kdstatustinggal' => 1,
                'kdkartuidentitas' => 1,
                'kdpekerjaan' => 3,
                'penduduk_namaayah' => 'Sutarman',
                'penduduk_namaibu' => 'Sulastri',
                'penduduk_namatempatbekerja' => 'PT Overcode Teknologi',
                'kdprovinsi' => 1,
                'kdkabupaten' => 1,
                'kdkecamatan' => 1,
                'kddesa' => 1,
            ],
            [
                'nik' => '3374123400000002',
                'no_kk' => '3374123400000001',
                'kdmutasimasuk' => 1,
                'penduduk_tanggalmutasi' => Carbon::now(),
                'penduduk_kewarganegaraan' => 'INDONESIA',
                'penduduk_nourutkk' => '2',
                'penduduk_goldarah' => 'A',
                'penduduk_noaktalahir' => 'AKT-00002',
                'penduduk_namalengkap' => 'Siti Aminah',
                'penduduk_tempatlahir' => 'Kudus',
                'penduduk_tanggallahir' => '1988-07-19',
                'kdjeniskelamin' => 2,
                'kdagama' => 1,
                'kdhubungankeluarga' => 2,
                'kdhubungankepalakeluarga' => 2,
                'kdstatuskawin' => 2,
                'kdaktanikah' => 1,
                'kdtercantumdalamkk' => 1,
                'kdstatustinggal' => 1,
                'kdkartuidentitas' => 1,
                'kdpekerjaan' => 5,
                'penduduk_namaayah' => 'Sukarman',
                'penduduk_namaibu' => 'Sumiyati',
                'penduduk_namatempatbekerja' => 'Rumah Tangga',
                'kdprovinsi' => 1,
                'kdkabupaten' => 1,
                'kdkecamatan' => 2,
                'kddesa' => 2,
            ],
            [
                'nik' => '3374123400000003',
                'no_kk' => '3374123400000002',
                'kdmutasimasuk' => 2,
                'penduduk_tanggalmutasi' => Carbon::now(),
                'penduduk_kewarganegaraan' => 'INDONESIA',
                'penduduk_nourutkk' => '1',
                'penduduk_goldarah' => 'B',
                'penduduk_noaktalahir' => 'AKT-00003',
                'penduduk_namalengkap' => 'Ahmad Fauzi',
                'penduduk_tempatlahir' => 'Kudus',
                'penduduk_tanggallahir' => '1992-02-11',
                'kdjeniskelamin' => 1,
                'kdagama' => 1,
                'kdhubungankeluarga' => 1,
                'kdhubungankepalakeluarga' => 1,
                'kdstatuskawin' => 1,
                'kdaktanikah' => 2,
                'kdtercantumdalamkk' => 1,
                'kdstatustinggal' => 1,
                'kdkartuidentitas' => 1,
                'kdpekerjaan' => 4,
                'penduduk_namaayah' => 'Slamet',
                'penduduk_namaibu' => 'Rohani',
                'penduduk_namatempatbekerja' => 'CV Fadhila Gorden',
                'kdprovinsi' => 1,
                'kdkabupaten' => 1,
                'kdkecamatan' => 3,
                'kddesa' => 3,
            ],
        ]);
    }
}
