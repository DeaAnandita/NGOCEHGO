<?php

namespace App\Http\Controllers;

use App\Models\DataKonflikSosial;
use App\Models\DataKeluarga;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KonflikSosialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $konfliksosials = DataKonflikSosial::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterKonflik = MasterKonflikSosial::pluck('konfliksosial', 'kdkonfliksosial')->toArray();
        $masterJawab = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik')->toArray();

        return view('keluarga.konfliksosial.index', compact('konfliksosials', 'masterKonflik', 'masterJawab', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();
        return view('konfliksosial.create', compact('keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_konfliksosial,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        $jumlahIndikator = MasterKonflikSosial::count();

        for ($i = 1; $i <= $jumlahIndikator; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        DataKonflikSosial::create($data);

        return redirect()->route('konfliksosial.index')->with('success', 'Data konflik sosial berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $konflik = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterKonflik = MasterKonflikSosial::all();
        $masterJawab = MasterJawabKonflik::all();
        return view('konfliksosial.edit', compact('konflik', 'keluargas', 'masterKonflik', 'masterJawab'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $konflik = DataKonflikSosial::where('no_kk', $no_kk)->firstOrFail();
        $data = ['no_kk' => $request->no_kk];

        $jumlahIndikator = MasterKonflikSosial::count();
        for ($i = 1; $i <= $jumlahIndikator; $i++) {
            $data["konfliksosial_$i"] = $request->input("konfliksosial_$i", 0);
        }

        $konflik->update($data);

        return redirect()->route('konfliksosial.index')->with('success', 'Data konflik sosial berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        DataKonflikSosial::where('no_kk', $no_kk)->delete();
        return redirect()->route('konfliksosial.index')->with('success', 'Data konflik sosial berhasil dihapus.');
    }

    public function exportPdf()
    {
        // ambil semua data konflik sosial
        $data = DataKonflikSosial::all();
        $totalKeluarga = $data->count();

        if ($totalKeluarga === 0) {
            return back()->with('error', 'Tidak ada data konflik sosial untuk dianalisis.');
        }

        // hitung kategori
        $ringan = $sedang = $berat = 0;
        foreach ($data as $row) {
            $skor = 0;
            for ($i = 1; $i <= 32; $i++) {
                $val = $row->{"konfliksosial_$i"};
                if ($val == 1) $skor++;
            }

            if ($skor >= 3) $berat++;
            elseif ($skor >= 1) $sedang++;
            else $ringan++;
        }

        $persenRingan = round(($ringan / $totalKeluarga) * 100, 1);
        $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
        $persenBerat = round(($berat / $totalKeluarga) * 100, 1);

        // dominan
        $kategori = ['Ringan' => $ringan, 'Sedang' => $sedang, 'Berat' => $berat];
        arsort($kategori);
        $dominan = array_key_first($kategori);
        $persenDominan = match ($dominan) {
            'Ringan' => $persenRingan,
            'Sedang' => $persenSedang,
            'Berat' => $persenBerat,
        };

        // ambil master pertanyaan
        $master = MasterKonflikSosial::pluck('konfliksosial', 'kdkonfliksosial')->toArray();

        // hitung jumlah ADA per soal
        $soalCount = [];
        foreach ($master as $kode => $nama) {
            $jumlah = $data->where("konfliksosial_$kode", 1)->count();
            $soalCount[$kode] = [
                'nama' => ucfirst(Str::lower($nama)),
                'jumlah' => $jumlah,
            ];
        }

        // urutkan dan ambil top 8
        usort($soalCount, fn($a, $b) => $b['jumlah'] <=> $a['jumlah']);
        $topSoal = array_slice($soalCount, 0, 8);

        // chart data
        $pieChartData = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Ringan', 'Sedang', 'Berat'],
                'datasets' => [[
                    'data' => [$ringan, $sedang, $berat],
                    'backgroundColor' => ['#60a5fa', '#f59e0b', '#ef4444']
                ]]
            ]
        ];

        $barChartData = [
            'type' => 'bar',
            'data' => [
                'labels' => array_map(fn($x) => Str::limit($x['nama'], 30), $topSoal),
                'datasets' => [[
                    'label' => 'Jumlah Keluarga',
                    'data' => array_column($topSoal, 'jumlah'),
                    'backgroundColor' => '#4f46e5'
                ]]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['display' => false],
                    'title' => ['display' => true, 'text' => 'Top Konflik Sosial (berdasarkan jumlah keluarga)']
                ],
                'scales' => [
                    'y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1, 'precision' => 0]]
                ]
            ]
        ];

        $pieChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($pieChartData));
        $barChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($barChartData)) . "&_t=" . time();

        // ubah ke base64 agar bisa di-embed
        $pieChartBase64 = 'data:image/png;base64,' . base64_encode(@file_get_contents($pieChartUrl));
        $barChartBase64 = 'data:image/png;base64,' . base64_encode(@file_get_contents($barChartUrl));

        // buat PDF
        $pdf = Pdf::loadView('laporan.konfliksosial', [
            'totalKeluarga' => $totalKeluarga,
            'ringan' => $ringan,
            'sedang' => $sedang,
            'berat' => $berat,
            'persenRingan' => $persenRingan,
            'persenSedang' => $persenSedang,
            'persenBerat' => $persenBerat,
            'dominan' => $dominan,
            'persenDominan' => $persenDominan,
            'topSoal' => $topSoal,
            'pieChartUrl' => $pieChartBase64,
            'barChartUrl' => $barChartBase64,
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Konflik-Sosial.pdf');
    }
}
