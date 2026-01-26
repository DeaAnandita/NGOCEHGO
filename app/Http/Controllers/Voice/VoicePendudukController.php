<?php
namespace App\Http\Controllers\Voice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataPenduduk;
use App\Models\DataKelahiran;
use App\Models\DataSosialEkonomi;
use App\Models\DataUsahaArt;
use App\Models\DataProgramSerta;
use App\Models\DataLembagaDesa;
use App\Models\DataLembagaMasyarakat;
use App\Models\DataLembagaEkonomi;

// Master umum
use App\Models\MasterMutasiMasuk;
use App\Models\MasterProvinsi;
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

use Illuminate\Support\Facades\DB;use App\Models\VoiceFingerprint; 
use App\Http\Controllers\Voice\VoiceFingerprintController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VoicePendudukController extends Controller
{
    public function index(Request $request)
    {
        $mutasi = MasterMutasiMasuk::pluck('mutasimasuk', 'kdmutasimasuk');
        $provinsi = MasterProvinsi::pluck('provinsi', 'kdprovinsi');

        // Master data penduduk utama - SAMA PERSIS DENGAN BLADE
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

            // Program Serta
            'program_serta' => MasterProgramSerta::pluck('programserta', 'kdprogramserta'),
            'jawab_program_serta' => MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta'),

            // Lembaga
            'lembaga' => MasterLembaga::pluck('lembaga', 'kdlembaga'),
            'jawab_lemdes' => MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes'),
            'jawab_lemmas' => MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas'),
            'jawab_lemek' => MasterJawabLemek::pluck('jawablemek', 'kdjawablemek'),
        ];

        $programSerta = MasterProgramSerta::pluck('programserta', 'kdprogramserta')->toArray();
        $jawabProgramSerta = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();
        $lembaga = MasterLembaga::pluck('lembaga', 'kdlembaga')->toArray();
        $jawabLemdes = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray();
        $jawabLemmas = MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas')->toArray();
        $jawabLemek = MasterJawabLemek::pluck('jawablemek', 'kdjawablemek')->toArray();
        // ✅ JAVASCRIPT OPTIONS UNTUK BLADE
        $jsOptions = [
            'programSertaOptions' => MasterProgramSerta::pluck('programserta', 'kdprogramserta')->toArray(),
            'jawabProgramSertaOptions' => MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray(),
            'lembagaOptions' => MasterLembaga::pluck('lembaga', 'kdlembaga')->toArray(),
            'jawabLemdesOptions' => MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray(),
            'jawabLemmasOptions' => MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas')->toArray(),
            'jawabLemekOptions' => MasterJawabLemek::pluck('jawablemek', 'kdjawablemek')->toArray(),
        ];

        return view('voice.penduduk', compact(
            'mutasi', 'provinsi', 'masters',
            'programSerta', 'jawabProgramSerta', 
            'lembaga', 'jawabLemdes', 'jawabLemmas', 'jawabLemek',
            'jsOptions'  // ✅ TAMBAH INI
        ));


    }

    public function validateVoice(Request $request)
{
    $request->validate([
        'voice_sample' => 'required|file|mimes:webm,ogg,wav,mp3|max:20480',
    ]);

    try {
        $file = $request->file('voice_sample');
        $extension = $file->getClientOriginalExtension();
        $filename = 'voice_validation_' . Str::random(40) . '.' . $extension;
        $path = $file->storeAs('voice_samples', $filename, 'public');

        // Dummy embedding untuk testing
        $currentEmbedding = $this->extractVoiceEmbedding('');

        // Pastikan embedding valid
        if (!is_array($currentEmbedding) || count($currentEmbedding) < 20) {
            Storage::disk('public')->delete($path);
            return response()->json([
                'allowed' => false,
                'message' => 'Gagal memproses suara.'
            ], 422);
        }

        $threshold = 0.65;
        $existing = VoiceFingerprint::all();

        foreach ($existing as $record) {
            $existingEmbedding = is_array($record->embedding) 
                ? $record->embedding 
                : json_decode($record->embedding, true);

            if (!is_array($existingEmbedding) || count($existingEmbedding) !== count($currentEmbedding)) {
                continue;
            }

            $similarity = $this->cosineSimilarity($currentEmbedding, $existingEmbedding);
            if ($similarity >= $threshold) {
                Storage::disk('public')->delete($path);
                return response()->json([
                    'allowed' => false,
                    'message' => 'Suara sudah terdaftar.'
                ]);
            }
        }

        // Simpan sementara
        session(['temp_voice_embedding' => $currentEmbedding]);
        Storage::disk('public')->delete($path);

        return response()->json([
            'allowed' => true,
            'message' => 'Validasi berhasil.'
        ]);

    } catch (\Exception $e) {
        \Log::error('Voice validation error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
        return response()->json([
            'allowed' => false,
            'message' => 'Error server: ' . $e->getMessage()
        ], 500);
    }
}

    public function storeAll(Request $request)
    {
        \Log::info('VoicePenduduk storeAll called', [
            'user_id' => auth()->id(),
            'is_ajax' => $request->ajax(),
            'headers' => $request->headers->all(),
            'no_kk' => $request->no_kk,
            'nik' => $request->nik
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Sesi login telah berakhir. Silakan login kembali.'
            ], 401);
        }

        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
                'error' => 'Request tidak valid.'
            ], 400);
        }

        try {
            // Validasi input dasar - SAMA PERSIS DENGAN BLADE
            $request->validate([
                'no_kk' => 'required|digits:16|exists:data_keluarga,no_kk',
                'nik' => 'required|digits:16|unique:data_penduduk,nik',
                'penduduk_namalengkap' => 'required|string|max:255',
                'kdjeniskelamin' => 'required|exists:master_jeniskelamin,kdjeniskelamin',
                'kdagama' => 'required|exists:master_agama,kdagama',
                'penduduk_tanggallahir' => 'required',
                'penduduk_nourutkk' => 'nullable|string|regex:/^\d{2}$/',
            ]);

            // Validasi wilayah datang jika mutasi adalah "datang"
            if ($request->kdmutasimasuk == '3') {
                $request->validate([
                    'kdprovinsi' => 'required|exists:master_provinsi,kdprovinsi',
                    'kdkabupaten' => 'required|exists:master_kabupaten,kdkabupaten',
                    'kdkecamatan' => 'required|exists:master_kecamatan,kdkecamatan',
                    'kddesa' => 'required|exists:master_desa,kddesa',
                ]);
            }

            $data = $request->all();
            if (!empty($data['penduduk_tanggallahir'])) {
$data['penduduk_tanggallahir'] = Carbon::parse($data['penduduk_tanggallahir'])->format('Y-m-d');

            }

            // Validasi nomor urut KK
            if (!empty($data['penduduk_nourutkk'])) {
                $nourut = (int)$data['penduduk_nourutkk'];
                if ($nourut < 1 || $nourut > 99) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Nomor urut KK harus antara 1 sampai 99.'
                    ], 422);
                }
                $data['penduduk_nourutkk'] = str_pad($nourut, 2, '0', STR_PAD_LEFT);
            }

            // Mulai transaksi
            return DB::transaction(function () use ($data) {
                // 1. Simpan Data Penduduk Utama - **SAMA PERSIS DENGAN BLADE**
                $penduduk = DataPenduduk::create([
                    'no_kk'                     => $data['no_kk'],
                    'nik'                       => $data['nik'],
                    'penduduk_namalengkap'      => $data['penduduk_namalengkap'],
                    'penduduk_nourutkk'         => $data['penduduk_nourutkk'] ?? null,        // TAMBAH
                    'penduduk_tempatlahir'      => $data['penduduk_tempatlahir'] ?? null,     // TAMBAH (sebelumnya penduduk_tempat_lahir)
                    'penduduk_goldarah'         => $data['penduduk_goldarah'] ?? null,        // TAMBAH
                    'penduduk_noaktalahir'      => $data['penduduk_noaktalahir'] ?? null,     // TAMBAH
                    'penduduk_kewarganegaraan'  => $data['penduduk_kewarganegaraan'] ?? 'INDONESIA',
                    'kdjeniskelamin'            => $data['kdjeniskelamin'],
                    'kdagama'                   => $data['kdagama'],
                    'kdhubungankeluarga'        => $data['kdhubungankeluarga'] ?? null,
                    'kdhubungankepalakeluarga'  => $data['kdhubungankepalakeluarga'] ?? null,
                    'kdstatuskawin'             => $data['kdstatuskawin'] ?? null,
                    'kdaktanikah'               => $data['kdaktanikah'] ?? null,
                    'kdtercantumdalamkk'        => $data['kdtercantumdalamkk'] ?? null,
                    'kdstatustinggal'           => $data['kdstatustinggal'] ?? null,
                    'kdkartuidentitas'          => $data['kdkartuidentitas'] ?? null,
                    'kdpekerjaan'               => $data['kdpekerjaan'] ?? null,
                    'penduduk_namaayah'         => $data['penduduk_namaayah'] ?? null,        // TAMBAH
                    'penduduk_namaibu'          => $data['penduduk_namaibu'] ?? null,         // TAMBAH
                    'penduduk_namatempatbekerja'=> $data['penduduk_namatempatbekerja'] ?? null, // TAMBAH

                    // Mutasi
                    'kdmutasimasuk'             => $data['kdmutasimasuk'] ?? null,
                    'penduduk_tanggalmutasi'    => $data['penduduk_tanggalmutasi'] ?? null,

                    // Wilayah asal (hanya jika mutasi datang)
                    'kdprovinsi'                => $data['kdprovinsi'] ?? null,
                    'kdkabupaten'               => $data['kdkabupaten'] ?? null,
                    'kdkecamatan'               => $data['kdkecamatan'] ?? null,
                    'kddesa'                    => $data['kddesa'] ?? null,

                    'penduduk_tanggallahir' => $data['penduduk_tanggallahir']?? null,

                ]);

                DataKelahiran::create([
                    'nik'                     => $penduduk->nik,
                    'kdtempatpersalinan'      => $data['kdtempatpersalinan'] ?? null,
                    'kdjeniskelahiran'        => $data['kdjeniskelahiran'] ?? null,
                    'kdpertolonganpersalinan' => $data['kdpertolonganpersalinan'] ?? null,
                    'kelahiran_jamkelahiran'  => $data['kelahiran_jamkelahiran'] ?? null,   // ← SUDAH BENAR
                    'kelahiran_kelahiranke'   => $data['kelahiran_kelahiranke'] ?? null,
                    'kelahiran_berat'         => $data['kelahiran_berat'] ?? null,
                    'kelahiran_panjang'       => $data['kelahiran_panjang'] ?? null,
                    'kelahiran_nikibu'        => $data['kelahiran_nikibu'] ?? null,
                    'kelahiran_nikayah'       => $data['kelahiran_nikayah'] ?? null,
                ]);

                // 3. Simpan Sosial Ekonomi - **SAMA PERSIS DENGAN BLADE**
                DataSosialEkonomi::create([
                    'nik'                        => $penduduk->nik,
                    'kdpartisipasisekolah'       => $data['kdpartisipasisekolah'] ?? null,
                    'kdtingkatsulitdisabilitas'  => $data['kdtingkatsulitdisabilitas'] ?? null,
                    'kdstatuskedudukankerja'     => $data['kdstatuskedudukankerja'] ?? null,
                    'kdijasahterakhir'           => $data['kdijasahterakhir'] ?? null,
                    'kdpenyakitkronis'           => $data['kdpenyakitkronis'] ?? null,
                    'kdpendapatanperbulan'       => $data['kdpendapatanperbulan'] ?? null,
                    'kdjenisdisabilitas'         => $data['kdjenisdisabilitas'] ?? null,
                    'kdlapanganusaha'            => $data['kdlapanganusaha'] ?? null,
                    'kdimunisasi'                => $data['kdimunisasi'] ?? null,
                ]);

                // 4. Simpan Usaha ART - **SAMA PERSIS DENGAN BLADE**
                DataUsahaArt::create([
                    'nik'                    => $penduduk->nik,
                    'kdlapanganusaha'        => $data['kdlapanganusaha'] ?? null,
                    'kdtempatusaha'          => $data['kdtempatusaha'] ?? null,
                    'kdomsetusaha'           => $data['kdomsetusaha'] ?? null,
                    'usahaart_jumlahpekerja' => $data['usahaart_jumlahpekerja'] ?? 0,
                    'usahaart_namausaha'     => $data['usahaart_namausaha'] ?? null,
                ]);

                // 5. Program Serta (8 pertanyaan) → programserta_1 sampai programserta_8
                $program = ['nik' => $penduduk->nik];
                for ($i = 1; $i <= 8; $i++) {
                    $field = "programserta_$i";
                    $value = $data[$field] ?? 0;
                    $program[$field] = in_array($value, ['1', '2', '3', 1, 2, 3]) ? (int)$value : 0;
                }
                DataProgramSerta::create($program);

                // 6. Lembaga Desa (9 pertanyaan) → lemdes_1 sampai lemdes_9
                $lemdes = ['nik' => $penduduk->nik];
                for ($i = 1; $i <= 9; $i++) {
                    $field = "lemdes_$i";
                    $value = $data[$field] ?? 0;
                    $lemdes[$field] = in_array($value, ['1', '2', '3', 1, 2, 3]) ? (int)$value : 0;
                }
                DataLembagaDesa::create($lemdes);

                // 7. Lembaga Masyarakat (48 pertanyaan) → lemmas_1 sampai lemmas_48
                $lemmas = ['nik' => $penduduk->nik];
                for ($i = 1; $i <= 48; $i++) {
                    $field = "lemmas_$i";
                    $value = $data[$field] ?? 0;
                    $lemmas[$field] = in_array($value, ['1', '2', '3', 1, 2, 3]) ? (int)$value : 0;
                }
                DataLembagaMasyarakat::create($lemmas);

                // 8. Lembaga Ekonomi (74 pertanyaan) → lemek_1 sampai lemek_74
                $lemek = ['nik' => $penduduk->nik];
                for ($i = 1; $i <= 74; $i++) {   
                    $field = "lemek_$i";
                    $value = $data[$field] ?? 0;
                    $lemek[$field] = in_array($value, ['1', '2', '3', 1, 2, 3]) ? (int)$value : 0;
                }
                DataLembagaEkonomi::create($lemek);

                // ==================== SIMPAN VOICE FINGERPRINT ====================
                if (session()->has('temp_voice_embedding')) {
                    $fingerprint = session('temp_voice_embedding');

                    VoiceFingerprint::create([
                        'nik'        => $penduduk->nik,
                        'no_kk'      => $penduduk->no_kk,
                        'embedding'  => $fingerprint,
                    ]);

                    session()->forget('temp_voice_embedding');
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'Data penduduk berhasil disimpan sepenuhnya!',
                    'penduduk_id' => $penduduk->id
                ]);

            });


        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $field => $err) {
                $fieldName = str_replace(['_', 'kd', 'kdhubungan'], [' ', '', 'hubungan'], $field);
                $errors[] = ucfirst($fieldName) . ': ' . $err[0];
            }
            return response()->json([
                'success' => false,
                'error' => implode(' | ', $errors)
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Voice Penduduk Store Error: ' . $e->getMessage(), [
                'data' => $request->all(),
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan data penduduk: ' . $e->getMessage()
            ], 500);
        }
    }

    // Wilayah Datang - SAMA PERSIS DENGAN BLADE
    public function getKabupaten($kdprovinsi)
    {
        return response()->json(
            \DB::table('master_kabupaten')
                ->where('kdprovinsi', $kdprovinsi)
                ->orderBy('kabupaten')
                ->pluck('kabupaten', 'kdkabupaten')
        );
    }

    public function getKecamatan($kdkabupaten)
    {
        return response()->json(
            \DB::table('master_kecamatan')
                ->where('kdkabupaten', $kdkabupaten)
                ->orderBy('kecamatan')
                ->pluck('kecamatan', 'kdkecamatan')
        );
    }

    public function getDesa($kdkecamatan)
    {
        return response()->json(
            \DB::table('master_desa')
                ->where('kdkecamatan', $kdkecamatan)
                ->orderBy('desa')
                ->pluck('desa', 'kddesa')
        );
    }

    // Tambahan method untuk cek NIK real-time (opsional, biasa ada di blade)
    public function checkNik(Request $request)
    {
        $nik = $request->nik;
        $exists = DataPenduduk::where('nik', $nik)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK sudah terdaftar' : 'NIK tersedia'
        ]);
    }
        // Helper functions
    private function extractVoiceEmbedding(string $audioPath): ?array
    {
        // Dummy embedding tetap - cukup untuk testing
        return array_fill(0, 512, 0.5);
    }
    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = $normA = $normB = 0;
        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }
        return ($normA == 0 || $normB == 0) ? 0 : $dot / (sqrt($normA) * sqrt($normB));
    }
}
