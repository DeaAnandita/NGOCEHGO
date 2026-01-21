<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\AgendaExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaKelembagaan;
use App\Models\{
    MasterJenisAgenda,
    MasterStatusAgenda,
    MasterTempatAgenda,
    MasterUnitKeputusan,
    MasterPeriodeKelembagaan
};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AgendaKelembagaanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $agenda = AgendaKelembagaan::with(['jenis', 'unit', 'status', 'tempat'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('judul_agenda', 'like', '%' . $request->search . '%');
            })
            ->latest('tanggal')
            ->paginate($perPage)
            ->withQueryString();

        return view('kelembagaan.agenda.index', compact('agenda'));
    }


    public function create()
    {
        return view('kelembagaan.agenda.create', [
            'jenis' => MasterJenisAgenda::all(),
            'unit' => MasterUnitKeputusan::all(),
            'status' => MasterStatusAgenda::all(),
            'tempat' => MasterTempatAgenda::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
        ]);
    }

    public function store(Request $request)
    {
        AgendaKelembagaan::create($request->all());
        return redirect()->route('kelembagaan.agenda.index')->with('success', 'Agenda berhasil disimpan');
    }
    public function show($id)
    {
        $agenda = AgendaKelembagaan::with(['jenis', 'unit', 'status', 'tempat', 'periode'])
            ->findOrFail($id);

        return view('kelembagaan.agenda.show', compact('agenda'));
    }

    public function edit($id)
    {
        return view('kelembagaan.agenda.edit', [
            'agenda' => AgendaKelembagaan::findOrFail($id),
            'jenis' => MasterJenisAgenda::all(),
            'unit' => MasterUnitKeputusan::all(),
            'status' => MasterStatusAgenda::all(),
            'tempat' => MasterTempatAgenda::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        AgendaKelembagaan::findOrFail($id)->update($request->all());
        return redirect()->route('kelembagaan.agenda.index')->with('success', 'Agenda berhasil diupdate');
    }

    public function destroy($id)
    {
        AgendaKelembagaan::findOrFail($id)->delete();
        return redirect()->route('kelembagaan.agenda.index')->with('success', 'Agenda berhasil dihapus');
    }
    public function export()
    {
        $agenda = AgendaKelembagaan::with(['jenis', 'unit', 'status', 'tempat'])
            ->orderBy('tanggal')
            ->get();

        return Excel::download(new AgendaExport($agenda), 'agenda.xlsx');
    }
    public function exportPdf()
    {
        $agenda = AgendaKelembagaan::with(['jenis', 'unit', 'status', 'tempat'])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('kelembagaan.agenda.pdf', compact('agenda'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('agenda.pdf');
    }
}
