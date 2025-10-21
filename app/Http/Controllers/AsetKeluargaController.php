<?php

namespace App\Http\Controllers;

use App\Models\DataBangunKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;
use App\Models\MasterJawabBangun;
use Illuminate\Http\Request;

class BangunKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bangunkeluargas = DataBangunKeluarga::with('keluarga')->get();
        $masterPembangunan = MasterPembangunanKeluarga::pluck('pembangunankeluarga', 'kdpembangunankeluarga')->toArray();
        $masterJawab = MasterJawabBangun::pluck('jawabbangun', 'kdjawabbangun')->toArray();

        return view('keluarga.bangunkeluarga.index', compact('bangunkeluargas', 'masterPembangunan', 'masterJawab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::all();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.create', compact('keluargas', 'masterPembangunan', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $data = $request->only(['no_kk']);

        // Simpan jawaban 1-51
        for ($i = 1; $i <= 51; $i++) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", 0); // default 0
        }

        DataBangunKeluarga::create($data);

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data bangun keluarga berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::all();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.edit', compact('bangunkeluarga', 'keluargas', 'masterPembangunan', 'masterJawab'));
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);

        for ($i = 1; $i <= 51; $i++) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", 0); // default 0
        }

        $bangunkeluarga->update($data);

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data bangun keluarga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $bangunkeluarga->delete();

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data bangun keluarga berhasil dihapus.');
    }
}
