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

    /**
     * Export laporan analisis aset keluarga ke PDF
     */
    public function exportPdf()
    {
        $data = DB::table('data_asetkeluarga')->get();
        $totalKeluarga = $data->count();

        if ($totalKeluarga === 0) {
            return back()->with('error', 'Tidak ada data aset keluarga untuk dianalisis.');
        }

        // 🔹 Hitung skor tiap keluarga
        $baik = $sedang = $buruk = 0;
        foreach ($data as $row) {
            $skor = 0;
            for ($i = 1; $i <= 42; $i++) {
                $val = $row->{"asetkeluarga_$i"};
                // anggap 1 = YA, 2 = TIDAK, 0/null = tidak diisi
                if ($val == 1) $skor++;
            }

            if ($skor >= 25) $baik++;
            elseif ($skor >= 15) $sedang++;
            else $buruk++;
        }

        // 🔹 Hitung persentase
        $persenBaik = round(($baik / $totalKeluarga) * 100, 1);
        $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
        $persenBuruk = round(($buruk / $totalKeluarga) * 100, 1);

        // 🔹 Tentukan kategori dominan
        $kategori = ['Baik' => $baik, 'Sedang' => $sedang, 'Buruk' => $buruk];
        arsort($kategori);
        $dominan = array_key_first($kategori);
        $persenDominan = match ($dominan) {
            'Baik' => $persenBaik,
            'Sedang' => $persenSedang,
            'Buruk' => $persenBuruk,
        };

        // 🔹 Ambil nama aset dan hitung jumlah YA
        $asetMaster = DB::table('master_asetkeluarga')
            ->pluck('asetkeluarga', 'kdasetkeluarga')
            ->toArray();

        $asetCount = [];
        foreach ($asetMaster as $kode => $nama) {
            $jumlah = $data->where("asetkeluarga_$kode", 1)->count(); // ambil yg jawab YA
            $asetCount[$kode] = [
                'nama' => Str::replaceFirst('Memiliki ', '', $nama),
                'jumlah' => $jumlah
            ];
        }

        // 🔹 Urutkan berdasarkan jumlah terbanyak
        usort($asetCount, fn($a, $b) => $b['jumlah'] <=> $a['jumlah']);
        $topAset = array_slice($asetCount, 0, 5);

        // === Generate QuickChart dan ubah ke Base64 biar bisa tampil di DomPDF ===
        $pieChartData = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Baik', 'Sedang', 'Buruk'],
                'datasets' => [[
                    'data' => [$baik, $sedang, $buruk],
                    'backgroundColor' => ['#10b981', '#f59e0b', '#ef4444']
                ]]
            ]
        ];

        // -----------------------------------------------------------------
        // 1. Ambil nilai maksimal + beri ruang 5 poin di atasnya
        $maxVal = $topAset ? max(array_column($topAset, 'jumlah')) : 0;
        $maxVal = $maxVal + 5;               // ruang di atas
        $step   = 1;                         // atau 5 bila data besar

        $barChartData = [
            'type' => 'bar',
            'data' => [
                'labels' => array_column($topAset, 'nama'),
                'datasets' => [[
                    'label' => 'Jumlah Dimiliki',
                    'data' => array_column($topAset, 'jumlah'),
                    'backgroundColor' => '#4f46e5',
                ]]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'layout' => [
                    'padding' => 10
                ],
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'beginAtZero' => true,
                        'grace' => 0, // ⬅️ PAKSA tanpa gap di bawah
                        'ticks' => [
                            'beginAtZero' => true, // ⬅️ QuickChart kadang cuma baca ini
                            'stepSize' => 1,
                            'precision' => 0,
                        ],
                        'min' => 0, // ⬅️ tetap disertakan untuk jaga-jaga
                        'suggestedMin' => 0,
                    ],
                    'x' => [
                        'type' => 'category',
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => '5 Aset Keluarga Paling Banyak Dimiliki',
                    ],
                ],
            ],
        ];

        $pieChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($pieChartData));
        $barChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($barChartData)) . "&_t=" . time();

        $pieChartBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($pieChartUrl));
        $barChartBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($barChartUrl));

        // 🔹 Generate PDF
        $pdf = Pdf::loadView('laporan.asetkeluarga', [
            'totalKeluarga' => $totalKeluarga,
            'baik' => $baik,
            'sedang' => $sedang,
            'buruk' => $buruk,
            'persenBaik' => $persenBaik,
            'persenSedang' => $persenSedang,
            'persenBuruk' => $persenBuruk,
            'dominan' => $dominan,
            'persenDominan' => $persenDominan,
            'topAset' => $topAset,
            'pieChartUrl' => $pieChartBase64,
            'barChartUrl' => $barChartBase64,
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Aset-Keluarga.pdf');
    }

    //VOICE INPUT
    public function voiceInput()
    {
        $keluargas = DataKeluarga::all();
        $masterAset = MasterAsetKeluarga::all();
        return view('keluarga.asetkeluarga.voice', compact('keluargas', 'masterAset'));
    }

}
