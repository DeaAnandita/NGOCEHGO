<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaDesa;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemdes;
use Illuminate\Http\Request;

class LembagaDesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data lembaga desa dengan relasi penduduk
        $lembagaDesas = DataLembagaDesa::with('penduduk')->get();

        // Ambil master lembaga hanya yang kdjenislembaga = 2 (Lembaga Pemerintahan Desa)
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)
            ->pluck('lembaga', 'kdlembaga')
            ->toArray();

        // Ambil daftar pilihan jawaban dari master_jawablemdes
        $masterJawabLemdes = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray();

        return view('penduduk.lemdes.index', compact('lembagaDesas', 'masterLembaga', 'masterJawabLemdes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();

        // Ambil hanya lembaga pemerintahan desa dari master_lembaga
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)->get();

        // Ambil semua opsi jawaban (Ya, Tidak, Pernah, dst)
        $masterJawabLemdes = MasterJawabLemdes::all();

        return view('penduduk.lemdes.create', compact('penduduks', 'masterLembaga', 'masterJawabLemdes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|unique:data_lembagadesa,nik|exists:data_penduduk,nik',
        ]);

        $data = [
            'nik' => $request->nik,
            'kdjenislembaga' => 2 // ✅ Tetap fix: Lembaga Pemerintahan Desa
        ];

        // Loop untuk semua kolom lemdes_1 sampai lemdes_9
        for ($i = 1; $i <= 9; $i++) {
            $data["lemdes_$i"] = $request->input("lemdes_$i", 0);
        }

        DataLembagaDesa::create($data);

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)->get();
        $masterJawabLemdes = MasterJawabLemdes::all();

        return view('penduduk.lemdes.edit', compact('lembagaDesa', 'penduduks', 'masterLembaga', 'masterJawabLemdes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
        ]);

        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();

        $data = [
            'nik' => $request->nik,
            'kdjenislembaga' => 2
        ];

        for ($i = 1; $i <= 9; $i++) {
            $data["lemdes_$i"] = $request->input("lemdes_$i", 0);
        }

        $lembagaDesa->update($data);

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $lembagaDesa->delete();

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil dihapus.');
    }
}
