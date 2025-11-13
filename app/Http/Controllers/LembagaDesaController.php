<?php

namespace App\Http\Controllers;

use App\Models\DataLembagaDesa;
use App\Models\DataPenduduk;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemdes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LembagaDesaController extends Controller
{
    /* ============================================================
     *  🔹 TAMPIL DATA (INDEX)
     * ============================================================
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $lembagaDesas = DataLembagaDesa::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)->pluck('lembaga', 'kdlembaga');
        $masterJawabLemdes = MasterJawabLemdes::pluck('jawablemdes', 'kdjawablemdes');

        return view('penduduk.lemdes.index', compact(
            'lembagaDesas', 'masterLembaga', 'masterJawabLemdes', 'search', 'perPage'
        ));
    }

    /* ============================================================
     *  🔹 FORM TAMBAH DATA
     * ============================================================
     */
    public function create()
    {
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)->get();
        $masterJawabLemdes = MasterJawabLemdes::all();

        return view('penduduk.lemdes.create', compact('penduduks', 'masterLembaga', 'masterJawabLemdes'));
    }

    /* ============================================================
     *  🔹 SIMPAN DATA BARU
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:data_lembagadesa,nik|exists:data_penduduk,nik',
        ]);

        $data = [
            'nik' => $request->nik,
            'kdjenislembaga' => 2
        ];

        for ($i = 1; $i <= 9; $i++) {
            $data["lemdes_$i"] = $request->input("lemdes_$i", 0);
        }

        DataLembagaDesa::create($data);

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil ditambahkan.');
    }

    /* ============================================================
     *  🔹 EDIT DATA
     * ============================================================
     */
    public function edit($nik)
    {
        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterLembaga = MasterLembaga::where('kdjenislembaga', 2)->get();
        $masterJawabLemdes = MasterJawabLemdes::all();

        return view('penduduk.lemdes.edit', compact('lembagaDesa', 'penduduks', 'masterLembaga', 'masterJawabLemdes'));
    }

    /* ============================================================
     *  🔹 UPDATE DATA
     * ============================================================
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required|exists:data_penduduk,nik',
        ]);

        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();

        $data = ['nik' => $request->nik, 'kdjenislembaga' => 2];
        for ($i = 1; $i <= 9; $i++) {
            $data["lemdes_$i"] = $request->input("lemdes_$i", 0);
        }

        $lembagaDesa->update($data);

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil diperbarui.');
    }

    /* ============================================================
     *  🔹 HAPUS DATA
     * ============================================================
     */
    public function destroy($nik)
    {
        $lembagaDesa = DataLembagaDesa::where('nik', $nik)->firstOrFail();
        $lembagaDesa->delete();

        return redirect()->route('penduduk.lemdes.index')->with('success', 'Data lembaga desa berhasil dihapus.');
    }

    /* ============================================================
     *  📊 EXPORT PDF: LAPORAN ANALISIS KELEMBAGAAN DESA
     * ============================================================
     */
    public function exportPdf()
    {
        $data = DataLembagaDesa::all();
        $totalPenduduk = $data->count();

        if ($totalPenduduk === 0) {
            return back()->with('error', 'Tidak ada data lembaga desa untuk dianalisis.');
        }

        $jabatanKeys = [
            'Kepala Desa / Lurah' => 'lemdes_1',
            'Sekretaris Desa / Lurah' => 'lemdes_2',
            'Kepala Urusan' => 'lemdes_3',
            'Kepala Dusun / Lingkungan' => 'lemdes_4',
            'Staf Desa / Kelurahan' => 'lemdes_5',
            'Ketua BPD' => 'lemdes_6',
            'Wakil Ketua BPD' => 'lemdes_7',
            'Sekretaris BPD' => 'lemdes_8',
            'Anggota BPD' => 'lemdes_9',
        ];

        $counts = array_fill_keys(array_keys($jabatanKeys), 0);
        $minSatu = 0;
        $tanpaJabatan = 0;
        $lebihDua = 0;
        $sumJabatan = 0;

        foreach ($data as $row) {
            $activeCount = 0;
            foreach ($jabatanKeys as $nama => $col) {
                if (isset($row->{$col}) && $row->{$col} == 2) { // nilai "YA"
                    $counts[$nama]++;
                    $activeCount++;
                }
            }
            $minSatu += ($activeCount > 0);
            $tanpaJabatan += ($activeCount === 0);
            $lebihDua += ($activeCount > 2);
            $sumJabatan += $activeCount;
        }

        $safePct = fn($num, $den) => $den ? round(($num / $den) * 100, 1) : 0;
        $rataJabatan = $totalPenduduk ? round($sumJabatan / $totalPenduduk, 2) : 0;

        $jabatanList = [];
        foreach ($jabatanKeys as $nama => $col) {
            $jumlah = $counts[$nama];
            $persen = $safePct($jumlah, $totalPenduduk);
            $jabatanList[] = [
                'nama' => $nama,
                'jumlah' => $jumlah,
                'persen' => $persen,
                'keterisian' => $persen
            ];
        }

        $rataKeterlibatan = round(($sumJabatan / ($totalPenduduk * 9)) * 100, 1);
        $kategori = $rataKeterlibatan >= 80
            ? 'Sangat Aktif'
            : ($rataKeterlibatan >= 60
                ? 'Cukup Aktif'
                : 'Kurang Aktif');

        $rekomendasi = match ($kategori) {
            'Sangat Aktif' => [
                'Pertahankan partisipasi aparatur dengan sistem penghargaan kinerja.',
                'Optimalkan pembagian tugas agar tidak tumpang tindih jabatan.',
                'Laksanakan pelatihan lanjutan bagi aparatur muda untuk regenerasi kelembagaan.',
            ],
            'Cukup Aktif' => [
                'Lakukan rotasi jabatan secara berkala untuk meningkatkan pemerataan peran aparatur.',
                'Dorong peningkatan kapasitas perangkat desa yang belum memiliki jabatan.',
                'Evaluasi jabatan administratif yang masih kosong.',
            ],
            default => [
                'Segera lakukan pengisian jabatan kosong dalam struktur pemerintahan desa.',
                'Adakan pelatihan dasar pemerintahan desa untuk meningkatkan minat aparatur.',
                'Tinjau ulang mekanisme seleksi dan partisipasi masyarakat dalam kelembagaan.',
            ],
        };

        $viewData = [
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'totalPenduduk' => $totalPenduduk,
            'rataKeterlibatan' => $rataKeterlibatan,
            'kategori' => $kategori,
            'minSatu' => $minSatu,
            'tanpaJabatan' => $tanpaJabatan,
            'lebihDua' => $lebihDua,
            'rataJabatan' => $rataJabatan,
            'jabatanList' => $jabatanList,
            'rekomendasi' => $rekomendasi,
        ];

        $pdf = Pdf::loadView('laporan.lembagadesa', $viewData)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Lembaga-Desa.pdf');
    }
}
