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
        $keluarga  = DataKeluarga::findOrFail($id);
        $mutasis   = MasterMutasiMasuk::select('kdmutasimasuk', 'mutasimasuk')->get();
        $dusuns    = MasterDusun::select('kddusun', 'dusun')->get();
        $provinsis = MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get();

        // Tambahkan ini 3 baris
        $kabupatens = $keluarga->kdprovinsi ? MasterKabupaten::where('kdprovinsi', $keluarga->kdprovinsi)->orderBy('kabupaten')->get() : collect();
        $kecamatans = $keluarga->kdkabupaten ? MasterKecamatan::where('kdkabupaten', $keluarga->kdkabupaten)->orderBy('kecamatan')->get() : collect();
        $desas      = $keluarga->kdkecamatan ? MasterDesa::where('kdkecamatan', $keluarga->kdkecamatan)->orderBy('desa')->get() : collect();

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
    public function kabupaten($kdprovinsi)
    {
        $kabupatens = MasterKabupaten::where('kdprovinsi', $kdprovinsi)
            ->select('kdkabupaten', 'kabupaten')
            ->orderBy('kabupaten')
            ->get();

        return response()->json($kabupatens);
    }

    public function kecamatan($kdkabupaten)
    {
        $kecamatans = MasterKecamatan::where('kdkabupaten', $kdkabupaten)
            ->select('kdkecamatan', 'kecamatan')
            ->orderBy('kecamatan')
            ->get();

        return response()->json($kecamatans);
    }

    public function desa($kdkecamatan)
    {
        $desas = MasterDesa::where('kdkecamatan', $kdkecamatan)
            ->select('kddesa', 'desa')
            ->orderBy('desa')
            ->get();

        return response()->json($desas);
    }

    public static function exportPDF()
    {
        // Ambil data utama
        $masterMutasi = MasterMutasiMasuk::all();
        $masterDusun = MasterDusun::all();
        $data = DataKeluarga::all();
        $total = $data->count();

        // Hitung persentase mutasi datang
        $mutasi_datang = DataKeluarga::where('kdmutasimasuk', 'datang')->count();  // Asumsi kode 'datang'
        $persen_datang = $total > 0 ? round(($mutasi_datang / $total) * 100, 2) : 0;

        // Hitung kepadatan per dusun (rata-rata)
        $kepadatan_per_dusun = DataKeluarga::select('kddusun', DB::raw('COUNT(*) as count'))
            ->groupBy('kddusun')
            ->get()
            ->avg('count') ?? 0;

        // Hitung persen migran dari daerah miskin (asumsi daftar provinsi miskin, sesuaikan)
        $daerah_miskin = ['Papua', 'NTT'];  // Contoh, ambil dari config atau DB
        $migran_miskin = DataKeluarga::where('kdmutasimasuk', 'datang')
            ->whereHas('provinsi', function ($q) use ($daerah_miskin) {
                $q->whereIn('provinsi', $daerah_miskin);
            })->count();
        $persen_migran_miskin = $mutasi_datang > 0 ? round(($migran_miskin / $mutasi_datang) * 100, 2) : 0;

        // Skor kerentanan
        $skor = ($persen_datang * 0.4) + ($kepadatan_per_dusun * 0.003) + ($persen_migran_miskin * 0.3);  // Adjust multiplier kepadatan agar masuk skala 0-100
        $skor = max(0, min(100, round($skor, 2)));

        // Kategori dan rekomendasi (mirip contoh aset)
        if ($skor > 50 || $persen_datang > 20) {
            $kategori = 'Rentan Kemiskinan Migrasi';
            $rekomendasi = [
                'Program integrasi migran dengan BLT khusus.',
                'Mapping infrastruktur di dusun padat.',
                'Kerja sama antar-daerah untuk pencegahan migrasi.'
            ];
        } elseif ($skor >= 30) {
            $kategori = 'Rawan Kemiskinan Lokal';
            $rekomendasi = [
                'Alokasi PKH targeted per RW/RT.',
                'Pembangunan infrastruktur desa.',
                'Monitoring migrasi berkala.'
            ];
        } else {
            $kategori = 'Stabil';
            $rekomendasi = [
                'Edukasi manajemen migrasi.',
                'Program CSR untuk investasi.',
                'Survey tahunan deteksi dini.'
            ];
        }

        // Persentase ringkasan
        $persen_kepadatan_tinggi = round(($kepadatan_per_dusun > 50 ? 100 : ($kepadatan_per_dusun / 50) * 100), 2);  // Asumsi threshold 50

        $pdf = Pdf::loadView('laporan.keluarga', [
            'data' => $data,
            'masterMutasi' => $masterMutasi,
            'masterDusun' => $masterDusun,
            'persen_datang' => $persen_datang,
            'kepadatan_per_dusun' => round($kepadatan_per_dusun, 2),
            'persen_migran_miskin' => $persen_migran_miskin,
            'skor' => $skor,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
            'persen_kepadatan_tinggi' => $persen_kepadatan_tinggi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Analisis_Data_Keluarga.pdf');
    }
}
