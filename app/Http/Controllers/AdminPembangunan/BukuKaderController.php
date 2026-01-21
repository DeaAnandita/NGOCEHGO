<?php

namespace App\Http\Controllers\AdminPembangunan;

use App\Http\Controllers\Controller;
use App\Models\BukuKader;
use App\Models\MasterKaderBidang;
use App\Models\MasterPendidikan;
use App\Models\MasterStatusKader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BukuKaderExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class BukuKaderController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuKader::with(['pendidikan', 'bidang', 'status', 'penduduk']);

        // 🔍 Search by nama penduduk
        if ($request->search) {
            $query->whereHas('penduduk', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->per_page ?? 10;

        $data = $query->orderBy('kader_tanggal', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin-pembangunan.kader.index', compact('data'));
    }

    public function create()
    {
        return view('admin-pembangunan.kader.create', [
            'pendidikan' => MasterPendidikan::all(),
            'bidang' => MasterKaderBidang::all(),
            'status' => MasterStatusKader::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdkader' => 'required|numeric',
            'kader_tanggal' => 'required|date',
            'kdpenduduk' => 'required|string|size:16', // NIK
            'kdpendidikan' => 'required',
            'kdbidang' => 'required',
            'kdstatuskader' => 'required',
        ]);

        BukuKader::create([
            'kdkader' => $request->kdkader,
            'kader_tanggal' => $request->kader_tanggal,
            'kdpenduduk' => $request->kdpenduduk,
            'kdpendidikan' => $request->kdpendidikan,
            'kdbidang' => $request->kdbidang,
            'kdstatuskader' => $request->kdstatuskader,
            'kader_keterangan' => $request->kader_keterangan,

            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.kader.index')
            ->with('success', 'Data kader berhasil disimpan');
    }

    public function edit($id)
    {
        return view('admin-pembangunan.kader.edit', [
            'item' => BukuKader::where('reg', $id)->firstOrFail(),
            'pendidikan' => MasterPendidikan::all(),
            'bidang' => MasterKaderBidang::all(),
            'status' => MasterStatusKader::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kdkader' => 'required',
            'kader_tanggal' => 'required|date',
            'kdpenduduk' => 'required|string|size:16', // NIK
            'kdpendidikan' => 'required',
            'kdbidang' => 'required',
            'kdstatuskader' => 'required',
        ]);

        $data = BukuKader::where('reg', $id)->firstOrFail();

        $data->update([
            'kdkader' => $request->kdkader,
            'kader_tanggal' => $request->kader_tanggal,
            'kdpenduduk' => $request->kdpenduduk,
            'kdpendidikan' => $request->kdpendidikan,
            'kdbidang' => $request->kdbidang,
            'kdstatuskader' => $request->kdstatuskader,
            'kader_keterangan' => $request->kader_keterangan,

            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.kader.index')
            ->with('success', 'Data kader diperbarui');
    }

    public function destroy($id)
    {
        BukuKader::where('reg', $id)->firstOrFail()->delete();
        return back()->with('success', 'Data kader dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuKaderExport($request->search),
            'buku_kader.xlsx'
        );
    }
    public function exportPdf(Request $request)
    {
        $query = BukuKader::with(['pendidikan', 'bidang', 'status', 'penduduk']);

        if ($request->search) {
            $query->whereHas('penduduk', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%");
            });
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin-pembangunan.kader.export_pdf', [
            'data' => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_kader.pdf');
    }
}
