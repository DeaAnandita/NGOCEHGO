<?php

namespace App\Http\Controllers;

use App\Models\DataUsahaArt;
use App\Models\DataPenduduk;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterTempatUsaha;
use App\Models\MasterOmsetUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class UsahaArtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua data usahaart dengan relasi penduduk
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $usahaarts = DataUsahaArt::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $penduduks = DataPenduduk::all();

        return view('penduduk.usahaart.index', compact('usahaarts', 'penduduks', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penduduk.usahaart.create', [
            'penduduks' => DataPenduduk::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'tempat_usahas' => MasterTempatUsaha::all(),
            'omset_usahas' => MasterOmsetUsaha::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik|unique:data_usahaart,nik',
            'kdlapanganusaha' => 'required|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_jumlahpekerja' => 'required|numeric|min:0',
            'usahaart_namausaha' => 'required|string|max:255',
            'kdtempatusaha' => 'required|integer|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha' => 'required|integer|exists:master_omsetusaha,kdomsetusaha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataUsahaArt::create($request->all());

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $usahaart = DataUsahaArt::findOrFail($nik);
        return view('penduduk.usahaart.edit', [
            'usahaart' => $usahaart,
            'penduduks' => DataPenduduk::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'tempat_usahas' => MasterTempatUsaha::all(),
            'omset_usahas' => MasterOmsetUsaha::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_usahaart,nik,' . $nik . ',nik',
            'kdlapanganusaha' => 'required|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_jumlahpekerja' => 'required|numeric|min:0',
            'usahaart_namausaha' => 'required|string|max:255',
            'kdtempatusaha' => 'required|integer|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha' => 'required|integer|exists:master_omsetusaha,kdomsetusaha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usahaart = DataUsahaArt::findOrFail($nik);
        $usahaart->update($request->all());

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $usahaart = DataUsahaArt::findOrFail($nik);
        $usahaart->delete();

        return redirect()->route('penduduk.usahaart.index')->with('success', 'Data usaha artisanal berhasil dihapus.');
    }
      private function getLaporanData()
    {
        // Ambil semua data usaha dengan relasi ke tabel master
        $data = DB::table('data_usahaart')
            ->leftJoin('master_lapanganusaha', 'data_usahaart.kdlapanganusaha', '=', 'master_lapanganusaha.kdlapanganusaha')
            ->leftJoin('master_tempatusaha', 'data_usahaart.kdtempatusaha', '=', 'master_tempatusaha.kdtempatusaha')
            ->leftJoin('master_omsetusaha', 'data_usahaart.kdomsetusaha', '=', 'master_omsetusaha.kdomsetusaha')
            ->select(
                'master_lapanganusaha.lapanganusaha',
                'master_tempatusaha.tempatusaha',
                'master_omsetusaha.omsetusaha',
                'data_usahaart.usahaart_jumlahpekerja',
                'data_usahaart.usahaart_namausaha'
            )
            ->get();

        $total = $data->count();

        if ($total == 0) {
            return [
                'data' => [],
                'summary' => null,
                'error' => 'Belum ada data usaha yang bisa dianalisis.'
            ];
        }

        // === Hitung agregasi otomatis ===
        $avg_pekerja = round($data->avg('usahaart_jumlahpekerja'), 2);

        // Dominan sektor usaha
        $dominant_sector = $data->groupBy('lapanganusaha')
            ->map->count()
            ->sortDesc()
            ->take(3)
            ->map(function ($count, $sector) use ($total) {
                return [
                    'sector' => $sector,
                    'percentage' => round(($count / $total) * 100, 2)
                ];
            })
            ->values();

        // Persentase punya tempat usaha
        $has_place = $data->where('tempatusaha', 'ADA')->count();
        $place_percentage = round(($has_place / $total) * 100, 2);

        // Omzet dominan
        $omzet_counts = $data->groupBy('omsetusaha')->map->count();
        $top_omzet = $omzet_counts->sortDesc()->keys()->first();

        // Skor produktivitas sederhana
        $score = round((($place_percentage + ($avg_pekerja * 10)) / 2), 2);

$dominant = collect($dominant_sector ?? [])->pluck('sector')->join(', ');
$place = $place_percentage ?? 0;
$omzet = $top_omzet ?? '-';
$avg = $avg_pekerja ?? 0;

$general_interpretation = "Perlu penguatan modal, pelatihan manajemen usaha, serta digitalisasi pemasaran untuk meningkatkan pendapatan keluarga.";

$summary = [
    'total_usaha' => $total,
    'avg_pekerja' => $avg_pekerja,
    'dominant_sector' => $dominant_sector,
    'place_percentage' => $place_percentage,
    'top_omzet' => $top_omzet,
    'score' => $score,
    'general_interpretation' => $general_interpretation
];



        

        return compact('data', 'summary');
    }

    /**
     * Tampilkan laporan di browser (HTML)
     */
    public function laporan()
    {
        $laporan = $this->getLaporanData();

        if (isset($laporan['error'])) {
            return back()->with('error', $laporan['error']);
        }

        return view('laporan.usahaart', $laporan);
    }

    /**
     * Export laporan ke PDF
     */
public function exportPdf()
{
    $laporan = $this->getLaporanData();

    if (isset($laporan['error'])) {
        return back()->with('error', $laporan['error']);
    }

    // TANGGAL CETAK OTOMATIS (WAKTU SINGAPURA, format: 11 November 2025)
    $tanggal_cetak = Carbon::now('Asia/Singapore')->translatedFormat('d F Y');

    $pdf = Pdf::loadView('laporan.usahaart', array_merge($laporan, [
        'tanggal_cetak' => $tanggal_cetak
    ]))
    ->setPaper('A4', 'portrait')
    ->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
        'isFontSubsettingEnabled' => true,
    ]);

    return $pdf->stream('Laporan-Sosial-Ekonomi-' . now('Asia/Singapore')->format('Y-m-d') . '.pdf');
}

}