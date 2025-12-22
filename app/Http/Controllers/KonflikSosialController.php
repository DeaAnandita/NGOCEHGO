<?php

namespace App\Http\Controllers;

use App\Models\DataBangunKeluarga;
use App\Models\DataKonflikSosial;
use App\Models\DataKeluarga;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class KonflikSosialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $konfliksosials = DataKonflikSosial::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        $masterKonflik = MasterKonflikSosial::pluck('konfliksosial', 'kdkonfliksosial')->toArray();
        $masterJawab = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik')->toArray();

        return view('keluarga.konfliksosial.index', compact('konfliksosials', 'masterKonflik', 'masterJawab', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();

        return view('keluarga.konfliksosial.create', compact('keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk'=> 'required|unique:data_konfliksosial,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        $totalKonflik = MasterKonflikSosial::count();

        for ($i = 1; $i <= $totalKonflik; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        DataKonflikSosial::create($data);

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();

        return view('keluarga.konfliksosial.edit', compact('konfliksosial', 'keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
        'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();

        $totalKonflik = MasterKonflikSosial::count();

        for ($i = 1; $i <= $totalKonflik; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        $konfliksosial->update($data);

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $konfliksosial = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $konfliksosial->delete();

        return redirect()->route('keluarga.konfliksosial.index')
            ->with('success', 'Data konflik sosial berhasil dihapus.');
    }


    public function exportPdf()
{
    $groups = [
        'konflik_sara' => [1, 2, 3, 4],
        'kekerasan_fisik' => [5, 6, 13, 14],
        'kriminalitas' => [7, 8, 9],
        'penyimpangan_perilaku' => [10, 11, 12],
        'kejahatan_seksual' => [15, 16, 17, 18],
        'kehamilan_rentan' => [19, 20, 21],
        'pertengkaran_keluarga' => [22, 23, 24, 25, 26],
        'kdrt' => [27, 28, 29, 30, 31, 32],
    ];

    $data = [];
    $totalKeluarga = DataKonflikSosial::count();

    foreach ($groups as $key => $indices) {
        $query = DataKonflikSosial::query();
        $query->where(function ($q) use ($indices) {
            foreach ($indices as $i) {
                // HITUNG HANYA YANG ADA (1)
                $q->orWhere("konfliksosial_{$i}", 1);
            }
        });

        // dihitung per KK, bukan per kolom
        $data[$key] = $query->distinct('no_kk')->count('no_kk');
    }

    $totalKasus = array_sum($data);

    /* ===============================
       DETEKSI KOLOM DESA
    ================================ */
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
        $desaTerbanyak = DataKonflikSosial::join(
                'data_keluarga',
                'data_keluarga.no_kk',
                '=',
                'data_konfliksosial.no_kk'
            )
            ->where(function ($q) {
                for ($i = 1; $i <= 32; $i++) {
                    $q->orWhere("data_konfliksosial.konfliksosial_$i", 1);
                }
            })
            ->select("data_keluarga.$kolomDesa as nama_desa", DB::raw('COUNT(DISTINCT data_konfliksosial.no_kk) as total'))
            ->groupBy("data_keluarga.$kolomDesa")
            ->orderByDesc('total')
            ->first();

        $desaTertinggi = $desaTerbanyak->nama_desa ?? 'Tidak Ada Data';
    }

    /* ===============================
       KATEGORI RISIKO (REALISTIS)
    ================================ */
    if ($totalKasus >= 15) $kategori = 'Risiko Tinggi';
    elseif ($totalKasus >= 7) $kategori = 'Risiko Sedang';
    elseif ($totalKasus >= 1) $kategori = 'Risiko Rendah';
    else $kategori = 'Sangat Aman';

    /* ===============================
       REKOMENDASI DINAMIS
    ================================ */
    $rekomendasi = [];

    if ($data['konflik_sara'] > 0)
        $rekomendasi[] = 'Penguatan nilai toleransi dan deteksi dini konflik sosial berbasis masyarakat.';

    if ($data['kdrt'] > 0)
        $rekomendasi[] = 'Perlu layanan konseling keluarga dan mekanisme perlindungan korban KDRT.';

    if ($data['kriminalitas'] > 1)
        $rekomendasi[] = 'Peningkatan koordinasi keamanan lingkungan dan ronda warga.';

    if ($data['penyimpangan_perilaku'] > 1)
        $rekomendasi[] = 'Edukasi bahaya miras, narkoba, dan judi bagi keluarga.';

    if ($data['kehamilan_rentan'] > 0)
        $rekomendasi[] = 'Penyuluhan kesehatan reproduksi remaja dan ketahanan keluarga.';

    if (empty($rekomendasi))
        $rekomendasi[] = 'Kondisi sosial relatif aman. Monitoring rutin tetap diperlukan.';

    $pdf = Pdf::loadView('laporan.konfliksosial', compact(
        'data',
        'totalKasus',
        'desaTertinggi',
        'kategori',
        'rekomendasi',
        'totalKeluarga'
    ))->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Konflik-Sosial.pdf');
}

}
