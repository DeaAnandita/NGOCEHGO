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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $keluargas = DataKeluarga::with(['mutasi', 'dusun', 'provinsi', 'kabupaten', 'kecamatan', 'desa'])
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhere('keluarga_kepalakeluarga', 'like', "%{$search}%")
                    ->orWhereHas('dusun', fn($q) => $q->where('dusun', 'like', "%{$search}%"));
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('keluarga.index', compact('keluargas', 'search', 'perPage'));
    }

    public function create()
    {
        $mutasis   = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns    = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get();

        return view('keluarga.create', compact('mutasis', 'dusuns', 'provinsis'));
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
        $keluarga  = DataKeluarga::findOrFail($id);
        $mutasis   = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns    = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get();

        // Tambahkan ini 3 baris
        $kabupatens = $keluarga->kdprovinsi ? MasterKabupaten::where('kdprovinsi', $keluarga->kdprovinsi)->orderBy('kabupaten')->get() : collect();
        $kecamatans = $keluarga->kdkabupaten ? MasterKecamatan::where('kdkabupaten', $keluarga->kdkabupaten)->orderBy('kecamatan')->get() : collect();
        $desas      = $keluarga->kdkecamatan ? MasterDesa::where('kdkecamatan', $keluarga->kdkecamatan)->orderBy('desa')->get() : collect();

        return view('keluarga.edit', compact('keluarga', 'mutasis', 'dusuns', 'provinsis', 'kabupatens', 'kecamatans', 'desas'));
    }

    public function update(Request $request, $id)
    {
        $keluarga = DataKeluarga::findOrFail($id);

        $validated = $request->validate([
            'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
            'keluarga_tanggalmutasi' => 'required|date',
            'no_kk' => 'required|string|max:20' . $id . ',no_kk',
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
    public function kabupaten($kdprovinsi)
    {
        $kabupatens = MasterKabupaten::where('kdprovinsi', $kdprovinsi)
            ->select('kdkabupaten', 'kabupaten')
            ->orderBy('kabupaten')
            ->get();

        return response()->json($kabupatens);
    }

    public function kecamatan($kdkabupaten)
    {
        $kecamatans = MasterKecamatan::where('kdkabupaten', $kdkabupaten)
            ->select('kdkecamatan', 'kecamatan')
            ->orderBy('kecamatan')
            ->get();

        return response()->json($kecamatans);
    }

    public function desa($kdkecamatan)
    {
        $desas = MasterDesa::where('kdkecamatan', $kdkecamatan)
            ->select('kddesa', 'desa')
            ->orderBy('desa')
            ->get();

        return response()->json($desas);
    }
}
