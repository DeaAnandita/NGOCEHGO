<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\KegiatanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\{
    MasterJenisKegiatan,
    MasterStatusKegiatan,
    MasterSumberDana,
    MasterUnitKeputusan,
    MasterPeriodeKelembagaan,
    Keputusan
};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $kegiatan = Kegiatan::with(['jenis', 'unit', 'periode', 'status', 'sumberDana'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('kelembagaan.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('kelembagaan.kegiatan.create', [
            'jenis' => MasterJenisKegiatan::all(),
            'unit' => MasterUnitKeputusan::all(),
            'status' => MasterStatusKegiatan::all(),
            'sumber' => MasterSumberDana::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'keputusan' => Keputusan::orderBy('tanggal_keputusan', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'kdjenis' => 'required',
            'kdunit' => 'required',
            'kdperiode' => 'required',
            'kdstatus' => 'required',
            'kdsumber' => 'required',
            'pagu_anggaran' => 'required|numeric',
            'tgl_mulai' => 'required|date',
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('kelembagaan.kegiatan.index')
            ->with('success', 'Kegiatan berhasil disimpan');
    }
    public function show($id)
    {
        $kegiatan = Kegiatan::with([
            'jenis',
            'unit',
            'status',
            'sumberDana',
            'periode',
            'keputusan'
        ])->findOrFail($id);

        return view('kelembagaan.kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        return view('kelembagaan.kegiatan.edit', [
            'kegiatan' => Kegiatan::findOrFail($id),
            'jenis' => MasterJenisKegiatan::all(),
            'unit' => MasterUnitKeputusan::all(),
            'status' => MasterStatusKegiatan::all(),
            'sumber' => MasterSumberDana::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'keputusan' => Keputusan::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'kdjenis' => 'required',
            'kdunit' => 'required',
            'kdperiode' => 'required',
            'kdstatus' => 'required',
            'kdsumber' => 'required',
            'pagu_anggaran' => 'required|numeric',
            'tgl_mulai' => 'required|date',
        ]);

        Kegiatan::findOrFail($id)->update($request->all());

        return redirect()->route('kelembagaan.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diupdate');
    }

    public function destroy($id)
    {
        Kegiatan::findOrFail($id)->delete();
        return redirect()->route('kelembagaan.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus');
    }
    public function export()
    {
        return Excel::download(new KegiatanExport, 'kegiatan.xlsx');
    }
    public function exportPdf()
    {
        $kegiatan = Kegiatan::with([
            'jenis',
            'unit',
            'periode',
            'status',
            'sumberDana',
            'keputusan'
        ])->get();

        $pdf = Pdf::loadView('kelembagaan.kegiatan.pdf', compact('kegiatan'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('kegiatan.pdf');
    }
}
