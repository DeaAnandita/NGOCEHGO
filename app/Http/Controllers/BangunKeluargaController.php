<?php

namespace App\Http\Controllers;

use App\Models\DataBangunKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;
use App\Models\MasterJawabBangun;
use Illuminate\Http\Request;

class BangunKeluargaController extends Controller
{
    // Daftar data
    public function index()
    {
        $bangunkeluargas = DataBangunKeluarga::with('keluarga')->get();

        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))
            ->get(['kdpembangunankeluarga', 'pembangunankeluarga']);

        $masterJawab = MasterJawabBangun::pluck('jawabbangun', 'kdjawabbangun'); // ['1' => 'Ya', '2'=>'Tidak', ...]

        return view('keluarga.bangunkeluarga.index', compact('bangunkeluargas', 'masterPembangunan', 'masterJawab'));
    }

    // Form tambah data
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.create', compact('keluargas', 'masterPembangunan', 'masterJawab'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_bangunkeluarga,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];

        foreach (range(1, 51) as $i) {
            $field = "bangunkeluarga_$i";
            $value = $request->input($field);
            $data[$field] = $value !== null && $value !== '' ? (int)$value : null;
        }

        DataBangunKeluarga::create($data);

        return redirect()->route('keluarga.bangunkeluarga.index')
            ->with('success', 'Data bangun keluarga berhasil ditambahkan.');
    }



    // Form edit data
    public function edit($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.edit', compact(
            'bangunkeluarga', 'keluargas', 'masterPembangunan', 'masterJawab'
        ));
    }

    // Update data
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',

        ]);

        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];

        foreach (range(1, 51) as $i) {
            $field = "bangunkeluarga_$i";
            $value = $request->input($field);
            $data[$field] = $value !== null && $value !== '' ? $value : null;
        }


        $bangunkeluarga->update($data);

        return redirect()->route('keluarga.bangunkeluarga.index')
            ->with('success', 'Data bangun keluarga berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($no_kk)
    {
        $data = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data->delete();

        return redirect()->route('keluarga.bangunkeluarga.index')
            ->with('success', 'Data bangun keluarga berhasil dihapus.');
    }
}
