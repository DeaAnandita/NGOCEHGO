<?php

namespace App\Http\Controllers;

use App\Models\DataSejahteraKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;
use Illuminate\Http\Request;

class SejahteraKeluargaController extends Controller
{
    /**
     * Tampilkan daftar data sejahtera keluarga.
     */
    public function index()
    {
        $sejahterakeluargas = DataSejahteraKeluarga::with('keluarga')->get();

        // Ambil label soal dari master_pembangunan_keluarga (typejawab 2 = uraian)
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))
            ->get(['kdpembangunankeluarga', 'pembangunankeluarga']);

        return view('keluarga.sejahterakeluarga.index', compact('sejahterakeluargas', 'masterPembangunan'));
    }

    /**
     * Tampilkan form tambah data.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))->get();

        return view('keluarga.sejahterakeluarga.create', compact('keluargas', 'masterPembangunan'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $request->validate([
             'no_kk' => 'required|unique:data_sejahterakeluarga,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];

        foreach (range(61, 68) as $i) {
            $data["sejahterakeluarga_$i"] = $request->input("sejahterakeluarga_$i", null);
        }

        DataSejahteraKeluarga::create($data);

        return redirect()->route('keluarga.sejahterakeluarga.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data.
     */
    public function edit($no_kk)
    {
        $sejahterakeluarga = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))->get();

        return view('keluarga.sejahterakeluarga.edit', compact('sejahterakeluarga', 'keluargas', 'masterPembangunan'));
    }

    /**
     * Update data sejahtera keluarga.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_sejahterakeluarga,no_kk|exists:data_keluarga,no_kk',
        ]);

        $sejahterakeluarga = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];

        foreach (range(61, 68) as $i) {
            $data["sejahterakeluarga_$i"] = $request->input("sejahterakeluarga_$i", null);
        }

        $sejahterakeluarga->update($data);

        return redirect()->route('keluarga.sejahterakeluarga.index')
            ->with('success', 'Data sejahtera keluarga berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy($no_kk)
    {
        $data = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data->delete();

        return redirect()->route('keluarga.sejahterakeluarga.index')
            ->with('success', 'Data sejahtera keluarga berhasil dihapus.');
    }
}
