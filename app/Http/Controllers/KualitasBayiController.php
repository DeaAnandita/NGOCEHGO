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
    $data = DataKualitasBayi::all();
    $totalKeluarga = $data->count();

    if ($totalKeluarga === 0) {
        return back()->with('error', 'Tidak ada data kualitas bayi untuk dianalisis.');
    }

    // ✅ Hitung kategori Baik/Sedang/Buruk
    $baik = $sedang = $buruk = 0;
    foreach ($data as $row) {
        $skor = 0;
        for ($i = 1; $i <= 7; $i++) {
            if (in_array($row->{"kualitasbayi_$i"}, [2, 3])) {
                $skor++;
            }
        }

        if ($skor >= 5) $baik++;
        elseif ($skor >= 3) $sedang++;
        else $buruk++;
    }

    // ✅ Persentase kategori
    $persenBaik = round(($baik / $totalKeluarga) * 100, 1);
    $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
    $persenBuruk = round(($buruk / $totalKeluarga) * 100, 1);

    // ✅ Dominan
    $kategori = ['Baik' => $baik, 'Sedang' => $sedang, 'Buruk' => $buruk];
    arsort($kategori);
    $dominan = array_key_first($kategori);

    $persenDominan = match ($dominan) {
        'Baik' => $persenBaik,
        'Sedang' => $persenSedang,
        'Buruk' => $persenBuruk,
    };

    // ✅ Ambil master indikator
    $master = MasterKualitasBayi::pluck('kualitasbayi', 'kdkualitasbayi')->toArray();

    // ✅ Hitung persentase indikator
    $persen = [];
    foreach ($master as $kode => $indikator) {
        $jumlahAda = $data->where("kualitasbayi_$kode", 2)->count();
        $persen[$kode] = round(($jumlahAda / $totalKeluarga) * 100, 1);
    }

    // ✅ Hitung top indikator
    $kualitasCount = [];
    foreach ($master as $kode => $nama) {
        $jumlah = $data->where("kualitasbayi_$kode", 2)->count();
        $kualitasCount[] = [
            'nama' => $nama,
            'jumlah' => $jumlah
        ];
    }

    usort($kualitasCount, fn($a, $b) => $b['jumlah'] <=> $a['jumlah']);

    $topAset = array_slice($kualitasCount, 0, 5);

    // ✅ Rekomendasi
    $rekomendasi = [
        "Penguatan imunisasi dasar bayi.",
        "Pemantauan pertumbuhan dan gizi bayi.",
        "Peningkatan cakupan ASI eksklusif.",
        "Pemantauan tanda bahaya bayi secara berkala.",
    ];

    // ✅ Generate PDF
    $pdf = Pdf::loadView('laporan.kualitasbayi', [
        'totalKeluarga' => $totalKeluarga,
        'baik' => $baik,
        'sedang' => $sedang,
        'buruk' => $buruk,
        'persenBaik' => $persenBaik,
        'persenSedang' => $persenSedang,
        'persenBuruk' => $persenBuruk,
        'dominan' => $dominan,
        'persenDominan' => $persenDominan,

        // ✅ Tambahan penting !!
        'master' => $master,
        'persen' => $persen,
        'topAset' => $topAset,
        'rekomendasi' => $rekomendasi,

        'periode' => now()->translatedFormat('F Y'),
        'tanggal' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Kualitas-Bayi.pdf');
}
}