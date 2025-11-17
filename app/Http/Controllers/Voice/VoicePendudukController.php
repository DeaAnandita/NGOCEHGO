<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPenduduk;
use App\Models\DataKeluarga;
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
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class VoicePendudukController extends Controller
{
    // =====================================================
    // MENU AWAL
    // =====================================================
    public function index()
    {
        return view('voice.index');
    }

    // =====================================================
    // FORM VOICE UNTUK INPUT PENDUDUK
    // =====================================================
    public function penduduk()
    {
        return view('voice.penduduk', [
            'keluargas' => DataKeluarga::all(),
            'mutasi_masuks' => MasterMutasiMasuk::all(),
            'jenis_kelamins' => MasterJenisKelamin::all(),
            'agamas' => MasterAgama::all(),
            'hubungan_keluargas' => MasterHubunganKeluarga::all(),
            'hubungan_kepala_keluargas' => MasterHubunganKepalaKeluarga::all(),
            'status_kawins' => MasterStatusKawin::all(),
            'akta_nikahs' => MasterAktaNikah::all(),
            'tercantum_dalam_kks' => MasterTercantumDalamKK::all(),
            'status_tinggals' => MasterStatusTinggal::all(),
            'kartu_identitass' => MasterKartuIdentitas::all(),
            'pekerjaans' => MasterPekerjaan::all(),
            'provinsis' => MasterProvinsi::all(),
            'kabupatens' => MasterKabupaten::all(),
            'kecamatans' => MasterKecamatan::all(),
            'desas' => MasterDesa::all(),
        ]);
    }

    // =====================================================
    // CEK NIK — untuk validasi via suara
    // =====================================================
    public function cekNik(Request $request)
    {
        $exists = DataPenduduk::where('nik', $request->nik)->exists();
        return response()->json(['exists' => $exists]);
    }

    // =====================================================
    // CEK NO_KK — agar tidak memasukkan no_kk sembarangan
    // =====================================================
    public function cekNoKk(Request $request)
    {
        $exists = DataKeluarga::where('no_kk', $request->no_kk)->exists();
        return response()->json(['exists' => $exists]);
    }

    // =====================================================
    // STORE VIA VOICE
    // =====================================================
    public function store(Request $request)
    {
        try {

            $data = $request->all();

            // Jika wilayah kosong, hapus saja field wilayah
            if (empty($data['kdprovinsi'])) {
                unset($data['kdprovinsi'], $data['kdkabupaten'], $data['kdkecamatan'], $data['kddesa']);
            }

            DataPenduduk::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk berhasil disimpan melalui Voice Input'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
