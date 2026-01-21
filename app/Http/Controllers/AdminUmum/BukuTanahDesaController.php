<?php

namespace App\Http\Controllers\AdminUmum;

use App\Http\Controllers\Controller;
use App\Models\BukuTanahDesa;
use App\Models\MasterStatusHakTanah;
use App\Models\MasterPenggunaanTanah;
use App\Models\MasterMutasiTanah;
use App\Models\MasterJenisPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuTanahDesaExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuTanahDesaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BukuTanahDesa::with(['statusHak', 'penggunaan', 'mutasi', 'jenisPemilik'])
            ->when($search, function ($q) use ($search) {
                $q->where('kdtanahdesa', 'like', "%$search%")
                    ->orWhere('pemiliktanahdesa', 'like', "%$search%");
            })
            ->orderBy('tanggaltanahdesa', 'desc')
            ->paginate(10);

        return view('admin_umum.tanahdesa.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('admin_umum.tanahdesa.create', [
            'statusHak'    => MasterStatusHakTanah::orderBy('statushaktanah')->get(),
            'penggunaan'   => MasterPenggunaanTanah::orderBy('penggunaantanah')->get(),
            'mutasi'       => MasterMutasiTanah::orderBy('mutasitanah')->get(),
            'jenisPemilik' => MasterJenisPemilik::orderBy('jenispemilik')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdtanahdesa'          => 'required|unique:buku_tanahdesa,kdtanahdesa',
            'tanggaltanahdesa'    => 'required|date',
            'pemiliktanahdesa'    => 'required',
            'luastanahdesa'       => 'nullable|numeric',
            'fototanahdesa'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'kdtanahdesa',
            'tanggaltanahdesa',
            'kdjenispemilik',
            'pemiliktanahdesa',
            'kdpemilik',
            'luastanahdesa',
            'kdstatushaktanah',
            'kdpenggunaantanah',
            'kdmutasitanah',
            'tanggalmutasitanahdesa',
            'keterangantanahdesa'
        ]);

        if ($request->hasFile('fototanahdesa')) {
            $data['fototanahdesa'] = $request->file('fototanahdesa')->store('tanahdesa', 'public');
        }

        $data['userinput'] = Auth::user()->name ?? 'system';
        $data['inputtime'] = now();

        BukuTanahDesa::create($data);

        return redirect()
            ->route('admin-umum.tanahdesa.index')
            ->with('success', 'Data tanah desa berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuTanahDesa::with(['statusHak', 'penggunaan', 'mutasi', 'jenisPemilik'])
            ->findOrFail($id);

        return view('admin_umum.tanahdesa.show', compact('data'));
    }

    public function edit($id)
    {
        return view('admin_umum.tanahdesa.edit', [
            'data' => BukuTanahDesa::findOrFail($id),
            'statusHak'    => MasterStatusHakTanah::orderBy('statushaktanah')->get(),
            'penggunaan'   => MasterPenggunaanTanah::orderBy('penggunaantanah')->get(),
            'mutasi'       => MasterMutasiTanah::orderBy('mutasitanah')->get(),
            'jenisPemilik' => MasterJenisPemilik::orderBy('jenispemilik')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = BukuTanahDesa::findOrFail($id);

        $request->validate([
            'tanggaltanahdesa' => 'required|date',
            'pemiliktanahdesa' => 'required',
            'luastanahdesa'    => 'nullable|numeric',
            'fototanahdesa'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $input = $request->only([
            'tanggaltanahdesa',
            'kdjenispemilik',
            'pemiliktanahdesa',
            'kdpemilik',
            'luastanahdesa',
            'kdstatushaktanah',
            'kdpenggunaantanah',
            'kdmutasitanah',
            'tanggalmutasitanahdesa',
            'keterangantanahdesa'
        ]);

        if ($request->hasFile('fototanahdesa')) {
            if ($data->fototanahdesa && Storage::disk('public')->exists($data->fototanahdesa)) {
                Storage::disk('public')->delete($data->fototanahdesa);
            }

            $input['fototanahdesa'] = $request->file('fototanahdesa')->store('tanahdesa', 'public');
        }

        $data->update($input);

        return redirect()
            ->route('admin-umum.tanahdesa.index')
            ->with('success', 'Data tanah desa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = BukuTanahDesa::findOrFail($id);

        if ($data->fototanahdesa && Storage::disk('public')->exists($data->fototanahdesa)) {
            Storage::disk('public')->delete($data->fototanahdesa);
        }

        $data->delete();

        return redirect()
            ->route('admin-umum.tanahdesa.index')
            ->with('success', 'Data tanah desa berhasil dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuTanahDesaExport($request->search),
            'buku_tanah_desa.xlsx'
        );
    }
    public function exportPdf(Request $request)
    {
        $query = BukuTanahDesa::with(['statusHak', 'penggunaan', 'mutasi', 'jenisPemilik']);

        if ($request->search) {
            $query->where('kdtanahdesa', 'like', "%{$request->search}%")
                ->orWhere('pemiliktanahdesa', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin_umum.tanahdesa.export_pdf', [
            'data'   => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_tanah_desa.pdf');
    }
}
