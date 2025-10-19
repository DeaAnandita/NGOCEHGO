<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\DataKeluarga;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterDusun;
use Illuminate\Http\Request;

class DataKeluargaController extends Controller
{
    public function index()
    {
        $data = DataKeluarga::with(['mutasi', 'dusun'])->get();

        return view('administrasi.keluarga.index', compact('data'));
    }

    public function create()
    {
        $mutasi = MasterMutasiMasuk::all();
        $dusun = MasterDusun::all();

        return view('administrasi.keluarga.form', [
            'isEdit' => false,
            'data' => new DataKeluarga(),
            'mutasi' => $mutasi,
            'dusun' => $dusun,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|string|max:16|unique:data_keluarga,no_kk',
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'keluarga_rw' => 'required|string|max:3',
            'keluarga_rt' => 'required|string|max:3',
            'keluarga_alamatlengkap' => 'required|string',
        ]);

        DataKeluarga::create($validated + [
            'kdmutasimasuk' => $request->kdmutasimasuk,
            'kddusun' => $request->kddusun,
            'keluarga_tanggalmutasi' => $request->keluarga_tanggalmutasi ?? now(),
        ]);

        return redirect()->route('administrasi.keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $data = DataKeluarga::findOrFail($no_kk);
        $mutasi = MasterMutasiMasuk::all();
        $dusun = MasterDusun::all();

        return view('administrasi.keluarga.form', [
            'isEdit' => true,
            'data' => $data,
            'mutasi' => $mutasi,
            'dusun' => $dusun,
        ]);
    }

    public function update(Request $request, $no_kk)
    {
        $data = DataKeluarga::findOrFail($no_kk);

        $validated = $request->validate([
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'keluarga_rw' => 'required|string|max:3',
            'keluarga_rt' => 'required|string|max:3',
            'keluarga_alamatlengkap' => 'required|string',
        ]);

        $data->update($validated + [
            'kdmutasimasuk' => $request->kdmutasimasuk,
            'kddusun' => $request->kddusun,
            'keluarga_tanggalmutasi' => $request->keluarga_tanggalmutasi ?? now(),
        ]);

        return redirect()->route('administrasi.keluarga.index')->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $data = DataKeluarga::findOrFail($no_kk);
        $data->delete();

        return redirect()->route('administrasi.keluarga.index')->with('success', 'Data keluarga berhasil dihapus.');
    }
}
