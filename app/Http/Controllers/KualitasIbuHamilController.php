<?php

namespace App\Http\Controllers;

use App\Models\DataKualitasIbuHamil;
use App\Models\DataKeluarga;
use App\Models\MasterKualitasIbuHamil;
use App\Models\MasterJawabKualitasIbuHamil;
use Illuminate\Http\Request;

class KualitasIbuHamilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $kualitasibuhamils = DataKualitasIbuHamil::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $masterKualitas = MasterKualitasIbuHamil::pluck('kualitasibuhamil', 'kdkualitasibuhamil')->toArray();
        $masterJawab = MasterJawabKualitasIbuHamil::pluck('jawabkualitasibuhamil', 'kdjawabkualitasibuhamil')->toArray();
        return view('keluarga.kualitasibuhamil.index', compact('kualitasibuhamils', 'masterKualitas', 'masterJawab', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasIbuHamil::all();
        $masterJawab = MasterJawabKualitasIbuHamil::all();

        return view('keluarga.kualitasibuhamil.create', compact('keluargas', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'kualitasibuhamil*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 13; $i++) {
            $data["kualitasibuhamil_$i"] = $request->input("kualitasibuhamil_$i", 0);
        }

        DataKualitasIbuHamil::create($data);

        return redirect()->route('keluarga.kualitasibuhamil.index')->with('success', 'Data kualitas ibu hamil berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasIbuHamil::all();
        $masterJawab = MasterJawabKualitasIbuHamil::all();
        return view('keluarga.kualitasibuhamil.edit', compact('kualitasibuhamil', 'keluargas', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'kualitasibuhamil_*' => 'nullable|in:0,1,2'
        ]);

        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 13; $i++) {
            $data["kualitasibuhamil_$i"] = $request->input("DataKualitasIbuHamil$i", 0);
        }

        $kualitasibuhamil->update($data);

        return redirect()->route('keluarga.kualitasibuhamil.index')->with('success', 'Data kualitas ibu hamil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();
        $kualitasibuhamil->delete();

        return redirect()->route('keluarga.kualitasibuhamil.index')->with('success', 'Data kualitas ibu hamil berhasil dihapus.');
    }
}