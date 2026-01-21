<?php

namespace App\Http\Controllers\AdminUmum;

use App\Exports\BukuAparatExport;
use App\Http\Controllers\Controller;
use App\Models\BukuAparat;
use App\Models\MasterAparat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BukuAparatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $data = BukuAparat::with('masterAparat')
            ->when($search, function ($q) use ($search) {
                $q->where('namaaparat', 'like', "%{$search}%") // ✅ tambah
                    ->orWhere('nipaparat', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            })


            ->orderBy('tanggalpengangkatan', 'desc')
            ->paginate(10);

        return view('admin_umum.aparat.index', compact('data', 'search'));
    }

    public function create()
    {
        $masterAparat = MasterAparat::orderBy('aparat')->get();
        return view('admin_umum.aparat.create', compact('masterAparat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdaparat' => 'required|exists:master_aparat,kdaparat',
            'namaaparat' => 'required|string|max:150', // ✅ tambah
            'nipaparat' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'pangkataparat' => 'nullable|string|max:100',
            'nomorpengangkatan' => 'nullable|string|max:100',
            'tanggalpengangkatan' => 'nullable|date',
            'keteranganaparatdesa' => 'nullable|string',
            'fotopengangkatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'statusaparatdesa' => 'required|in:Aktif,Nonaktif',
        ]);

        $data = $request->all();

        if ($request->hasFile('fotopengangkatan')) {
            $data['fotopengangkatan'] = $request->file('fotopengangkatan')
                ->store('aparat', 'public');
        }

        $data['userinput'] = Auth::user()->name ?? 'system';
        $data['inputtime'] = now();

        BukuAparat::create($data);

        return redirect()
            ->route('admin-umum.aparat.index')
            ->with('success', 'Data aparat berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuAparat::with('masterAparat')->findOrFail($id);
        return view('admin_umum.aparat.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BukuAparat::findOrFail($id);
        $masterAparat = MasterAparat::orderBy('aparat')->get();

        return view('admin_umum.aparat.edit', compact('data', 'masterAparat'));
    }

    public function update(Request $request, $id)
    {
        $data = BukuAparat::findOrFail($id);

        $request->validate([
            'kdaparat' => 'required|exists:master_aparat,kdaparat',
            'namaaparat' => 'required|string|max:150', // ✅ tambah
            'nipaparat' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'pangkataparat' => 'nullable|string|max:100',
            'nomorpengangkatan' => 'nullable|string|max:100',
            'tanggalpengangkatan' => 'nullable|date',
            'keteranganaparatdesa' => 'nullable|string',
            'fotopengangkatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'statusaparatdesa' => 'required|in:Aktif,Nonaktif',
        ]);

        $input = $request->all();

        if ($request->hasFile('fotopengangkatan')) {
            if ($data->fotopengangkatan && Storage::disk('public')->exists($data->fotopengangkatan)) {
                Storage::disk('public')->delete($data->fotopengangkatan);
            }

            $input['fotopengangkatan'] = $request->file('fotopengangkatan')
                ->store('aparat', 'public');
        }

        $data->update($input);

        return redirect()
            ->route('admin-umum.aparat.index')
            ->with('success', 'Data aparat berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = BukuAparat::findOrFail($id);

        if ($data->fotopengangkatan && Storage::disk('public')->exists($data->fotopengangkatan)) {
            Storage::disk('public')->delete($data->fotopengangkatan);
        }

        $data->delete();

        return redirect()
            ->route('admin-umum.aparat.index')
            ->with('success', 'Data aparat berhasil dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuAparatExport($request->status),
            'data_aparat.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = BukuAparat::with('masterAparat');

        if ($request->status) {
            $query->where('statusaparatdesa', $request->status);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin_umum.aparat.export_pdf', [
            'data' => $data,
            'status' => $request->status
        ])->setPaper('A4', 'landscape');

        return $pdf->download('data_aparat.pdf');
    }
}
