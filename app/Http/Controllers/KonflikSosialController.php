<?php

namespace App\Http\Controllers;

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
        foreach ($groups as $key => $indices) {
            $query = DataKonflikSosial::query();
            $query->where(function ($q) use ($indices) {
                foreach ($indices as $i) {
                    $q->orWhere("konfliksosial_{$i}", '>', 0);
                }
            });
            $data[$key] = $query->count();
        }

        $totalKasus = array_sum($data);

        // deteksi kolom desa
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
            $desaTerbanyak = DataKonflikSosial::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_konfliksosial.no_kk')
                ->select("data_keluarga.$kolomDesa as nama_desa", DB::raw('COUNT(data_konfliksosial.no_kk) as total'))
                ->groupBy("data_keluarga.$kolomDesa")
                ->orderByDesc('total')
                ->first();

            $desaTertinggi = $desaTerbanyak->nama_desa ?? 'Tidak Ada Data';
        }

        if ($totalKasus > 60) $kategori = 'Risiko Sangat Tinggi';
        elseif ($totalKasus > 30) $kategori = 'Risiko Tinggi';
        elseif ($totalKasus > 10) $kategori = 'Risiko Sedang';
        else $kategori = 'Risiko Rendah';

        $rekomendasi = [];
        if ($data['konflik_sara'] > 3) $rekomendasi[] = 'Perkuat kegiatan lintas agama dan toleransi masyarakat.';
        if ($data['kdrt'] > 5) $rekomendasi[] = 'Perlu program perlindungan korban KDRT & konseling keluarga.';
        if ($data['kriminalitas'] > 3) $rekomendasi[] = 'Tingkatkan koordinasi dengan aparat keamanan desa.';
        if ($data['kehamilan_rentan'] > 2) $rekomendasi[] = 'Edukasi kesehatan reproduksi remaja.';
        if ($data['penyimpangan_perilaku'] > 2) $rekomendasi[] = 'Adakan penyuluhan bahaya miras/narkoba.';
        if (empty($rekomendasi)) $rekomendasi[] = 'Kondisi sosial relatif aman. Tetap lakukan pembinaan dan monitoring.';

        $pdf = Pdf::loadView('laporan.konfliksosial', [
            'data' => $data,
            'totalKasus' => $totalKasus,
            'desaTertinggi' => $desaTertinggi,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Konflik-Sosial.pdf');
    }
}
