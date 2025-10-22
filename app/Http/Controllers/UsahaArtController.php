<?php

namespace App\Http\Controllers;

use App\Models\DataUsahaArt;
use App\Models\DataPenduduk;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterTempatUsaha;
use App\Models\MasterOmsetUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsahaArtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penduduk.usahaart.index', [
            'usahaarts' => DataUsahaArt::with('penduduk')->get(),
            'penduduks' => DataPenduduk::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penduduk.usahaart.create', [
            'penduduks' => DataPenduduk::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'tempat_usahas' => MasterTempatUsaha::all(),
            'omset_usahas' => MasterOmsetUsaha::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik|unique:data_usahaart,nik',
            'kdlapanganusaha' => 'required|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_jumlahpekerja' => 'required|numeric|min:0',
            'usahaart_namausaha' => 'required|string|max:255',
            'kdtempatusaha' => 'required|integer|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha' => 'required|integer|exists:master_omsetusaha,kdomsetusaha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataUsahaArt::create($request->all());

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $usahaart = DataUsahaArt::findOrFail($nik);
        return view('penduduk.usahaart.edit', [
            'usahaart' => $usahaart,
            'penduduks' => DataPenduduk::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'tempat_usahas' => MasterTempatUsaha::all(),
            'omset_usahas' => MasterOmsetUsaha::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik|unique:data_usahaart,nik,' . $nik . ',nik',
            'kdlapanganusaha' => 'required|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_jumlahpekerja' => 'required|numeric|min:0',
            'usahaart_namausaha' => 'required|string|max:255',
            'kdtempatusaha' => 'required|integer|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha' => 'required|integer|exists:master_omsetusaha,kdomsetusaha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usahaart = DataUsahaArt::findOrFail($nik);
        $usahaart->update($request->all());

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $usahaart = DataUsahaArt::findOrFail($nik);
        $usahaart->delete();

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil dihapus.');
    }
}