<?php

namespace App\Http\Controllers;

use App\Models\DataAsetLahan;
use App\Models\DataKeluarga;
use App\Models\MasterAsetLahan;
use App\Models\MasterJawabLahan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsetLahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $asetlahans = DataAsetLahan::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter
        $masterAset = MasterAsetLahan::pluck('asetlahan', 'kdasetlahan')->toArray();
        $masterJawab = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan')->toArray();
        return view('keluarga.asetlahan.index', compact('asetlahans', 'masterAset', 'masterJawab', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetLahan::all();
        $masterJawab = MasterJawabLahan::all();
        return view('keluarga.asetlahan.create', compact('keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetlahan,no_kk|exists:data_keluarga,no_kk',
            'asetlahan_*' => 'nullable|in:0,1,2'
        ]);

        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 10; $i++) {
            $data["asetlahan_$i"] = $request->input("asetlahan_$i", 0);
        }

        DataAsetLahan::create($data);

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_kk)
    {
        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetLahan::all();
        $masterJawab = MasterJawabLahan::all();
        return view('keluarga.asetlahan.edit', compact('asetlahan', 'keluargas', 'masterAset', 'masterJawab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
            'asetlahan_*' => 'nullable|in:0,1,2'
        ]);

        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $data = $request->only(['no_kk']);
        for ($i = 1; $i <= 10; $i++) {
            $data["asetlahan_$i"] = $request->input("asetlahan_$i", 0);
        }

        $asetlahan->update($data);

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_kk)
    {
        $asetlahan = DataAsetLahan::where('no_kk', $no_kk)->firstOrFail();
        $asetlahan->delete();

        return redirect()->route('keluarga.asetlahan.index')->with('success', 'Data aset lahan berhasil dihapus.');
    }

    public static function exportPDF()
    {
        // Ambil data master aset lahan
        $master = MasterAsetLahan::all();
        $data = DataAsetLahan::all();
        $total = $data->count();

        // Mapping nilai kdjawablahan ke nilai luas rata-rata approximasi
        $luas_map = [
            0 => 0,
            1 => 0.15, 2 => 0.25, 3 => 0.35, 4 => 0.45, 5 => 0.55,
            6 => 0.65, 7 => 0.75, 8 => 0.85, 9 => 0.95,
            10 => 3.0, 11 => 7.5,
        ];

        $indikator = [];
        $total_luas_all = 0;
        $total_memiliki_all = 0;

        for ($i = 1; $i <= 10; $i++) {
            $count_memiliki = DataAsetLahan::where("asetlahan_$i", '>', 0)->count();
            $count_tidak = $total - $count_memiliki;
            $persen_memiliki = $total > 0 ? ($count_memiliki / $total) * 100 : 0;

            // Query SUM dengan alias yang tetap
            $total_luas = DataAsetLahan::select(DB::raw("
                SUM(CASE 
                    WHEN asetlahan_$i = 1 THEN {$luas_map[1]}
                    WHEN asetlahan_$i = 2 THEN {$luas_map[2]}
                    WHEN asetlahan_$i = 3 THEN {$luas_map[3]}
                    WHEN asetlahan_$i = 4 THEN {$luas_map[4]}
                    WHEN asetlahan_$i = 5 THEN {$luas_map[5]}
                    WHEN asetlahan_$i = 6 THEN {$luas_map[6]}
                    WHEN asetlahan_$i = 7 THEN {$luas_map[7]}
                    WHEN asetlahan_$i = 8 THEN {$luas_map[8]}
                    WHEN asetlahan_$i = 9 THEN {$luas_map[9]}
                    WHEN asetlahan_$i = 10 THEN {$luas_map[10]}
                    WHEN asetlahan_$i = 11 THEN {$luas_map[11]}
                    ELSE 0 
                END) AS total_luas_aset
            "))->first()->total_luas_aset ?? 0;

            $avg_luas = $total > 0 ? $total_luas / $total : 0;

            $keterangan = $persen_memiliki > 70 
                ? 'Mayoritas keluarga memiliki aset lahan ini, menunjukkan akses yang baik ke sumber daya ini.'
                : ($persen_memiliki > 40 
                    ? 'Sebagian keluarga memiliki aset lahan ini, tetapi masih ada ruang untuk peningkatan akses dan pemanfaatan.'
                    : 'Mayoritas keluarga tidak memiliki aset lahan ini, memerlukan intervensi prioritas untuk redistribusi atau bantuan.');

            $indikator["aset_$i"] = [
                'count_memiliki' => $count_memiliki,
                'count_tidak' => $count_tidak,
                'total_luas' => round($total_luas, 2),
                'avg_luas' => round($avg_luas, 2),
                'persen_memiliki' => round($persen_memiliki, 2),
                'keterangan' => $keterangan,
            ];

            $total_luas_all += $total_luas;
            $total_memiliki_all += $count_memiliki;
        }

        // Rata-rata luas total per keluarga
        $avg_total_luas_raw = $total > 0 ? $total_luas_all / $total : 0;
        $avg_total_luas = round($avg_total_luas_raw); // dibulatkan ke integer terdekat

        // Hitung skor kepemilikan lahan (berdasarkan persentase memiliki dan luas, realistis berdasarkan data nasional ~0.38 HA rata-rata)
        $avg_persen_memiliki = array_sum(array_column($indikator, 'persen_memiliki')) / 10;
        $skor = ($avg_persen_memiliki * 0.5) + (($avg_total_luas_raw / 1) * 50); // Skala disesuaikan, max ~1 HA dianggap tinggi untuk kemiskinan
        $skor = max(0, min(100, round($skor, 2)));

        // Hitung derived indikator (lahan produktif 1-5, non-produktif 6-10)
        $avg_persen_produktif = array_sum(array_map(fn($i) => $indikator["aset_$i"]['persen_memiliki'], range(1, 5))) / 5;
        $avg_persen_nonproduktif = array_sum(array_map(fn($i) => $indikator["aset_$i"]['persen_memiliki'], range(6, 10))) / 5;
        $avg_luas_produktif = array_sum(array_map(fn($i) => $indikator["aset_$i"]['avg_luas'], range(1, 5)));
        $count_tanpa_produktif = $total - round(($total * $avg_persen_produktif / 100), 0); // Estimasi keluarga tanpa lahan produktif

        // Tentukan kategori, analisis, dan rekomendasi berdasarkan data realistis
        if ($skor < 30 || $avg_persen_produktif < 20 || $avg_total_luas_raw < 0.3) {
            $kategori = 'Miskin / Rentan Kemiskinan - Kurang Aset Lahan';
            $analisis = 'Berdasarkan data, kepemilikan lahan produktif sangat rendah dengan rata-rata hanya ' . round($avg_luas_produktif, 2) . ' HA per keluarga dan ' . round($avg_persen_produktif, 2) . '% keluarga yang memiliki. Ini menunjukkan kerentanan tinggi terhadap kemiskinan struktural di sektor agraria, di mana sekitar ' . $count_tanpa_produktif . ' keluarga tidak memiliki akses ke lahan penghasil pendapatan, sesuai dengan rata-rata nasional kepemilikan lahan miskin sekitar 0.3-0.6 HA.';
            $rekomendasi = [
                'Lakukan reforma agraria untuk mendistribusikan lahan negara kepada ' . $count_tanpa_produktif . ' keluarga miskin, fokus pada lahan pertanian dan peternakan sesuai Undang-Undang Reforma Agraria.',
                'Berikan bantuan bibit, pupuk, dan alat pertanian melalui Program Keluarga Harapan (PKH) atau dana desa untuk meningkatkan produktivitas lahan kecil.',
                'Kolaborasi dengan Kementerian ATR/BPN untuk sertifikasi tanah bagi keluarga tanpa kepemilikan legal, mengurangi lahan tidak terpakai.',
                'Monitoring melalui survey tahunan untuk menilai dampak terhadap pengurangan kemiskinan rural.'
            ];
        } elseif ($avg_persen_produktif < 50 || $avg_total_luas_raw < 0.5) {
            $kategori = 'Menengah Bawah / Rawan Kemiskinan - Lahan Kecil';
            $analisis = 'Data menunjukkan lahan produktif mulai ada dengan ' . round($avg_persen_produktif, 2) . '% kepemilikan dan rata-rata ' . round($avg_luas_produktif, 2) . ' HA, namun masih rendah dibandingkan standar nasional (rata-rata 0.38 HA). Sekitar ' . $count_tanpa_produktif . ' keluarga rawan kemiskinan jika terjadi guncangan seperti bencana alam atau fluktuasi harga komoditas.';
            $rekomendasi = [
                'Pendampingan intensif pertanian berkelanjutan untuk optimalisasi lahan kecil melalui program Kementerian Pertanian, targetkan ' . round($total * (50 - $avg_persen_produktif) / 100, 0) . ' keluarga dengan luas <0.5 HA.',
                'Program kredit lunak (KUR) untuk sewa atau pinjam lahan produktif, guna meningkatkan pendapatan keluarga.',
                'Edukasi pengelolaan lahan kerjasama untuk mengurangi lahan idle, bekerja sama dengan koperasi desa.',
                'Bangun infrastruktur irigasi dan akses pasar untuk lahan perikanan/peternakan, sesuai RPJMN 2020-2024.'
            ];
        } elseif ($avg_persen_nonproduktif > 40) {
            $kategori = 'Menengah / Potensi Berkembang - Lahan Non-Produktif Dominan';
            $analisis = 'Kepemilikan lahan produktif cukup dengan ' . round($avg_persen_produktif, 2) . '% dan rata-rata ' . round($avg_luas_produktif, 2) . ' HA, tetapi lahan non-produktif mendominasi di ' . round($avg_persen_nonproduktif, 2) . '%. Ini menunjukkan potensi konversi lahan untuk peningkatan ekonomi, terutama bagi ' . round($total * $avg_persen_nonproduktif / 100, 0) . ' keluarga dengan lahan sewa/pinjaman.';
            $rekomendasi = [
                'Dorong konversi lahan non-produktif menjadi produktif melalui insentif pajak tanah (PBB) dan program agribisnis.',
                'Pelatihan berkelanjutan untuk pemanfaatan lahan hutan/perkebunan, sesuai kebijakan moratorium izin hutan.',
                'Pengembangan koperasi lahan keluarga untuk kerjasama skala besar, targetkan peningkatan produktivitas 20%.',
                'Survey berkala untuk memantau kemajuan dan mencegah degradasi aset lahan.'
            ];
        } else {
            $kategori = 'Sejahtera / Stabil - Lahan Produktif Tinggi';
            $analisis = 'Distribusi lahan seimbang dengan kepemilikan produktif ' . round($avg_persen_produktif, 2) . '% dan rata-rata total luas ' . round($avg_total_luas_raw, 2) . ' HA per keluarga, melebihi rata-rata nasional. Ini menandakan stabilitas ekonomi berbasis agraria, dengan potensi untuk mendukung keluarga lain.';
            $rekomendasi = [
                'Pertahankan kestabilan melalui program asuransi lahan dan diversifikasi usaha pertanian.',
                'Dukung ekspansi lahan besar untuk ekspor hasil pertanian, sesuai Nawacita pembangunan dari pinggiran.',
                'Libatkan keluarga sejahtera dalam CSR untuk pinjam lahan produktif ke ' . $count_tanpa_produktif . ' keluarga miskin.',
                'Evaluasi berkelanjutan untuk redistribusi lahan tidak terpakai, sesuai kebijakan perlindungan sosial RPJMN.'
            ];
        }

        // Buat data untuk dikirim ke view PDF
        $pdf = Pdf::loadView('laporan.asetlahan', [
            'data' => $data,
            'master' => $master,
            'indikator' => $indikator,
            'skor' => $skor,
            'kategori' => $kategori,
            'analisis' => $analisis,
            'rekomendasi' => $rekomendasi,
            'avg_persen_produktif' => round($avg_persen_produktif, 2),
            'avg_persen_nonproduktif' => round($avg_persen_nonproduktif, 2),
            'avg_total_luas' => $avg_total_luas,
            'avg_total_luas_raw' => round($avg_total_luas_raw, 2),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Analisis_Aset_Lahan.pdf');
    }
}