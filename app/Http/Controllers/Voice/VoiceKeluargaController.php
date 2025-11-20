<?php
namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataKeluarga;
use App\Models\DataPrasaranaDasar;
use App\Models\DataAsetKeluarga;
use App\Models\DataAsetLahan;
use App\Models\DataAsetTernak;
use App\Models\DataAsetPerikanan;
use App\Models\DataSarprasKerja;
use App\Models\DataBangunKeluarga;
use App\Models\DataSejahteraKeluarga;
use App\Models\DataKonflikSosial;
use App\Models\DataKualitasIbuHamil;
use App\Models\DataKualitasBayi;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterDusun;
use App\Models\MasterProvinsi;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;
use App\Models\MasterStatusPemilikBangunan;
use App\Models\MasterStatusPemilikLahan;
use App\Models\MasterJenisFisikBangunan;
use App\Models\MasterJenisLantaiBangunan;
use App\Models\MasterKondisiLantaiBangunan;
use App\Models\MasterJenisDindingBangunan;
use App\Models\MasterKondisiDindingBangunan;
use App\Models\MasterJenisAtapBangunan;
use App\Models\MasterKondisiAtapBangunan;
use App\Models\MasterSumberAirMinum;
use App\Models\MasterKondisiSumberAir;
use App\Models\MasterCaraPerolehanAir;
use App\Models\MasterSumberPeneranganUtama;
use App\Models\MasterSumberDayaTerpasang;
use App\Models\MasterBahanBakarMemasak;
use App\Models\MasterFasilitasTempatBab;
use App\Models\MasterPembuanganAkhirTinja;
use App\Models\MasterCaraPembuanganSampah;
use App\Models\MasterManfaatMataAir;
use App\Models\MasterAsetLahan;
use App\Models\MasterJawabLahan;
use App\Models\MasterAsetTernak;
use App\Models\MasterAsetPerikanan;
use App\Models\MasterSarpraskerja;
use App\Models\MasterJawabSarpras;
use App\Models\MasterPembangunanKeluarga;
use App\Models\MasterJawabBangun;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;
use App\Models\MasterKualitasIbuHamil;
use App\Models\MasterJawabKualitasIbuHamil;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawabKualitasBayi;
use Illuminate\Support\Facades\DB;

class VoiceKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $mutasi = MasterMutasiMasuk::pluck('mutasimasuk', 'kdmutasimasuk');
        $dusun = MasterDusun::pluck('dusun', 'kddusun');
        $provinsi = MasterProvinsi::pluck('provinsi', 'kdprovinsi');
        $asetKeluarga = MasterAsetKeluarga::orderBy('kdasetkeluarga')->pluck('asetkeluarga', 'kdasetkeluarga');
        $jawab = MasterJawab::pluck('jawab', 'kdjawab');
        $lahan = MasterAsetLahan::orderBy('kdasetlahan')->pluck('asetlahan', 'kdasetlahan');
        $jawabLahan = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan');
        $asetTernak = MasterAsetTernak::orderBy('kdasetternak')->pluck('asetternak', 'kdasetternak');
        $asetPerikanan = MasterAsetPerikanan::orderBy('kdasetperikanan')->pluck('asetperikanan', 'kdasetperikanan');
        $sarprasOptions = MasterSarpraskerja::orderBy('kdsarpraskerja')->pluck('sarpraskerja', 'kdsarpraskerja');
        $jawabSarprasOptions = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras');
        $bangunKeluarga = MasterPembangunanKeluarga::where('kdtypejawab', 1)
            ->orderBy('kdpembangunankeluarga')
            ->limit(51)
            ->pluck('pembangunankeluarga', 'kdpembangunankeluarga');
        $jawabBangunOptions = MasterJawabBangun::pluck('jawabbangun', 'kdjawabbangun');
        $sejahteraKeluarga = MasterPembangunanKeluarga::where('kdtypejawab', 2)
            ->whereBetween('kdpembangunankeluarga', [61, 68])
            ->orderBy('kdpembangunankeluarga')
            ->pluck('pembangunankeluarga', 'kdpembangunankeluarga');
        $konflikSosialOptions = MasterKonflikSosial::orderBy('kdkonfliksosial')->pluck('konfliksosial', 'kdkonfliksosial');
        $jawabKonflikOptions = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik');
        $kualitasIbuHamilOptions = MasterKualitasIbuHamil::orderBy('kdkualitasibuhamil')->pluck('kualitasibuhamil', 'kdkualitasibuhamil');
        $jawabKualitasIbuHamilOptions = MasterJawabKualitasIbuHamil::pluck('jawabkualitasibuhamil', 'kdjawabkualitasibuhamil');
        $kualitasBayiOptions = MasterKualitasBayi::orderBy('kdkualitasbayi')->pluck('kualitasbayi', 'kdkualitasbayi');
        $jawabKualitasBayiOptions = MasterJawabKualitasBayi::pluck('jawabkualitasbayi', 'kdjawabkualitasbayi');

        $masters = [
            'status_pemilik_bangunan' => MasterStatusPemilikBangunan::pluck('statuspemilikbangunan', 'kdstatuspemilikbangunan'),
            'status_pemilik_lahan' => MasterStatusPemilikLahan::pluck('statuspemiliklahan', 'kdstatuspemiliklahan'),
            'jenis_fisik_bangunan' => MasterJenisFisikBangunan::pluck('jenisfisikbangunan', 'kdjenisfisikbangunan'),
            'jenis_lantai' => MasterJenisLantaiBangunan::pluck('jenislantaibangunan', 'kdjenislantaibangunan'),
            'kondisi_lantai' => MasterKondisiLantaiBangunan::pluck('kondisilantaibangunan', 'kdkondisilantaibangunan'),
            'jenis_dinding' => MasterJenisDindingBangunan::pluck('jenisdindingbangunan', 'kdjenisdindingbangunan'),
            'kondisi_dinding' => MasterKondisiDindingBangunan::pluck('kondisidindingbangunan', 'kdkondisidindingbangunan'),
            'jenis_atap' => MasterJenisAtapBangunan::pluck('jenisatapbangunan', 'kdjenisatapbangunan'),
            'kondisi_atap' => MasterKondisiAtapBangunan::pluck('kondisiatapbangunan', 'kdkondisiatapbangunan'),
            'sumber_air_minum' => MasterSumberAirMinum::pluck('sumberairminum', 'kdsumberairminum'),
            'kondisi_sumber_air' => MasterKondisiSumberAir::pluck('kondisisumberair', 'kdkondisisumberair'),
            'cara_perolehan_air' => MasterCaraPerolehanAir::pluck('caraperolehanair', 'kdcaraperolehanair'),
            'sumber_penerangan' => MasterSumberPeneranganUtama::pluck('sumberpeneranganutama', 'kdsumberpeneranganutama'),
            'daya_terpasang' => MasterSumberDayaTerpasang::pluck('sumberdayaterpasang', 'kdsumberdayaterpasang'),
            'bahan_bakar' => MasterBahanBakarMemasak::pluck('bahanbakarmemasak', 'kdbahanbakarmemasak'),
            'fasilitas_bab' => MasterFasilitasTempatBab::pluck('fasilitastempatbab', 'kdfasilitastempatbab'),
            'pembuangan_tinja' => MasterPembuanganAkhirTinja::pluck('pembuanganakhirtinja', 'kdpembuanganakhirtinja'),
            'pembuangan_sampah' => MasterCaraPembuanganSampah::pluck('carapembuangansampah', 'kdcarapembuangansampah'),
            'manfaat_mataair' => MasterManfaatMataAir::pluck('manfaatmataair', 'kdmanfaatmataair'),
        ];

        return view('voice.index', compact(
            'mutasi', 'dusun', 'provinsi', 'asetKeluarga', 'jawab', 'lahan', 'jawabLahan',
            'asetTernak', 'asetPerikanan', 'sarprasOptions', 'jawabSarprasOptions',
            'bangunKeluarga', 'jawabBangunOptions', 'sejahteraKeluarga',
            'konflikSosialOptions', 'jawabKonflikOptions', 'kualitasIbuHamilOptions',
            'jawabKualitasIbuHamilOptions', 'kualitasBayiOptions', 'jawabKualitasBayiOptions'
        ) + ['masters' => $masters]);
    }

    public function storeAll(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'no_kk' => 'required|digits:16|unique:data_keluarga,no_kk',
                'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
                'keluarga_tanggalmutasi' => 'required|date',
                'keluarga_kepalakeluarga' => 'required|string|max:255',
                'kddusun' => 'required|exists:master_dusun,kddusun',
                'keluarga_rw' => 'required|numeric|min:0|max:999',
                'keluarga_rt' => 'required|numeric|min:0|max:999',
                'keluarga_alamatlengkap' => 'required|string|max:500',
                'prasdas_luaslantai' => 'required|numeric|min:0',
                'prasdas_jumlahkamar' => 'required|integer|min:0',
                // Validasi wilayah jika ada
                // 'kdprovinsi' => 'nullable|exists:master_provinsi,kdprovinsi',
                // 'kdkabupaten' => 'nullable|exists:master_kabupaten,kdkabupaten',
                // 'kdkecamatan' => 'nullable|exists:master_kecamatan,kdkecamatan',
                // 'kddesa' => 'nullable|exists:master_desa,kddesa',
            ]);

            $data = $request->all();

            // Mulai transaksi
            return DB::transaction(function () use ($data) {
                // Simpan Data Keluarga
                $keluarga = DataKeluarga::create([
                    'no_kk' => $data['no_kk'],
                    'kdmutasimasuk' => $data['kdmutasimasuk'],
                    'keluarga_tanggalmutasi' => $data['keluarga_tanggalmutasi'],
                    'keluarga_kepalakeluarga' => $data['keluarga_kepalakeluarga'],
                    'kddusun' => $data['kddusun'],
                    'keluarga_rw' => $data['keluarga_rw'],
                    'keluarga_rt' => $data['keluarga_rt'],
                    'keluarga_alamatlengkap' => $data['keluarga_alamatlengkap'],
                    // 'kdprovinsi' => $data['kdprovinsi'] ?? null,
                    // 'kdkabupaten' => $data['kdkabupaten'] ?? null,
                    // 'kdkecamatan' => $data['kdkecamatan'] ?? null,
                    // 'kddesa' => $data['kddesa'] ?? null,
                ]);
                $keluarga->update([
                    'wilayah_datang_required' => $data['wilayah_datang_required'] ?? null,
                    'kdprovinsi' => $data['kdprovinsi'] ?? null,
                    'kdkabupaten' => $data['kdkabupaten'] ?? null,
                    'kdkecamatan' => $data['kdkecamatan'] ?? null,
                    'kddesa' => $data['kddesa'] ?? null,
                ]);
                // Simpan Prasarana Dasar
                DataPrasaranaDasar::create([
                    'no_kk' => $keluarga->no_kk,
                    'kdstatuspemilikbangunan' => $data['kdstatuspemilikbangunan'],
                    'kdstatuspemiliklahan' => $data['kdstatuspemiliklahan'] ?? null,
                    'kdjenisfisikbangunan' => $data['kdjenisfisikbangunan'] ?? null,
                    'kdjenislantaibangunan' => $data['kdjenislantaibangunan'] ?? null,
                    'kdkondisilantaibangunan' => $data['kdkondisilantaibangunan'] ?? null,
                    'kdjenisdindingbangunan' => $data['kdjenisdindingbangunan'] ?? null,
                    'kdkondisidindingbangunan' => $data['kdkondisidindingbangunan'] ?? null,
                    'kdjenisatapbangunan' => $data['kdjenisatapbangunan'] ?? null,
                    'kdkondisiatapbangunan' => $data['kdkondisiatapbangunan'] ?? null,
                    'kdsumberairminum' => $data['kdsumberairminum'] ?? null,
                    'kdkondisisumberair' => $data['kdkondisisumberair'] ?? null,
                    'kdcaraperolehanair' => $data['kdcaraperolehanair'] ?? null,
                    'kdsumberpeneranganutama' => $data['kdsumberpeneranganutama'] ?? null,
                    'kdsumberdayaterpasang' => $data['kdsumberdayaterpasang'] ?? null,
                    'kdbahanbakarmemasak' => $data['kdbahanbakarmemasak'] ?? null,
                    'kdfasilitastempatbab' => $data['kdfasilitastempatbab'] ?? null,
                    'kdpembuanganakhirtinja' => $data['kdpembuanganakhirtinja'] ?? null,
                    'kdcarapembuangansampah' => $data['kdcarapembuangansampah'] ?? null,
                    'kdmanfaatmataair' => $data['kdmanfaatmataair'] ?? null,
                    'prasdas_luaslantai' => $data['prasdas_luaslantai'],
                    'prasdas_jumlahkamar' => $data['prasdas_jumlahkamar'],
                ]);

                // Simpan Aset Keluarga
                $asetData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 42; $i++) {
                    $field = "asetkeluarga_$i";
                    $asetData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataAsetKeluarga::create($asetData);

                // Simpan Aset Lahan
                $asetLahanData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 10; $i++) {
                    $field = "asetlahan_$i";
                    $asetLahanData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataAsetLahan::create($asetLahanData);

                // Simpan Aset Ternak
                $asetTernakData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 24; $i++) {
                    $field = "asetternak_$i";
                    $asetTernakData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataAsetTernak::create($asetTernakData);

                // Simpan Aset Perikanan
                $asetPerikananData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 6; $i++) {
                    $field = "asetperikanan_$i";
                    $asetPerikananData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataAsetPerikanan::create($asetPerikananData);

                // Simpan Sarpras Kerja
                $asetSarprasData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 25; $i++) {
                    $field = "sarpraskerja_$i";
                    $asetSarprasData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataSarprasKerja::create($asetSarprasData);

                // Simpan Bangun Keluarga
                $bangunData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 51; $i++) {
                    $field = "bangunkeluarga_$i";
                    $bangunData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataBangunKeluarga::create($bangunData);

                // Simpan Sejahtera Keluarga
                $sejahteraData = ['no_kk' => $keluarga->no_kk];
                for ($i = 61; $i <= 68; $i++) {
                    $field = "sejahterakeluarga_$i";
                    $sejahteraData[$field] = $data[$field] ?? '';
                }
                DataSejahteraKeluarga::create($sejahteraData);

                // Simpan Konflik Sosial
                $konflikData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 32; $i++) {
                    $field = "konfliksosial_$i";
                    $konflikData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataKonflikSosial::create($konflikData);

                // Simpan Kualitas Ibu Hamil
                $kualitasData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 13; $i++) {
                    $field = "kualitasibuhamil_$i";
                    $kualitasData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataKualitasIbuHamil::create($kualitasData);

                // Simpan Kualitas Bayi
                $kualitasBayiData = ['no_kk' => $keluarga->no_kk];
                for ($i = 1; $i <= 7; $i++) {
                    $field = "kualitasbayi_$i";
                    $kualitasBayiData[$field] = isset($data[$field]) && is_numeric($data[$field]) ? $data[$field] : 0;
                }
                DataKualitasBayi::create($kualitasBayiData);

                return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $field => $err) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . $err[0];
            }
            return response()->json([
                'success' => false,
                'error' => implode(' | ', $errors)
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Voice Store Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Wilayah Datang
    public function getKabupaten($kdprovinsi)
    {
        $kabupaten = DB::table('master_kabupaten')
            ->where('kdprovinsi', $kdprovinsi)
            ->orderBy('kabupaten')
            ->pluck('kabupaten', 'kdkabupaten');

        return response()->json($kabupaten);
    }

    public function getKecamatan($kdkabupaten)
    {
        $kecamatan = DB::table('master_kecamatan')
            ->where('kdkabupaten', $kdkabupaten)
            ->orderBy('kecamatan')
            ->pluck('kecamatan', 'kdkecamatan');

        return response()->json($kecamatan);
    }

    public function getDesa($kdkecamatan)
    {
        $desa = DB::table('master_desa')
            ->where('kdkecamatan', $kdkecamatan)
            ->orderBy('desa')
            ->pluck('desa', 'kddesa');

        return response()->json($desa);
    }
}