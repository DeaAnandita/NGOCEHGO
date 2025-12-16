<?php

namespace App\Http\Controllers;

use App\Models\{DataPrasaranaDasar, 
MasterStatusPemilikBangunan, MasterStatusPemilikLahan, MasterJenisFisikBangunan,
MasterKondisiLantaiBangunan, MasterJenisLantaiBangunan, MasterJenisDindingBangunan, MasterKondisiDindingBangunan,
MasterJenisAtapBangunan, MasterKondisiAtapBangunan, MasterSumberAirMinum, MasterKondisiSumberAir,
MasterCaraPerolehanAir, MasterSumberPeneranganUtama, MasterSumberDayaTerpasang, MasterBahanBakarMemasak,
MasterFasilitasTempatBab,MasterPembuanganAkhirTinja, MasterCaraPembuanganSampah, MasterManfaatMataAir};
use App\Models\DataKeluarga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrasaranaDasarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10 data per halaman

        $prasaranas = DataPrasaranaDasar::with([
            'keluarga',
            'statuspemilikbangunan',
            'statuspemiliklahan',
            'jenisfisikbangunan',
            'jenislantaibangunan',
            'kondisilantaibangunan',
            'jenisdindingbangunan',
            'kondisidindingbangunan',
            'jenisatapbangunan',
            'kondisiatapbangunan',
            'sumberairminum',
            'kondisisumberair',
            'caraperolehanair',
            'sumberpeneranganutama',
            'sumberdayaterpasang',
            'bahanbakarmemasak',
            'fasilitastempatbab',
            'pembuanganakhirtinja',
            'carapembuangansampah',
            'manfaatmataair'
        ])
        ->when($search, function ($query, $search) {
            $query->where('no_kk', 'like', "%{$search}%")
                ->orWhereHas('keluarga', function ($q) use ($search) {
                    $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                });
        })
        ->orderBy('no_kk', 'asc')
        ->paginate($perPage)
        ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('keluarga.prasarana.index', compact('prasaranas', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $statuspemilikbangunan = MasterStatusPemilikBangunan::all();
        $statuspemiliklahan = MasterStatusPemilikLahan::all();
        $jenisfisikbangunan = MasterJenisFisikBangunan::all();
        $jenislantaibangunan = MasterJenisLantaiBangunan::all();
        $kondisilantaibangunan = MasterKondisiLantaiBangunan::all();
        $jenisdindingbangunan = MasterJenisDindingBangunan::all();
        $kondisidindingbangunan = MasterKondisiDindingBangunan::all();
        $jenisatapbangunan = MasterJenisAtapBangunan::all();
        $kondisiatapbangunan = MasterKondisiAtapBangunan::all();
        $sumberairminum = MasterSumberAirMinum::all();
        $kondisisumberair = MasterKondisiSumberAir::all();
        $caraperolehanair = MasterCaraPerolehanAir::all();
        $sumberpeneranganutama = MasterSumberPeneranganUtama::all();
        $sumberdayaterpasang = MasterSumberDayaTerpasang::all();
        $bahanbakarmemasak = MasterBahanBakarMemasak::all();
        $fasilitastempatbab = MasterFasilitasTempatBab::all();
        $pembuanganakhirtinja = MasterPembuanganAkhirTinja::all();
        $carapembuangansampah = MasterCaraPembuanganSampah::all();
        $manfaatmataair = MasterManfaatMataAir::all();

        return view('keluarga.prasarana.create', compact('keluargas', 
        'statuspemilikbangunan', 'statuspemiliklahan', 'jenisfisikbangunan', 'jenislantaibangunan', 'kondisilantaibangunan',
        'jenisdindingbangunan', 'kondisidindingbangunan', 'jenisatapbangunan', 'kondisiatapbangunan', 'sumberairminum','kondisisumberair',
        'caraperolehanair', 'sumberpeneranganutama', 'sumberdayaterpasang', 'bahanbakarmemasak', 'fasilitastempatbab', 
        'pembuanganakhirtinja', 'carapembuangansampah', 'manfaatmataair'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|string|unique:data_prasaranadasar,no_kk|exists:data_keluarga,no_kk',
            'kdstatuspemilikbangunan' => 'nullable|integer',
            'kdstatuspemiliklahan' => 'nullable|integer',
            'kdjenisfisikbangunan' => 'nullable|integer',
            'kdjenislantaibangunan' => 'nullable|integer',
            'kdkondisilantaibangunan' => 'nullable|integer',
            'kdjenisdindingbangunan' => 'nullable|integer',
            'kdkondisidindingbangunan' => 'nullable|integer',
            'kdjenisatapbangunan' => 'nullable|integer',
            'kdkondisiatapbangunan' => 'nullable|integer',
            'kdsumberairminum' => 'nullable|integer',
            'kdkondisisumberair' => 'nullable|integer',
            'kdcaraperolehanair' => 'nullable|integer',
            'kdsumberpeneranganutama' => 'nullable|integer',
            'kdsumberdayaterpasang' => 'nullable|integer',
            'kdbahanbakarmemasak' => 'nullable|integer',
            'kdfasilitastempatbab' => 'nullable|integer',
            'kdpembuanganakhirtinja' => 'nullable|integer',
            'kdcarapembuangansampah' => 'nullable|integer',
            'kdmanfaatmataair' => 'nullable|integer',
            'prasdas_luaslantai' => 'nullable|numeric',
            'prasdas_jumlahkamar' => 'nullable|integer',
        ]);

        DataPrasaranaDasar::create($validated);

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $keluargas = DataKeluarga::all();
        $statuspemilikbangunan = MasterStatusPemilikBangunan::all();
        $statuspemiliklahan = MasterStatusPemilikLahan::all();
        $jenisfisikbangunan = MasterJenisFisikBangunan::all();
        $jenislantaibangunan = MasterJenisLantaiBangunan::all();
        $kondisilantaibangunan = MasterKondisiLantaiBangunan::all();
        $jenisdindingbangunan = MasterJenisDindingBangunan::all();
        $kondisidindingbangunan = MasterKondisiDindingBangunan::all();
        $jenisatapbangunan = MasterJenisAtapBangunan::all();
        $kondisiatapbangunan = MasterKondisiAtapBangunan::all();
        $sumberairminum = MasterSumberAirMinum::all();
        $kondisisumberair = MasterKondisiSumberAir::all();
        $caraperolehanair = MasterCaraPerolehanAir::all();
        $sumberpeneranganutama = MasterSumberPeneranganUtama::all();
        $sumberdayaterpasang = MasterSumberDayaTerpasang::all();
        $bahanbakarmemasak = MasterBahanBakarMemasak::all();
        $fasilitastempatbab = MasterFasilitasTempatBab::all();
        $pembuanganakhirtinja = MasterPembuanganAkhirTinja::all();
        $carapembuangansampah = MasterCaraPembuanganSampah::all();
        $manfaatmataair = MasterManfaatMataAir::all();

        return view('keluarga.prasarana.edit', compact('prasarana', 'keluargas', 
        'statuspemilikbangunan', 'statuspemiliklahan', 'jenisfisikbangunan', 'jenislantaibangunan', 'kondisilantaibangunan',
        'jenisdindingbangunan', 'kondisidindingbangunan', 'jenisatapbangunan', 'kondisiatapbangunan', 'sumberairminum','kondisisumberair',
        'caraperolehanair', 'sumberpeneranganutama', 'sumberdayaterpasang', 'bahanbakarmemasak', 'fasilitastempatbab', 
        'pembuanganakhirtinja', 'carapembuangansampah', 'manfaatmataair'));
    }

    public function update(Request $request, $no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();

        $validated = $request->validate([
            'no_kk' => 'required|string|exists:data_keluarga,no_kk',
            'kdstatuspemilikbangunan' => 'nullable|integer',
            'kdstatuspemiliklahan' => 'nullable|integer',
            'kdjenisfisikbangunan' => 'nullable|integer',
            'kdjenislantaibangunan' => 'nullable|integer',
            'kdkondisilantaibangunan' => 'nullable|integer',
            'kdjenisdindingbangunan' => 'nullable|integer',
            'kdkondisidindingbangunan' => 'nullable|integer',
            'kdjenisatapbangunan' => 'nullable|integer',
            'kdkondisiatapbangunan' => 'nullable|integer',
            'kdsumberairminum' => 'nullable|integer',
            'kdkondisisumberair' => 'nullable|integer',
            'kdcaraperolehanair' => 'nullable|integer',
            'kdsumberpeneranganutama' => 'nullable|integer',
            'kdsumberdayaterpasang' => 'nullable|integer',
            'kdbahanbakarmemasak' => 'nullable|integer',
            'kdfasilitastempatbab' => 'nullable|integer',
            'kdpembuanganakhirtinja' => 'nullable|integer',
            'kdcarapembuangansampah' => 'nullable|integer',
            'kdmanfaatmataair' => 'nullable|integer',
            'prasdas_luaslantai' => 'nullable|numeric',
            'prasdas_jumlahkamar' => 'nullable|integer',
        ]);

        $prasarana->update($validated);

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();
        $prasarana->delete();

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil dihapus.');
    }

    public static function exportPDF()
{
    // Ambil semua data prasarana
    $data = DataPrasaranaDasar::all();
    $total = $data->count() ?: 1; // Hindari pembagian nol

    // Rata-rata luas lantai dan jumlah kamar
    $avg_luas_lantai = round(DataPrasaranaDasar::avg('prasdas_luaslantai') ?? 0, 2);
    $avg_jumlah_kamar = round(DataPrasaranaDasar::avg('prasdas_jumlahkamar') ?? 0, 2);

    // Hitung persentase "layak" untuk setiap indikator utama
    $indikator = [];

    // 1. Status Pemilik Bangunan (layak = Milik Sendiri)
    $indikator['status_bangunan'] = round(DataPrasaranaDasar::where('kdstatuspemilikbangunan', 1)->count() / $total * 100, 2);

    // 2. Status Pemilik Lahan (layak = Milik Sendiri)
    $indikator['status_lahan'] = round(DataPrasaranaDasar::where('kdstatuspemiliklahan', 1)->count() / $total * 100, 2);

    // 3. Lantai (layak = bahan berkualitas tinggi-menengah: marmer sampai kayu)
    $indikator['lantai_jenis'] = round(DataPrasaranaDasar::whereIn('kdjenislantaibangunan', [1,2,3,4,5])->count() / $total * 100, 2);

    // 4. Kondisi Lantai Bagus
    $indikator['lantai_kondisi'] = round(DataPrasaranaDasar::where('kdkondisilantaibangunan', 1)->count() / $total * 100, 2);

    // 5. Dinding (layak = tembok/kayu/kalsiboard)
    $indikator['dinding_jenis'] = round(DataPrasaranaDasar::whereIn('kdjenisdindingbangunan', [1,2,3])->count() / $total * 100, 2);

    // 6. Kondisi Dinding Bagus
    $indikator['dinding_kondisi'] = round(DataPrasaranaDasar::where('kdkondisidindingbangunan', 1)->count() / $total * 100, 2);

    // 7. Atap (layak = beton/genteng/asbes)
    $indikator['atap_jenis'] = round(DataPrasaranaDasar::whereIn('kdjenisatapbangunan', [1,2,3,4,5])->count() / $total * 100, 2);

    // 8. Kondisi Atap Bagus
    $indikator['atap_kondisi'] = round(DataPrasaranaDasar::where('kdkondisiatapbangunan', 1)->count() / $total * 100, 2);

    // 9. Sumber Air Minum Aman
    $indikator['air_minum'] = round(DataPrasaranaDasar::whereIn('kdsumberairminum', [1,2,3,4,5,6])->count() / $total * 100, 2);

    // 10. Kondisi Air Baik
    $indikator['kondisi_air'] = round(DataPrasaranaDasar::where('kdkondisisumberair', 1)->count() / $total * 100, 2);

    // 11. Cara Perolehan Air (Tidak Membeli)
    $indikator['perolehan_air'] = round(DataPrasaranaDasar::where('kdcaraperolehanair', 3)->count() / $total * 100, 2);

    // 12. Penerangan Listrik PLN
    $indikator['penerangan'] = round(DataPrasaranaDasar::where('kdsumberpeneranganutama', 1)->count() / $total * 100, 2);

    // 13. Daya ≥1300 Watt
    $indikator['daya_listrik'] = round(DataPrasaranaDasar::whereIn('kdsumberdayaterpasang', [3,4,5])->count() / $total * 100, 2);

    // 14. Bahan Bakar Modern (listrik/gas/biogas)
    $indikator['bahan_bakar'] = round(DataPrasaranaDasar::whereIn('kdbahanbakarmemasak', [1,2,3,4])->count() / $total * 100, 2);

    // 15. Fasilitas BAB Sendiri
    $indikator['fasilitas_bab'] = round(DataPrasaranaDasar::where('kdfasilitastempatbab', 1)->count() / $total * 100, 2);

    // 16. Pembuangan Tinja Aman (tangki/SPAL)
    $indikator['pembuangan_tinja'] = round(DataPrasaranaDasar::whereIn('kdpembuanganakhirtinja', [1,2])->count() / $total * 100, 2);

    // 17. Pembuangan Sampah Resmi
    $indikator['pembuangan_sampah'] = round(DataPrasaranaDasar::whereIn('kdcarapembuangansampah', [3,4])->count() / $total * 100, 2);

    // Skor keseluruhan: rata-rata dari 17 indikator
    $skor = round(array_sum($indikator) / 17, 2);

    // Kategori sub-indikator
    $persen_bangunan = round((
        $indikator['status_bangunan'] + $indikator['status_lahan'] +
        $indikator['lantai_jenis'] + $indikator['lantai_kondisi'] +
        $indikator['dinding_jenis'] + $indikator['dinding_kondisi'] +
        $indikator['atap_jenis'] + $indikator['atap_kondisi']
    ) / 8, 2);

    $persen_air_sanitasi = round((
        $indikator['air_minum'] + $indikator['kondisi_air'] + $indikator['perolehan_air'] +
        $indikator['fasilitas_bab'] + $indikator['pembuangan_tinja'] + $indikator['pembuangan_sampah']
    ) / 6, 2);

    $persen_energi = round((
        $indikator['penerangan'] + $indikator['daya_listrik'] + $indikator['bahan_bakar']
    ) / 3, 2);

    // Tentukan kategori & rekomendasi
    if ($skor < 40 || $persen_bangunan < 50) {
        $kategori = 'Miskin / Rentan Kemiskinan';
        $rekomendasi = [
            'Prioritaskan bantuan renovasi rumah tidak layak huni (BSPS/Risha).',
            'Program penyediaan air bersih (sumur bor, PAM desa) dan jamban keluarga.',
            'Subsidi LPG 3kg dan listrik pra-bayar untuk rumah tangga miskin.',
            'Kolaborasi dengan PUPR untuk sertifikasi lahan dan bangunan milik sendiri.'
        ];
    } elseif ($skor < 65 || $persen_air_sanitasi < 60) {
        $kategori = 'Menengah Bawah / Rawan Kemiskinan';
        $rekomendasi = [
            'Pendampingan kredit mikro untuk perbaikan atap, lantai, dan dinding.',
            'Program STBM (Sanitasi Total Berbasis Masyarakat) dan pengelolaan sampah.',
            'Konversi bahan bakar kayu bakar ke gas kota atau biogas.',
            'Bantuan infrastruktur listrik dan air bersih di wilayah tertinggal.'
        ];
    } elseif ($skor < 85) {
        $kategori = 'Menengah / Potensi Berkembang';
        $rekomendasi = [
            'Dorong peningkatan daya listrik dan penggunaan energi terbarukan.',
            'Pengembangan sistem pembuangan sampah terpadu dan IPAL komunal.',
            'Pelatihan pengelolaan air limbah rumah tangga.',
            'Monitoring berkala untuk mencegah penurunan akses prasarana.'
        ];
    } else {
        $kategori = 'Sejahtera / Stabil';
        $rekomendasi = [
            'Pertahankan akses prasarana melalui program pemeliharaan rutin.',
            'Kembangkan infrastruktur ramah lingkungan (panel surya, biogas).',
            'Arahkan CSR perusahaan untuk membantu keluarga rentan di sekitar.',
            'Evaluasi distribusi prasarana secara berkala.'
        ];
    }

    $pdf = Pdf::loadView('laporan.prasaranadasar', [
        'data' => $data,
        'total' => $total,
        'indikator' => $indikator,
        'skor' => $skor,
        'kategori' => $kategori,
        'rekomendasi' => $rekomendasi,
        'persen_bangunan' => $persen_bangunan,
        'persen_air_sanitasi' => $persen_air_sanitasi,
        'persen_energi' => $persen_energi,
        'avg_luas_lantai' => $avg_luas_lantai,
        'avg_jumlah_kamar' => $avg_jumlah_kamar,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('Laporan_Analisis_Prasarana_Dasar_Keluarga.pdf');
}
}