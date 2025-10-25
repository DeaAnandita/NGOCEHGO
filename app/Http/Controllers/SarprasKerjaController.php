<?php

namespace App\Http\Controllers;

use App\Models\DataSarprasKerja;
use App\Models\DataKeluarga;
use App\Models\MasterSarprasKerja;
use App\Models\MasterJawabSarpras;
use Illuminate\Http\Request;

class SarprasKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $sarpraskerjas = DataSarprasKerja::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter
        $masterSarpras = MasterSarprasKerja::pluck('sarpraskerja', 'kdsarpraskerja')->toArray();
        $masterJawab = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();
        return view('keluarga.sarpraskerja.index', compact('sarpraskerjas', 'masterSarpras', 'masterJawab', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterSarpras = MasterSarprasKerja::all();
        $masterJawab = MasterJawabSarpras::all();

        return view('keluarga.sarpraskerja.create', compact('keluargas', 'masterSarpras', 'masterJawab'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_sarpraskerja,no_kk|exists:data_keluarga,no_kk',
            'sarpraskerja_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 25; $i++) {
            $data["sarpraskerja_$i"] = $request->input("sarpraskerja_$i", 0);
        }

        DataSarprasKerja::create($data);

        return redirect()->route('keluarga.sarpraskerja.index')->with('success', 'Data sarpras kerja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $sarpraskerja = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterSarpras = MasterSarprasKerja::all();
        $masterJawab = MasterJawabSarpras::all();
        return view('keluarga.sarpraskerja.edit', compact('sarpraskerja', 'keluargas', 'masterSarpras', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_sarpraskerja,no_kk|exists:data_keluarga,no_kk',
            'sarpraskerja_*' => 'nullable|in:0,1,2'
        ]);

        $sarpraskerja = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 25; $i++) {
            $data["sarpraskerja_$i"] = $request->input("sarpraskerja_$i", 0);
        }

        $sarpraskerja->update($data);

        return redirect()->route('keluarga.sarpraskerja.index')->with('success', 'Data sarpras kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $sarpraskerja = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $sarpraskerja->delete();

        return redirect()->route('keluarga.sarpraskerja.index')->with('success', 'Data sarpras kerja berhasil dihapus.');
    }
}