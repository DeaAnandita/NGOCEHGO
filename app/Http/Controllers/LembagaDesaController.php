<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaDesa;
use App\Models\DataPenduduk;
use App\Models\MasterJenisLembaga;
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
        $lembagadesas = DataLembagaDesa::with('penduduk')->get();
        $masterJenis = MasterJenisLembaga::pluck('jenislembaga', 'kdjenislembaga')->toArray();
        $masterLembaga = MasterLembaga::pluck('lembaga', 'kdlembaga')->toArray();
        $masterJawab = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray();

        return view('penduduk.lemdes.index', compact('lembagadesas', 'masterJenis', 'masterLembaga', 'masterJawab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();
        $masterJenis = MasterJenisLembaga::all();
        $masterLembaga = MasterLembaga::all();
        $masterJawab = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray();

        return view('penduduk.lemdes.create', compact('penduduks', 'masterJenis', 'masterLembaga', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
            'kdjenislembaga' => 'required|exists:master_jenislembaga,kdjenislembaga',
            'lemdes_*' => 'nullable|in:0,1,2,3,4'
        ]);

        $data = $request->only(['nik', 'kdjenislembaga']);
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
        $lembagadesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterJenis = MasterJenisLembaga::all();
        $masterLembaga = MasterLembaga::all();
        $masterJawab = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes')->toArray();

        return view('penduduk.lemdes.edit', compact('lembagadesa', 'penduduks', 'masterJenis', 'masterLembaga', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
            'kdjenislembaga' => 'required|exists:master_jenislembaga,kdjenislembaga',
            'lemdes_*' => 'nullable|in:0,1,2,3,4'
        ]);

        $lembagadesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $data = $request->only(['nik', 'kdjenislembaga']);
        for ($i = 1; $i <= 9; $i++) {
            $data["lemdes_$i"] = $request->input("lemdes_$i", 0);
        }

        $lembagadesa->update($data);

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagadesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $lembagadesa->delete();

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil dihapus.');
    }
}
