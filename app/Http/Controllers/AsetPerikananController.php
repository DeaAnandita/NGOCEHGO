<?php

namespace App\Http\Controllers;

use App\Models\DataAsetPerikanan;
use App\Models\DataKeluarga;
use App\Models\MasterAsetPerikanan;
use Illuminate\Http\Request;

class AsetPerikananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $asetperikanans = DataAsetPerikanan::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        // Ambil daftar master aset perikanan (nama jenis aset)
        $masterAset = MasterAsetPerikanan::pluck('asetperikanan', 'kdasetperikanan')->toArray();

        return view('keluarga.asetperikanan.index', compact('asetperikanans', 'masterAset', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetPerikanan::all();

        return view('keluarga.asetperikanan.create', compact('keluargas', 'masterAset'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_kk' => 'required|unique:data_asetperikanan,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];

        // Loop otomatis untuk 24 kolom asetperikanan
        for ($i = 1; $i <= 6; $i++) {
            $data["asetperikanan_$i"] = $request->input("asetperikanan_$i", 0);
        }

        DataAsetPerikanan::create($data);

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetPerikanan::all();

        return view('keluarga.asetperikanan.edit', compact('asetperikanan', 'keluargas', 'masterAset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetperikanan,no_kk|exists:data_keluarga,no_kk',
            'asetperikanan_*' => 'nullable|string|max:255',
        ]);

        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 6; $i++) {
            $data["asetperikanan_$i"] = $request->input("asetperikanan_$i", null);
        }

        $asetperikanan->update($data);

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();
        $asetperikanan->delete();

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil dihapus.');
    }
}
