<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAsetKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_asetkeluarga')->truncate();

        DB::table('master_asetkeluarga')->insert([
            ['kdasetkeluarga' => 1, 'asetkeluarga' => 'Memiliki tabung gas 5,5kg atau lebih'],
            ['kdasetkeluarga' => 2, 'asetkeluarga' => 'Memiliki komputer/laptop'],
            ['kdasetkeluarga' => 3, 'asetkeluarga' => 'Memiliki tv dan elektronik sejenis lainnya'],
            ['kdasetkeluarga' => 4, 'asetkeluarga' => 'Memiliki ac/pendingin ruangan'],
            ['kdasetkeluarga' => 5, 'asetkeluarga' => 'Memiliki kulkas/lemari es atau pemanas air (water heater)'],
            ['kdasetkeluarga' => 6, 'asetkeluarga' => 'Memiliki rumah di tempat lain'],
            ['kdasetkeluarga' => 7, 'asetkeluarga' => 'Memiliki diesel listrik/bbm'],
            ['kdasetkeluarga' => 8, 'asetkeluarga' => 'Memiliki sepeda motor pribadi'],
            ['kdasetkeluarga' => 9, 'asetkeluarga' => 'Memiliki mobil pribadi dan sejenisnya'],
            ['kdasetkeluarga' => 10, 'asetkeluarga' => 'Memiliki perahu bermotor'],
            ['kdasetkeluarga' => 11, 'asetkeluarga' => 'Memiliki kapal barang'],
            ['kdasetkeluarga' => 12, 'asetkeluarga' => 'Memiliki kapal penumpang'],
            ['kdasetkeluarga' => 13, 'asetkeluarga' => 'Memiliki kapal pesiar'],
            ['kdasetkeluarga' => 14, 'asetkeluarga' => 'Memiliki atau menyewa helikopter pribadi'],
            ['kdasetkeluarga' => 15, 'asetkeluarga' => 'Memiliki atau menyewa pesawat terbang pribadi'],
            ['kdasetkeluarga' => 16, 'asetkeluarga' => 'Memiliki ternak besar'],
            ['kdasetkeluarga' => 17, 'asetkeluarga' => 'Memiliki ternak kecil'],
            ['kdasetkeluarga' => 18, 'asetkeluarga' => 'Memiliki hiasan emas/berlian'],
            ['kdasetkeluarga' => 19, 'asetkeluarga' => 'Memiliki buku tabungan bank'],
            ['kdasetkeluarga' => 20, 'asetkeluarga' => 'Memiliki buku surat berharga'],
            ['kdasetkeluarga' => 21, 'asetkeluarga' => 'Memiliki sertifikat deposito'],
            ['kdasetkeluarga' => 22, 'asetkeluarga' => 'Memiliki sertifikat tanah'],
            ['kdasetkeluarga' => 23, 'asetkeluarga' => 'Memiliki sertifikat bangunan'],
            ['kdasetkeluarga' => 24, 'asetkeluarga' => 'Memiliki perusahaan industri besar'],
            ['kdasetkeluarga' => 25, 'asetkeluarga' => 'Memiliki perusahaan industri menengah'],
            ['kdasetkeluarga' => 26, 'asetkeluarga' => 'Memiliki perusahaan industri kecil'],
            ['kdasetkeluarga' => 27, 'asetkeluarga' => 'Memiliki usaha perikanan'],
            ['kdasetkeluarga' => 28, 'asetkeluarga' => 'Memiliki usaha peternakan'],
            ['kdasetkeluarga' => 29, 'asetkeluarga' => 'Memiliki usaha perkebunan'],
            ['kdasetkeluarga' => 30, 'asetkeluarga' => 'Memiliki usaha pasar swalayan'],
            ['kdasetkeluarga' => 31, 'asetkeluarga' => 'Memiliki usaha di pasar swalayan'],
            ['kdasetkeluarga' => 32, 'asetkeluarga' => 'Memiliki usaha di pasar tradisional'],
            ['kdasetkeluarga' => 33, 'asetkeluarga' => 'Memiliki usaha di pasar desa'],
            ['kdasetkeluarga' => 34, 'asetkeluarga' => 'Memiliki usaha transportasi'],
            ['kdasetkeluarga' => 35, 'asetkeluarga' => 'Memiliki saham di perusahaan'],
            ['kdasetkeluarga' => 36, 'asetkeluarga' => 'Pelanggan Telkom'],
            ['kdasetkeluarga' => 37, 'asetkeluarga' => 'Memiliki hp GSM'],
            ['kdasetkeluarga' => 38, 'asetkeluarga' => 'Memiliki hp CDMA'],
            ['kdasetkeluarga' => 39, 'asetkeluarga' => 'Memiliki usaha wartel'],
            ['kdasetkeluarga' => 40, 'asetkeluarga' => 'Memiliki parabola'],
            ['kdasetkeluarga' => 41, 'asetkeluarga' => 'Berlangganan koran/majalah'],
        ]);
    }
}
