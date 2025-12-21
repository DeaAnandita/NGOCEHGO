<?php

namespace App\Http\Controllers;

use App\Models\DataKualitasBayi;
use App\Models\DataKeluarga;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawabKualitasBayi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KualitasBayiController extends Controller
{
    /**
     * Menampilkan daftar data kualitas bayi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $kualitasbayis = DataKualitasBayi::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterKualitas = MasterKualitasBayi::pluck('kualitasbayi', 'kdkualitasbayi')->toArray();
        $masterJawab = MasterJawabKualitasBayi::pluck('jawabkualitasbayi', 'kdjawabkualitasbayi')->toArray();

        return view('keluarga.kualitasbayi.index', compact(
            'kualitasbayis',
            'masterKualitas',
            'masterJawab',
            'search',
            'perPage'
        ));
    }

    /**
     * Form tambah data kualitas bayi baru.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasBayi::all();
        $masterJawab = MasterJawabKualitasBayi::all();

        return view('keluarga.kualitasbayi.create', compact(
            'keluargas',
            'masterKualitas',
            'masterJawab'
        ));
    }

    /**
     * Simpan data kualitas bayi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_kualitasbayi,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 7; $i++) {
            $data["kualitasbayi_$i"] = $request->input("kualitasbayi_$i", 0);
        }

        DataKualitasBayi::create($data);

        return redirect()->route('keluarga.kualitasbayi.index')
            ->with('success', 'Data kualitas bayi berhasil ditambahkan.');
    }

    /**
     * Form edit data kualitas bayi.
     */
    public function edit($no_kk)
    {
        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKualitas = MasterKualitasBayi::all();
        $masterJawab = MasterJawabKualitasBayi::all();

        return view('keluarga.kualitasbayi.edit', compact(
            'kualitasbayi',
            'keluargas',
            'masterKualitas',
            'masterJawab'
        ));
    }

    /**
     * Update data kualitas bayi.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 7; $i++) {
            $data["kualitasbayi_$i"] = $request->input("kualitasbayi_$i", 0);
        }

        $kualitasbayi->update($data);

        return redirect()->route('keluarga.kualitasbayi.index')
            ->with('success', 'Data kualitas bayi berhasil diperbarui.');
    }

    /**
     * Hapus data kualitas bayi.
     */
    public function destroy($no_kk)
    {
        $kualitasbayi = DataKualitasBayi::where('no_kk', $no_kk)->firstOrFail();
        $kualitasbayi->delete();

        return redirect()->route('keluarga.kualitasbayi.index')
            ->with('success', 'Data kualitas bayi berhasil dihapus.');
    }

    /**
     * Export laporan analisis kualitas bayi ke PDF.
     */
   public function exportPdf()
{
    // Mapping nomor pertanyaan ke key yang akan digunakan di view
    $pertanyaan = [
        1 => 'keguguran',         // Keguguran kandungan
        2 => 'lahir_normal',      // Bayi lahir hidup normal
        3 => 'lahir_cacat',       // Bayi lahir hidup cacat
        4 => 'lahir_mati',        // Bayi lahir mati
        5 => 'bblr',              // Bayi lahir berat kurang dari 2,5 kg
        6 => 'makrosomia',        // Bayi lahir berat lebih dari 4 kg
        7 => 'kelainan_organ',    // Bayi 0-5 tahun hidup yang menderita kelainan organ
    ];

    $data = [];

    foreach ($pertanyaan as $nomor => $key) {
        $kolom = "kualitasbayi_{$nomor}";

        $counts = DataKualitasBayi::selectRaw("
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

    // Total data keluarga yang memiliki record kualitas bayi
    $totalData = DataKualitasBayi::count();

    if ($totalData === 0) {
        return back()->with('error', 'Tidak ada data kualitas bayi untuk dianalisis.');
    }

    // Deteksi kolom desa (nama_desa / desa / kelurahan)
    $kolomDesa = null;
    if (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'nama_desa')) {
        $kolomDesa = 'nama_desa';
    } elseif (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'desa')) {
        $kolomDesa = 'desa';
    } elseif (DB::getSchemaBuilder()->hasColumn('data_keluarga', 'kelurahan')) {
        $kolomDesa = 'kelurahan';
    }

    $desaTertinggi = 'Tidak Ada Data';
    if ($kolomDesa) {
        $desaTerbanyak = DataKualitasBayi::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasbayi.no_kk')
            ->select("data_keluarga.{$kolomDesa} as nama_desa")
            ->groupBy("data_keluarga.{$kolomDesa}")
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $desaTertinggi = $desaTerbanyak?->nama_desa ?? 'Tidak Ada Data';
    }

    // Hitung indikator risiko utama
    $totalRisikoTinggi = ($data['keguguran_ada'] ?? 0) + ($data['keguguran_pernah'] ?? 0) +
                         ($data['lahir_mati_ada'] ?? 0) + ($data['lahir_mati_pernah'] ?? 0) +
                         ($data['lahir_cacat_ada'] ?? 0) + ($data['lahir_cacat_pernah'] ?? 0) +
                         ($data['kelainan_organ_ada'] ?? 0) + ($data['kelainan_organ_pernah'] ?? 0);

    $persenRisikoTinggi = round($totalRisikoTinggi / $totalData * 100, 2);

    $persenBBLRAtauMakrosomia = round(((
        ($data['bblr_ada'] ?? 0) + ($data['bblr_pernah'] ?? 0) +
        ($data['makrosomia_ada'] ?? 0) + ($data['makrosomia_pernah'] ?? 0)
    ) / $totalData) * 100, 2);

    // Tentukan kategori risiko
    if ($persenRisikoTinggi > 15 || $persenBBLRAtauMakrosomia > 20) {
        $kategori = 'Risiko Tinggi';
    } elseif ($persenRisikoTinggi > 5 || $persenBBLRAtauMakrosomia > 10) {
        $kategori = 'Risiko Sedang';
    } else {
        $kategori = 'Risiko Rendah';
    }

    // Rekomendasi otomatis berdasarkan data
    $rekomendasi = [];

    if ($data['keguguran_ada'] ?? 0 > 3 || $data['keguguran_pernah'] ?? 0 > 5) {
        $rekomendasi[] = 'Perkuat program pencegahan keguguran melalui edukasi kesehatan reproduksi dan pemeriksaan kehamilan dini.';
    }

    if (($data['lahir_mati_ada'] ?? 0) + ($data['lahir_cacat_ada'] ?? 0) > 2) {
        $rekomendasi[] = 'Lakukan audit perinatal dan tingkatkan kualitas persalinan serta deteksi dini kelainan kongenital.';
    }

    if (($data['bblr_ada'] ?? 0) + ($data['bblr_pernah'] ?? 0) > 5) {
        $rekomendasi[] = 'Optimalkan program pemberian makanan tambahan ibu hamil dan pemantauan gizi balita untuk cegah BBLR.';
    }

    if ($data['kelainan_organ_ada'] ?? 0 > 2) {
        $rekomendasi[] = 'Perlu skrining kelainan bawaan pada bayi baru lahir dan rujukan ke fasilitas spesialis.';
    }

    if ($data['lahir_normal_ada'] ?? 0 < $totalData * 0.7) {
        $rekomendasi[] = 'Tingkatkan cakupan persalinan ditolong tenaga kesehatan terlatih dan promosi ASI eksklusif.';
    }

    if (empty($rekomendasi)) {
        $rekomendasi[] = 'Kondisi kesehatan bayi secara umum baik. Tetap lakukan pemantauan pertumbuhan dan imunisasi rutin.';
    }

    $pdf = Pdf::loadView('laporan.kualitasbayi', [
        'data'          => $data,
        'totalData'     => $totalData,
        'desaTertinggi' => $desaTertinggi,
        'kategori'      => $kategori,
        'rekomendasi'   => $rekomendasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Kualitas-Bayi-' . Carbon::now()->format('Y-m') . '.pdf');
}
}