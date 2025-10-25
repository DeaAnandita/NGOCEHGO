<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaEkonomi;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemek;
use Illuminate\Http\Request;

class LembagaEkonomiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua data lembaga ekonomi dengan relasi penduduk
        $search = $request->input('search');

        // Ambil semua data lembaga desa dengan relasi penduduk
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $lembagaEkonomis = DataLembagaEkonomi::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        // Ambil master lembaga hanya yang kdjenislembaga = 4 (Lembaga Ekonomi)
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)
            ->pluck('lembaga', 'kdlembaga')
            ->toArray();

        // Ambil daftar pilihan jawaban dari master_jawablemek
        $masterJawabLemek = MasterJawabLemek::pluck('jawablemek', 'kdjawablemek')->toArray();

        return view('penduduk.lembagaekonomi.index', compact('lembagaEkonomis', 'masterLembaga', 'masterJawabLemek', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();

        // Ambil hanya lembaga ekonomi dari master_lembaga
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)->get();

        // Ambil semua opsi jawaban (Ya, Tidak, Pernah, dst)
        $masterJawabLemek = MasterJawabLemek::all();

        return view('penduduk.lembagaekonomi.create', compact('penduduks', 'masterLembaga', 'masterJawabLemek'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|unique:data_lembagaekonomi,nik|exists:data_penduduk,nik',
        ]);

        $data = ['nik' => $request->nik];

        // Loop dari 1 sampai 75 sesuai struktur kolom di tabel
        for ($i = 1; $i <= 75; $i++) {
            $data["lemek_$i"] = $request->input("lemek_$i", 0);
        }

        DataLembagaEkonomi::create($data);

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)->get();
        $masterJawabLemek = MasterJawabLemek::all();

        return view('penduduk.lembagaekonomi.edit', compact('lembagaEkonomi', 'penduduks', 'masterLembaga', 'masterJawabLemek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|unique:data_lembagaekonomi,nik|exists:data_penduduk,nik',
        ]);

        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();

        $data = ['nik' => $request->nik];

        for ($i = 1; $i <= 75; $i++) {
            $data["lemek_$i"] = $request->input("lemek_$i", 0);
        }

        $lembagaEkonomi->update($data);

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();
        $lembagaEkonomi->delete();

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil dihapus.');
    }
}
