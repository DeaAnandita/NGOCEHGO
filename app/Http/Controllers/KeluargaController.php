<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use App\Models\MasterDusun;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeluargaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $keluargas = DataKeluarga::with(['mutasi', 'dusun', 'provinsi', 'kabupaten', 'kecamatan', 'desa'])
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhere('keluarga_kepalakeluarga', 'like', "%{$search}%")
                    ->orWhereHas('dusun', fn($q) => $q->where('dusun', 'like', "%{$search}%"));
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('keluarga.index', compact('keluargas', 'search', 'perPage'));
    }

    public function create()
    {
        $mutasis   = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns    = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get();

        return view('keluarga.create', compact('mutasis', 'dusuns', 'provinsis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
            'keluarga_tanggalmutasi' => 'required|date',
            'no_kk' => 'required|string|max:20|unique:data_keluarga,no_kk',
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'kddusun' => 'required|exists:master_dusun,kddusun',
            'keluarga_rw' => 'required|string|max:10',
            'keluarga_rt' => 'required|string|max:10',
            'keluarga_alamatlengkap' => 'required|string',
            'kdprovinsi' => 'nullable|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|exists:master_desa,kddesa',
        ]);

        DataKeluarga::create($validated);

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $keluarga = DataKeluarga::findOrFail($id);
        $mutasis = MasterMutasiMasuk::all();
        $dusuns = MasterDusun::all();
        $provinsis = MasterProvinsi::orderBy('provinsi')->get();

        // Load data wilayah asal untuk edit
        $kabupatens = $keluarga->kdprovinsi 
            ? \DB::table('master_kabupaten')->where('kdprovinsi', $keluarga->kdprovinsi)->orderBy('kabupaten')->get(['kdkabupaten', 'kabupaten'])
            : collect();

        $kecamatans = $keluarga->kdkabupaten 
            ? \DB::table('master_kecamatan')->where('kdkabupaten', $keluarga->kdkabupaten)->orderBy('kecamatan')->get(['kdkecamatan', 'kecamatan'])
            : collect();

        $desas = $keluarga->kdkecamatan 
            ? \DB::table('master_desa')->where('kdkecamatan', $keluarga->kdkecamatan)->orderBy('desa')->get(['kddesa', 'desa'])
            : collect();

        return view('keluarga.edit', compact('keluarga', 'mutasis', 'dusuns', 'provinsis', 'kabupatens', 'kecamatans', 'desas'));
    }

    public function update(Request $request, $id)
    {
        $keluarga = DataKeluarga::findOrFail($id);

        $validated = $request->validate([
            'kdmutasimasuk' => 'required|exists:master_mutasimasuk,kdmutasimasuk',
            'keluarga_tanggalmutasi' => 'required|date',
            'no_kk' => 'required|string|max:20' . $id . ',no_kk',
            'keluarga_kepalakeluarga' => 'required|string|max:255',
            'kddusun' => 'required|exists:master_dusun,kddusun',
            'keluarga_rw' => 'required|string|max:10',
            'keluarga_rt' => 'required|string|max:10',
            'keluarga_alamatlengkap' => 'required|string',
            'kdprovinsi' => 'nullable|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|exists:master_desa,kddesa',
        ]);

        $keluarga->update($validated);

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keluarga = DataKeluarga::findOrFail($id);
        $keluarga->delete();

        return redirect()->route('dasar-keluarga.index')
            ->with('success', 'Data keluarga berhasil dihapus.');
    }
    public function getKabupaten($kdprovinsi)
    {
        $kabupatens = \DB::table('master_kabupaten')
            ->where('kdprovinsi', $kdprovinsi)
            ->orderBy('kabupaten')
            ->get(['kdkabupaten', 'kabupaten']); // ambil sebagai collection of objects

        return response()->json($kabupatens);
    }

    public function getKecamatan($kdkabupaten)
    {
        $kecamatans = \DB::table('master_kecamatan')
            ->where('kdkabupaten', $kdkabupaten)
            ->orderBy('kecamatan')
            ->get(['kdkecamatan', 'kecamatan']);

        return response()->json($kecamatans);
    }

    public function getDesa($kdkecamatan)
    {
        $desas = \DB::table('master_desa')
            ->where('kdkecamatan', $kdkecamatan)
            ->orderBy('desa')
            ->get(['kddesa', 'desa']);

        return response()->json($desas);
    }

    public function exportPdf()
{
    // --- DATA DASAR ---
    $ringkasan = DB::table('data_keluarga')
        ->leftJoin('data_penduduk', 'data_penduduk.no_kk', '=', 'data_keluarga.no_kk')
        ->selectRaw('
            COUNT(DISTINCT data_keluarga.no_kk) as total_keluarga,
            COUNT(data_penduduk.nik) as total_penduduk
        ')
        ->first();

    // --- REKAP PER DUSUN ---
    $perDusun = DB::table('data_keluarga')
        ->leftJoin('master_dusun', 'master_dusun.kddusun', '=', 'data_keluarga.kddusun')
        ->leftJoin('data_penduduk', 'data_penduduk.no_kk', '=', 'data_keluarga.no_kk')
        ->selectRaw('
            COALESCE(master_dusun.dusun, "Tidak ada dusun") as dusun,
            COUNT(DISTINCT data_keluarga.no_kk) as total_keluarga,
            COUNT(data_penduduk.nik) as total_penduduk
        ')
        ->groupBy('master_dusun.dusun')
        ->orderBy('dusun')
        ->get();

    $jumlahDusun   = $perDusun->count();
    $dusunTerpadat = $perDusun->sortByDesc('total_penduduk')->first();

    // --- REKAP RW / RT (FORMAT HIARKIS) ---
    $rekapRwRtRaw = DB::table('data_keluarga')
        ->leftJoin('data_penduduk', 'data_penduduk.no_kk', '=', 'data_keluarga.no_kk')
        ->selectRaw('
            data_keluarga.keluarga_rw as rw,
            data_keluarga.keluarga_rt as rt,
            COUNT(DISTINCT data_keluarga.no_kk) as total_keluarga,
            COUNT(data_penduduk.nik) as total_penduduk
        ')
        ->groupBy('data_keluarga.keluarga_rw', 'data_keluarga.keluarga_rt')
        ->orderBy('data_keluarga.keluarga_rw')
        ->orderBy('data_keluarga.keluarga_rt')
        ->get();

    // Kelompokkan per RW untuk tabel hierarkis
    $rekapRwRt = $rekapRwRtRaw->groupBy('rw');

    // Hitung total
    $jumlahRw = $rekapRwRt->count();
    $jumlahRt = $rekapRwRtRaw->count();
    $totalKeluargaKeseluruhan = $rekapRwRtRaw->sum('total_keluarga');
    $totalPendudukKeseluruhan = $rekapRwRtRaw->sum('total_penduduk');

    // --- RINGKASAN MUTASI ---
    $mutasiMasuk = DB::table('data_penduduk')
        ->leftJoin('master_mutasimasuk', 'master_mutasimasuk.kdmutasimasuk', '=', 'data_penduduk.kdmutasimasuk')
        ->leftJoin('data_keluarga', 'data_keluarga.no_kk', '=', 'data_penduduk.no_kk')
        ->select(
            'master_mutasimasuk.mutasimasuk',
            DB::raw('COUNT(DISTINCT data_penduduk.no_kk) as jumlah_keluarga'),
            DB::raw('COUNT(data_penduduk.nik) as jumlah_penduduk')
        )
        ->groupBy('master_mutasimasuk.kdmutasimasuk', 'master_mutasimasuk.mutasimasuk')
        ->orderBy('master_mutasimasuk.kdmutasimasuk')
        ->get();

    if ($mutasiMasuk->count() < 4) {
        $existingTypes = $mutasiMasuk->pluck('mutasimasuk')->toArray();
        $allTypes = ['MUTASI TETAP', 'MUTASI LAHIR', 'MUTASI DATANG', 'MUTASI KELUAR'];
        foreach ($allTypes as $type) {
            if (!in_array($type, $existingTypes)) {
                $mutasiMasuk->push((object)[
                    'mutasimasuk' => $type,
                    'jumlah_keluarga' => 0,
                    'jumlah_penduduk' => 0
                ]);
            }
        }
    }

    // =============================
    // ANALISIS & INTERPRETASI (DIPERBAIKI: GUNAKAN $rekapRwRtRaw)
    // =============================
    $catatan = [];

    // 1. Rata-rata anggota keluarga
    if ($ringkasan->total_penduduk > 0) {
        $rataPendudukPerKk = round($ringkasan->total_penduduk / $ringkasan->total_keluarga);
        $catatan[] = "Rata-rata jumlah anggota keluarga adalah {$rataPendudukPerKk} orang per Kartu Keluarga (KK). Ini mencerminkan struktur rumah tangga umum di wilayah ini.";

        if ($rataPendudukPerKk >= 6) {
            $catatan[] = "Rata-rata yang tinggi (≥6 orang/KK) mengindikasikan potensi beban ekonomi lebih besar pada keluarga, terutama dalam akses pendidikan, kesehatan, dan pangan. Hal ini bisa menjadi faktor risiko kemiskinan jika tidak didukung oleh pendapatan yang memadai.";
        } elseif ($rataPendudukPerKk <= 3) {
            $catatan[] = "Rata-rata yang relatif rendah (≤3 orang/KK) mungkin disebabkan oleh tren keluarga kecil, urbanisasi, atau migrasi pekerja. Ini bisa menunjukkan penurunan pertumbuhan penduduk alami dan kebutuhan program pemberdayaan keluarga muda.";
        } else {
            $catatan[] = "Rata-rata ini berada dalam kisaran normal (4-5 orang/KK), tetapi perlu pemantauan lanjutan terhadap keluarga dengan anggota di atas rata-rata untuk identifikasi rumah tangga rentan.";
        }
    }

    // 2. Analisis Dusun (tetap sama)
    if ($jumlahDusun > 0) {
        $rataPendudukPerDusun = round($ringkasan->total_penduduk / $jumlahDusun);

        if ($dusunTerpadat) {
            $persenPendudukTerpadat = round(($dusunTerpadat->total_penduduk / $ringkasan->total_penduduk) * 100);
            $catatan[] = "Dusun {$dusunTerpadat->dusun} adalah wilayah terpadat dengan {$dusunTerpadat->total_penduduk} jiwa ({$persenPendudukTerpadat}% dari total penduduk) dan {$dusunTerpadat->total_keluarga} KK.";

            if ($persenPendudukTerpadat > 25) {
                $catatan[] = "Konsentrasi penduduk yang tinggi di satu dusun ini menandakan ketimpangan spasial, yang bisa memperburuk akses layanan dasar — faktor utama kemiskinan struktural.";
            }
        }

        $dusunDiAtasRata = $perDusun->filter(fn($d) => $d->total_penduduk > $rataPendudukPerDusun * 1.3)->count();
        if ($dusunDiAtasRata > 0) {
            $catatan[] = "Terdapat {$dusunDiAtasRata} dusun dengan penduduk >30% di atas rata-rata, mengindikasikan distribusi penduduk yang tidak merata.";
        }
    }

    // 3. Analisis RW/RT — DIPERBAIKI: gunakan $rekapRwRtRaw
    if ($jumlahRt > 0) {
        $rataPendudukPerRt = round($ringkasan->total_penduduk / $jumlahRt);

        // Cari RT terpadat dari data mentah
        $rtTerpadat = $rekapRwRtRaw->sortByDesc('total_penduduk')->first();

        if ($rtTerpadat) {
            $catatan[] = "RT {$rtTerpadat->rt} di RW {$rtTerpadat->rw} memiliki penduduk tertinggi ({$rtTerpadat->total_penduduk} jiwa dari {$rtTerpadat->total_keluarga} KK), dibandingkan rata-rata {$rataPendudukPerRt} jiwa per RT.";

            if ($rtTerpadat->total_penduduk > $rataPendudukPerRt * 1.5) {
                $catatan[] = "Kepadatan tinggi di RT ini menunjukkan kebutuhan intervensi mikro seperti verifikasi kemiskinan dan program bantuan targeted (PKH, BLT, BPNT).";
            }
        }
    }

    // 4. Analisis Mutasi — DIPERBAIKI: gunakan jumlah_penduduk
    $mutasiLahir   = $mutasiMasuk->where('mutasimasuk', 'MUTASI LAHIR')->first()?->jumlah_penduduk ?? 0;
    $mutasiDatang  = $mutasiMasuk->where('mutasimasuk', 'MUTASI DATANG')->first()?->jumlah_penduduk ?? 0;
    $mutasiKeluar  = $mutasiMasuk->where('mutasimasuk', 'MUTASI KELUAR')->first()?->jumlah_penduduk ?? 0;

    $migrasiNeto = $mutasiDatang - $mutasiKeluar;

    $catatan[] = "Mutasi penduduk: kelahiran {$mutasiLahir} jiwa, migrasi neto " . ($migrasiNeto >= 0 ? "+{$migrasiNeto}" : "{$migrasiNeto}") . " jiwa (datang: {$mutasiDatang}, keluar: {$mutasiKeluar}).";

    if ($migrasiNeto < 0 && abs($migrasiNeto) > 10) {
        $catatan[] = "Migrasi keluar signifikan dapat menjadi indikator kurangnya peluang ekonomi lokal.";
    }
    if ($mutasiDatang > 20) {
        $catatan[] = "Penduduk pendatang cukup banyak ({$mutasiDatang} jiwa) — perlu integrasi sosial dan pendaftaran kependudukan.";
    }

    // 5. Kesimpulan umum
    $catatan[] = "Data secara keseluruhan menunjukkan pola kepadatan dan mutasi yang dapat menjadi dasar penentuan prioritas program penanggulangan kemiskinan.";

    // =============================
    // REKOMENDASI
    // =============================
    $namaDusunTerpadat = $dusunTerpadat?->dusun ?? '-';
    $namaRtTerpadat = $rtTerpadat ? "RT {$rtTerpadat->rt}/RW {$rtTerpadat->rw}" : '-';

    $rekomendasiIntervensi = [
        "Prioritaskan verifikasi kemiskinan di Dusun {$namaDusunTerpadat} dan {$namaRtTerpadat} sebagai wilayah terpadat.",
        "Fokus program PKH, BPNT, dan BLT pada keluarga di wilayah dengan kepadatan tinggi.",
        "Perkuat pendaftaran penduduk pendatang dan integrasi sosial.",
        "Pantau terus pertumbuhan penduduk alami dan migrasi untuk perencanaan jangka panjang.",
        "Integrasikan data luas wilayah dan status ekonomi untuk analisis kepadatan yang lebih akurat di masa mendatang."
    ];

    // === PASS DATA KE VIEW (HAPUS $perRwRt!) ===
    $pdf = Pdf::loadView('laporan.keluarga', [
        'ringkasan'                 => $ringkasan,
        'perDusun'                  => $perDusun,
        'jumlahDusun'               => $jumlahDusun,
        'dusunTerpadat'             => $dusunTerpadat,
        'rekapRwRt'                 => $rekapRwRt,                    // Untuk tabel hierarkis
        'jumlahRw'                  => $jumlahRw,
        'jumlahRt'                  => $jumlahRt,
        'totalKeluargaKeseluruhan'  => $totalKeluargaKeseluruhan,
        'totalPendudukKeseluruhan'  => $totalPendudukKeseluruhan,
        'mutasiMasuk'               => $mutasiMasuk,
        'catatan'                   => $catatan,
        'rekomendasiIntervensi'     => $rekomendasiIntervensi,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('laporan_data_keluarga.pdf');
}

}
