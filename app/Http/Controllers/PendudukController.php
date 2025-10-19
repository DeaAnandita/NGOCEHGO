<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penduduk.index', [
            'penduduks' => DataPenduduk::with('keluarga')->get(),
            'keluargas' => DataKeluarga::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penduduk.create', [
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_penduduk,nik',
            'no_kk' => 'required|string|size:16|exists:data_keluarga,no_kk',
            'penduduk_nourutkk' => 'required|string|max:4',
            'penduduk_goldarah' => 'required|string|max:2',
            'penduduk_noaktalahir' => 'required|string|max:255',
            'penduduk_namalengkap' => 'required|string|max:255',
            'penduduk_tempatlahir' => 'required|string|max:255',
            'penduduk_tanggallahir' => 'required|date',
            'kdmutasimasuk' => 'nullable|integer|exists:master_mutasimasuk,kdmutasimasuk',
            'penduduk_tanggalmutasi' => 'required|date',
            'penduduk_kewarganegaraan' => 'required|string|max:255',
            'kdjeniskelamin' => 'nullable|integer|exists:master_jeniskelamin,kdjeniskelamin',
            'kdagama' => 'nullable|integer|exists:master_agama,kdagama',
            'kdhubungankeluarga' => 'nullable|integer|exists:master_hubungankeluarga,kdhubungankeluarga',
            'kdhubungankepalakeluarga' => 'nullable|integer|exists:master_hubungankepalakeluarga,kdhubungankepalakeluarga',
            'kdstatuskawin' => 'nullable|integer|exists:master_statuskawin,kdstatuskawin',
            'kdaktanikah' => 'nullable|integer|exists:master_aktanikah,kdaktanikah',
            'kdtercantumdalamkk' => 'nullable|integer|exists:master_tercantumdalamkk,kdtercantumdalamkk',
            'kdstatustinggal' => 'nullable|integer|exists:master_statustinggal,kdstatustinggal',
            'kdkartuidentitas' => 'nullable|integer|exists:master_kartuidentitas,kdkartuidentitas',
            'kdpekerjaan' => 'nullable|integer|exists:master_pekerjaan,kdpekerjaan',
            'penduduk_namaayah' => 'nullable|string|max:255',
            'penduduk_namaibu' => 'nullable|string|max:255',
            'penduduk_namatempatbekerja' => 'nullable|string|max:255',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataPenduduk::create($request->all());

        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $penduduk = DataPenduduk::findOrFail($nik);
        return view('penduduk.edit', [
            'penduduk' => $penduduk,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_penduduk,nik,' . $nik . ',nik',
            'no_kk' => 'required|string|size:16|exists:data_keluarga,no_kk',
            'penduduk_nourutkk' => 'required|string|max:4',
            'penduduk_goldarah' => 'required|string|max:2',
            'penduduk_noaktalahir' => 'required|string|max:255',
            'penduduk_namalengkap' => 'required|string|max:255',
            'penduduk_tempatlahir' => 'required|string|max:255',
            'penduduk_tanggallahir' => 'required|date',
            'kdmutasimasuk' => 'nullable|integer|exists:master_mutasimasuk,kdmutasimasuk',
            'penduduk_tanggalmutasi' => 'required|date',
            'penduduk_kewarganegaraan' => 'required|string|max:255',
            'kdjeniskelamin' => 'nullable|integer|exists:master_jeniskelamin,kdjeniskelamin',
            'kdagama' => 'nullable|integer|exists:master_agama,kdagama',
            'kdhubungankeluarga' => 'nullable|integer|exists:master_hubungankeluarga,kdhubungankeluarga',
            'kdhubungankepalakeluarga' => 'nullable|integer|exists:master_hubungankepalakeluarga,kdhubungankepalakeluarga',
            'kdstatuskawin' => 'nullable|integer|exists:master_statuskawin,kdstatuskawin',
            'kdaktanikah' => 'nullable|integer|exists:master_aktanikah,kdaktanikah',
            'kdtercantumdalamkk' => 'nullable|integer|exists:master_tercantumdalamkk,kdtercantumdalamkk',
            'kdstatustinggal' => 'nullable|integer|exists:master_statustinggal,kdstatustinggal',
            'kdkartuidentitas' => 'nullable|integer|exists:master_kartuidentitas,kdkartuidentitas',
            'kdpekerjaan' => 'nullable|integer|exists:master_pekerjaan,kdpekerjaan',
            'penduduk_namaayah' => 'nullable|string|max:255',
            'penduduk_namaibu' => 'nullable|string|max:255',
            'penduduk_namatempatbekerja' => 'nullable|string|max:255',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $penduduk = DataPenduduk::findOrFail($nik);
        $penduduk->update($request->all());

        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $penduduk = DataPenduduk::findOrFail($nik);
        $penduduk->delete();

        return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil dihapus.');
    }
}