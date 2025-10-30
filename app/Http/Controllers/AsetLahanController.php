<?php

namespace App\Http\Controllers;

use App\Models\DataAsetLahan;
use App\Models\DataKeluarga;
use App\Models\MasterAsetLahan;
use App\Models\MasterJawabLahan;
use Illuminate\Http\Request;

class AsetLahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $asetlahans = DataAsetLahan::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter
        $masterAset = MasterAsetLahan::pluck('asetlahan', 'kdasetlahan')->toArray();
        $masterJawab = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan')->toArray();
        return view('keluarga.asetlahan.index', compact('asetlahans', 'masterAset', 'masterJawab', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetLahan::all();
        $masterJawab = MasterJawabLahan::all();
        return view('keluarga.asetlahan.create', compact('keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetlahan,no_kk|exists:data_keluarga,no_kk',
            'asetlahan_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 10; $i++) {
            $data["asetlahan_$i"] = $request->input("asetlahan_$i", 0);
        }

        DataAsetLahan::create($data);

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetLahan::all();
        $masterJawab = MasterJawabLahan::all();
        return view('keluarga.asetlahan.edit', compact('asetlahan', 'keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'asetlahan_*' => 'nullable|in:0,1,2'
        ]);

        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 10; $i++) {
            $data["asetlahan_$i"] = $request->input("asetlahan_$i", 0);
        }

        $asetlahan->update($data);

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $asetlahan->delete();

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil dihapus.');
    }
}