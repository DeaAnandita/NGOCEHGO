<?php

namespace App\Http\Controllers;

use App\Models\DataKelahiran;
use App\Models\DataPenduduk;
use Illuminate\Http\Request;

class KelahiranController extends Controller
{
    public function index($nik)
    {
        $penduduk = DataPenduduk::where('nik', $nik)->firstOrFail();
        $kelahirans = DataKelahiran::where('nik', $nik)->get();
        return view('penduduk.kelahiran.index', compact('penduduk', 'kelahirans'))->render();
    }

    public function create($nik)
    {
        $penduduk = DataPenduduk::where('nik', $nik)->firstOrFail();
        return view('penduduk.kelahiran.create', compact('penduduk'));
    }

    public function store(Request $request, $nik)
    {
        $penduduk = DataPenduduk::where('nik', $nik)->firstOrFail();
        $validated = $request->validate([
            'tanggal_kelahiran' => 'required|date',
            'tempat_kelahiran' => 'required|string|max:255',
        ]);

        DataKelahiran::create(array_merge($validated, ['nik' => $nik]));
        return redirect()->route('penduduk.kelahiran.index', $nik)->with('success', 'Data kelahiran berhasil ditambahkan.');
    }
}