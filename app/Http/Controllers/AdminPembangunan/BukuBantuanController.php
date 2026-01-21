<?php

namespace App\Http\Controllers\AdminPembangunan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BukuBantuan;
use App\Models\MasterSasaran;
use App\Models\MasterBantuan;
use App\Models\MasterSumberDana;
use Illuminate\Support\Facades\Auth;
use App\Exports\BukuBantuanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class BukuBantuanController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuBantuan::with(['sasaran', 'bantuan', 'sumber']);

        // 🔍 Search
        if ($request->search) {
            $query->where('bantuan_nama', 'like', '%' . $request->search . '%');
        }

        // 📄 Per page
        $perPage = $request->per_page ?? 10;

        $data = $query->paginate($perPage)->withQueryString();

        return view('admin-pembangunan.bantuan.index', compact('data'));
    }

    public function create()
    {
        return view('admin-pembangunan.bantuan.create', [
            'sasaran' => MasterSasaran::all(),
            'bantuan' => MasterBantuan::all(),
            'sumber'  => MasterSumberDana::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdsasaran' => 'required',
            'kdbantuan' => 'required',
            'bantuan_nama' => 'required',
            'bantuan_awal' => 'required|date',
            'bantuan_akhir' => 'nullable|date',
            'bantuan_jumlah' => 'required|numeric',
            'kdsumber' => 'required'
        ]);

        BukuBantuan::create([
            'kdsasaran' => $request->kdsasaran,
            'kdbantuan' => $request->kdbantuan,
            'bantuan_nama' => $request->bantuan_nama,
            'bantuan_awal' => $request->bantuan_awal,
            'bantuan_akhir' => $request->bantuan_akhir,
            'bantuan_jumlah' => $request->bantuan_jumlah,
            'bantuan_keterangan' => $request->bantuan_keterangan,
            'kdsumber' => $request->kdsumber,

            // 🔥 wajib karena kolom NOT NULL
            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.bantuan.index')
            ->with('success', 'Data bantuan berhasil disimpan');
    }


    public function edit($id)
    {
        return view('admin-pembangunan.bantuan.edit', [
            'item' => BukuBantuan::findOrFail($id),
            'sasaran' => MasterSasaran::all(),
            'bantuan' => MasterBantuan::all(),
            'sumber' => MasterSumberDana::all(),
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kdsasaran' => 'required',
            'kdbantuan' => 'required',
            'bantuan_nama' => 'required',
            'bantuan_awal' => 'required|date',
            'bantuan_akhir' => 'nullable|date',
            'bantuan_jumlah' => 'required|numeric',
            'kdsumber' => 'required'
        ]);

        $data = BukuBantuan::findOrFail($id);

        $data->update([
            'kdsasaran' => $request->kdsasaran,
            'kdbantuan' => $request->kdbantuan,
            'bantuan_nama' => $request->bantuan_nama,
            'bantuan_awal' => $request->bantuan_awal,
            'bantuan_akhir' => $request->bantuan_akhir,
            'bantuan_jumlah' => $request->bantuan_jumlah,
            'bantuan_keterangan' => $request->bantuan_keterangan,
            'kdsumber' => $request->kdsumber,

            // siapa yang terakhir mengedit
            'userinput' => Auth::check() ? Auth::user()->name : 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-pembangunan.bantuan.index')
            ->with('success', 'Data bantuan diperbarui');
    }


    public function destroy($id)
    {
        BukuBantuan::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus');
    }
    public function show($id)
    {
        $item = BukuBantuan::with([
            'sasaran',
            'bantuan',
            'sumber',
        ])->where('reg', $id)->firstOrFail();

        return view('admin-pembangunan.bantuan.show', compact('item'));
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuBantuanExport($request->search),
            'buku_bantuan.xlsx'
        );
    }
    public function exportPdf(Request $request)
    {
        $query = BukuBantuan::with(['sasaran', 'bantuan', 'sumber']);

        if ($request->search) {
            $query->where('bantuan_nama', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin-pembangunan.bantuan.export_pdf', [
            'data' => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_bantuan.pdf');
    }
}
