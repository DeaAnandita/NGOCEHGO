<?php

namespace App\Http\Controllers;

use App\Models\DataKualitasBayi;
use App\Models\DataKeluarga;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawab;
use Illuminate\Http\Request;

class KualitasBayiController extends Controller
{
    /**
     * Menampilkan daftar data kualitas bayi.
     */
    public function index()
    {
        $kualitasbayis = DataKualitasBayi::with('keluarga')->get();
        $masterKualitas = MasterKualitasBayi::pluck('kualitasbayi', 'kdkualitasbayi')->toArray();
        $masterJawab = MasterJawab::pluck('jawab', 'kdjawab')->toArray();
        return view('keluarga.kualitasbayi.index', compact('kualitasbayis', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Form tambah data kualitas bayi baru.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasBayi::all();
        $masterJawab = MasterJawab::all();
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
        for ($i = 1; $i <= 30; $i++) {
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
        $masterJawab = MasterJawab::all();

        return view('keluarga.kualitasbayi.edit', compact('kualitasbayi', 'keluargas', 'masterKualitas', 'masterJawab'));
    }

    /**
     * Update data kualitas bayi.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'kualitasbayi_*' => 'nullable|in:0,1,2'
        ]);

        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 30; $i++) {
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
