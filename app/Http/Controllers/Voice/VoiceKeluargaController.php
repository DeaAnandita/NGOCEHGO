<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataKeluarga;
use App\Models\DataPrasaranaDasar;
use App\Models\DataAsetKeluarga;
use App\Models\DataAsetLahan;
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

class VoiceKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $mutasi   = MasterMutasiMasuk::pluck('mutasimasuk', 'kdmutasimasuk');
        $dusun    = MasterDusun::pluck('dusun', 'kddusun');
        $provinsi = MasterProvinsi::pluck('provinsi', 'kdprovinsi');
        $asetKeluarga = MasterAsetKeluarga::orderBy('kdasetkeluarga')->pluck('asetkeluarga', 'kdasetkeluarga');
        $jawab = MasterJawab::pluck('jawab', 'kdjawab');
        $lahan = MasterAsetLahan::orderBy('kdasetlahan')->pluck('asetlahan', 'kdasetlahan');
        $jawabLahan = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan');

        $masters = [
            'status_pemilik_bangunan' => MasterStatusPemilikBangunan::pluck('statuspemilikbangunan', 'kdstatuspemilikbangunan'),
            'status_pemilik_lahan'    => MasterStatusPemilikLahan::pluck('statuspemiliklahan', 'kdstatuspemiliklahan'),
            'jenis_fisik_bangunan'    => MasterJenisFisikBangunan::pluck('jenisfisikbangunan', 'kdjenisfisikbangunan'),
            'jenis_lantai'            => MasterJenisLantaiBangunan::pluck('jenislantaibangunan', 'kdjenislantaibangunan'),
            'kondisi_lantai'          => MasterKondisiLantaiBangunan::pluck('kondisilantaibangunan', 'kdkondisilantaibangunan'),
            'jenis_dinding'           => MasterJenisDindingBangunan::pluck('jenisdindingbangunan', 'kdjenisdindingbangunan'),
            'kondisi_dinding'         => MasterKondisiDindingBangunan::pluck('kondisidindingbangunan', 'kdkondisidindingbangunan'),
            'jenis_atap'              => MasterJenisAtapBangunan::pluck('jenisatapbangunan', 'kdjenisatapbangunan'),
            'kondisi_atap'            => MasterKondisiAtapBangunan::pluck('kondisiatapbangunan', 'kdkondisiatapbangunan'),
            'sumber_air_minum'        => MasterSumberAirMinum::pluck('sumberairminum', 'kdsumberairminum'),
            'kondisi_sumber_air'      => MasterKondisiSumberAir::pluck('kondisisumberair', 'kdkondisisumberair'),
            'cara_perolehan_air'      => MasterCaraPerolehanAir::pluck('caraperolehanair', 'kdcaraperolehanair'),
            'sumber_penerangan'       => MasterSumberPeneranganUtama::pluck('sumberpeneranganutama', 'kdsumberpeneranganutama'),
            'daya_terpasang'          => MasterSumberDayaTerpasang::pluck('sumberdayaterpasang', 'kdsumberdayaterpasang'),
            'bahan_bakar'             => MasterBahanBakarMemasak::pluck('bahanbakarmemasak', 'kdbahanbakarmemasak'),
            'fasilitas_bab'           => MasterFasilitasTempatBab::pluck('fasilitastempatbab', 'kdfasilitastempatbab'),
            'pembuangan_tinja'        => MasterPembuanganAkhirTinja::pluck('pembuanganakhirtinja', 'kdpembuanganakhirtinja'),
            'pembuangan_sampah'       => MasterCaraPembuanganSampah::pluck('carapembuangansampah', 'kdcarapembuangansampah'),
            'manfaat_mataair'         => MasterManfaatMataAir::pluck('manfaatmataair', 'kdmanfaatmataair'),
        ];

        return view('voice.index', compact('mutasi', 'dusun', 'provinsi', 'asetKeluarga', 'jawab', 'lahan', 'jawabLahan') + ['masters' => $masters]);
    }

    public function storeAll(Request $request)
    {
        try {
            $request->validate([
                'no_kk' => 'required|digits:16|unique:data_keluarga,no_kk',
                'kdmutasimasuk' => 'required',
                'keluarga_tanggalmutasi' => 'required|date',
                'keluarga_kepalakeluarga' => 'required|string',
                'kddusun' => 'required',
                'keluarga_rw' => 'required|numeric|max:999',
                'keluarga_rt' => 'required|numeric|max:999',
                'keluarga_alamatlengkap' => 'required|string',
                'kdstatuspemilikbangunan' => 'required',
                'prasdas_luaslantai' => 'required|numeric',
                'prasdas_jumlahkamar' => 'required|integer',
            ]);

            $data = $request->all();

            $noKk = $data['no_kk'];
            if (strlen($noKk) !== 16) {
                return response()->json(['success' => false, 'error' => 'No KK harus 16 digit!'], 422);
            }

            if (DataKeluarga::where('no_kk', $noKk)->exists()) {
                return response()->json(['success' => false, 'error' => 'No KK sudah terdaftar!'], 422);
            }

            if (empty($data['kdprovinsi'])) {
                unset($data['kdprovinsi'], $data['kdkabupaten'], $data['kdkecamatan'], $data['kddesa']);
            }

            $keluarga = DataKeluarga::create([
                'no_kk' => $noKk,
                'kdmutasimasuk' => $data['kdmutasimasuk'],
                'keluarga_tanggalmutasi' => $data['keluarga_tanggalmutasi'],
                'keluarga_kepalakeluarga' => $data['keluarga_kepalakeluarga'],
                'kddusun' => $data['kddusun'],
                'keluarga_rw' => $data['keluarga_rw'],
                'keluarga_rt' => $data['keluarga_rt'],
                'keluarga_alamatlengkap' => $data['keluarga_alamatlengkap'],
                'kdprovinsi' => $data['kdprovinsi'] ?? null,
                'kdkabupaten' => $data['kdkabupaten'] ?? null,
                'kdkecamatan' => $data['kdkecamatan'] ?? null,
                'kddesa' => $data['kddesa'] ?? null,
            ]);

            DataPrasaranaDasar::create([
                'no_kk' => $keluarga->no_kk,
                'kdstatuspemilikbangunan' => $data['kdstatuspemilikbangunan'],
                'kdstatuspemiliklahan' => $data['kdstatuspemiliklahan'],
                'kdjenisfisikbangunan' => $data['kdjenisfisikbangunan'],
                'kdjenislantaibangunan' => $data['kdjenislantaibangunan'],
                'kdkondisilantaibangunan' => $data['kdkondisilantaibangunan'],
                'kdjenisdindingbangunan' => $data['kdjenisdindingbangunan'],
                'kdkondisidindingbangunan' => $data['kdkondisidindingbangunan'],
                'kdjenisatapbangunan' => $data['kdjenisatapbangunan'],
                'kdkondisiatapbangunan' => $data['kdkondisiatapbangunan'],
                'kdsumberairminum' => $data['kdsumberairminum'],
                'kdkondisisumberair' => $data['kdkondisisumberair'],
                'kdcaraperolehanair' => $data['kdcaraperolehanair'],
                'kdsumberpeneranganutama' => $data['kdsumberpeneranganutama'],
                'kdsumberdayaterpasang' => $data['kdsumberdayaterpasang'],
                'kdbahanbakarmemasak' => $data['kdbahanbakarmemasak'],
                'kdfasilitastempatbab' => $data['kdfasilitastempatbab'],
                'kdpembuanganakhirtinja' => $data['kdpembuanganakhirtinja'],
                'kdcarapembuangansampah' => $data['kdcarapembuangansampah'],
                'kdmanfaatmataair' => $data['kdmanfaatmataair'],
                'prasdas_luaslantai' => $data['prasdas_luaslantai'],
                'prasdas_jumlahkamar' => $data['prasdas_jumlahkamar'],
            ]);

            $asetData = ['no_kk' => $keluarga->no_kk];
            for ($i = 1; $i <= 42; $i++) {
                $field = "asetkeluarga_$i";
                $asetData[$field] = $data[$field] ?? 0;
            }
            DataAsetKeluarga::create($asetData);

            // storeAll() → TAMBAHKAN SETELAH DataAsetKeluarga
            $asetLahanData = ['no_kk' => $keluarga->no_kk];
            for ($i = 1; $i <= 10; $i++) {
                $field = "asetlahan_$i";
                $asetLahanData[$field] = $data[$field] ?? 0;
            }
            DataAsetLahan::create($asetLahanData);

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $msg = '';
            foreach ($e->errors() as $field => $err) {
                $msg .= ucfirst($field) . ': ' . $err[0] . ' ';
            }
            return response()->json(['success' => false, 'error' => trim($msg)], 422);
        } catch (\Exception $e) {
            \Log::error('Voice Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}