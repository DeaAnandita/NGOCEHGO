<?php

namespace App\Http\Controllers;

use App\Models\DataSejahteraKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SejahteraKeluargaController extends Controller
{
    /**
     * Tampilkan daftar data sejahtera keluarga.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $sejahterakeluargas = DataSejahteraKeluarga::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        // Ambil label soal dari master_pembangunan_keluarga (typejawab 2 = uraian)
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))
            ->get(['kdpembangunankeluarga', 'pembangunankeluarga']);

        return view('keluarga.sejahterakeluarga.index', compact('sejahterakeluargas', 'masterPembangunan', 'search', 'perPage'));
    }

    /**
     * Tampilkan form tambah data.
     */
    public function create()
    {
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))->get();

        return view('keluarga.sejahterakeluarga.create', compact('keluargas', 'masterPembangunan'));
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $request->validate([
             'no_kk' => 'required|unique:data_sejahterakeluarga,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];

        foreach (range(61, 68) as $i) {
            $data["sejahterakeluarga_$i"] = $request->input("sejahterakeluarga_$i", null);
        }

        DataSejahteraKeluarga::create($data);

        return redirect()->route('keluarga.sejahterakeluarga.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data.
     */
    public function edit($no_kk)
    {
        $sejahterakeluarga = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $masterPembangunan = MasterPembangunanKeluarga::whereIn('kdpembangunankeluarga', range(61, 68))->get();

        return view('keluarga.sejahterakeluarga.edit', compact('sejahterakeluarga', 'keluargas', 'masterPembangunan'));
    }

    /**
     * Update data sejahtera keluarga.
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $sejahterakeluarga = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();

        $data = ['no_kk' => $request->no_kk];

        foreach (range(61, 68) as $i) {
            $data["sejahterakeluarga_$i"] = $request->input("sejahterakeluarga_$i", null);
        }

        $sejahterakeluarga->update($data);

        return redirect()->route('keluarga.sejahterakeluarga.index')
            ->with('success', 'Data sejahtera keluarga berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy($no_kk)
    {
        $data = DataSejahteraKeluarga::where('no_kk', $no_kk)->firstOrFail();
        $data->delete();

        return redirect()->route('keluarga.sejahterakeluarga.index')
            ->with('success', 'Data sejahtera keluarga berhasil dihapus.');
    }

    public static function exportPDF()
    {
        // Ambil data master indikator
        $master = MasterPembangunanKeluarga::all();
        $data = DataSejahteraKeluarga::all();

        // Hitung rata-rata tiap indikator
        $indikator = [
            'uang_saku'   => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_61 AS DECIMAL(10,2))')),
            'rokok'       => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_62 AS DECIMAL(10,2))')),
            'kopi_kali'   => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_63 AS DECIMAL(10,2))')),
            'kopi_jam'    => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_64 AS DECIMAL(10,2))')),
            'pulsa'       => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_65 AS DECIMAL(10,2))')),
            'pendapatan'  => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_66 AS DECIMAL(10,2))')),
            'pengeluaran' => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_67 AS DECIMAL(10,2))')),
            'belanja'     => DataSejahteraKeluarga::avg(DB::raw('CAST(sejahterakeluarga_68 AS DECIMAL(10,2))')),
        ];

        // Hitung derived indikator
        $rasio_pengeluaran = $indikator['pendapatan'] > 0 ? $indikator['pengeluaran'] / $indikator['pendapatan'] : 0;
        $rasio_belanja = $indikator['belanja'] / max($indikator['pendapatan'], 1);
        $persen_rokok = ($indikator['rokok'] / max($indikator['pendapatan'], 1)) * 100;
        $persen_pulsa = ($indikator['pulsa'] / max($indikator['pendapatan'], 1)) * 100;
        $persen_kopi  = (($indikator['kopi_kali'] + $indikator['kopi_jam']) / 2) / max($indikator['pendapatan'], 1) * 100;

        // Hitung skor kesejahteraan
        $skor = 100 
            - ($rasio_pengeluaran * 50)
            - ($persen_rokok / 4)
            - ($persen_kopi / 4)
            - ($persen_pulsa / 4)
            + (($indikator['uang_saku'] > 5000 ? 5 : 0));

        $skor = max(0, min(100, round($skor, 2)));

        // Tentukan kategori & arah kebijakan otomatis
        if ($indikator['pendapatan'] < 1000000 || $rasio_pengeluaran > 1.2) {
            $kategori = 'Miskin / Rentan Kemiskinan';
            $rekomendasi = [
                'Program bantuan langsung tunai (BLT, PKH, sembako).',
                'Pelatihan wirausaha dan pemberdayaan ekonomi desa.',
                'Fokus intervensi keluarga berpendapatan rendah.'
            ];
        } elseif ($persen_rokok > 15 || $persen_kopi > 10) {
            $kategori = 'Konsumtif Tidak Efisien';
            $rekomendasi = [
                'Kampanye pengurangan pengeluaran rokok dan kopi.',
                'Edukasi manajemen keuangan keluarga produktif.',
                'Arahkan ke program tabungan dan investasi keluarga.'
            ];
        } elseif ($indikator['uang_saku'] < 5000) {
            $kategori = 'Kurang Dukungan Pendidikan Anak';
            $rekomendasi = [
                'Bantuan uang saku anak sekolah melalui dana desa.',
                'Program BKB & beasiswa anak keluarga miskin.',
                'Pendampingan pendidikan keluarga sadar gizi & literasi.'
            ];
        } elseif ($rasio_belanja < 0.4) {
            $kategori = 'Rentan Pangan / Gizi';
            $rekomendasi = [
                'Program ketahanan pangan desa & dapur gizi keluarga.',
                'Distribusi bahan pokok bagi keluarga rentan.'
            ];
        } else {
            $kategori = 'Sejahtera Stabil';
            $rekomendasi = [
                'Dorong tabungan keluarga dan investasi pendidikan.',
                'Pertahankan pola konsumsi seimbang dan produktif.',
                'Kembangkan usaha mikro rumah tangga.'
            ];
        }

        // Buat data untuk dikirim ke view PDF
        $pdf = Pdf::loadView('laporan.sejahterakeluarga', [
            'data' => $data,
            'master' => $master,
            'indikator' => $indikator,
            'skor' => $skor,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Analisis_Sejahtera_Keluarga.pdf');
    }
}


