<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;
use Illuminate\Http\Request;

class AsetKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asetkeluargas = DataAsetKeluarga::with('keluarga')->get();
        $masterAset = MasterAsetKeluarga::pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $masterJawab = MasterJawab::pluck('jawab', 'kdjawab')->toArray();
        return view('keluarga.asetkeluarga.index', compact('asetkeluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetKeluarga::all();
        $masterJawab = MasterJawab::all();
        return view('keluarga.asetkeluarga.create', compact('keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        DataAsetKeluarga::create($data);

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetKeluarga::all();
        $masterJawab = MasterJawab::all();
        return view('keluarga.asetkeluarga.edit', compact('asetkeluarga', 'keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'nullable|in:0,1,2'
        ]);

        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        $asetkeluarga->update($data);

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $asetkeluarga->delete();

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil dihapus.');
    }
}