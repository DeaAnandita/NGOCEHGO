<?php

namespace App\Http\Controllers;

use App\Models\DataBangunKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;
use App\Models\MasterJawabBangun; // pastikan model ini ada (tabel master jawaban)
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BangunKeluargaController extends Controller
{
    /**
     * Tampilkan daftar data bangun keluarga.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $bangunkeluargas = DataBangunKeluarga::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterPembangunankeluarga = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $masterJawab = MasterJawabBangun::pluck('jawabbangun', 'kdjawabbangun')->toArray();

        return view('keluarga.bangunkeluarga.index', compact(
            'bangunkeluargas',
            'masterPembangunankeluarga',
            'masterJawab',
            'search',
            'perPage'
        ));
    }

    /**
     * Form tambah data.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterBangun = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.create', compact('keluargas', 'masterBangun', 'masterJawab'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_bangunkeluarga,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        foreach (range(1, 51) as $i) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", null);
        }

        DataBangunKeluarga::create($data);

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data Bangun Keluarga berhasil ditambahkan.');
    }

    /**
     * Edit data.
     */
    public function edit($no_kk)
    {
        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $masterJawab = MasterJawabBangun::all();

        return view('keluarga.bangunkeluarga.edit', compact(
            'bangunkeluarga',
            'keluargas',
            'masterPembangunan',
            'masterJawab'
        ));
    }

    /**
     * Update data.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $bangunkeluarga = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];
        foreach (range(1, 51) as $i) {
            $data["bangunkeluarga_$i"] = $request->input("bangunkeluarga_$i", null);
        }

        $bangunkeluarga->update($data);

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data Bangun Keluarga berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy($no_kk)
    {
        $data = DataBangunKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data->delete();

        return redirect()->route('keluarga.bangunkeluarga.index')->with('success', 'Data Bangun Keluarga berhasil dihapus.');
    }

    /**
     * Export PDF analisis Bangun Keluarga
     */
    public static function exportPDF()
    {
        $master = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
        $data = DataBangunKeluarga::with('keluarga')->get();

        // Hitung rata-rata tiap indikator (anggap nilai numerik)
        $indikator = [];
        foreach (range(1, 51) as $i) {
            $indikator["indikator_$i"] = round(DataBangunKeluarga::avg(DB::raw("CAST(bangunkeluarga_$i AS DECIMAL(10,2))")), 2);
        }

        $totalSkor = round(collect($indikator)->avg() * 100, 2);

        if ($totalSkor < 40) {
            $kategori = 'Keluarga Rentan / Belum Mandiri';
            $rekomendasi = [
                'Perlu peningkatan kesejahteraan dasar (pangan, sandang, papan).',
                'Dorong keluarga mengikuti kegiatan sosial dan kesehatan.',
                'Fasilitasi tabungan keluarga dan pelatihan ekonomi produktif.'
            ];
        } elseif ($totalSkor < 70) {
            $kategori = 'Keluarga Berkembang';
            $rekomendasi = [
                'Fokus pada peningkatan ketahanan ekonomi dan kesehatan keluarga.',
                'Dorong partisipasi aktif dalam kegiatan masyarakat.',
                'Perlu pembinaan rutin dan edukasi keluarga tangguh.'
            ];
        } else {
            $kategori = 'Keluarga Tangguh dan Mandiri';
            $rekomendasi = [
                'Pertahankan praktik baik sosial dan ekonomi keluarga.',
                'Dorong keterlibatan dalam pembangunan desa.',
                'Kembangkan program keluarga pelopor ketahanan sosial.'
            ];
        }

        $pdf = Pdf::loadView('laporan.bangunkeluarga', [
            'data' => $data,
            'master' => $master,
            'indikator' => $indikator,
            'skor' => $totalSkor,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Analisis_Bangun_Keluarga.pdf');
    }
}
