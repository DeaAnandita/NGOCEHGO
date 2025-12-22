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


    public static function exportPDF()
{
    $master = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(1, 51))->get();
    $data = DataBangunKeluarga::with('keluarga')->get();

    $totalKeluarga = $data->count();

    /* ===============================
    REKAP PER INDIKATOR (1–51)
    =============================== */
    $indikator = [];

    foreach (range(1, 51) as $i) {
        $jumlahYa = DataBangunKeluarga::where("bangunkeluarga_$i", 1)->count();
        $persen = $totalKeluarga > 0
            ? round(($jumlahYa / $totalKeluarga) * 100, 2)
            : 0;

        $indikator["indikator_$i"] = [
            'jumlah' => $jumlahYa,
            'persen' => $persen
        ];
    }

    /* ===============================
    KELOMPOK VARIABEL ANALISIS
    =============================== */
    $kelompokVariabel = [
        'Ketahanan Ekonomi Keluarga' => [1,2,3,4,5,6,7,8],
        'Kesehatan dan Gizi Keluarga' => [9,10,11,12,13,14],
        'Pendidikan dan Kualitas SDM' => [15,16,17,18,19],
        'Ketahanan Sosial dan Partisipasi' => [20,21,22,23,24],
        'Lingkungan dan Kelayakan Hunian' => [25,26,27,28,29],
        'Pengasuhan dan Perlindungan Anak' => [30,31,32,33],
        'Perencanaan dan Kemandirian Keluarga' => [34,35,36,37,38],
    ];

    /* ===============================
    ANALISIS PER VARIABEL
    =============================== */
    $analisisVariabel = [];

    foreach ($kelompokVariabel as $namaVariabel => $daftarIndikator) {
        $totalPersen = 0;

        foreach ($daftarIndikator as $i) {
            $totalPersen += $indikator["indikator_$i"]['persen'] ?? 0;
        }

        $rataVariabel = round($totalPersen / count($daftarIndikator), 2);

        if ($rataVariabel < 40) {
            $kategori = 'Rendah';
            $interpretasi = 'Variabel ini menunjukkan kondisi yang masih lemah dan menjadi faktor utama kerentanan keluarga.';
        } elseif ($rataVariabel < 70) {
            $kategori = 'Sedang';
            $interpretasi = 'Variabel ini berada pada tahap berkembang, namun pemenuhannya belum merata di seluruh keluarga.';
        } else {
            $kategori = 'Baik';
            $interpretasi = 'Variabel ini tergolong kuat dan telah dipenuhi oleh sebagian besar keluarga.';
        }

        $analisisVariabel[] = [
            'nama' => $namaVariabel,
            'skor' => $rataVariabel,
            'kategori' => $kategori,
            'interpretasi' => $interpretasi,
        ];
    }

    /* ===============================
    SKOR & INTERPRETASI UMUM DESA
    =============================== */
    $skorDesa = collect($analisisVariabel)->avg('skor');

    if ($skorDesa < 40) {
        $kategoriDesa = 'Mayoritas Keluarga Rentan';
    } elseif ($skorDesa < 70) {
        $kategoriDesa = 'Keluarga Berkembang';
    } else {
        $kategoriDesa = 'Keluarga Relatif Mandiri';
    }

    /* ===============================
    ANALISIS UMUM (DESKRIPTIF)
    =============================== */
    $analisisUmum = [
        'Hasil analisis menunjukkan bahwa tingkat pemenuhan indikator bangun keluarga bersifat tidak merata antar variabel pembangunan keluarga.',
        'Variabel dengan skor rendah mengindikasikan adanya kerentanan struktural yang berpotensi memengaruhi kesejahteraan keluarga secara berkelanjutan.',
        'Sementara itu, variabel dengan capaian tinggi mencerminkan keberhasilan program pembangunan keluarga yang telah berjalan dan dapat direplikasi.'
    ];

    /* ===============================
    REKOMENDASI KEBIJAKAN
    =============================== */
    $rekomendasi = [
        'Memprioritaskan intervensi pada variabel dengan skor rendah, khususnya ketahanan ekonomi, kesehatan, dan lingkungan keluarga.',
        'Mengintegrasikan hasil analisis bangun keluarga sebagai dasar perencanaan program pembangunan desa berbasis data.',
        'Mempertahankan dan memperluas praktik baik dari keluarga yang telah mencapai kategori mandiri.'
    ];

    $pdf = Pdf::loadView('laporan.bangunkeluarga', [
        'data' => $data,
        'master' => $master,
        'indikator' => $indikator,
        'skor' => $skorDesa,
        'kategori' => $kategoriDesa,
        'analisisVariabel' => $analisisVariabel,
        'analisisUmum' => $analisisUmum,
        'rekomendasi' => $rekomendasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('Laporan_Analisis_Bangun_Keluarga.pdf');
}
}