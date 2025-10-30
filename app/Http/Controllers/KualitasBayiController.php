<?php

namespace App\Http\Controllers;

use App\Models\DataKualitasBayi;
use App\Models\DataKeluarga;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawabKualitasBayi;
use Illuminate\Http\Request;

class KualitasBayiController extends Controller
{
    /**
     * Menampilkan daftar data kualitas bayi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $kualitasbayis = DataKualitasBayi::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $masterKualitas = MasterKualitasBayi::pluck('kualitasbayi', 'kdkualitasbayi')->toArray();
        $masterJawab = MasterJawabKualitasBayi::pluck('jawabkualitasbayi', 'kdjawabkualitasbayi')->toArray();
        return view('keluarga.kualitasbayi.index', compact('kualitasbayis', 'masterKualitas', 'masterJawab', 'search', 'perPage'));
    }

    /**
     * Form tambah data kualitas bayi baru.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasBayi::all();
        $masterJawab = MasterJawabKualitasBayi::all();
        return view('keluarga.kualitasbayi.create', compact('keluargas', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Simpan data kualitas bayi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'kualitasbayi_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 7; $i++) {
            $data["kualitasbayi_$i"] = $request->input("kualitasbayi_$i", 0);
        }

        DataKualitasBayi::create($data);

        return redirect()->route('keluarga.kualitasbayi.index')->with('success', 'Data kualitas bayi berhasil ditambahkan.');
    }

    /**
     * Form edit data kualitas bayi.
     */
    public function edit($no_kk)
    {
        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasBayi::all();
        $masterJawab = MasterJawabKualitasBayi::all();

        return view('keluarga.kualitasbayi.edit', compact('kualitasbayi', 'keluargas', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Update data kualitas bayi.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_kualitasbayi,no_kk|exists:data_keluarga,no_kk',
            'kualitasbayi_*' => 'nullable|in:0,1,2'
        ]);

        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 7; $i++) {
            $data["kualitasbayi_$i"] = $request->input("kualitasbayi_$i", 0);
        }

        $kualitasbayi->update($data);

        return redirect()->route('keluarga.kualitasbayi.index')->with('success', 'Data kualitas bayi berhasil diperbarui.');
    }

    /**
     * Hapus data kualitas bayi.
     */
    public function destroy($no_kk)
    {
        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $kualitasbayi->delete();

        return redirect()->route('keluarga.kualitasbayi.index')->with('success', 'Data kualitas bayi berhasil dihapus.');
    }
}
