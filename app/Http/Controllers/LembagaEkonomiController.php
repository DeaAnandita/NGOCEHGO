<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaEkonomi;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemek;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LembagaEkonomiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua data lembaga ekonomi dengan relasi penduduk
        $search = $request->input('search');

        // Ambil semua data lembaga desa dengan relasi penduduk
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $lembagaEkonomis = DataLembagaEkonomi::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        // Ambil master lembaga hanya yang kdjenislembaga = 4 (Lembaga Ekonomi)
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)
            ->pluck('lembaga', 'kdlembaga')
            ->toArray();

        // Ambil daftar pilihan jawaban dari master_jawablemek
        $masterJawabLemek = MasterJawabLemek::pluck('jawablemek', 'kdjawablemek')->toArray();

        return view('penduduk.lembagaekonomi.index', compact('lembagaEkonomis', 'masterLembaga', 'masterJawabLemek', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();

        // Ambil hanya lembaga ekonomi dari master_lembaga
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)->get();

        // Ambil semua opsi jawaban (Ya, Tidak, Pernah, dst)
        $masterJawabLemek = MasterJawabLemek::all();

        return view('penduduk.lembagaekonomi.create', compact('penduduks', 'masterLembaga', 'masterJawabLemek'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|unique:data_lembagaekonomi,nik|exists:data_penduduk,nik',
        ]);

        $data = ['nik' => $request->nik];

        // Loop dari 1 sampai 75 sesuai struktur kolom di tabel
        for ($i = 1; $i <= 75; $i++) {
            $data["lemek_$i"] = $request->input("lemek_$i", 0);
        }

        DataLembagaEkonomi::create($data);

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 4)->get();
        $masterJawabLemek = MasterJawabLemek::all();

        return view('penduduk.lembagaekonomi.edit', compact('lembagaEkonomi', 'penduduks', 'masterLembaga', 'masterJawabLemek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
        ]);

        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();

        $data = ['nik' => $request->nik];

        for ($i = 1; $i <= 75; $i++) {
            $data["lemek_$i"] = $request->input("lemek_$i", 0);
        }

        $lembagaEkonomi->update($data);

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagaEkonomi = DataLembagaEkonomi::where('nik', $nik)->firstOrFail();
        $lembagaEkonomi->delete();

        return redirect()->route('penduduk.lembagaekonomi.index')->with('success', 'Data lembaga ekonomi berhasil dihapus.');
    }
/**
     * Export laporan analisis lembaga ekonomi ke PDF
     */
    public function exportPDF()
{
    $master = MasterLembaga::where('kdjenislembaga', 4)->get();
    $data   = DataLembagaEkonomi::all();
    $total  = $data->count();

    if ($total === 0) {
        return back()->with('error', 'Tidak ada data lembaga ekonomi.');
    }

    $max_lembaga = $master->count();
    $indikator = [];

    for ($i = 1; $i <= $max_lembaga; $i++) {
        $count_ya = DataLembagaEkonomi::where("lemek_$i", 1)->count();
        $count_tidak = $total - $count_ya;
        $persen_ya = ($count_ya / $total) * 100;

        $indikator["lemek_$i"] = [
            'count_ya'    => $count_ya,
            'count_tidak' => $count_tidak,
            'persen_ya'   => round($persen_ya, 2),
            'keterangan'  => $persen_ya > 70
                ? 'Mayoritas penduduk terlibat aktif dalam lembaga ekonomi ini.'
                : ($persen_ya > 40
                    ? 'Partisipasi cukup, namun masih dapat ditingkatkan.'
                    : 'Partisipasi rendah, perlu penguatan kelembagaan.')
        ];
    }

    $sum_cases = implode(' + ', array_map(fn($i) =>
        "CASE WHEN lemek_$i = 1 THEN 1 ELSE 0 END",
        range(1, $max_lembaga)
    ));

    $total_partisipasi = DataLembagaEkonomi::select(
        DB::raw("SUM($sum_cases) as total")
    )->first()->total ?? 0;

    $avg_partisipasi = round($total_partisipasi / $total, 2);
    $skor = round(($avg_partisipasi / $max_lembaga) * 100, 2);

    // Logika kategori, analisis, dan rekomendasi
    if ($skor < 20) {
        $kategori = 'Rendah / Perlu Penguatan';
        $analisis = 'Berdasarkan data, partisipasi rata-rata hanya ' . $avg_partisipasi . ' lembaga per penduduk dari total ' . $max_lembaga . ' jenis lembaga. Hal ini menunjukkan keterlibatan rendah dalam lembaga ekonomi, yang dapat menghambat pertumbuhan ekonomi lokal.';
        $rekomendasi = [
            'Lakukan sosialisasi massal mengenai pentingnya keterlibatan dalam lembaga ekonomi seperti koperasi dan BUMDes.',
            'Pembentukan lembaga ekonomi baru di wilayah dengan partisipasi rendah.',
            'Pelatihan manajemen dan keterampilan bagi pengelola lembaga.',
            'Alokasi anggaran khusus untuk penguatan kelembagaan ekonomi desa.'
        ];
    } elseif ($skor < 50) {
        $kategori = 'Sedang / Rawan Penurunan';
        $analisis = 'Partisipasi mencapai rata-rata ' . $avg_partisipasi . ' lembaga per penduduk, menunjukkan keterlibatan sedang. Namun, diperlukan upaya berkelanjutan untuk mencegah penurunan akibat faktor eksternal.';
        $rekomendasi = [
            'Program pendampingan berkelanjutan untuk lembaga yang ada.',
            'Integrasi lembaga ekonomi dengan program pemerintah pusat seperti PNPM.',
            'Edukasi digital untuk modernisasi operasional lembaga.',
            'Monitoring dan evaluasi tahunan partisipasi penduduk.'
        ];
    } elseif ($skor < 80) {
        $kategori = 'Baik / Berkembang';
        $analisis = 'Tingkat partisipasi baik dengan rata-rata ' . $avg_partisipasi . ' lembaga, mendukung dinamika ekonomi yang sehat di tingkat masyarakat.';
        $rekomendasi = [
            'Ekspansi lembaga ke sektor unggulan daerah untuk diversifikasi.',
            'Kolaborasi dengan swasta untuk investasi dalam lembaga.',
            'Peningkatan kapasitas melalui workshop dan sertifikasi.',
            'Promosi best practices antar-lembaga sukses.'
        ];
    } else {
        $kategori = 'Tinggi / Maju';
        $analisis = 'Partisipasi tinggi di seluruh jenis lembaga ekonomi, dengan rata-rata ' . $avg_partisipasi . ' per penduduk, menandakan ekosistem kelembagaan yang kuat dan berkontribusi signifikan terhadap pembangunan.';
        $rekomendasi = [
            'Inovasi lembaga untuk adaptasi terhadap perkembangan teknologi.',
            'Penguatan tata kelola dan transparansi dalam operasional.',
            'Libatkan lembaga dalam program nasional dan internasional.',
            'Penelitian dan pengembangan model lembaga baru.'
        ];
    }

    $pdf = Pdf::loadView('laporan.lembagaekonomi', [
        'total' => $total,
        'skor' => $skor,
        'kategori' => $kategori,
        'master' => $master,
        'indikator' => $indikator,
        'analisis' => $analisis,
        'rekomendasi' => $rekomendasi,
        'avg_partisipasi' => $avg_partisipasi,
        'max_lembaga' => $max_lembaga,
        'total_partisipasi' => $total_partisipasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan_Analisis_Lembaga_Ekonomi.pdf');
}
}