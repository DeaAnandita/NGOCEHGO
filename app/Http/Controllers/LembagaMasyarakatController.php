<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaMasyarakat;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemmas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LembagaMasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10);

    $lembagamasyarakats = DataLembagaMasyarakat::with('penduduk')
        ->when($search, function ($query, $search) {
            $query->where('nik', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', function ($q) use ($search) {
                      $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                  });
        })
        ->orderBy('nik', 'asc')
        ->paginate($perPage)
        ->appends([
            'search' => $search,
            'per_page' => $perPage
        ]);

    $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)
        ->pluck('lembaga', 'kdlembaga')
        ->toArray();

    $masterJawabLemmas = MasterJawabLemmas::pluck('jawablemmas', 'kdjawablemmas')
        ->toArray();

    return view(
        'penduduk.lembagamasyarakat.index',
        compact(
            'lembagamasyarakats',
            'masterLembaga',
            'masterJawabLemmas',
            'search',
            'perPage'
        )
    );
}

    public function create()
    {
        $penduduks = DataPenduduk::all();

        // Ambil hanya lembaga masyarakat dari master_lembaga
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)->get();

        // Ambil semua opsi jawaban (Ya, Tidak, Pernah, dst)
        $masterJawabLemmas = MasterJawabLemmas::all();

        return view('penduduk.lembagamasyarakat.create', compact('penduduks', 'masterLembaga', 'masterJawabLemmas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|unique:data_lembagamasyarakat,nik|exists:data_penduduk,nik',
        ]);

        $data = ['nik' => $request->nik];

        // Loop dari 1 sampai 27 sesuai struktur kolom di tabel
        for ($i = 1; $i <= 27; $i++) {
            $data["lemmas_$i"] = $request->input("lemmas_$i", 0);
        }

        DataLembagaMasyarakat::create($data);

        return redirect()->route('penduduk.lemmas.index')->with('success', 'Data lembaga masyarakat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 3)->get();
        $masterJawabLemmas = MasterJawabLemmas::all();

        return view('penduduk.lembagamasyarakat.edit', compact('lembagaMasyarakat', 'penduduks', 'masterLembaga', 'masterJawabLemmas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
        ]);

        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();

        $data = ['nik' => $request->nik];

        for ($i = 1; $i <= 27; $i++) {
            $data["lemmas_$i"] = $request->input("lemmas_$i", 0);
        }

        $lembagaMasyarakat->update($data);

        return redirect()->route('penduduk.lemmas.index')->with('success', 'Data lembaga masyarakat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $lembagaMasyarakat = DataLembagaMasyarakat::where('nik', $nik)->firstOrFail();
        $lembagaMasyarakat->delete();

        return redirect()->route('penduduk.lemmas.index')->with('success', 'Data lembaga masyarakat berhasil dihapus.');
    }
/**
     * Export laporan analisis lembaga masyarakat ke PDF
     */
  public function exportPdf()
{
    $data = DataLembagaMasyarakat::all();
    $totalPenduduk = $data->count();

    if ($totalPenduduk === 0) {
        return back()->with('error', 'Tidak ada data lembaga masyarakat untuk dianalisis.');
    }

    $max_lembaga = 48;

    /* =====================================================
     * HITUNG INDIKATOR PER LEMBAGA
     * ===================================================== */
    $indikator = [];

    for ($i = 1; $i <= $max_lembaga; $i++) {
        $countYa = 0;

        foreach ($data as $row) {
            if (isset($row->{"lemmas_$i"}) && $row->{"lemmas_$i"} == 1) {
                $countYa++;
            }
        }

        $countTidak = $totalPenduduk - $countYa;
        $persenYa = ($countYa / $totalPenduduk) * 100;

        $indikator["lemmas_$i"] = [
            'count_ya' => $countYa,
            'count_tidak' => $countTidak,
            'persen_ya' => round($persenYa, 2),
            'keterangan' => $persenYa > 70
                ? 'Mayoritas penduduk aktif dalam lembaga ini.'
                : ($persenYa > 40
                    ? 'Sebagian penduduk terlibat, perlu peningkatan partisipasi.'
                    : 'Partisipasi rendah, memerlukan pembinaan kelembagaan.')
        ];
    }

    /* =====================================================
     * HITUNG PARTISIPASI PER PENDUDUK
     * ===================================================== */
    $rendah = $sedang = $tinggi = 0;
    $totalSkor = 0;

    foreach ($data as $row) {
        $skor = 0;

        for ($i = 1; $i <= $max_lembaga; $i++) {
            if (isset($row->{"lemmas_$i"}) && $row->{"lemmas_$i"} == 1) {
                $skor++;
            }
        }

        $totalSkor += $skor;

        if ($skor >= 8) {
            $tinggi++;
        } elseif ($skor >= 3) {
            $sedang++;
        } else {
            $rendah++;
        }
    }

    $avgPartisipasi = round($totalSkor / $totalPenduduk);
    $skorPartisipasi = round(($avgPartisipasi / $max_lembaga) * 100, 2);

    $persenRendah = round(($rendah / $totalPenduduk) * 100, 1);
    $persenSedang = round(($sedang / $totalPenduduk) * 100, 1);
    $persenTinggi = round(($tinggi / $totalPenduduk) * 100, 1);

    /* =====================================================
     * KATEGORI DOMINAN
     * ===================================================== */
    $kategoriArr = [
        'Rendah' => $rendah,
        'Sedang' => $sedang,
        'Tinggi' => $tinggi,
    ];
    arsort($kategoriArr);
    $kategoriDominan = array_key_first($kategoriArr);

    /* =====================================================
     * ANALISIS & REKOMENDASI
     * ===================================================== */
    if ($skorPartisipasi < 25) {
        $analisis = 'Partisipasi masyarakat dalam lembaga masih sangat rendah.';
        $rekomendasi = [
            'Pembinaan intensif lembaga masyarakat.',
            'Pelibatan tokoh masyarakat.',
            'Integrasi bantuan berbasis partisipasi.',
            'Sosialisasi peran lembaga.',
        ];
    } elseif ($skorPartisipasi < 50) {
        $analisis = 'Partisipasi masyarakat tergolong sedang.';
        $rekomendasi = [
            'Pelatihan pengurus lembaga.',
            'Kolaborasi lintas lembaga.',
            'Insentif berbasis partisipasi.',
            'Monitoring rutin.',
        ];
    } else {
        $analisis = 'Tingkat partisipasi masyarakat tergolong tinggi.';
        $rekomendasi = [
            'Pertahankan dukungan anggaran.',
            'Kembangkan program lintas sektor.',
            'Jadikan lembaga aktif sebagai role model.',
            'Libatkan lembaga dalam perencanaan desa.',
        ];
    }
    $periode = Carbon::now()->translatedFormat('F Y');
    $tanggal = Carbon::now()->translatedFormat('d F Y');


    /* =====================================================
     * MASTER LEMBAGA
     * ===================================================== */
    $master = MasterLembaga::where('kdjenislembaga', 3)->get();
    $lembaga = [];

foreach ($master as $index => $row) {
    $kode = 'lemmas_' . ($index + 1);

    $lembaga[] = (object)[
        'nama_lembaga' => $row->lembaga,
        'jumlah_ya'    => $indikator[$kode]['count_ya'] ?? 0,
        'jumlah_tidak' => $indikator[$kode]['count_tidak'] ?? 0,
        'keterangan'   => $indikator[$kode]['keterangan'] ?? '-',
    ];
}


    /* =====================================================
     * GENERATE PDF
     * ===================================================== */
    /* =====================================================
 * GENERATE PDF
 * ===================================================== */
$pdf = Pdf::loadView('laporan.lembagamasyarakat', compact(
    'data',
    'master',
    'indikator',        // Already present
    'lembaga',          // <-- ADD THIS LINE
    'totalPenduduk',
    'skorPartisipasi',
    'kategoriDominan',
    'rendah',
    'sedang',
    'tinggi',
    'persenRendah',
    'persenSedang',
    'persenTinggi',
    'analisis',
    'rekomendasi',
    'periode',
    'tanggal'
))->setPaper('a4', 'portrait');

return $pdf->stream('Laporan_Analisis_Lembaga_Masyarakat.pdf');

}
}