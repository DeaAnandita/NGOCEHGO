<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKeluarga;
use App\Models\MasterDusun;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class VoiceKeluargaController extends Controller
{

    public function index()
    {
        return view('voice.index');
    }
    public function keluarga()
    {
        $mutasi = MasterMutasiMasuk::all();
        $dusun = MasterDusun::all();
        $provinsi = MasterProvinsi::all();
        $kabupaten = MasterKabupaten::all();
        $kecamatan = MasterKecamatan::all();
        $desa = MasterDesa::all();

        return view('voice.keluarga', compact('mutasi', 'dusun', 'provinsi', 'kabupaten', 'kecamatan', 'desa'));
    }

    public function store(Request $request)
    {
        try {
            DataKeluarga::create($request->all());
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
