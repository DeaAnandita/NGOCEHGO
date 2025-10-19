<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use App\Models\MasterDusun;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    public function index()
    {
        $keluargas = DataKeluarga::with(['mutasi', 'dusun'])->get();
        return view('keluarga.index', compact('keluargas'));
    }

    public function create()
    {
        $mutasis = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->get();
        $kabupatens = MasterKabupaten::select('kdkabupaten', 'kabupaten')->get();
        $kecamatans = MasterKecamatan::select('kdkecamatan', 'kecamatan')->get();
        $desas = MasterDesa::select('kddesa', 'desa')->get();

        return view('keluarga.create', compact(
            'mutasis',
            'dusuns',
            'provinsis',
            'kabupatens',
            'kecamatans',
            'desas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
            'keluarga_tanggalmutasi' => 'required|date',
            'no_kk' => 'required|string|max:20|unique:data_keluarga,no_kk',
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'kddusun' => 'required|exists:master_dusun,kddusun',
            'keluarga_rw' => 'required|string|max:10',
            'keluarga_rt' => 'required|string|max:10',
            'keluarga_alamatlengkap' => 'required|string',
            'kdprovinsi' => 'nullable|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|exists:master_desa,kddesa',
        ]);

        DataKeluarga::create($validated);

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $keluarga = DataKeluarga::findOrFail($id);
        $mutasis = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->get();
        $kabupatens = MasterKabupaten::select('kdkabupaten', 'kabupaten')->get();
        $kecamatans = MasterKecamatan::select('kdkecamatan', 'kecamatan')->get();
        $desas = MasterDesa::select('kddesa', 'desa')->get();

        return view('keluarga.edit', compact(
            'keluarga',
            'mutasis',
            'dusuns',
            'provinsis',
            'kabupatens',
            'kecamatans',
            'desas'
        ));
    }

    public function update(Request $request, $id)
    {
        $keluarga = DataKeluarga::findOrFail($id);

        $validated = $request->validate([
            'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
            'keluarga_tanggalmutasi' => 'required|date',
            'no_kk' => 'required|string|max:20|unique:data_keluarga,no_kk,' . $id . ',no_kk',
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'kddusun' => 'required|exists:master_dusun,kddusun',
            'keluarga_rw' => 'required|string|max:10',
            'keluarga_rt' => 'required|string|max:10',
            'keluarga_alamatlengkap' => 'required|string',
            'kdprovinsi' => 'nullable|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|exists:master_desa,kddesa',
        ]);

        $keluarga->update($validated);

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keluarga = DataKeluarga::findOrFail($id);
        $keluarga->delete();

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil dihapus.');
    }
}
