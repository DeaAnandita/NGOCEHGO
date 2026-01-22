<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\PencairanExport;
use App\Http\Controllers\Controller;
use App\Models\kegiatan;
use App\Models\PencairanDana;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PencairanDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = PencairanDana::with('kegiatan');

        if ($request->search) {
            $query->whereHas('kegiatan', function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->bulan) {
            $query->whereMonth('tanggal_cair', substr($request->bulan, 5, 2))
                ->whereYear('tanggal_cair', substr($request->bulan, 0, 4));
        }

        $data = $query->orderBy('tanggal_cair', 'desc')->paginate(15);

        return view('kelembagaan.pencairan.index', compact('data'));
    }


    public function create()
    {
        $kegiatan = Kegiatan::all();
        return view('kelembagaan.pencairan.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required',
            'tanggal_cair' => 'required|date',
            'jumlah' => 'required|numeric|min:1'
        ]);

        $kegiatan = Kegiatan::findOrFail($request->kegiatan_id);

        $sudahCair = $kegiatan->pencairanDana()->sum('jumlah');
        $sisa = $kegiatan->pagu_anggaran - $sudahCair;

        if ($request->jumlah > $sisa) {
            return back()->withErrors(['jumlah' => 'Melebihi sisa anggaran kegiatan']);
        }

        PencairanDana::create($request->all());

        return redirect()->route('kelembagaan.pencairan.index')
            ->with('success', 'Pencairan berhasil dicatat');
    }
    public function edit($id)
    {
        $pencairan = PencairanDana::with('kegiatan')->findOrFail($id);

        $sudahCair = $pencairan->kegiatan->pencairanDana()
            ->where('id', '!=', $id)
            ->sum('jumlah');

        $sisaAnggaran = $pencairan->kegiatan->pagu_anggaran - $sudahCair;

        return view('kelembagaan.pencairan.edit', compact(
            'pencairan',
            'sudahCair',
            'sisaAnggaran'
        ));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_cair' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
            'no_sp2d' => 'nullable'
        ]);

        $pencairan = PencairanDana::findOrFail($id);
        $kegiatan = $pencairan->kegiatan;

        // hitung ulang sisa (kecuali data ini)
        $sudah = $kegiatan->pencairanDana()
            ->where('id', '!=', $id)
            ->sum('jumlah');

        $sisa = $kegiatan->pagu_anggaran - $sudah;

        if ($request->jumlah > $sisa) {
            return back()->withErrors(['jumlah' => 'Melebihi sisa anggaran']);
        }

        $pencairan->update($request->only('tanggal_cair', 'jumlah', 'no_sp2d'));

        return redirect()
            ->route('kelembagaan.pencairan.index')
            ->with('success', 'Pencairan berhasil diperbarui');
    }

    public function show($id)
    {
        $pencairan = PencairanDana::with('kegiatan', 'realisasi')->findOrFail($id);

        $totalRealisasi = $pencairan->realisasi->sum('jumlah');
        $sisa = $pencairan->jumlah - $totalRealisasi;

        return view('kelembagaan.pencairan.show', compact('pencairan', 'totalRealisasi', 'sisa'));
    }
    public function destroy($id)
    {
        $pencairan = PencairanDana::findOrFail($id);

        // (opsional) kalau sudah ada realisasi, jangan boleh dihapus
        if ($pencairan->realisasi()->count() > 0) {
            return back()->withErrors([
                'error' => 'Pencairan tidak bisa dihapus karena sudah memiliki realisasi'
            ]);
        }

        $pencairan->delete();

        return redirect()->route('kelembagaan.pencairan.index')
            ->with('success', 'Pencairan berhasil dihapus');
    }
    public function export()
    {
        $data = PencairanDana::with([
            'kegiatan',
            'realisasiPengeluaran'
        ])->get();

        return Excel::download(new PencairanExport($data), 'pencairan_dana.xlsx');
    }

    public function exportPdf()
    {
        $pencairan = PencairanDana::with([
            'kegiatan',
            'realisasiPengeluaran'
        ])->get();

        $pdf = Pdf::loadView(
            'kelembagaan.pencairan.pdf',
            compact('pencairan')
        )->setPaper('A4', 'landscape');

        return $pdf->download('pencairan_dana.pdf');
    }
}
