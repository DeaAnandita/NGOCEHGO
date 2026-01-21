<?php

namespace App\Http\Controllers\AdminPembangunan;

use App\Http\Controllers\Controller;
use App\Models\BukuProyek;
use App\Models\MasterKegiatan;
use App\Models\MasterLokasi;
use App\Models\MasterPelaksana;
use App\Models\MasterSumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BukuProyekExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class BukuProyekController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuProyek::with(['kegiatan', 'pelaksana', 'lokasi', 'sumber']);

        // 🔍 Search berdasarkan nama kegiatan
        if ($request->search) {
            $query->whereHas('kegiatan', function ($q) use ($request) {
                $q->where('kegiatan', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->per_page ?? 10;

        $data = $query->orderBy('proyek_tanggal', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin-pembangunan.proyek.index', compact('data'));
    }

    public function create()
    {
        return view('admin-pembangunan.proyek.create', [
            'kegiatan' => MasterKegiatan::all(),
            'pelaksana' => MasterPelaksana::all(),
            'lokasi' => MasterLokasi::all(),
            'sumber' => MasterSumberDana::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdproyek' => 'required',
            'proyek_tanggal' => 'required|date',
            'kdkegiatan' => 'required',
            'kdpelaksana' => 'required',
            'kdlokasi' => 'required',
            'kdsumber' => 'required',
            'proyek_nominal' => 'required|numeric',
        ]);

        BukuProyek::create([
            'kdproyek' => $request->kdproyek,
            'proyek_tanggal' => $request->proyek_tanggal,
            'kdkegiatan' => $request->kdkegiatan,
            'kdpelaksana' => $request->kdpelaksana,
            'kdlokasi' => $request->kdlokasi,
            'kdsumber' => $request->kdsumber,
            'proyek_nominal' => $request->proyek_nominal,
            'proyek_manfaat' => $request->proyek_manfaat,
            'proyek_keterangan' => $request->proyek_keterangan,

            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.proyek.index')
            ->with('success', 'Data proyek berhasil disimpan');
    }

    public function edit($id)
    {
        return view('admin-pembangunan.proyek.edit', [
            'item' => BukuProyek::where('reg', $id)->firstOrFail(),
            'kegiatan' => MasterKegiatan::all(),
            'pelaksana' => MasterPelaksana::all(),
            'lokasi' => MasterLokasi::all(),
            'sumber' => MasterSumberDana::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kdproyek' => 'required',
            'proyek_tanggal' => 'required|date',
            'kdkegiatan' => 'required',
            'kdpelaksana' => 'required',
            'kdlokasi' => 'required',
            'kdsumber' => 'required',
            'proyek_nominal' => 'required|numeric',
        ]);

        $data = BukuProyek::where('reg', $id)->firstOrFail();

        $data->update([
            'kdproyek' => $request->kdproyek,
            'proyek_tanggal' => $request->proyek_tanggal,
            'kdkegiatan' => $request->kdkegiatan,
            'kdpelaksana' => $request->kdpelaksana,
            'kdlokasi' => $request->kdlokasi,
            'kdsumber' => $request->kdsumber,
            'proyek_nominal' => $request->proyek_nominal,
            'proyek_manfaat' => $request->proyek_manfaat,
            'proyek_keterangan' => $request->proyek_keterangan,

            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.proyek.index')
            ->with('success', 'Data proyek diperbarui');
    }

    public function destroy($id)
    {
        BukuProyek::where('reg', $id)->firstOrFail()->delete();
        return back()->with('success', 'Data proyek dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuProyekExport($request->search),
            'buku_proyek.xlsx'
        );
    }
    public function show($id)
    {
        $data = BukuProyek::with(['kegiatan', 'pelaksana', 'lokasi', 'sumber'])
            ->where('reg', $id)
            ->firstOrFail();

        return view('admin-pembangunan.proyek.show', compact('data'));
    }
    public function exportPdf(Request $request)
    {
        $query = BukuProyek::with(['kegiatan', 'pelaksana', 'lokasi', 'sumber']);

        if ($request->search) {
            $query->where('kdproyek', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin-pembangunan.proyek.export_pdf', [
            'data' => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_proyek.pdf');
    }
}
