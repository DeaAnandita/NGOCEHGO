<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPenduduk;
use App\Models\DataKeluarga;
use App\Models\DataSosialEkonomi;
use App\Models\DataUsahaArt;
use App\Models\DataKelahiran;
use App\Models\DataProgramSerta;        // BARU
use App\Models\DataLembagaDesa;         // BARU
use App\Models\DataLembagaMasyarakat;
use App\Models\DataLembagaEkonomi;

// Master umum
use App\Models\MasterMutasiMasuk;
use App\Models\MasterJenisKelamin;
use App\Models\MasterAgama;
use App\Models\MasterHubunganKeluarga;
use App\Models\MasterHubunganKepalaKeluarga;
use App\Models\MasterStatusKawin;
use App\Models\MasterAktaNikah;
use App\Models\MasterTercantumDalamKK;
use App\Models\MasterStatusTinggal;
use App\Models\MasterKartuIdentitas;
use App\Models\MasterPekerjaan;
use App\Models\MasterProvinsi;

// Master Sosial Ekonomi
use App\Models\MasterPartisipasiSekolah;
use App\Models\MasterTingkatSulitDisabilitas;
use App\Models\MasterStatusKedudukanKerja;
use App\Models\MasterIjasahTerakhir;
use App\Models\MasterPenyakitKronis;
use App\Models\MasterPendapatanPerbulan;
use App\Models\MasterJenisDisabilitas;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterImunisasi;

// Master Usaha ART
use App\Models\MasterTempatUsaha;
use App\Models\MasterOmsetUsaha;

// Master Kelahiran
use App\Models\MasterTempatPersalinan;
use App\Models\MasterJenisKelahiran;
use App\Models\MasterPertolonganPersalinan;

// MASTER BARU UNTUK 2 MODUL TERBARU
use App\Models\MasterProgramSerta;
use App\Models\MasterJawabProgramSerta;
use App\Models\MasterJenisLembaga;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemdes;
use App\Models\MasterJawabLemmas;
use App\Models\MasterJawabLemek;

class VoicePendudukController extends Controller
{
    public function penduduk(Request $request)
    {
        $mutasi   = MasterMutasiMasuk::pluck('mutasimasuk', 'kdmutasimasuk');
        $provinsi = MasterProvinsi::pluck('provinsi', 'kdprovinsi');

        $masters = [
            // Data Pribadi & Keluarga
            'jenis_kelamin' => MasterJenisKelamin::pluck('jeniskelamin', 'kdjeniskelamin'),
            'agama' => MasterAgama::pluck('agama', 'kdagama'),
            'hubungan_keluarga' => MasterHubunganKeluarga::pluck('hubungankeluarga', 'kdhubungankeluarga'),
            'hubungan_kepala_keluarga' => MasterHubunganKepalaKeluarga::pluck('hubungankepalakeluarga', 'kdhubungankepalakeluarga'),
            'status_kawin' => MasterStatusKawin::pluck('statuskawin', 'kdstatuskawin'),
            'akta_nikah' => MasterAktaNikah::pluck('aktanikah', 'kdaktanikah'),
            'tercantum_kk' => MasterTercantumDalamKK::pluck('tercantumdalamkk', 'kdtercantumdalamkk'),
            'status_tinggal' => MasterStatusTinggal::pluck('statustinggal', 'kdstatustinggal'),
            'kartu_identitas' => MasterKartuIdentitas::pluck('kartuidentitas', 'kdkartuidentitas'),
            'pekerjaan' => MasterPekerjaan::pluck('pekerjaan', 'kdpekerjaan'),

            // Sosial Ekonomi
            'partisipasi_sekolah' => MasterPartisipasiSekolah::pluck('partisipasisekolah', 'kdpartisipasisekolah'),
            'tingkat_sulit_disabilitas' => MasterTingkatSulitDisabilitas::pluck('tingkatsulitdisabilitas', 'kdtingkatsulitdisabilitas'),
            'status_kedudukan_kerja' => MasterStatusKedudukanKerja::pluck('statuskedudukankerja', 'kdstatuskedudukankerja'),
            'ijasah_terakhir' => MasterIjasahTerakhir::pluck('ijasahterakhir', 'kdijasahterakhir'),
            'penyakit_kronis' => MasterPenyakitKronis::pluck('penyakitkronis', 'kdpenyakitkronis'),
            'pendapatan_perbulan' => MasterPendapatanPerbulan::pluck('pendapatanperbulan', 'kdpendapatanperbulan'),
            'jenis_disabilitas' => MasterJenisDisabilitas::pluck('jenisdisabilitas', 'kdjenisdisabilitas'),
            'lapangan_usaha' => MasterLapanganUsaha::pluck('lapanganusaha', 'kdlapanganusaha'),
            'imunisasi' => MasterImunisasi::pluck('imunisasi', 'kdimunisasi'),

            // Usaha ART
            'tempat_usaha' => MasterTempatUsaha::pluck('tempatusaha', 'kdtempatusaha'),
            'omset_usaha' => MasterOmsetUsaha::pluck('omsetusaha', 'kdomsetusaha'),

            // Kelahiran
            'tempat_persalinan' => MasterTempatPersalinan::pluck('tempatpersalinan', 'kdtempatpersalinan'),
            'jenis_kelahiran' => MasterJenisKelahiran::pluck('jeniskelahiran', 'kdjeniskelahiran'),
            'pertolongan_persalinan' => MasterPertolonganPersalinan::pluck('pertolonganpersalinan', 'kdpertolonganpersalinan'),

            // BARU: Program Serta
            'program_serta' => MasterProgramSerta::pluck('programserta', 'kdprogramserta'),
            'jawab_program_serta' => MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta'),

            // BARU: Lembaga Desa
            'lembaga' => MasterLembaga::pluck('lembaga', 'kdlembaga'),
            'jawab_lemdes' => MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes'),
            //Lembaga Masyarakat
            'jawab_lemmas' => MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas'),
            //Lembaga Ekonomi
            'jawab_lemek'  => MasterJawabLemek::pluck('jawablemek','kdjawablemek'),
       
        ];


        return view('voice.penduduk', compact('mutasi', 'provinsi') + ['masters' => $masters]);
    }

    // Simpan semua data utama (penduduk + sosial + usaha) - tetap sama
    public function storeAll(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|digits:16|unique:data_penduduks,nik',
                'penduduk_namalengkap' => 'required|string|max:255',
                'no_kk' => 'required|exists:data_keluarga,no_kk',
                'kdjeniskelamin' => 'required',
                'kdagama' => 'required',
                'kdmutasimasuk' => 'required',
                'penduduk_tanggalmutasi'=> 'required|date',
            ]);

            $data = $request->all();

            if (empty($data['kdprovinsi'])) {
                unset($data['kdprovinsi'], $data['kdkabupaten'], $data['kdkecamatan'], $data['kddesa']);
            }

            $penduduk = DataPenduduk::create($data);

            // Simpan Sosial Ekonomi
            $sosialFields = [
                'kdpartisipasisekolah', 'kdtingkatsulitdisabilitas', 'kdstatuskedudukankerja',
                'kdijasahterakhir', 'kdpenyakitkronis', 'kdpendapatanperbulan',
                'kdjenisdisabilitas', 'kdlapanganusaha', 'kdimunisasi'
            ];
            $sosialData = ['data_penduduk_id' => $penduduk->id];
            foreach ($sosialFields as $field) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    $sosialData[$field] = $data[$field];
                }
            }
            if (count($sosialData) > 1) {
                DataSosialEkonomi::create($sosialData);
            }

            // Simpan Usaha ART
            $usahaFields = [
                'usahaart_namausaha', 'kdtempatusaha', 'kdlapanganusaha_art',
                'kdomsetusaha', 'usahaart_jumlahpekerja'
            ];
            $usahaData = ['data_penduduk_id' => $penduduk->id];
            $hasUsaha = false;
            foreach ($usahaFields as $field) {
                if (!empty($data[$field])) {
                    $usahaData[$field] = $data[$field];
                    $hasUsaha = true;
                }
            }
            if ($hasUsaha) {
                DataUsahaArt::create($usahaData);
            }

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $msg = '';
            foreach ($e->errors() as $field => $err) {
                $msg .= ucfirst(str_replace('_', ' ', $field)) . ': ' . $err[0] . ' ';
            }
            return response()->json(['success' => false, 'error' => trim($msg)], 422);
        } catch (\Exception $e) {
            \Log::error('Voice Penduduk Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function storeKelahiran(Request $request) { /* tetap seperti sebelumnya */ }

    // BARU: Simpan Program Serta
    public function storeProgramSerta(Request $request)
    {
        $request->validate(['nik' => 'required|exists:data_penduduks,nik']);

        $fields = ['kks_kps','kip','kis','bpjs_non_pbi','jamsostek','asuransi_lain','pkh','raskin'];
        $data = $request->only($fields);

        $filled = collect($data)->filter(fn($v) => $v && $v !== '1')->count();
        if ($filled === 0) {
            return response()->json(['success' => true, 'message' => 'Tidak ada data Program Serta']);
        }

        $insert = ['nik' => $request->nik];
        foreach ($fields as $f) {
            $val = $request->input($f);
            if ($val && $val !== '1') $insert[$f] = $val;
        }

        DataProgramSerta::updateOrCreate(['nik' => $request->nik], $insert);

        return response()->json(['success' => true]);
    }

    // BARU: Simpan Lembaga Desa
    public function storeLembagaDesa(Request $request)
    {
        $request->validate(['nik' => 'required|exists:data_penduduks,nik']);

        $fields = [
            'kepala_desa','sekretaris_desa','kepala_urusan','kepala_dusun','staf_desa',
            'ketua_bpd','wakil_ketua_bpd','sekretaris_bpd','anggota_bpd'
        ];  
        $data = $request->only($fields);

        $filled = collect($data)->filter(fn($v) => $v && $v !== '1')->count();
        if ($filled === 0) {
            return response()->json(['success' => true, 'message' => 'Tidak ada data Lembaga Desa']);
        }

        $insert = ['nik' => $request->nik];
        foreach ($fields as $f) {
            $val = $request->input($f);
            if ($val && $val !== '1') $insert[$f] = $val;
        }

        DataLembagaDesa::updateOrCreate(['nik' => $request->nik], $insert);

        return response()->json(['success' => true]);
   
    }
    public function storeLembagaMasyarakat(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|exists:data_penduduks,nik',
        ]);

        // Daftar field form → kdjenislembaga (sama persis seperti di storeAll)
        $fields = [
            'lembaga_pengurus_rt'                => 42,
            'lembaga_anggota_pengurus_rt'        => 43,
            'lembaga_pengurus_rw'                => 44,
            'lembaga_anggota_pengurus_rw'        => 45,
            'lembaga_pengurus_lkmd'              => 46,
            'lembaga_anggota_lkmd'               => 47,
            'lembaga_pengurus_pkk'               => 48,
            'lembaga_anggota_pkk'                => 49,
            'lembaga_pengurus_lembaga_adat'      => 50,
            'lembaga_pengurus_karang_taruna'     => 51,
            'lembaga_anggota_karang_taruna'      => 52,
            'lembaga_pengurus_hansip'            => 53,
            'lembaga_pengurus_poskamling'        => 54,
            'lembaga_pengurus_org_perempuan'     => 55,
            'lembaga_anggota_org_perempuan'      => 56,
            'lembaga_pengurus_org_bapak'         => 57,
            'lembaga_anggota_org_bapak'          => 58,
            'lembaga_pengurus_org_keagamaan'     => 59,
            'lembaga_anggota_org_keagamaan'      => 60,
            'lembaga_pengurus_org_wartawan'      => 61,
            'lembaga_anggota_org_wartawan'       => 62,
            'lembaga_pengurus_posyandu'          => 63,
            'lembaga_pengurus_posyantekdes'      => 64,
            'lembaga_pengurus_kel_tani'          => 65,
            'lembaga_anggota_kel_tani'           => 66,
            'lembaga_pengurus_gotong_royong'     => 67,
            'lembaga_anggota_gotong_royong'      => 68,
            'lembaga_pengurus_org_guru'          => 69,
            'lembaga_anggota_org_guru'           => 70,
            'lembaga_pengurus_org_dokter'        => 71,
            'lembaga_anggota_org_dokter'         => 72,
            'lembaga_pengurus_org_pensiunan'     => 73,
            'lembaga_anggota_org_pensiunan'      => 74,
            'lembaga_pengurus_org_pemirsa'       => 75,
            'lembaga_anggota_org_pemirsa'        => 76,
            'lembaga_pengurus_pencinta_alam'     => 77,
            'lembaga_anggota_pencinta_alam'      => 78,
            'lembaga_pengurus_ilmu_pengetahuan'  => 79,
            'lembaga_anggota_ilmu_pengetahuan'   => 80,
            'lembaga_pemilik_yayasan'            => 81,
            'lembaga_pengurus_yayasan'           => 82,
            'lembaga_anggota_yayasan'            => 83,
            'lembaga_pengurus_satgas_kebersihan' => 84,
            'lembaga_anggota_satgas_kebersihan'  => 85,
            'lembaga_pengurus_satgas_kebakaran'  => 86,
            'lembaga_anggota_satgas_kebakaran'   => 87,
            'lembaga_pengurus_posko_bencana'     => 88,
            'lembaga_anggota_tim_bencana'        => 89,
        ];

        $recordsToInsert = [];
        $hasData = false;

        foreach ($fields as $field => $kdjenislembaga) {
            $jawaban = $request->input($field);

            // Hanya simpan jika jawaban bukan kosong dan bukan "Tidak Diisi" (kd = 1)
            if ($jawaban && $jawaban != '1') {
                $recordsToInsert[] = [
                    'nik'            => $request->nik,
                    'kdjenislembaga' => $kdjenislembaga,
                    'kdjawablemmas'  => $jawaban,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
                $hasData = true;
            }
        }

        // Jika tidak ada yang dipilih selain "Tidak Diisi"
        if (!$hasData) {
            // Hapus semua record lembaga masyarakat untuk NIK ini (opsional)
            DataLembagaMasyarakat::where('nik', $request->nik)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada data Lembaga Masyarakat yang disimpan.'
            ]);
        }

        // Hapus data lama, lalu insert baru (agar tidak duplikat saat update)
        DataLembagaMasyarakat::where('nik', $request->nik)->delete();
        DataLembagaMasyarakat::insert($recordsToInsert);

        return response()->json(['success' => true]);
    }
    public function storeLembagaEkonomi(Request $request)
{
    $request->validate([
        'nik' => 'required|digits:16|exists:data_penduduks,nik',
    ]);

    // Mapping field JS → kdlembaga (90 s/d 164)
    $fields = [
        '90'  => '90',  // KOPERASI
        '91'  => '91',  // UNIT USAHA SIMPAN PINJAM
        '92'  => '92',  // INDUSTRI KERAJINAN TANGAN
        '93'  => '93',  // INDUSTRI PAKAIAN
        '94'  => '94',  // INDUSTRI USAHA MAKANAN
        '95'  => '95',  // INDUSTRI ALAT RUMAH TANGGA
        '96'  => '96',  // INDUSTRI USAHA BAHAN BANGUNAN
        '97'  => '97',  // INDUSTRI ALAT PERTANIAN
        '98'  => '98',  // RESTORAN
        '99'  => '99',  // TOKO/ SWALAYAN
        '100' => '100', // WARUNG KELONTONGAN/KIOS
        '101' => '101', // ANGKUTAN DARAT
        '102' => '102', // ANGKUTAN SUNGAI
        '103' => '103', // ANGKUTAN LAUT
        '104' => '104', // ANGKUTAN UDARA
        '105' => '105', // JASA EKSPEDISI
        '106' => '106', // TUKANG SUMUR (duplikat, tetap disertakan)
        '107' => '107', // USAHA PASAR HARIAN
        '108' => '108', // USAHA PASAR MINGGUAN
        '109' => '109', // USAHA PASAR TERNAK
        '110' => '110', // USAHA PASAR HASIL BUMI DAN TAMBANG
        '111' => '111', // USAHA PERDAGANGAN ANTAR PULAU
        '112' => '112', // PENGIJON
        '113' => '113', // PEDAGANG PENGUMPUL/TENGKULAK
        '114' => '114', // USAHA PETERNAKAN
        '115' => '115', // USAHA PERIKANAN
        '116' => '116', // USAHA PERKEBUNAN
        '117' => '117', // KELOMPOK SIMPAN PINJAM
        '118' => '118', // USAHA MINUMAN
        '119' => '119', // INDUSTRI FARMASI
        '120' => '120', // INDUSTRI KAROSERI
        '121' => '121', // PENITIPAN KENDARAAN BERMOTOR
        '122' => '122', // INDUSTRI PERAKITAN ELEKTRONIK
        '123' => '123', // PENGOLAHAN KAYU
        '124' => '124', // BIOSKOP
        '125' => '125', // FILM KELILING
        '126' => '126', // SANDIWARA/DRAMA
        '127' => '127', // GROUP LAWAK
        '128' => '128', // JAIPONGAN
        '129' => '129', // WAYANG ORANG/GOLEK
        '130' => '130', // GROUP MUSIK/BAND
        '131' => '131', // GROUP VOKAL/PADUAN SUARA
        '132' => '132', // USAHA PERSEWAAN TENAGA LISTRIK
        '133' => '133', // USAHA PENGECER GAS DAN BAHAN BAKAR MINYAK
        '134' => '134', // USAHA AIR MINUM DALAM KEMASAN
        '135' => '135', // TUKANG KAYU
        '136' => '136', // TUKANG BATU
        '137' => '137', // TUKANG JAHIT/BORDIR
        '138' => '138', // TUKANG CUKUR
        '139' => '139', // TUKANG SERVICE ELEKTRONIK
        '140' => '140', // TUKANG BESI
        '141' => '141', // TUKANG PIJAT/URUT
        '142' => '142', // TUKANG SUMUR (kedua)
        '143' => '143', // NOTARIS
        '144' => '144', // PENGACARA/ADVOKAT
        '145' => '145', // KONSULTAN MANAJEMEN
        '146' => '146', // KONSULTAN TEKNIS
        '147' => '147', // PEJABAT PEMBUAT AKTA TANAH
        '148' => '148', // LOSMEN
        '149' => '149', // WISMA
        '150' => '150', // ASRAMA
        '151' => '151', // PERSEWAAN KAMAR
        '152' => '152', // KONTRAKAN RUMAH
        '153' => '153', // MESS
        '154' => '154', // HOTEL
        '155' => '155', // HOME STAY
        '156' => '156', // VILLA
        '157' => '157', // TOWN HOUSE
        '158' => '158', // USAHA ASURANSI
        '159' => '159', // LEMBAGA KEUANGAN BUKAN BANK
        '160' => '160', // LEMBAGA PERKREDITAN RAKYAT
        '161' => '161', // PEGADAIAN
        '162' => '162', // BANK PERKREDITAN RAKYAT
        '163' => '163', // USAHA PENYEWAAN ALAT PESTA
        '164' => '164', // USAHA PENGOLAHAN DAN PENJUALAN HASIL HUTAN
    ];

    $recordsToInsert = [];
    $hasData = false;

    foreach ($fields as $field => $kdlembaga) {
        $jawaban = $request->input($field);

        // Hanya simpan jika jawaban bukan kosong dan bukan "Tidak Diisi" (kode 1)
        if ($jawaban && $jawaban !== '1') {
            $recordsToInsert[] = [
                'nik'           => $request->nik,
                'kdlembaga'     => $kdlembaga,
                'kdjawablemek'  => $jawaban,   // 2=Ya, 3=Pernah, 4=Tidak
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            $hasData = true;
        }
    }

    // Jika semua jawaban "Tidak Diisi" → hapus semua data lembaga ekonomi untuk NIK ini
    if (!$hasData) {
        DataLembagaEkonomi::where('nik', $request->nik)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Tidak ada data Lembaga Ekonomi yang disimpan.'
        ]);
    }

    // Hapus data lama, lalu insert yang baru (agar selalu fresh saat update)
    DataLembagaEkonomi::where('nik', $request->nik)->delete();
    DataLembagaEkonomi::insert($recordsToInsert);

    return response()->json(['success' => true]);
}
}