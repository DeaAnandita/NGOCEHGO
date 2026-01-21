<?php

namespace App\Http\Controllers\AdminUmum;

use App\Http\Controllers\Controller;
use App\Models\BukuTanahKasDesa;
use App\Models\MasterPerolehanTKD;
use App\Models\MasterJenisTKD;
use App\Models\MasterPatok;
use App\Models\MasterPapanNama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BukuTanahKasDesaExport;

class BukuTanahKasDesaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BukuTanahKasDesa::with(['perolehan', 'jenis', 'patok', 'papanNama'])
            ->when($search, function ($q) use ($search) {
                $q->where('kdtanahkasdesa', 'like', "%$search%")
                    ->orWhere('lokasitanahkasdesa', 'like', "%$search%");
            })
            ->orderBy('tanggaltanahkasdesa', 'desc')
            ->paginate(10);

        return view('admin_umum.tanahkasdesa.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('admin_umum.tanahkasdesa.create', [
            'perolehan' => MasterPerolehanTKD::orderBy('perolehantkd')->get(),
            'jenis'     => MasterJenisTKD::orderBy('jenistkd')->get(),
            'patok'     => MasterPatok::orderBy('patok')->get(),
            'papan'     => MasterPapanNama::orderBy('papannama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdtanahkasdesa' => 'required|unique:buku_tanah_kas_desa,kdtanahkasdesa',
            'luastanahkasdesa' => 'nullable|numeric',
            'fototanahkasdesa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'kdtanahkasdesa',
            'asaltanahkasdesa',
            'sertifikattanahkasdesa',
            'luastanahkasdesa',
            'kelastanahkasdesa',
            'tanggaltanahkasdesa',
            'kdperolehantkd',
            'kdjenistkd',
            'kdpatok',
            'kdpapannama',
            'lokasitanahkasdesa',
            'peruntukantanahkasdesa',
            'mutasitanahkasdesa',
            'keterangantanahkasdesa'
        ]);

        if ($request->hasFile('fototanahkasdesa')) {
            $data['fototanahkasdesa'] = $request->file('fototanahkasdesa')
                ->store('tanahkasdesa', 'public');
        }

        $data['userinput'] = Auth::user()->name ?? 'system';
        $data['inputtime'] = now();

        BukuTanahKasDesa::create($data);

        return redirect()
            ->route('admin-umum.tanahkasdesa.index')
            ->with('success', 'Data Tanah Kas Desa berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuTanahKasDesa::with(['perolehan', 'jenis', 'patok', 'papanNama'])
            ->findOrFail($id);

        return view('admin_umum.tanahkasdesa.show', compact('data'));
    }

    public function edit($id)
    {
        return view('admin_umum.tanahkasdesa.edit', [
            'data'      => BukuTanahKasDesa::findOrFail($id),
            'perolehan' => MasterPerolehanTKD::orderBy('perolehantkd')->get(),
            'jenis'     => MasterJenisTKD::orderBy('jenistkd')->get(),
            'patok'     => MasterPatok::orderBy('patok')->get(),
            'papan'     => MasterPapanNama::orderBy('papannama')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = BukuTanahKasDesa::findOrFail($id);

        $request->validate([
            'luastanahkasdesa' => 'nullable|numeric',
            'fototanahkasdesa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $input = $request->only([
            'asaltanahkasdesa',
            'sertifikattanahkasdesa',
            'luastanahkasdesa',
            'kelastanahkasdesa',
            'tanggaltanahkasdesa',
            'kdperolehantkd',
            'kdjenistkd',
            'kdpatok',
            'kdpapannama',
            'lokasitanahkasdesa',
            'peruntukantanahkasdesa',
            'mutasitanahkasdesa',
            'keterangantanahkasdesa'
        ]);

        if ($request->hasFile('fototanahkasdesa')) {
            if ($data->fototanahkasdesa && Storage::disk('public')->exists($data->fototanahkasdesa)) {
                Storage::disk('public')->delete($data->fototanahkasdesa);
            }

            $input['fototanahkasdesa'] = $request->file('fototanahkasdesa')
                ->store('tanahkasdesa', 'public');
        }

        $data->update($input);

        return redirect()
            ->route('admin-umum.tanahkasdesa.index')
            ->with('success', 'Data Tanah Kas Desa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = BukuTanahKasDesa::findOrFail($id);

        if ($data->fototanahkasdesa && Storage::disk('public')->exists($data->fototanahkasdesa)) {
            Storage::disk('public')->delete($data->fototanahkasdesa);
        }

        $data->delete();

        return redirect()
            ->route('admin-umum.tanahkasdesa.index')
            ->with('success', 'Data Tanah Kas Desa berhasil dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuTanahKasDesaExport($request->search),
            'buku_tanah_kas_desa.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = BukuTanahKasDesa::with(['perolehan', 'jenis', 'patok', 'papanNama']);

        if ($request->search) {
            $query->where('kdtanahkasdesa', 'like', "%{$request->search}%")
                ->orWhere('lokasitanahkasdesa', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin_umum.tanahkasdesa.export_pdf', [
            'data'   => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_tanah_kas_desa.pdf');
    }
}
