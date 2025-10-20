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
     * Tampilkan daftar data Bangun Keluarga.
     */
    public function index()
    {
        // Ambil data bangun keluarga beserta relasi ke keluarga
        $dataBangunKeluarga = DataBangunKeluarga::with('keluarga')->paginate(10);

        // Ambil semua master jawaban (harus pakai all(), bukan pluck)
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.index', compact('dataBangunKeluarga', 'masterJawab'));
    }

    /**
     * Tampilkan form tambah data Bangun Keluarga.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterBangun = MasterPembangunanKeluarga::all();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.create', compact('keluargas', 'masterBangun', 'masterJawab'));
    }

    /**
     * Simpan data Bangun Keluarga baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $data = $request->only(['no_kk']);

        // Ambil semua masterBangun untuk menentukan jumlah pertanyaan
        $jumlah = MasterPembangunanKeluarga::count();

        for ($i = 1; $i <= $jumlah; $i++) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", null);
        }

        DataBangunKeluarga::create($data);

        return redirect()->route('keluarga.bangunkeluarga.index')
                         ->with('success', 'Data bangun keluarga berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data Bangun Keluarga.
     */
    public function edit($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterBangun = MasterPembangunanKeluarga::all();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.edit', compact('bangunkeluarga', 'keluargas', 'masterBangun', 'masterJawab'));
    }

    /**
     * Update data Bangun Keluarga.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);

        $jumlah = MasterPembangunanKeluarga::count();

        for ($i = 1; $i <= $jumlah; $i++) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", null);
        }

        $bangunkeluarga->update($data);

        return redirect()->route('keluarga.bangunkeluarga.index')
                         ->with('success', 'Data bangun keluarga berhasil diperbarui.');
    }

    /**
     * Hapus data Bangun Keluarga.
     */
    public function destroy($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $bangunkeluarga->delete();

        return redirect()->route('keluarga.bangunkeluarga.index')
                         ->with('success', 'Data bangun keluarga berhasil dihapus.');
    }
}
