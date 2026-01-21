<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\KeputusanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keputusan;
use App\Models\{
    MasterJenisKeputusan,
    MasterUnitKeputusan,
    MasterPeriodeKelembagaan,
    MasterStatusKeputusan,
    MasterMetodeKeputusan,
    MasterJabatanKelembagaan
};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class KeputusanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $keputusan = Keputusan::with(['jenis', 'unit', 'status', 'metode'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('judul_keputusan', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_sk', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('kelembagaan.keputusan.index', compact('keputusan'));
    }


    public function create()
    {
        return view('kelembagaan.keputusan.create', [
            'jenis'   => MasterJenisKeputusan::all(),
            'unit'    => MasterUnitKeputusan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'status'  => MasterStatusKeputusan::all(),
            'metode'  => MasterMetodeKeputusan::all(),
            'jabatan' => MasterJabatanKelembagaan::all(),
        ]);
    }

    public function store(Request $request)
    {
        Keputusan::create($request->all());
        return redirect()->route('kelembagaan.keputusan.index')->with('success', 'Keputusan berhasil disimpan');
    }

    public function show($id)
    {
        $keputusan = Keputusan::with(['jenis', 'unit', 'periode', 'status', 'metode', 'jabatan'])->findOrFail($id);
        return view('kelembagaan.keputusan.show', compact('keputusan'));
    }

    public function edit($id)
    {
        return view('kelembagaan.keputusan.edit', [
            'keputusan' => Keputusan::findOrFail($id),
            'jenis'   => MasterJenisKeputusan::all(),
            'unit'    => MasterUnitKeputusan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'status'  => MasterStatusKeputusan::all(),
            'metode'  => MasterMetodeKeputusan::all(),
            'jabatan' => MasterJabatanKelembagaan::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        Keputusan::findOrFail($id)->update($request->all());
        return redirect()->route('kelembagaan.keputusan.index')->with('success', 'Keputusan berhasil diupdate');
    }

    public function destroy($id)
    {
        Keputusan::findOrFail($id)->delete();
        return redirect()->route('kelembagaan.keputusan.index')->with('success', 'Keputusan berhasil dihapus');
    }
    // ================= EXPORT EXCEL =================
    public function export(Request $request)
    {
        return Excel::download(
            new KeputusanExport($request->search),
            'keputusan.xlsx'
        );
    }


    public function exportPdf(Request $request)
    {
        $keputusan = Keputusan::with(['jenis', 'unit', 'status', 'metode', 'jabatan', 'periode'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('judul_keputusan', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_sk', 'like', '%' . $request->search . '%');
            })
            ->get();

        $pdf = Pdf::loadView('kelembagaan.keputusan.pdf', compact('keputusan'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('keputusan.pdf');
    }
}
