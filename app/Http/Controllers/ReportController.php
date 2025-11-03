<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataAsetKeluarga;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataAsetKeluargaExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $from = $request->input('from');
        $to = $request->input('to');

        $data = DataAsetKeluarga::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->whereHas('keluarga', function ($q) use ($search) {
                    $q->where('nama_kepala_keluarga', 'like', "%{$search}%")
                      ->orWhere('no_kk', 'like', "%{$search}%");
                });
            })
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to]);
            })
            ->latest()
            ->paginate(10);

        return view('report.index', compact('data', 'search', 'from', 'to'));
    }

    public function exportPdf(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $data = DataAsetKeluarga::with('keluarga')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to]);
            })
            ->get();

        $pdf = Pdf::loadView('report.pdf', compact('data'));
        return $pdf->download('laporan_aset_keluarga.pdf');
    }

    public function exportExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        return Excel::download(new DataAsetKeluargaExport($from, $to), 'laporan_aset_keluarga.xlsx');
    }
}
