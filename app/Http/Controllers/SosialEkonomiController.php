<?php

namespace App\Http\Controllers;

use App\Models\DataSosialEkonomi;
use App\Models\DataPenduduk;
use App\Models\MasterPartisipasiSekolah;
use App\Models\MasterIjasahTerakhir;
use App\Models\MasterJenisDisabilitas;
use App\Models\MasterTingkatSulitDisabilitas;
use App\Models\MasterPenyakitKronis;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterStatusKedudukanKerja;
use App\Models\MasterPendapatanPerbulan;
use App\Models\MasterImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SosialEkonomiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data sosialekonomi dengan relasi penduduk
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $sosialekonomis = DataSosialEkonomi::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        return view('penduduk.sosialekonomi.index', compact('sosialekonomis', 'search', 'perPage'));
    }

    public function create()
    {
        return view('penduduk.sosialekonomi.create', [
            'penduduks' => DataPenduduk::all(),
            'partisipasi_sekolahs' => MasterPartisipasiSekolah::all(),
            'ijasah_terakhirs' => MasterIjasahTerakhir::all(),
            'jenis_disabilitass' => MasterJenisDisabilitas::all(),
            'tingkat_sulit_disabilitass' => MasterTingkatSulitDisabilitas::all(),
            'penyakit_kroniss' => MasterPenyakitKronis::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'status_kedudukan_kerjas' => MasterStatusKedudukanKerja::all(),
            'pendapatan_perbulans' => MasterPendapatanPerbulan::all(),
            'imunisis' => MasterImunisasi::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_sosialekonomi,nik|exists:data_penduduk,nik',
            'kdpartisipasisekolah' => 'nullable|integer|exists:master_partisipasisekolah,kdpartisipasisekolah',
            'kdijasahterakhir' => 'nullable|integer|exists:master_ijasahterakhir,kdijasahterakhir',
            'kdjenisdisabilitas' => 'nullable|integer|exists:master_jenisdisabilitas,kdjenisdisabilitas',
            'kdtingkatsulitdisabilitas' => 'nullable|integer|exists:master_tingkatsulitdisabilitas,kdtingkatsulitdisabilitas',
            'kdpenyakitkronis' => 'nullable|integer|exists:master_penyakitkronis,kdpenyakitkronis',
            'kdlapanganusaha' => 'nullable|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'kdstatuskedudukankerja' => 'nullable|integer|exists:master_statuskedudukankerja,kdstatuskedudukankerja',
            'kdpendapatanperbulan' => 'nullable|integer|exists:master_pendapatanperbulan,kdpendapatanperbulan',
            'kdimunisasi' => 'nullable|integer|exists:master_imunisasi,kdimunisasi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataSosialEkonomi::create($request->all());

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil ditambahkan.');
    }

    public function edit($nik)
    {
        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        return view('penduduk.sosialekonomi.edit', [
            'sosialekonomi' => $sosialekonomi,
            'penduduks' => DataPenduduk::all(),
            'partisipasi_sekolahs' => MasterPartisipasiSekolah::all(),
            'ijasah_terakhirs' => MasterIjasahTerakhir::all(),
            'jenis_disabilitass' => MasterJenisDisabilitas::all(),
            'tingkat_sulit_disabilitass' => MasterTingkatSulitDisabilitas::all(),
            'penyakit_kroniss' => MasterPenyakitKronis::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'status_kedudukan_kerjas' => MasterStatusKedudukanKerja::all(),
            'pendapatan_perbulans' => MasterPendapatanPerbulan::all(),
            'imunisis' => MasterImunisasi::all(),
        ]);
    }

    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16' . $nik . ',nik',
            'kdpartisipasisekolah' => 'nullable|integer|exists:master_partisipasisekolah,kdpartisipasisekolah',
            'kdijasahterakhir' => 'nullable|integer|exists:master_ijasahterakhir,kdijasahterakhir',
            'kdjenisdisabilitas' => 'nullable|integer|exists:master_jenisdisabilitas,kdjenisdisabilitas',
            'kdtingkatsulitdisabilitas' => 'nullable|integer|exists:master_tingkatsulitdisabilitas,kdtingkatsulitdisabilitas',
            'kdpenyakitkronis' => 'nullable|integer|exists:master_penyakitkronis,kdpenyakitkronis',
            'kdlapanganusaha' => 'nullable|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'kdstatuskedudukankerja' => 'nullable|integer|exists:master_statuskedudukankerja,kdstatuskedudukankerja',
            'kdpendapatanperbulan' => 'nullable|integer|exists:master_pendapatanperbulan,kdpendapatanperbulan',
            'kdimunisasi' => 'nullable|integer|exists:master_imunisasi,kdimunisasi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        $sosialekonomi->update($request->all());

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil diperbarui.');
    }

    public function destroy($nik)
    {
        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        $sosialekonomi->delete();

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil dihapus.');
    }
}