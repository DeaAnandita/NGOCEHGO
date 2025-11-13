<?php

namespace App\Http\Controllers;

use App\Models\DataAsetTernak;
use App\Models\DataKeluarga;
use App\Models\MasterAsetTernak;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AsetTernakController extends Controller
{
    /** 
     * 🟢 INDEX - Menampilkan data aset ternak 
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $asetternaks = DataAsetTernak::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterAset = MasterAsetTernak::pluck('asetternak', 'kdasetternak')->toArray();

        return view('keluarga.asetternak.index', compact('asetternaks', 'masterAset', 'search', 'perPage'));
    }

    /** 
     * 🟢 CREATE - Form tambah data 
     */
    public function create()
    {
        $keluargas  = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.create', compact('keluargas', 'masterAset'));
    }

    /** 
     * 🟢 STORE - Simpan data baru 
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetternak,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", 0);
        }

        DataAsetTernak::create($data);

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil ditambahkan.');
    }

    /** 
     * 🟢 EDIT - Form ubah data 
     */
    public function edit($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $keluargas  = DataKeluarga::all();
        $masterAset = MasterAsetTernak::all();

        return view('keluarga.asetternak.edit', compact('asetternak', 'keluargas', 'masterAset'));
    }

    /** 
     * 🟢 UPDATE - Simpan perubahan data 
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $data = ['no_kk' => $request->no_kk];

        for ($i = 1; $i <= 24; $i++) {
            $data["asetternak_$i"] = $request->input("asetternak_$i", 0);
        }

        $asetternak->update($data);

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil diperbarui.');
    }

    /** 
     * 🟢 DESTROY - Hapus data 
     */
    public function destroy($no_kk)
    {
        $asetternak = DataAsetTernak::where('no_kk', $no_kk)->firstOrFail();
        $asetternak->delete();

        return redirect()->route('keluarga.asetternak.index')
            ->with('success', 'Data aset ternak berhasil dihapus.');
    }

    /** 
     * 🧾 EXPORT PDF - Laporan Analisis Aset Ternak (Format Baru)
     */
    public function exportPdf()
{
    $data = DataAsetTernak::all();
    $totalKeluarga = $data->count();

    if ($totalKeluarga === 0) {
        return back()->with('error', 'Tidak ada data aset ternak untuk dianalisis.');
    }

    // 🔹 Hitung skor total tiap keluarga
    $skorTotal = [];
    foreach ($data as $row) {
        $skor = 0;
        for ($i = 1; $i <= 24; $i++) {
            $skor += (int) $row->{"asetternak_$i"};
        }
        $skorTotal[] = $skor;
    }

    // 🔹 Ambil nilai tertinggi aktual di dataset sebagai acuan 100%
    $maxActual = max($skorTotal);
    if ($maxActual == 0) $maxActual = 1; // antisipasi pembagian 0

    // 🔹 Konversi ke skala 0–100
    $skorPersen = array_map(fn($v) => ($v / $maxActual) * 100, $skorTotal);
    $skorRataRata = round(array_sum($skorPersen) / $totalKeluarga, 2);

    // 🔹 Klasifikasi kategori berdasarkan skor persen
    $rendah = $sedang = $tinggi = 0;
    foreach ($skorPersen as $persen) {
        if ($persen >= 66.67) $tinggi++;
        elseif ($persen >= 33.33) $sedang++;
        else $rendah++;
    }

    $persenRendah = round(($rendah / $totalKeluarga) * 100, 1);
    $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
    $persenTinggi = round(($tinggi / $totalKeluarga) * 100, 1);

    $kategori = ['Rendah' => $rendah, 'Sedang' => $sedang, 'Tinggi' => $tinggi];
    arsort($kategori);
    $dominan = array_key_first($kategori);

    // 🔹 Hitung rata-rata tiap jenis ternak
    $master = MasterAsetTernak::pluck('asetternak', 'kdasetternak')->toArray();
    $indikator = [];
    foreach ($master as $kode => $nama) {
        $rata = round($data->avg("asetternak_$kode"), 2);
        $indikator[] = [
            'kode'  => $kode,
            'nama'  => Str::replaceFirst('Jumlah ', '', $nama),
            'nilai' => $rata,
        ];
    }

    // 🔹 Analisis naratif
    $interpretasi = match ($dominan) {
        'Tinggi' => 'Sebagian besar keluarga memiliki aset ternak produktif dalam jumlah besar.',
        'Sedang' => 'Kepemilikan ternak tergolong cukup, namun perlu peningkatan produktivitas dan kesehatan ternak.',
        default  => 'Kepemilikan ternak rendah, menunjukkan kerentanan ekonomi sektor peternakan.',
    };

    $rekomendasi = [
        'Bantuan bibit ternak unggul untuk keluarga dengan kategori rendah.',
        'Pelatihan penggemukan dan pengelolaan pakan ternak agar produktivitas meningkat.',
        'Pembentukan kelompok ternak mandiri untuk memperkuat ekonomi kolektif.',
        'Pendataan populasi ternak secara berkala untuk pemantauan potensi sektor peternakan.',
    ];

    // 🔹 Generate PDF
    $pdf = Pdf::loadView('laporan.asetternak', [
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
        'dominan'        => $dominan,
        'indikator'      => $indikator,
        'interpretasi'   => $interpretasi,
        'rekomendasi'    => $rekomendasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Aset-Ternak.pdf');
}

}
