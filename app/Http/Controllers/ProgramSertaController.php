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
        $programsertas = DataProgramSerta::with('penduduk')->get();
        $masterAset = MasterProgramSerta::pluck('programserta', 'kdprogramserta')->toArray();
        $masterJawab = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();

        return view('penduduk.programserta.index', compact('programsertas', 'masterAset', 'masterJawab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();
        $masterAset = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();

        return view('penduduk.programserta.create', compact('penduduks', 'masterAset', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
            'programserta_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['nik']);
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
        $programserta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterAset = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();

        return view('penduduk.programserta.edit', compact('programserta', 'penduduks', 'masterAset', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
            'programserta_*' => 'nullable|in:0,1,2'
        ]);

        $programserta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $data = $request->only(['nik']);
        for ($i = 1; $i <= 8; $i++) {
            $data["programserta_$i"] = $request->input("programserta_$i", 0);
        }

        $programserta->update($data);

        return redirect()->route('penduduk.programserta.index')->with('success', 'Data program serta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $programserta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $programserta->delete();

        return redirect()->route('penduduk.programserta.index')->with('success', 'Data program serta berhasil dihapus.');
    }
}
