<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKeluarga;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterDusun;
use App\Models\MasterProvinsi;

class VoiceKeluargaController extends Controller
{
    public function index()
    {
        return view('voice.index');
    }

    public function keluarga()
    {
        $mutasi   = MasterMutasiMasuk::all();
        $dusun    = MasterDusun::all();
        $provinsi = MasterProvinsi::all();

        return view('voice.keluarga', compact('mutasi', 'dusun', 'provinsi'));
    }

    public function cekNoKk(Request $request)
    {
        $exists = DataKeluarga::where('no_kk', $request->no_kk)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // Hapus field wilayah datang jika kosong
            if (empty($data['kdprovinsi'])) {
                unset($data['kdprovinsi'], $data['kdkabupaten'], $data['kdkecamatan'], $data['kddesa']);
            }

            DataKeluarga::create($data);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}