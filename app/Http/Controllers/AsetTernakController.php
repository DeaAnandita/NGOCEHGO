<?php

namespace App\Http\Controllers;

use App\Models\DataAsetTernak;
use App\Models\DataKeluarga;
use App\Models\MasterAsetTernak;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AsetTernakController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $asetternaks = DataAsetTernak::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterAset = MasterAsetTernak::pluck('asetternak', 'kdasetternak')->toArray();

        return view('keluarga.asetternak.index', compact('asetternaks', 'masterAset', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas  = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.create', compact('keluargas', 'masterAset'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetternak,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", 0);
        }

        DataAsetTernak::create($data);

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $keluargas  = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.edit', compact('asetternak', 'keluargas', 'masterAset'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $data = ['no_kk' => $request->no_kk];

        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", 0);
        }

        $asetternak->update($data);

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $asetternak->delete();

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil dihapus.');
    }

    /** ================================
     * 🧾 EXPORT PDF
     * ================================ */
    public function exportPdf()
    {
        $data = DataAsetTernak::all();
        $totalKeluarga = $data->count();

        if ($totalKeluarga === 0) {
            return back()->with('error', 'Tidak ada data aset ternak untuk dianalisis.');
        }

        // 1️⃣ keluarga punya / tidak punya ternak (LOGIKA ASLI)
        $keluargaPunyaTernak = $data->filter(function ($row) {
            for ($i = 1; $i <= 24; $i++) {
                if ($row->{"asetternak_$i"} > 0) {
                    return true;
                }
            }
            return false;
        })->count();

        $keluargaTanpaTernak = $totalKeluarga - $keluargaPunyaTernak;

        // 2️⃣ distribusi per jenis ternak (DITAMBAH jumlah_aset)
        $master = MasterAsetTernak::pluck('asetternak', 'kdasetternak')->toArray();
        $indikator = [];

        foreach ($master as $kode => $nama) {
            $jumlahKeluarga = DataAsetTernak::where("asetternak_$kode", '>', 0)->count();
            $jumlahAset     = DataAsetTernak::sum("asetternak_$kode"); // TAMBAHAN SAJA

            $indikator[] = [
                'kode'            => $kode,
                'nama'            => Str::replaceFirst('Jumlah ', '', $nama),
                'jumlah_keluarga' => $jumlahKeluarga,
                'jumlah_aset'     => $jumlahAset,
            ];
        }

        // 3️⃣ interpretasi (ASLI)
        if ($keluargaTanpaTernak > ($totalKeluarga * 0.6)) {
            $interpretasi = 'Mayoritas keluarga belum memiliki aset ternak produktif, menunjukkan tingkat kerentanan ekonomi yang tinggi.';
        } elseif ($keluargaPunyaTernak > ($totalKeluarga * 0.6)) {
            $interpretasi = 'Sebagian besar keluarga telah memiliki aset ternak sebagai sumber ekonomi pendukung.';
        } else {
            $interpretasi = 'Kepemilikan ternak belum merata dan masih terkonsentrasi pada kelompok tertentu.';
        }

        // 4️⃣ rekomendasi (ASLI)
        $rekomendasi = [];
        if ($keluargaTanpaTernak > 0) {
            $rekomendasi[] = 'Prioritas bantuan ternak produktif kepada keluarga tanpa aset.';
        }

        $rekomendasi[] = 'Pengembangan bantuan ternak berbasis potensi lokal desa.';
        $rekomendasi[] = 'Pendampingan teknis dan monitoring pasca bantuan.';
        $rekomendasi[] = 'Integrasi data aset ternak dengan data kemiskinan desa.';

        // 5️⃣ PDF
        $pdf = Pdf::loadView('laporan.asetternak', [
            'periode'             => Carbon::now()->translatedFormat('F Y'),
            'tanggal'             => Carbon::now()->translatedFormat('d F Y'),
            'totalKeluarga'       => $totalKeluarga,
            'keluargaPunyaTernak' => $keluargaPunyaTernak,
            'keluargaTanpaTernak' => $keluargaTanpaTernak,
            'indikator'           => $indikator,
            'interpretasi'        => $interpretasi,
            'rekomendasi'         => $rekomendasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Aset-Ternak.pdf');
    }
}
