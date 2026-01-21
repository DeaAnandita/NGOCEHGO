<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\AnggaranExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnggaranKelembagaan;
use App\Models\Kegiatan;
use App\Models\{
    MasterUnitKeputusan,
    MasterPeriodeKelembagaan,
    MasterSumberDana
};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AnggaranKelembagaanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $anggaran = AnggaranKelembagaan::with(['unit', 'periode'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('unit', function ($u) use ($request) {
                    $u->where('unit_keputusan', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('kelembagaan.anggaran.index', compact('anggaran'));
    }


    public function create()
    {
        return view('kelembagaan.anggaran.create', [
            'unit' => MasterUnitKeputusan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'sumber' => MasterSumberDana::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdunit' => 'required',
            'kdperiode' => 'required',
            'kdsumber' => 'required',
            'total_anggaran' => 'required|numeric'
        ]);

        $anggaran = AnggaranKelembagaan::create($request->all());

        return redirect()->route('kelembagaan.anggaran.show', $anggaran->id)
            ->with('success', 'Anggaran berhasil dibuat. Silakan input kegiatan.');
    }
    public function show($id)
    {

        $anggaran = AnggaranKelembagaan::with([
            'unit',
            'periode',
            'kegiatanAnggaran',
            'kegiatanAnggaran.kegiatan',
            'kegiatanAnggaran.sumber'
        ])->findOrFail($id);
        $kegiatan = Kegiatan::whereNotIn('id', function ($q) use ($anggaran) {
            $q->select('kegiatan_id')
                ->from('kegiatan_anggaran')
                ->where('anggaran_id', $anggaran->id);
        })
            ->orderBy('nama_kegiatan')
            ->get();

        $sumber = MasterSumberDana::orderBy('sumber_dana')->get();

        return view('kelembagaan.anggaran.show', compact(
            'anggaran',
            'kegiatan',
            'sumber'
        ));
    }



    public function edit($id)
    {
        return view('kelembagaan.anggaran.edit', [
            'anggaran' => AnggaranKelembagaan::findOrFail($id),
            'unit' => MasterUnitKeputusan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'sumber' => MasterSumberDana::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_anggaran' => 'required|numeric'
        ]);

        AnggaranKelembagaan::findOrFail($id)->update($request->all());

        return redirect()->route('kelembagaan.anggaran.index')
            ->with('success', 'Anggaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        AnggaranKelembagaan::findOrFail($id)->delete();

        return redirect()->route('kelembagaan.anggaran.index')
            ->with('success', 'Anggaran berhasil dihapus');
    }
    public function exportPdf()
    {
        $anggaran = AnggaranKelembagaan::with([
            'unit',
            'periode',
            'sumber',
            'kegiatanAnggaran.kegiatan',
            'kegiatanAnggaran.sumber'
        ])->get();

        $pdf = Pdf::loadView('kelembagaan.anggaran.pdf', compact('anggaran'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('anggaran_keuangan.pdf');
    }
    public function export(Request $request)
    {
        $anggaran = AnggaranKelembagaan::with([
            'unit',
            'periode',
            'sumber',
            'kegiatanAnggaran.kegiatan',
            'kegiatanAnggaran.sumber'
        ])->get();

        return Excel::download(new AnggaranExport($anggaran), 'anggaran_keuangan.xlsx');
    }
}
