<?php

namespace App\Http\Controllers;

use App\Models\DataAsetTernak;
use App\Models\DataKeluarga;
use App\Models\MasterAsetTernak;
use Illuminate\Http\Request;

class AsetTernakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data aset ternak beserta keluarga
        $asetternaks = DataAsetTernak::with('keluarga')->get();

        // Ambil daftar master aset ternak (keterangan jenis aset)
        $masterAset = MasterAsetTernak::pluck('asetternak', 'kdasetternak')->toArray();

        return view('keluarga.asetternak.index', compact('asetternaks', 'masterAset'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data keluarga dan daftar aset ternak untuk form
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.create', compact('keluargas', 'masterAset'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_kk' => 'required|unique:data_asetternak,no_kk|exists:data_keluarga,no_kk',
        ]);


        $data = ['no_kk' => $request->no_kk];

        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", 0);
        }

        DataAsetTernak::create($data);


        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.edit', compact('asetternak', 'keluargas', 'masterAset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetternak,no_kk,|exists:data_keluarga,no_kk',
            'asetternak_*' => 'nullable|string|max:255',
            
        ]);

        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", null);
        }

        $asetternak->update($data);

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $asetternak->delete();

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil dihapus.');
    }
}
