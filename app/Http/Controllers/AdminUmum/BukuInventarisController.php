<?php

namespace App\Http\Controllers\AdminUmum;

use App\Http\Controllers\Controller;
use App\Models\BukuInventaris;
use App\Models\MasterPengguna;
use App\Models\MasterSatuanBarang;
use App\Models\MasterAsalBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BukuInventarisExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuInventarisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BukuInventaris::with(['pengguna', 'satuan', 'asalBarang'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kdinventaris', 'like', "%{$search}%")
                        ->orWhere('inventaris_identitas', 'like', "%{$search}%");
                });
            })
            ->orderBy('inventaris_tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin_umum.inventaris.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('admin_umum.inventaris.create', [
            'pengguna' => MasterPengguna::orderBy('pengguna')->get(),
            'satuan'   => MasterSatuanBarang::orderBy('satuanbarang')->get(),
            'asal'     => MasterAsalBarang::orderBy('asalbarang')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdinventaris' => 'required|unique:buku_inventaris,kdinventaris',
            'inventaris_tanggal' => 'required|date',
            'inventaris_identitas' => 'required',
            'inventaris_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('inventaris_foto')) {
            $path = $request->file('inventaris_foto')->store('inventaris', 'public');
        }

        BukuInventaris::create([
            'kdinventaris' => $request->kdinventaris,
            'inventaris_tanggal' => $request->inventaris_tanggal,
            'kdpengguna' => $request->kdpengguna,
            'anak' => $request->anak,
            'inventaris_volume' => $request->inventaris_volume,
            'inventaris_hapus' => 0, // 🔥 PENTING
            'kdsatuanbarang' => $request->kdsatuanbarang,
            'inventaris_identitas' => $request->inventaris_identitas,
            'kdasalbarang' => $request->kdasalbarang,
            'barangasal' => $request->barangasal,
            'inventaris_harga' => $request->inventaris_harga,
            'inventaris_keterangan' => $request->inventaris_keterangan,
            'inventaris_foto' => $path,
            'userinput' => Auth::user()->name ?? 'system',
            'inputtime' => now(),
        ]);


        return redirect()->route('admin-umum.inventaris.index')
            ->with('success', 'Data inventaris berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuInventaris::with(['pengguna', 'satuan', 'asalBarang'])
            ->where('kdinventaris', $id)
            ->firstOrFail();

        return view('admin_umum.inventaris.show', compact('data'));
    }

    public function edit($id)
    {
        return view('admin_umum.inventaris.edit', [
            'data' => BukuInventaris::where('kdinventaris', $id)->firstOrFail(),
            'pengguna' => MasterPengguna::orderBy('pengguna')->get(),
            'satuan'   => MasterSatuanBarang::orderBy('satuanbarang')->get(),
            'asal'     => MasterAsalBarang::orderBy('asalbarang')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = BukuInventaris::where('kdinventaris', $id)->firstOrFail();

        $request->validate([
            'inventaris_tanggal' => 'required|date',
            'inventaris_identitas' => 'required',
            'inventaris_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $update = [
            'inventaris_tanggal' => $request->inventaris_tanggal,
            'kdpengguna' => $request->kdpengguna,
            'anak' => $request->anak,
            'inventaris_volume' => $request->inventaris_volume,
            'inventaris_hapus' => $request->inventaris_hapus ?? $data->inventaris_hapus, // 🔥
            'kdsatuanbarang' => $request->kdsatuanbarang,
            'inventaris_identitas' => $request->inventaris_identitas,
            'kdasalbarang' => $request->kdasalbarang,
            'barangasal' => $request->barangasal,
            'inventaris_harga' => $request->inventaris_harga,
            'inventaris_keterangan' => $request->inventaris_keterangan,
        ];

        // 🔥 file hanya diganti jika upload baru
        if ($request->hasFile('inventaris_foto')) {
            $update['inventaris_foto'] = $request->file('inventaris_foto')->store('inventaris', 'public');
        }

        $data->update($update);

        return redirect()->route('admin-umum.inventaris.index')
            ->with('success', 'Data inventaris berhasil diperbarui');
    }

    public function destroy($id)
    {
        BukuInventaris::where('kdinventaris', $id)->delete();

        return redirect()->route('admin-umum.inventaris.index')
            ->with('success', 'Data inventaris berhasil dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuInventarisExport($request->search),
            'buku_inventaris.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = BukuInventaris::with(['pengguna', 'satuan', 'asalBarang'])
            ->where('inventaris_hapus', 0);

        if ($request->search) {
            $query->where('kdinventaris', 'like', "%$request->search%")
                ->orWhere('inventaris_identitas', 'like', "%$request->search%");
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin_umum.inventaris.export_pdf', [
            'data' => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape');

        return $pdf->download('buku_inventaris.pdf');
    }
}
