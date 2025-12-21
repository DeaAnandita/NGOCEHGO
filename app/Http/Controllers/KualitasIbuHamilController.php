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
    // Mapping nomor pertanyaan ke label yang akan ditampilkan di laporan
    $pertanyaan = [
        1  => 'posyandu',              // Ibu hamil periksa di POSYANDU
        2  => 'puskesmas',             // Ibu hamil periksa di PUSKESMAS
        3  => 'rumahsakit',            // Ibu hamil periksa di rumah sakit
        4  => 'dokter',                // Ibu hamil periksa di dokter praktek
        5  => 'bidan',                 // ibu hamil periksa di bidan praktek
        6  => 'dukun',                 // Ibu hamil periksa di dukun terlatih
        7  => 'tidak_periksa',         // Ibu hamil tidak periksa kesehatan
        8  => 'meninggal',             // Ibu hamil yang meninggal
        9  => 'melahirkan',            // Ibu hamil melahirkan
        10 => 'nifas_sakit',           // Ibu nifas sakit
        11 => 'kematian_nifas',        // Kematian ibu nifas
        12 => 'nifas_sehat',           // Ibu nifas sehat
        13 => 'kematian_melahirkan',   // Kematian ibu saat melahirkan
    ];

    $data = [];

    foreach ($pertanyaan as $nomor => $key) {
        $kolom = "kualitasibuhamil_{$nomor}"; // Nama kolom sebenarnya di tabel

        $counts = DataKualitasIbuHamil::selectRaw("
            SUM(CASE WHEN {$kolom} = 1 THEN 1 ELSE 0 END) as ada,
            SUM(CASE WHEN {$kolom} = 2 THEN 1 ELSE 0 END) as pernah_ada,
            SUM(CASE WHEN {$kolom} = 3 THEN 1 ELSE 0 END) as tidak_ada,
            SUM(CASE WHEN {$kolom} = 0 OR {$kolom} IS NULL THEN 1 ELSE 0 END) as tidak_diisi
        ")->first();

        $data["{$key}_ada"]      = $counts->ada ?? 0;
        $data["{$key}_pernah"]   = $counts->pernah_ada ?? 0;
        $data["{$key}_tidak"]    = $counts->tidak_ada ?? 0;
        $data["{$key}_kosong"]   = $counts->tidak_diisi ?? 0;
    }

    // Total data ibu hamil yang terisi (memiliki record di tabel data_kualitasibuhamil)
    $totalData = DataKualitasIbuHamil::count();

    // Deteksi kolom desa
    $kolomDesa = null;
    if (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'nama_desa')) {
        $kolomDesa = 'nama_desa';
    } elseif (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'desa')) {
        $kolomDesa = 'desa';
    } elseif (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'kelurahan')) {
        $kolomDesa = 'kelurahan';
    }

    $desaTertinggi = 'Tidak Ada Data';
    if ($kolomDesa && $totalData > 0) {
        $desaTerbanyak = DataKualitasIbuHamil::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasibuhamil.no_kk')
            ->select("data_keluarga.{$kolomDesa} as nama_desa")
            ->groupBy("data_keluarga.{$kolomDesa}")
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $desaTertinggi = $desaTerbanyak?->nama_desa ?? 'Tidak Ada Data';
    }

    // Hitung indikator risiko
    $persenTidakPeriksa = $totalData > 0 
        ? round(($data['tidak_periksa_ada'] ?? 0) / $totalData * 100, 2) 
        : 0;

    $totalKematian = ($data['meninggal_ada'] ?? 0) + ($data['meninggal_pernah'] ?? 0) +
                     ($data['kematian_nifas_ada'] ?? 0) + ($data['kematian_nifas_pernah'] ?? 0) +
                     ($data['kematian_melahirkan_ada'] ?? 0) + ($data['kematian_melahirkan_pernah'] ?? 0);

    $persenKematian = $totalData > 0 ? round($totalKematian / $totalData * 100, 2) : 0;

    // Tentukan kategori risiko
    if ($persenKematian > 2 || $persenTidakPeriksa > 30) {
        $kategori = 'Risiko Tinggi';
    } elseif ($persenKematian > 0 || $persenTidakPeriksa > 15) {
        $kategori = 'Risiko Sedang';
    } else {
        $kategori = 'Risiko Rendah';
    }

    // Rekomendasi otomatis
    $rekomendasi = [];

    if ($data['tidak_periksa_ada'] ?? 0 > 5) {
        $rekomendasi[] = 'Segera tingkatkan sosialisasi pentingnya pemeriksaan kehamilan rutin di fasilitas kesehatan resmi.';
    }
    if ($data['dukun_ada'] ?? 0 > 3) {
        $rekomendasi[] = 'Lakukan edukasi intensif tentang bahaya persalinan dengan dukun non-medis.';
    }
    if ($totalKematian > 0) {
        $rekomendasi[] = 'Lakukan audit maternal segera dan perkuat sistem rujukan kasus berisiko tinggi.';
    }
    if (($data['posyandu_ada'] ?? 0) + ($data['puskesmas_ada'] ?? 0) + ($data['rumahsakit_ada'] ?? 0) < $totalData * 0.6) {
        $rekomendasi[] = 'Optimalkan peran Posyandu, Puskesmas, dan rumah sakit dalam pelayanan Antenatal Care (ANC).';
    }
    if (empty($rekomendasi)) {
        $rekomendasi[] = 'Kondisi kesehatan ibu hamil relatif baik. Tetap lakukan pemantauan dan edukasi rutin.';
    }

    $pdf = Pdf::loadView('laporan.kualitasibuhamil', [
        'data'          => $data,
        'totalData'     => $totalData,
        'desaTertinggi' => $desaTertinggi,
        'kategori'      => $kategori,
        'rekomendasi'   => $rekomendasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Kualitas-Ibu-Hamil-' . \Carbon\Carbon::now()->format('Y-m') . '.pdf');
}
}