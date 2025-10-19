<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDesaSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('master_provinsi')->insert([
             
            ['kdprovinsi' => 1, 'provinsi' => 'Jawa Tenggah'],
           
        ]);
	    DB::table('master_kabupaten')->insert([
             
            ['kdkabupaten' => 1, 'kdprovinsi' => 1, 'kabupaten' => 'Kudus'],
           
        ]);
	    DB::table('master_kecamatan')->insert([
            ['kdkecamatan' => 1, 'kdkabupaten' => 1, 'kecamatan' => 'Kota Kudus'],
            ['kdkecamatan' => 2, 'kdkabupaten' => 1, 'kecamatan' => 'Jati'],
            ['kdkecamatan' => 3, 'kdkabupaten' => 1, 'kecamatan' => 'Undaan'],
            ['kdkecamatan' => 4, 'kdkabupaten' => 1, 'kecamatan' => 'Kaliwungu'],
            ['kdkecamatan' => 5, 'kdkabupaten' => 1, 'kecamatan' => 'Mejobo'],
            ['kdkecamatan' => 6, 'kdkabupaten' => 1, 'kecamatan' => 'Jekulo'],
            ['kdkecamatan' => 7, 'kdkabupaten' => 1, 'kecamatan' => 'Bae'],
            ['kdkecamatan' => 8, 'kdkabupaten' => 1, 'kecamatan' => 'Gebog'],
            ['kdkecamatan' => 9, 'kdkabupaten' => 1, 'kecamatan' => 'Dawe'],
        ]);
	    DB::table('master_desa')->insert([
            // KECAMATAN KOTA KUDUS (kdkecamatan = 1)
            ['kddesa' => 1, 'kdkecamatan' => 1, 'desa' => 'Barongan'],
            ['kddesa' => 2, 'kdkecamatan' => 1, 'desa' => 'Burikan'],
            ['kddesa' => 3, 'kdkecamatan' => 1, 'desa' => 'Demaan'],
            ['kddesa' => 4, 'kdkecamatan' => 1, 'desa' => 'Janggalan'],
            ['kddesa' => 5, 'kdkecamatan' => 1, 'desa' => 'Kajeksan'],
            ['kddesa' => 6, 'kdkecamatan' => 1, 'desa' => 'Kaliputu'],
            ['kddesa' => 7, 'kdkecamatan' => 1, 'desa' => 'Kramat'],
            ['kddesa' => 8, 'kdkecamatan' => 1, 'desa' => 'Krandon'],
            ['kddesa' => 9, 'kdkecamatan' => 1, 'desa' => 'Mlati Kidul'],
            ['kddesa' => 10, 'kdkecamatan' => 1, 'desa' => 'Mlati Lor'],
            ['kddesa' => 11, 'kdkecamatan' => 1, 'desa' => 'Nganguk'],
            ['kddesa' => 12, 'kdkecamatan' => 1, 'desa' => 'Panjunan'],
            ['kddesa' => 13, 'kdkecamatan' => 1, 'desa' => 'Purwosari'],
            ['kddesa' => 14, 'kdkecamatan' => 1, 'desa' => 'Rendeng'],
            ['kddesa' => 15, 'kdkecamatan' => 1, 'desa' => 'Singocandi'],
            ['kddesa' => 16, 'kdkecamatan' => 1, 'desa' => 'Wergu Kulon'],
            ['kddesa' => 17, 'kdkecamatan' => 1, 'desa' => 'Wergu Wetan'],

            // KECAMATAN JATI (kdkecamatan = 2)
            ['kddesa' => 18, 'kdkecamatan' => 2, 'desa' => 'Bakalan Krapyak'],
            ['kddesa' => 19, 'kdkecamatan' => 2, 'desa' => 'Banget'],
            ['kddesa' => 20, 'kdkecamatan' => 2, 'desa' => 'Blimbing Kidul'],
            ['kddesa' => 21, 'kdkecamatan' => 2, 'desa' => 'Gamong'],
            ['kddesa' => 22, 'kdkecamatan' => 2, 'desa' => 'Garung Kidul'],
            ['kddesa' => 23, 'kdkecamatan' => 2, 'desa' => 'Garung Lor'],
            ['kddesa' => 24, 'kdkecamatan' => 2, 'desa' => 'Getas Pejaten'],
            ['kddesa' => 25, 'kdkecamatan' => 2, 'desa' => 'Jati Kulon'],
            ['kddesa' => 26, 'kdkecamatan' => 2, 'desa' => 'Jati Wetan'],
            ['kddesa' => 27, 'kdkecamatan' => 2, 'desa' => 'Jetiskapuan'],
            ['kddesa' => 28, 'kdkecamatan' => 2, 'desa' => 'Kaliputu Kidul'],
            ['kddesa' => 29, 'kdkecamatan' => 2, 'desa' => 'Loram Kulon'],
            ['kddesa' => 30, 'kdkecamatan' => 2, 'desa' => 'Loram Wetan'],
            ['kddesa' => 31, 'kdkecamatan' => 2, 'desa' => 'Megawon'],
            ['kddesa' => 32, 'kdkecamatan' => 2, 'desa' => 'Ngembal Kulon'],
            ['kddesa' => 33, 'kdkecamatan' => 2, 'desa' => 'Ngembalrejo'],
            ['kddesa' => 34, 'kdkecamatan' => 2, 'desa' => 'Pasuruhan Kidul'],
            ['kddesa' => 35, 'kdkecamatan' => 2, 'desa' => 'Pasuruhan Lor'],
            ['kddesa' => 36, 'kdkecamatan' => 2, 'desa' => 'Ploso'],
            ['kddesa' => 37, 'kdkecamatan' => 2, 'desa' => 'Tanjungkarang'],
            ['kddesa' => 38, 'kdkecamatan' => 2, 'desa' => 'Tumpangkrasak'],

            // KECAMATAN BAE (kdkecamatan = 3)
            ['kddesa' => 39, 'kdkecamatan' => 3, 'desa' => 'Bacin'],
            ['kddesa' => 40, 'kdkecamatan' => 3, 'desa' => 'Besito'],
            ['kddesa' => 41, 'kdkecamatan' => 3, 'desa' => 'Gondangmanis'],
            ['kddesa' => 42, 'kdkecamatan' => 3, 'desa' => 'Gribig'],
            ['kddesa' => 43, 'kdkecamatan' => 3, 'desa' => 'Karangbener'],
            ['kddesa' => 44, 'kdkecamatan' => 3, 'desa' => 'Loram Kulon'],
            ['kddesa' => 45, 'kdkecamatan' => 3, 'desa' => 'Ngembalrejo'],
            ['kddesa' => 46, 'kdkecamatan' => 3, 'desa' => 'Pedawang'],
            ['kddesa' => 47, 'kdkecamatan' => 3, 'desa' => 'Peganjaran'],
            ['kddesa' => 48, 'kdkecamatan' => 3, 'desa' => 'Purworejo'],

            // KECAMATAN GEBOG (kdkecamatan = 4)
            ['kddesa' => 49, 'kdkecamatan' => 4, 'desa' => 'Besito'],
            ['kddesa' => 50, 'kdkecamatan' => 4, 'desa' => 'Getassrabi'],
            ['kddesa' => 51, 'kdkecamatan' => 4, 'desa' => 'Gondosari'],
            ['kddesa' => 52, 'kdkecamatan' => 4, 'desa' => 'Gribig'],
            ['kddesa' => 53, 'kdkecamatan' => 4, 'desa' => 'Jurang'],
            ['kddesa' => 54, 'kdkecamatan' => 4, 'desa' => 'Karangmalang'],
            ['kddesa' => 55, 'kdkecamatan' => 4, 'desa' => 'Klumpit'],
            ['kddesa' => 56, 'kdkecamatan' => 4, 'desa' => 'Menawan'],
            ['kddesa' => 57, 'kdkecamatan' => 4, 'desa' => 'Padurenan'],
            ['kddesa' => 58, 'kdkecamatan' => 4, 'desa' => 'Rahtawu'],

            // KECAMATAN DAWE (kdkecamatan = 5)
            ['kddesa' => 59, 'kdkecamatan' => 5, 'desa' => 'Cendono'],
            ['kddesa' => 60, 'kdkecamatan' => 5, 'desa' => 'Colo'],
            ['kddesa' => 61, 'kdkecamatan' => 5, 'desa' => 'Cranggang'],
            ['kddesa' => 62, 'kdkecamatan' => 5, 'desa' => 'Dukuhwaringin'],
            ['kddesa' => 63, 'kdkecamatan' => 5, 'desa' => 'Glagah Kulon'],
            ['kddesa' => 64, 'kdkecamatan' => 5, 'desa' => 'Japan'],
            ['kddesa' => 65, 'kdkecamatan' => 5, 'desa' => 'Kajar'],
            ['kddesa' => 66, 'kdkecamatan' => 5, 'desa' => 'Kandangmas'],
            ['kddesa' => 67, 'kdkecamatan' => 5, 'desa' => 'Kuwukan'],
            ['kddesa' => 68, 'kdkecamatan' => 5, 'desa' => 'Lau'],
            ['kddesa' => 69, 'kdkecamatan' => 5, 'desa' => 'Margorejo'],
            ['kddesa' => 70, 'kdkecamatan' => 5, 'desa' => 'Piji'],
            ['kddesa' => 71, 'kdkecamatan' => 5, 'desa' => 'Rejosari'],
            ['kddesa' => 72, 'kdkecamatan' => 5, 'desa' => 'Samirejo'],
            ['kddesa' => 73, 'kdkecamatan' => 5, 'desa' => 'Soco'],
            ['kddesa' => 74, 'kdkecamatan' => 5, 'desa' => 'Tergo'],
            ['kddesa' => 75, 'kdkecamatan' => 5, 'desa' => 'Ternadi'],

            // KECAMATAN JEKULO (kdkecamatan = 6)
            ['kddesa' => 76, 'kdkecamatan' => 6, 'desa' => 'Bulung Cangkring'],
            ['kddesa' => 77, 'kdkecamatan' => 6, 'desa' => 'Bulung Kulon'],
            ['kddesa' => 78, 'kdkecamatan' => 6, 'desa' => 'Gondoharum'],
            ['kddesa' => 79, 'kdkecamatan' => 6, 'desa' => 'Hadipolo'],
            ['kddesa' => 80, 'kdkecamatan' => 6, 'desa' => 'Honggosoco'],
            ['kddesa' => 81, 'kdkecamatan' => 6, 'desa' => 'Jekulo'],
            ['kddesa' => 82, 'kdkecamatan' => 6, 'desa' => 'Klaling'],
            ['kddesa' => 83, 'kdkecamatan' => 6, 'desa' => 'Klumpit'],
            ['kddesa' => 84, 'kdkecamatan' => 6, 'desa' => 'Pladen'],
            ['kddesa' => 85, 'kdkecamatan' => 6, 'desa' => 'Sadang'],
            ['kddesa' => 86, 'kdkecamatan' => 6, 'desa' => 'Sidomulyo'],
            ['kddesa' => 87, 'kdkecamatan' => 6, 'desa' => 'Tanjungrejo'],
            ['kddesa' => 88, 'kdkecamatan' => 6, 'desa' => 'Terban'],

            // KECAMATAN KALIWUNGU (kdkecamatan = 7)
            ['kddesa' => 89, 'kdkecamatan' => 7, 'desa' => 'Bangsri'],
            ['kddesa' => 90, 'kdkecamatan' => 7, 'desa' => 'Blimbing Kidul'],
            ['kddesa' => 91, 'kdkecamatan' => 7, 'desa' => 'Garung Lor'],
            ['kddesa' => 92, 'kdkecamatan' => 7, 'desa' => 'Kaliwungu'],
            ['kddesa' => 93, 'kdkecamatan' => 7, 'desa' => 'Karangampel'],
            ['kddesa' => 94, 'kdkecamatan' => 7, 'desa' => 'Kedungdowo'],
            ['kddesa' => 95, 'kdkecamatan' => 7, 'desa' => 'Mijen'],
            ['kddesa' => 96, 'kdkecamatan' => 7, 'desa' => 'Papringan'],
            ['kddesa' => 97, 'kdkecamatan' => 7, 'desa' => 'Prambatan Kidul'],
            ['kddesa' => 98, 'kdkecamatan' => 7, 'desa' => 'Prambatan Lor'],
            ['kddesa' => 99, 'kdkecamatan' => 7, 'desa' => 'Setrokalangan'],
            ['kddesa' => 100, 'kdkecamatan' => 7, 'desa' => 'Wates'],

            // KECAMATAN UNDAAN (kdkecamatan = 8)
            ['kddesa' => 101, 'kdkecamatan' => 8, 'desa' => 'Berugenjang'],
            ['kddesa' => 102, 'kdkecamatan' => 8, 'desa' => 'Glagahwaru'],
            ['kddesa' => 103, 'kdkecamatan' => 8, 'desa' => 'Kalirejo'],
            ['kddesa' => 104, 'kdkecamatan' => 8, 'desa' => 'Karangrowo'],
            ['kddesa' => 105, 'kdkecamatan' => 8, 'desa' => 'Kutuk'],
            ['kddesa' => 106, 'kdkecamatan' => 8, 'desa' => 'Larumbung'],
            ['kddesa' => 107, 'kdkecamatan' => 8, 'desa' => 'Medini'],
            ['kddesa' => 108, 'kdkecamatan' => 8, 'desa' => 'Ngemplak'],
            ['kddesa' => 109, 'kdkecamatan' => 8, 'desa' => 'Ngentak'],
            ['kddesa' => 110, 'kdkecamatan' => 8, 'desa' => 'Ngurensiti'],
            ['kddesa' => 111, 'kdkecamatan' => 8, 'desa' => 'Pladen'],
            ['kddesa' => 112, 'kdkecamatan' => 8, 'desa' => 'Sambung'],
            ['kddesa' => 113, 'kdkecamatan' => 8, 'desa' => 'Sidorejo'],
            ['kddesa' => 114, 'kdkecamatan' => 8, 'desa' => 'Undaan Kidul'],
            ['kddesa' => 115, 'kdkecamatan' => 8, 'desa' => 'Undaan Lor'],
            ['kddesa' => 116, 'kdkecamatan' => 8, 'desa' => 'Undaan Tengah'],
            ['kddesa' => 117, 'kdkecamatan' => 8, 'desa' => 'Wonosoco'],

            // KECAMATAN MEJOBO (kdkecamatan = 9)
            ['kddesa' => 118, 'kdkecamatan' => 9, 'desa' => 'Gondangmanis'],
            ['kddesa' => 119, 'kdkecamatan' => 9, 'desa' => 'Golantepus'],
            ['kddesa' => 120, 'kdkecamatan' => 9, 'desa' => 'Hadiwarno'],
            ['kddesa' => 121, 'kdkecamatan' => 9, 'desa' => 'Jojo'],
            ['kddesa' => 122, 'kdkecamatan' => 9, 'desa' => 'Kirig'],
            ['kddesa' => 123, 'kdkecamatan' => 9, 'desa' => 'Kutuk'],
            ['kddesa' => 124, 'kdkecamatan' => 9, 'desa' => 'Mejobo'],
            ['kddesa' => 125, 'kdkecamatan' => 9, 'desa' => 'Payaman'],
            ['kddesa' => 126, 'kdkecamatan' => 9, 'desa' => 'Temulus'],
            ['kddesa' => 127, 'kdkecamatan' => 9, 'desa' => 'Tenggeles'],
            ['kddesa' => 128, 'kdkecamatan' => 9, 'desa' => 'Gulang'],
        ]);
    }
}