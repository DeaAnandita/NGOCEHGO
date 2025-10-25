<?php

namespace App\Http\Controllers;

use App\Models\DataKelahiran;
use App\Models\DataPenduduk;
use App\Models\MasterTempatPersalinan;
use App\Models\MasterJenisKelahiran;
use App\Models\MasterPertolonganPersalinan;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\MasterPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelahiranController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = DataKelahiran::with(['penduduk', 'ibu', 'ayah', 'tempatPersalinan', 'jenisKelahiran', 'pertolonganPersalinan', 'user']);
    //     if ($request->has('search')) {
    //         $search = $request->search;
    //         $query->whereHas('penduduk', function ($q) use ($search) {
    //             $q->where('penduduk_namalengkap', 'like', "%{$search}%");
    //         });
    //     }

    //     //$kelahirans = $query->orderBy('created_at', 'desc')->paginate(5);
    //     return view('penduduk.kelahiran.index');
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $kelahirans = DataKelahiran::with(['penduduk', 'pertolonganPersalinan', 'jenisKelahiran', 'tempatPersalinan'])
            ->when($search, function ($query, $search) {
                $query->where('nama_bayi', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
            })
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('penduduk.kelahiran.index', compact('kelahirans', 'search', 'perPage'));
    }


    //  public function create()
    // {
    //     return view('penduduk.kelahiran.create', [
    //         'penduduks' => DataPenduduk::all(),
    //         'tempat_persalinans' => MasterTempatPersalinan::all(),
    //         'jenis_kelahirans' => MasterJenisKelahiran::all(),
    //         'pertolongan_persalinans' => MasterPertolonganPersalinan::all(),
    //         'provinsis' => MasterProvinsi::all(),
    //         'kabupatens' => MasterKabupaten::all(),
    //         'kecamatans' => MasterKecamatan::all(),
    //         'desas' => MasterDesa::all(),
    //     ]);
        
    // }

    public function create()
    {
        $tempat_persalinans = MasterTempatPersalinan::all();
        $jenis_kelahirans = MasterJenisKelahiran::all();
        $pertolongan_persalinans = MasterPertolonganPersalinan::all();
        $penduduks = DataPenduduk::all();
        $provinsis = MasterProvinsi::all();
        $kabupatens = MasterKabupaten::all();
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();

        return view('penduduk.kelahiran.create', compact(
            'tempat_persalinans', 
            'jenis_kelahirans', 
            'pertolongan_persalinans', 
            'penduduks', 
            'provinsis', 'kabupatens', 'kecamatans', 'desas'
        ));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik',
            'kdtempatpersalinan' => 'nullable|integer|exists:master_tempatpersalinan,kdtempatpersalinan',
            'kdjeniskelahiran' => 'nullable|integer|exists:master_jeniskelahiran,kdjeniskelahiran',
            'kdpertolonganpersalinan' => 'nullable|integer|exists:master_pertolonganpersalinan,kdpertolonganpersalinan',
            'kelahiran_jamkelahiran' => 'nullable|date_format:H:i',
            'kelahiran_kelahiranke' => 'nullable|integer|min:1',
            'kelahiran_berat' => 'nullable|integer|min:0',
            'kelahiran_panjang' => 'nullable|integer|min:0',
            'kelahiran_nikibu' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_nikayah' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_rw' => 'nullable|string|max:3',
            'kelahiran_rt' => 'nullable|string|max:3',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        // $data['created_by'] = Auth::id();
        DataKelahiran::create($data);

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil ditambahkan.');
    }

    public function edit($kdkelahiran)
    {
        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        return view('penduduk.kelahiran.edit', [
            'kelahiran' => $kelahiran,
            'penduduks' => DataPenduduk::all(),
            'tempat_persalinans' => MasterTempatPersalinan::all(),
            'jenis_kelahirans' => MasterJenisKelahiran::all(),
            'pertolongan_persalinans' => MasterPertolonganPersalinan::all(),
            'provinsis' => MasterProvinsi::all(),
            'kabupatens' => MasterKabupaten::all(),
            'kecamatans' => MasterKecamatan::all(),
            'desas' => MasterDesa::all(),
        ]);
    }

    public function update(Request $request, $kdkelahiran)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik',
            'kdtempatpersalinan' => 'nullable|integer|exists:master_tempatpersalinan,kdtempatpersalinan',
            'kdjeniskelahiran' => 'nullable|integer|exists:master_jeniskelahiran,kdjeniskelahiran',
            'kdpertolonganpersalinan' => 'nullable|integer|exists:master_pertolonganpersalinan,kdpertolonganpersalinan',
            'kelahiran_jamkelahiran' => 'nullable',
            'kelahiran_kelahiranke' => 'nullable|integer|min:1',
            'kelahiran_berat' => 'nullable|integer|min:0',
            'kelahiran_panjang' => 'nullable|integer|min:0',
            'kelahiran_nikibu' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_nikayah' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_rw' => 'nullable|string|max:3',
            'kelahiran_rt' => 'nullable|string|max:3',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        $kelahiran->update($request->all());

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil diperbarui.');
    }

    public function destroy($kdkelahiran)
    {
        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        $kelahiran->delete();

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil dihapus.');
    }

    public function getPendudukDetails($nik)
    {
        $penduduk = DataPenduduk::with(['pekerjaan', 'provinsi', 'kabupaten', 'kecamatan', 'desa'])->findOrFail($nik);
        return response()->json([
            'nik' => $penduduk->nik,
            'nama' => $penduduk->penduduk_namalengkap,
            'tanggal_lahir' => $penduduk->penduduk_tanggallahir ? \Carbon\Carbon::parse($penduduk->penduduk_tanggallahir)->format('d/m/Y') : '-',
            'pekerjaan' => $penduduk->pekerjaan->pekerjaan ?? '-',
            'kewarganegaraan' => $penduduk->penduduk_kewarganegaraan ?? '-',
            'alamat' => ($penduduk->provinsi->provinsi ?? '-') . ', ' . ($penduduk->kabupaten->kabupaten ?? '-') . ', ' . ($penduduk->kecamatan->kecamatan ?? '-') . ', ' . ($penduduk->desa->desa ?? '-'),
            'rw' => $penduduk->rw ?? '-',
            'rt' => $penduduk->rt ?? '-',
        ]);
    }

    //  public function getKabupatens($kdprovinsi)
    // {
    //     $kabupaten = DB::table('master_kabupaten')
    //                     ->where('kdprovinsi', $kdprovinsi)
    //                     ->select('kdkabupaten', 'kabupaten')
    //                     ->get();
    //     return response()->json($kabupaten);
    // }

    // public function getKecamatans($kdkabupaten)
    // {
    //     $kecamatan = DB::table('master_kecamatan')
    //                     ->where('kdkabupaten', $kdkabupaten)
    //                     ->select('kdkecamatan', 'kecamatan')
    //                     ->get();
    //     return response()->json($kecamatan);
    // }

    // public function getDesas($kdkecamatan)
    // {
    //     $desa = DB::table('master_desa')
    //             ->where('kdkecamatan', $kdkecamatan)
    //             ->select('kddesa', 'desa')
    //             ->get();
    //     return response()->json($desa);
    // }
}