<?php

namespace App\Http\Controllers;

use App\Models\DataAsetPerikanan;
use App\Models\DataKeluarga;
use App\Models\MasterAsetPerikanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AsetPerikananController extends Controller
{
    /** 
     * 🟢 INDEX - Menampilkan daftar aset perikanan 
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $asetperikanans = DataAsetPerikanan::with('keluarga')
            ->when($search, function ($query, $search) {
                $query->where('no_kk', 'like', "%{$search}%")
                    ->orWhereHas('keluarga', function ($q) use ($search) {
                        $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('no_kk', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterAset = MasterAsetPerikanan::pluck('asetperikanan', 'kdasetperikanan')->toArray();

        return view('keluarga.asetperikanan.index', compact('asetperikanans', 'masterAset', 'search', 'perPage'));
    }

    /** 
     * 🟢 CREATE - Form tambah data baru 
     */
    public function create()
    {
        $keluargas  = DataKeluarga::all();
        $masterAset = MasterAsetPerikanan::all();

        return view('keluarga.asetperikanan.create', compact('keluargas', 'masterAset'));
    }

    /** 
     * 🟢 STORE - Simpan data baru 
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:data_asetperikanan,no_kk|exists:data_keluarga,no_kk',
        ]);

        $data = ['no_kk' => $request->no_kk];
        for ($i = 1; $i <= 6; $i++) {
            $data["asetperikanan_$i"] = $request->input("asetperikanan_$i", 0);
        }

        DataAsetPerikanan::create($data);

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil ditambahkan.');
    }

    /** 
     * 🟢 EDIT - Form ubah data 
     */
    public function edit($no_kk)
    {
        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();
        $keluargas     = DataKeluarga::all();
        $masterAset    = MasterAsetPerikanan::all();

        return view('keluarga.asetperikanan.edit', compact('asetperikanan', 'keluargas', 'masterAset'));
    }

    /** 
     * 🟢 UPDATE - Simpan perubahan data 
     */
    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|exists:data_keluarga,no_kk',
        ]);

        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();
        $data = ['no_kk' => $request->no_kk];

        for ($i = 1; $i <= 6; $i++) {
            $data["asetperikanan_$i"] = $request->input("asetperikanan_$i", 0);
        }

        $asetperikanan->update($data);

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil diperbarui.');
    }

    /** 
     * 🟢 DESTROY - Hapus data 
     */
    public function destroy($no_kk)
    {
        $asetperikanan = DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail();
        $asetperikanan->delete();

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil dihapus.');
    }

    /** 
     * 🧾 EXPORT PDF - Laporan Analisis Aset Perikanan (Format Laporan Sejahtera)
     */
    public function exportPdf()
    {
        $data = DataAsetPerikanan::all();
        $totalKeluarga = $data->count();

        if ($totalKeluarga === 0) {
            return back()->with('error', 'Tidak ada data aset perikanan untuk dianalisis.');
        }

        // 🔹 Hitung skor per keluarga
        $skorTotal = [];
        foreach ($data as $row) {
            $skor = 0;
            for ($i = 1; $i <= 6; $i++) {
                $skor += (int) $row->{"asetperikanan_$i"};
            }
            $skorTotal[] = $skor;
        }

        $skorRataRata = round(array_sum($skorTotal) / $totalKeluarga, 2);

        // 🔹 Kategori kesejahteraan aset
        $rendah = $sedang = $tinggi = 0;
        foreach ($skorTotal as $skor) {
            if ($skor >= 15) $tinggi++;
            elseif ($skor >= 8) $sedang++;
            else $rendah++;
        }

        $persenRendah = round(($rendah / $totalKeluarga) * 100, 1);
        $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
        $persenTinggi = round(($tinggi / $totalKeluarga) * 100, 1);

        // 🔹 Tentukan kategori dominan
        $kategori = ['Rendah' => $rendah, 'Sedang' => $sedang, 'Tinggi' => $tinggi];
        arsort($kategori);
        $dominan = array_key_first($kategori);

        // 🔹 Total rata-rata tiap jenis aset
        $master = MasterAsetPerikanan::pluck('asetperikanan', 'kdasetperikanan')->toArray();
        $indikator = [];
        foreach ($master as $kode => $nama) {
            $rata = round($data->avg("asetperikanan_$kode"), 2);
            $indikator[] = [
                'kode' => $kode,
                'nama' => Str::replaceFirst('Jumlah ', '', $nama),
                'nilai' => $rata,
            ];
        }

        // 🔹 Analisis ringkas
        $interpretasi = match ($dominan) {
            'Tinggi' => 'Sebagian besar keluarga memiliki aset perikanan produktif dalam jumlah besar.',
            'Sedang' => 'Kepemilikan aset tergolong cukup, namun perlu peningkatan kapasitas produksi.',
            default  => 'Kepemilikan aset rendah, masyarakat masih rentan dalam sektor perikanan.',
        };

        $rekomendasi = [
            'Pelatihan teknis budidaya dan tangkap ikan bagi keluarga nelayan kecil.',
            'Bantuan sarana perikanan (keramba, jaring, alat tangkap modern) bagi kelompok rendah.',
            'Pembentukan koperasi nelayan untuk memperkuat akses pasar dan modal.',
            'Pendataan aset perikanan secara berkala untuk pemantauan potensi desa.',
        ];

        // 🔹 Generate PDF
        $pdf = Pdf::loadView('laporan.asetperikanan', [
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

        return $pdf->stream('Laporan-Analisis-Aset-Perikanan.pdf');
    }
}
