<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaMasyarakat;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemmas;
use Illuminate\Http\Request;

class LembagaMasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data lembaga masyarakat dengan relasi penduduk
        $lembagaMasyarakats = DataLembagaMasyarakat::with('penduduk')->get();

        // Ambil master lembaga hanya yang kdjenislembaga = 3 (Lembaga Masyarakat)
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)
            ->pluck('lembaga', 'kdlembaga')
            ->toArray();

        // Ambil daftar pilihan jawaban dari master_jawablemmas
        $masterJawabLemmas = MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas')->toArray();

        return view('penduduk.lembagamasyarakat.index', compact('lembagaMasyarakats', 'masterLembaga', 'masterJawabLemmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();

        // Ambil hanya lembaga masyarakat dari master_lembaga
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)->get();

        // Ambil semua opsi jawaban (Ya, Tidak, Pernah, dst)
        $masterJawabLemmas = MasterJawabLemmas::all();

        return view('penduduk.lembagamasyarakat.create', compact('penduduks', 'masterLembaga', 'masterJawabLemmas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|unique:data_lembagamasyarakat,nik|exists:data_penduduk,nik',
        ]);

        $data = ['nik' => $request->nik];

        // Loop dari 1 sampai 48 sesuai struktur kolom di tabel
        for ($i = 1; $i <= 48; $i++) {
            $data["lemmas_$i"] = $request->input("lemmas_$i", 0);
        }

        DataLembagaMasyarakat::create($data);

        return redirect()->route('penduduk.lembagamasyarakat.index')->with('success', 'Data lembaga masyarakat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)->get();
        $masterJawabLemmas = MasterJawabLemmas::all();

        return view('penduduk.lembagamasyarakat.edit', compact('lembagaMasyarakat', 'penduduks', 'masterLembaga', 'masterJawabLemmas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
             'nik' => 'required|unique:data_lembagamasyarakat,nik|exists:data_penduduk,nik',
        ]);

        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();

        $data = ['nik' => $request->nik];

        for ($i = 1; $i <= 48; $i++) {
            $data["lemmas_$i"] = $request->input("lemmas_$i", 0);
        }

        $lembagaMasyarakat->update($data);

        return redirect()->route('penduduk.lembagamasyarakat.index')->with('success', 'Data lembaga masyarakat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();
        $lembagaMasyarakat->delete();

        return redirect()->route('penduduk.lembagamasyarakat.index')->with('success', 'Data lembaga masyarakat berhasil dihapus.');
    }
}