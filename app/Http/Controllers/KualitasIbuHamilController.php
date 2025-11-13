<?php

namespace App\Http\Controllers;

use App\Models\DataKualitasIbuHamil;
use App\Models\DataKeluarga;
use App\Models\MasterKualitasIbuHamil;
use App\Models\MasterJawabKualitasIbuHamil;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KualitasIbuHamilController extends Controller
{
    /**
     * Tampilkan daftar data kualitas ibu hamil
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $kualitasibuhamils = DataKualitasIbuHamil::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterKualitas = MasterKualitasIbuHamil::pluck('kualitasibuhamil', 'kdkualitasibuhamil')->toArray();
        $masterJawab = MasterJawabKualitasIbuHamil::pluck('jawabkualitasibuhamil', 'kdjawabkualitasibuhamil')->toArray();

        return view('keluarga.kualitasibuhamil.index', compact(
            'kualitasibuhamils',
            'masterKualitas',
            'masterJawab',
            'search',
            'perPage'
        ));
    }

    /**
     * Form tambah data baru
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasIbuHamil::all();
        $masterJawab = MasterJawabKualitasIbuHamil::all();

        return view('keluarga.kualitasibuhamil.create', compact(
            'keluargas',
            'masterKualitas',
            'masterJawab'
        ));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_kualitasibuhamil,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 13; $i++) {
            $data["kualitasibuhamil_$i"] = $request->input("kualitasibuhamil_$i", 0);
        }

        DataKualitasIbuHamil::create($data);

        return redirect()->route('keluarga.kualitasibuhamil.index')
            ->with('success', 'Data kualitas ibu hamil berhasil ditambahkan.');
    }

    /**
     * Form edit data
     */
    public function edit($no_kk)
    {
        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasIbuHamil::all();
        $masterJawab = MasterJawabKualitasIbuHamil::all();

        return view('keluarga.kualitasibuhamil.edit', compact(
            'kualitasibuhamil',
            'keluargas',
            'masterKualitas',
            'masterJawab'
        ));
    }

    /**
     * Update data
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 13; $i++) {
            $data["kualitasibuhamil_$i"] = $request->input("kualitasibuhamil_$i", 0);
        }

        $kualitasibuhamil->update($data);

        return redirect()->route('keluarga.kualitasibuhamil.index')
            ->with('success', 'Data kualitas ibu hamil berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy($no_kk)
    {
        $kualitasibuhamil = DataKualitasIbuHamil::where('no_kk', $no_kk)->firstOrFail();
        $kualitasibuhamil->delete();

        return redirect()->route('keluarga.kualitasibuhamil.index')
            ->with('success', 'Data kualitas ibu hamil berhasil dihapus.');
    }

    /**
     * Export laporan analisis kualitas ibu hamil ke PDF
     */
    public function exportPdf()
{
    $data = DataKualitasIbuHamil::all();
    $master = MasterKualitasIbuHamil::all();
    $totalKeluarga = $data->count();

    if ($totalKeluarga === 0) {
        return back()->with('error', 'Tidak ada data kualitas ibu hamil untuk dianalisis.');
    }

    // =============================
    // ✅ HITUNG PERSENTASE PER INDIKATOR
    // =============================

    $persen = [];

    foreach ($master as $item) {
        $kode = $item->kdkualitasibuhamil;
        $count = $data->where("kualitasibuhamil_$kode", 2)->count();
        $persen[$kode] = round(($count / $totalKeluarga) * 100, 1);
    }

    // =============================
    // ✅ ANALISIS UTAMA DAN SKOR
    // =============================

    $tidakPeriksa   = $persen[7]  ?? 0;
    $dukun         = $persen[6]  ?? 0;
    $meninggal     = ($persen[8] ?? 0) + ($persen[11] ?? 0) + ($persen[13] ?? 0);
    $nifasSakit    = $persen[10] ?? 0;

    $pemeriksaanMedis = (
        ($persen[1] ?? 0) +
        ($persen[2] ?? 0) +
        ($persen[3] ?? 0) +
        ($persen[4] ?? 0) +
        ($persen[5] ?? 0)
    ) / 5;

    $skor = 100;
    $skor -= $tidakPeriksa * 1.2;
    $skor -= $dukun * 1.5;
    $skor -= $meninggal * 3.0;
    $skor -= $nifasSakit * 1.0;
    $skor += $pemeriksaanMedis * 0.8;

    $skor = max(0, min(100, round($skor, 1)));

    // =============================
    // ✅ KATEGORI & REKOMENDASI
    // =============================

    if ($skor < 40 || $meninggal > 1) {
        $kategori = "Risiko Sangat Tinggi";
        $rekomendasi = [
            "Percepatan rujukan ibu hamil risiko tinggi.",
            "Program ambulance desa & posko persalinan darurat.",
            "Pendampingan intensif oleh bidan desa.",
            "Prioritaskan keluarga miskin untuk subsidi persalinan."
        ];
    }
    elseif ($skor < 70 || $tidakPeriksa > 20 || $dukun > 10) {
        $kategori = "Risiko Sedang";
        $rekomendasi = [
            "Program posyandu keliling untuk wilayah sulit akses.",
            "Edukasi ANC rutin oleh kader & PKK.",
            "Pemberian makanan tambahan untuk ibu hamil.",
        ];
    }
    else {
        $kategori = "Baik";
        $rekomendasi = [
            "Pertahankan pemeriksaan ANC minimal 4 kali.",
            "Perkuat kelas ibu hamil & edukasi gizi.",
            "Peningkatan layanan posyandu dan bidan desa."
        ];
    }

    // =============================
    // ✅ TOP 5 INDIKATOR
    // =============================

    $kualitasCount = [];

    foreach ($master as $item) {
        $kode = $item->kdkualitasibuhamil;
        $jumlah = $data->where("kualitasibuhamil_$kode", 2)->count();

        $kualitasCount[$kode] = [
            'nama'   => $item->kualitasibuhamil,
            'jumlah' => $jumlah
        ];
    }

    usort($kualitasCount, fn($a, $b) => $b['jumlah'] <=> $a['jumlah']);
    $topKualitas = array_slice($kualitasCount, 0, 5);

    // =============================
    // ✅ GENERATE PDF
    // =============================

    $pdf = Pdf::loadView('laporan.kualitasibuhamil', [
        'data'            => $data,
        'master'          => $master,
        'persen'          => $persen,
        'skor'            => $skor,
        'kategori'        => $kategori,
        'rekomendasi'     => $rekomendasi,
        'kualitasCount'   => $kualitasCount,
        'topKualitas'     => $topKualitas,
        'total'           => $totalKeluarga,
        'pemeriksaanMedis'=> $pemeriksaanMedis,
        'periode'         => Carbon::now()->translatedFormat('F Y'),
        'tanggal'         => Carbon::now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Kualitas-Ibu-Hamil.pdf');
}
}   