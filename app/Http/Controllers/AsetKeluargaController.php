<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AsetKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $asetkeluargas = DataAsetKeluarga::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterAset = MasterAsetKeluarga::pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $masterJawab = MasterJawab::pluck('jawab', 'kdjawab')->toArray();

        return view('keluarga.asetkeluarga.index', compact('asetkeluargas', 'masterAset', 'masterJawab', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetKeluarga::all();
        $masterJawab = MasterJawab::all();
        return view('keluarga.asetkeluarga.create', compact('keluargas', 'masterAset', 'masterJawab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetkeluarga,no_kk|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'sometimes|nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        DataAsetKeluarga::create($data);

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetKeluarga::all();
        $masterJawab = MasterJawab::all();
        return view('keluarga.asetkeluarga.edit', compact('asetkeluarga', 'keluargas', 'masterAset', 'masterJawab'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'asetkeluarga_*' => 'sometimes|nullable|in:0,1,2'
        ]);

        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 42; $i++) {
            $data["asetkeluarga_$i"] = $request->input("asetkeluarga_$i", 0);
        }

        $asetkeluarga->update($data);

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $asetkeluarga = DataAsetKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $asetkeluarga->delete();

        return redirect()->route('keluarga.asetkeluarga.index')->with('success', 'Data aset keluarga berhasil dihapus.');
    }

    public static function exportPDF()
    {
        // Ambil data master aset
        $master = MasterAsetKeluarga::all();
        $data = DataAsetKeluarga::all();
        $total = $data->count();

        // Jumlah aset maksimal = 41
        $max_aset = 41;

        // Hitung jumlah kepemilikan (YA = 1) dan tidak untuk setiap aset
        $indikator = [];
        $total_ya = 0;
        for ($i = 1; $i <= $max_aset; $i++) {
            $count_ya = DataAsetKeluarga::where("asetkeluarga_$i", 1)->count();
            $count_tidak = $total - $count_ya;
            $persen_ya = $total > 0 ? ($count_ya / $total) * 100 : 0;

            $indikator["aset_$i"] = [
                'count_ya' => $count_ya,
                'count_tidak' => $count_tidak,
                'persen_ya' => round($persen_ya, 2),
                'keterangan' => $persen_ya > 70 ? 'Mayoritas keluarga memiliki aset ini, menunjukkan akses yang baik.'
                            : ($persen_ya > 40 ? 'Sebagian keluarga memiliki aset ini, tetapi masih ada ruang untuk peningkatan.'
                            : 'Mayoritas keluarga tidak memiliki aset ini, memerlukan intervensi prioritas.')
            ];
            $total_ya += $count_ya;
        }

        // Hitung rata-rata jumlah aset per keluarga (hanya aset 1-41)
        $sum_cases = implode(' + ', array_map(function ($i) {
            return "CASE WHEN asetkeluarga_$i = 1 THEN 1 ELSE 0 END";
        }, range(1, $max_aset)));

        $avg_assets_raw = DataAsetKeluarga::select(DB::raw("AVG($sum_cases) as avg_assets"))->first()->avg_assets ?? 0;

        // Bulatkan ke bilangan bulat terdekat untuk tampilan
        $avg_assets = round($avg_assets_raw); // misal 19.42 → 19, 19.5 → 20

        // Skor kesejahteraan aset (persentase rata-rata kepemilikan dari total 41 aset)
        $skor = ($avg_assets_raw / $max_aset) * 100;
        $skor = max(0, min(100, round($skor, 2)));

        // Hitung rata-rata persentase kategori aset
        // Asumsi pembagian kategori (sesuaikan jika berbeda):
        // Dasar: 1-10 (10 aset)
        // Menengah: 11-25 (15 aset)
        // Mewah: 26-41 (16 aset) → total 10+15+16=41
        $avg_dasar = array_sum(array_map(fn($i) => $indikator["aset_$i"]['persen_ya'], range(1, 10))) / 10;
        $avg_menengah = array_sum(array_map(fn($i) => $indikator["aset_$i"]['persen_ya'], range(11, 25))) / 15;
        $avg_mewah = array_sum(array_map(fn($i) => $indikator["aset_$i"]['persen_ya'], range(26, 41))) / 16;

        // Logika kategori, analisis, dan rekomendasi
        if ($skor < 20 || $avg_dasar < 30) {
            $kategori = 'Miskin / Rentan Kemiskinan';
            $analisis = 'Berdasarkan data, kepemilikan aset dasar sangat rendah dengan rata-rata hanya ' . round($avg_dasar, 2) . '% keluarga yang memiliki aset dasar. Hal ini menunjukkan kerentanan tinggi terhadap kemiskinan struktural.';
            $rekomendasi = [
                'Prioritaskan distribusi aset dasar melalui program BLT atau PKH untuk keluarga yang tidak memiliki aset esensial.',
                'Lakukan pelatihan keterampilan dasar untuk usaha kecil guna meningkatkan aset menengah.',
                'Kolaborasi dengan pemerintah daerah untuk subsidi aset dasar seperti listrik dan air bersih.',
                'Monitoring ketat terhadap keluarga rentan untuk mencegah kemiskinan kronis.'
            ];
        } elseif ($avg_menengah < 40 || $avg_dasar < 50) {
            $kategori = 'Menengah Bawah / Rawan Kemiskinan';
            $analisis = 'Aset dasar mulai terpenuhi (' . round($avg_dasar, 2) . '% kepemilikan), namun aset menengah seperti transportasi atau ternak masih rendah di ' . round($avg_menengah, 2) . '%. Keluarga rawan kembali ke kemiskinan jika terjadi guncangan ekonomi.';
            $rekomendasi = [
                'Berikan pendampingan usaha mikro, fokus pada peternakan dan pertanian.',
                'Program kredit lunak untuk pembelian aset transportasi dasar.',
                'Edukasi keuangan untuk mengelola aset yang ada.',
                'Bangun infrastruktur pendukung seperti pasar desa.'
            ];
        } elseif ($avg_mewah < 20) {
            $kategori = 'Menengah / Potensi Berkembang';
            $analisis = 'Kepemilikan aset dasar dan menengah cukup baik, tetapi aset mewah masih minim di ' . round($avg_mewah, 2) . '%. Terdapat potensi besar untuk peningkatan ekonomi.';
            $rekomendasi = [
                'Dorong investasi aset mewah melalui insentif pajak untuk UMKM.',
                'Pelatihan digital untuk memanfaatkan aset teknologi.',
                'Bentuk koperasi keluarga untuk usaha kolektif.',
                'Survey berkala untuk memantau kemajuan.'
            ];
        } else {
            $kategori = 'Sejahtera / Stabil';
            $analisis = 'Distribusi aset seimbang dengan rata-rata ' . $avg_assets . ' aset per keluarga dari total 41 jenis aset. Menandakan stabilitas ekonomi yang tinggi.';
            $rekomendasi = [
                'Pertahankan kestabilan melalui program tabungan dan investasi pendidikan.',
                'Dukung ekspansi usaha besar untuk ekspor.',
                'Libatkan keluarga sejahtera dalam program CSR.',
                'Evaluasi berkelanjutan untuk pemerataan aset.'
            ];
        }

        $pdf = Pdf::loadView('laporan.asetkeluarga', [
            'data' => $data,
            'master' => $master,
            'indikator' => $indikator,
            'skor' => $skor,
            'kategori' => $kategori,
            'analisis' => $analisis,
            'rekomendasi' => $rekomendasi,
            'avg_dasar' => round($avg_dasar, 2),
            'avg_menengah' => round($avg_menengah, 2),
            'avg_mewah' => round($avg_mewah, 2),
            'avg_assets' => $avg_assets,              // sudah dibulatkan (19 atau 20 dst)
            'max_aset' => $max_aset,                  // kirim 41 ke view
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Analisis_Aset_Keluarga.pdf');
    }
}