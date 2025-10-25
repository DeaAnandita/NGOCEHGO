<?php

namespace App\Http\Controllers;

use App\Models\DataKonflikSosial;
use App\Models\DataKeluarga;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;
use Illuminate\Http\Request;

class KonflikSosialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $konfliksosials = DataKonflikSosial::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $masterKonflik = MasterKonflikSosial::pluck('konfliksosial', 'kdkonfliksosial')->toArray();
        $masterJawab = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik')->toArray();

        return view('keluarga.konfliksosial.index', compact('konfliksosials', 'masterKonflik', 'masterJawab', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();

        return view('keluarga.konfliksosial.create', compact('keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk'=> 'required|unique:data_konfliksosial,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        $totalKonflik = MasterKonflikSosial::count();

        for ($i = 1; $i <= $totalKonflik; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        DataKonflikSosial::create($data);

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();

        return view('keluarga.konfliksosial.edit', compact('konfliksosial', 'keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
        'no_kk' => 'required|unique:data_konfliksosial,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();

        $totalKonflik = MasterKonflikSosial::count();

        for ($i = 1; $i <= $totalKonflik; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        $konfliksosial->update($data);

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $konfliksosial->delete();

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil dihapus.');
    }
}
