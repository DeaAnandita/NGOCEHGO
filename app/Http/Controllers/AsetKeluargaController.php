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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $asetkeluargas = DataAsetKeluarga::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $masterAset = MasterAsetKeluarga::pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $masterJawab = MasterJawab::pluck('jawab', 'kdjawab')->toArray();

        return view('keluarga.asetkeluarga.index', compact('asetkeluargas', 'masterAset', 'masterJawab', 'search', 'perPage'));
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
            'no_kk' => 'required|unique:data_asetkeluarga,no_kk|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'sometimes|nullable|in:0,1,2'
        ], [
            'no_kk.required' => 'No KK wajib diisi.',
            'no_kk.unique' => 'No KK sudah digunakan.',
            'no_kk.exists' => 'No KK tidak ditemukan dalam data keluarga.'
        ]);


        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        DataAsetKeluarga::create($data);

        return redirect()->route('keluarga.asetkeluarga.index')
            ->with('success', 'Data aset keluarga berhasil ditambahkan.');
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
            'no_kk' => 'required|unique:data_asetkeluarga,no_kk|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'sometimes|nullable|in:0,1,2'
        ]);

        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        $asetkeluarga->update($data);

        return redirect()->route('keluarga.asetkeluarga.index')
            ->with('success', 'Data aset keluarga berhasil diperbarui.');
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