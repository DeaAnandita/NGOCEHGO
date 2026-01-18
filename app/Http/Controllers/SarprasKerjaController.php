<?php

namespace App\Http\Controllers;

use App\Models\DataSarprasKerja;
use App\Models\DataKeluarga;
use App\Models\MasterSarprasKerja;
use App\Models\MasterJawabSarpras;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SarprasKerjaController extends Controller
{
    /** 
     * 🟢 INDEX - Menampilkan data sarpras kerja 
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $sarpraskerjas = DataSarprasKerja::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterSarpras = MasterSarprasKerja::pluck('sarpraskerja', 'kdsarpraskerja')->toArray();
        $masterJawab   = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();

        return view('keluarga.sarpraskerja.index', compact('sarpraskerjas', 'masterSarpras', 'masterJawab', 'search', 'perPage'));
    }

    /** 
     * 🟢 CREATE - Form tambah data 
     */
    public function create()
    {
        $keluargas     = DataKeluarga::all();
        $masterSarpras = MasterSarprasKerja::all();
        $masterJawab   = MasterJawabSarpras::all();

        return view('keluarga.sarpraskerja.create', compact('keluargas', 'masterSarpras', 'masterJawab'));
    }

    /** 
     * 🟢 STORE - Simpan data baru 
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_sarpraskerja,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 25; $i++) {
            $data["sarpraskerja_$i"] = $request->input("sarpraskerja_$i", 0);
        }

        DataSarprasKerja::create($data);

        return redirect()->route('keluarga.sarpraskerja.index')
            ->with('success', 'Data sarpras kerja berhasil ditambahkan.');
    }

    /** 
     * 🟢 EDIT - Form ubah data 
     */
    public function edit($no_kk)
    {
        $sarpraskerja  = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $keluargas     = DataKeluarga::all();
        $masterSarpras = MasterSarprasKerja::all();
        $masterJawab   = MasterJawabSarpras::all();

        return view('keluarga.sarpraskerja.edit', compact('sarpraskerja', 'keluargas', 'masterSarpras', 'masterJawab'));
    }

    /** 
     * 🟢 UPDATE - Simpan perubahan data 
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $sarpraskerja = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $data = ['no_kk' => $request->no_kk];

        for ($i = 1; $i <= 25; $i++) {
            $data["sarpraskerja_$i"] = $request->input("sarpraskerja_$i", 0);
        }

        $sarpraskerja->update($data);

        return redirect()->route('keluarga.sarpraskerja.index')
            ->with('success', 'Data sarpras kerja berhasil diperbarui.');
    }

    /** 
     * 🟢 DESTROY - Hapus data 
     */
    public function destroy($no_kk)
    {
        $sarpraskerja = DataSarprasKerja::where('no_kk', $no_kk)->firstOrFail();
        $sarpraskerja->delete();

        return redirect()->route('keluarga.sarpraskerja.index')
            ->with('success', 'Data sarpras kerja berhasil dihapus.');
    }

    /** 
     * 🧾 EXPORT PDF - Laporan Analisis Sarpras Kerja (Format Baru)
     */
    public function exportPdf()
{
    $data = DataSarprasKerja::all();
    $totalKeluarga = $data->count();

    if ($totalKeluarga === 0) {
        return back()->with('error', 'Tidak ada data sarpras kerja untuk dianalisis.');
    }

    /* ============================
       MASTER DATA
    ============================= */
    $masterSarpras = MasterSarprasKerja::pluck('sarpraskerja', 'kdsarpraskerja')->toArray();
    $jawabMaster   = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();

    /* ============================
       1️⃣ HITUNG SKOR PER KELUARGA
    ============================= */
    $skorTotal = [];
    foreach ($data as $row) {
        $skor = 0;
        for ($i = 1; $i <= 25; $i++) {
            if ((int)$row->{"sarpraskerja_$i"} !== 6) {
                $skor++;
            }
        }
        $skorTotal[] = $skor;
    }

    $skorRataRata = round(array_sum($skorTotal) / $totalKeluarga, 2);

    /* ============================
       2️⃣ KATEGORI KELUARGA
    ============================= */
    $rendah = $sedang = $tinggi = 0;
    foreach ($skorTotal as $skor) {
        if ($skor >= 10) $tinggi++;
        elseif ($skor >= 4) $sedang++;
        else $rendah++;
    }

    $persenRendah = round(($rendah / $totalKeluarga) * 100, 1);
    $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
    $persenTinggi = round(($tinggi / $totalKeluarga) * 100, 1);

    $kategori = ['Rendah' => $rendah, 'Sedang' => $sedang, 'Tinggi' => $tinggi];
    arsort($kategori);
    $dominanKategori = array_key_first($kategori);

    /* ============================
       3️⃣ REKAP JUMLAH MEMILIKI
    ============================= */
    $indikator = [];
    foreach ($masterSarpras as $kode => $nama) {
        $jumlahPunya = $data->filter(function ($item) use ($kode) {
            return (int)$item->{"sarpraskerja_$kode"} !== 6;
        })->count();

        $indikator[] = [
            'nama'  => $nama,
            'nilai' => $jumlahPunya . ' Keluarga'
        ];
    }

    /* ============================
       4️⃣ REKAP DETAIL JAWABAN (1–6)
    ============================= */
    $rekapDetail = [];

    foreach ($masterSarpras as $kode => $namaSarpras) {

        $detail = [];
        $totalSarpras = 0;

        foreach ($jawabMaster as $kdJawab => $labelJawab) {
            $jumlah = $data->filter(function ($item) use ($kode, $kdJawab) {
                return (int)$item->{"sarpraskerja_$kode"} === (int)$kdJawab;
            })->count();

            $detail[$kdJawab] = [
                'jumlah' => $jumlah,
                'persen' => 0
            ];

            $totalSarpras += $jumlah;
        }

        foreach ($detail as $kd => $val) {
            $detail[$kd]['persen'] = $totalSarpras > 0
                ? round(($val['jumlah'] / $totalSarpras) * 100, 1)
                : 0;
        }

        $kodeDominan = collect($detail)->sortByDesc('jumlah')->keys()->first();

        $rekapDetail[] = [
            'nama'        => $namaSarpras,
            'total'       => $totalSarpras,
            'detail'      => $detail,
            'dominan'     => $jawabMaster[$kodeDominan] ?? '-',
            'kodeDominan' => $kodeDominan
        ];
    }

    /* ============================
       5️⃣ INTERPRETASI UMUM
    ============================= */
    $interpretasi = match ($dominanKategori) {
        'Tinggi' => 'Sebagian besar keluarga telah memiliki sarana dan prasarana kerja yang memadai.',
        'Sedang' => 'Kepemilikan sarana kerja cukup, namun belum merata.',
        default  => 'Sebagian besar keluarga belum memiliki sarana dan prasarana kerja yang memadai.',
    };

    /* ============================
       6️⃣ REKOMENDASI
    ============================= */
    $rekomendasi = [
        'Memprioritaskan bantuan sarana kerja bagi keluarga yang belum memiliki.',
        'Melakukan peremajaan alat kerja yang tidak layak pakai.',
        'Mengembangkan sarana kerja bersama berbasis kelompok.',
        'Mengintegrasikan data sarpras kerja ke perencanaan anggaran desa.'
    ];

    /* ============================
       7️⃣ GENERATE PDF
    ============================= */
    $pdf = Pdf::loadView('laporan.sarpraskerja', [
    'periode'        => Carbon::now()->translatedFormat('F Y'),
    'tanggal'        => Carbon::now()->translatedFormat('d F Y'),
    'totalKeluarga'  => $totalKeluarga,
    'skorRataRata'   => $skorRataRata,
    'rendah'         => $rendah,
    'sedang'         => $sedang,
    'tinggi'         => $tinggi,
    'persenRendah'   => $persenRendah,
    'persenSedang'   => $persenSedang,
    'persenTinggi'   => $persenTinggi,
    'dominan'        => $dominanKategori,
    'indikator'      => $indikator,
    'rekapDetail'    => $rekapDetail,
    'jawabMaster'    => $jawabMaster,
    'interpretasi'   => $interpretasi,
    'rekomendasi'    => $rekomendasi,
])->setPaper('a4', 'portrait');

return $pdf->stream('Laporan-Analisis-Sarpras-Kerja.pdf');

}

}