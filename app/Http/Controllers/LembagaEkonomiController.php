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
    public function exportPdf()
{
    $data = DataLembagaEkonomi::all();
    $totalPenduduk = $data->count();

    if ($totalPenduduk === 0) {
        return back()->with('error', 'Tidak ada data lembaga ekonomi untuk dianalisis.');
    }

    // Hitung kategori partisipasi
    $rendah = $sedang = $tinggi = 0;
    foreach ($data as $row) {
        $skor = 0;
        for ($i = 1; $i <= 75; $i++) {
            $val = $row->{"lemek_$i"};
            if ($val == 1) $skor++;
        }

        if ($skor >= 12) $tinggi++;        // ambang batas disesuaikan (misal ≥12 = tinggi)
        elseif ($skor >= 5) $sedang++;     // 5–11 = sedang
        else $rendah++;                    // <5 = rendah
    }

    $persenRendah = round(($rendah / $totalPenduduk) * 100, 1);
    $persenSedang = round(($sedang / $totalPenduduk) * 100, 1);
    $persenTinggi = round(($tinggi / $totalPenduduk) * 100, 1);

    $kategori = ['Rendah' => $rendah, 'Sedang' => $sedang, 'Tinggi' => $tinggi];
    arsort($kategori);
    $dominan = array_key_first($kategori);
    $persenDominan = match ($dominan) {
        'Rendah' => $persenRendah,
        'Sedang' => $persenSedang,
        'Tinggi' => $persenTinggi,
    };

    // Ambil data master indikator lembaga ekonomi
    $master = MasterLembaga::where('kdjenislembaga', 4)
    ->orderBy('kdlembaga', 'asc')
    ->pluck('lembaga', 'kdlembaga')
    ->values() // buang key asli, ubah jadi indeks numerik mulai 0
    ->toArray();

// Hitung jumlah "ADA" per indikator
$soalCount = [];
foreach ($master as $i => $nama) {
    $kolom = "lemek_" . ($i + 1); // sesuai urutan kolom 1–75
    $jumlah = $data->where($kolom, 1)->count();
    $soalCount[] = [
        'nama' => ucfirst(Str::lower($nama)),
        'jumlah' => $jumlah,
    ];
}


    // Ambil 10 besar aktivitas ekonomi dominan
    usort($soalCount, fn($a, $b) => $b['jumlah'] <=> $a['jumlah']);
    $topSoal = array_slice($soalCount, 0, 10);

    // Generate PDF tanpa diagram
    $pdf = Pdf::loadView('laporan.lembagaekonomi', [
        'totalPenduduk' => $totalPenduduk,
        'rendah' => $rendah,
        'sedang' => $sedang,
        'tinggi' => $tinggi,
        'persenRendah' => $persenRendah,
        'persenSedang' => $persenSedang,
        'persenTinggi' => $persenTinggi,
        'dominan' => $dominan,
        'persenDominan' => $persenDominan,
        'topSoal' => $topSoal,
        'periode' => Carbon::now()->translatedFormat('F Y'),
        'tanggal' => Carbon::now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Lembaga-Ekonomi.pdf');
}}