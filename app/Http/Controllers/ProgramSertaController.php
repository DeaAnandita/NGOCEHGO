<?php

namespace App\Http\Controllers;

use App\Models\DataProgramSerta;
use App\Models\DataPenduduk;
use App\Models\MasterProgramSerta;
use App\Models\MasterJawabProgramSerta;
use Illuminate\Http\Request;

class ProgramSertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data program serta dengan relasi penduduk
        $programSertas = DataProgramSerta::with('penduduk')->get();

        // Ambil daftar program dari master_programserta
        $masterProgramSerta = MasterProgramSerta::pluck('programserta', 'kdprogramserta')->toArray();

        // Ambil daftar pilihan jawaban dari master_jawabprogramserta
        $masterJawab = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();

        return view('penduduk.programserta.index', compact('programSertas', 'masterProgramSerta', 'masterJawab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();
        $masterProgramSerta = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::all();

        return view('penduduk.programserta.create', compact('penduduks', 'masterProgramSerta', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $rules = [
            'nik' => 'required|unique:data_programserta,nik|exists:data_penduduk,nik',
        ];

        for ($i = 1; $i <= 8; $i++) {
            $rules["programserta_$i"] = 'nullable|integer';
        }

        $request->validate($rules);

        $data = [
            'nik' => $request->nik,
        ];

        for ($i = 1; $i <= 8; $i++) {
            $data["programserta_$i"] = $request->input("programserta_$i", 0);
        }

        DataProgramSerta::create($data);

        return redirect()->route('penduduk.programserta.index')->with('success', 'Data program serta berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterProgramSerta = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::all();

        return view('penduduk.programserta.edit', compact('programSerta', 'penduduks', 'masterProgramSerta', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $rules = [
            'nik' => 'required|exists:data_penduduk,nik',
        ];

        for ($i = 1; $i <= 8; $i++) {
            $rules["programserta_$i"] = 'nullable|integer';
        }

        $request->validate($rules);

        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();

        $data = [
            'nik' => $request->nik,
        ];

        for ($i = 1; $i <= 8; $i++) {
            $data["programserta_$i"] = $request->input("programserta_$i", 0);
        }

        $programSerta->update($data);

        return redirect()->route('penduduk.programserta.index')->with('success', 'Data program serta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $programSerta->delete();

        return redirect()->route('penduduk.programserta.index')->with('success', 'Data program serta berhasil dihapus.');
    }
}
