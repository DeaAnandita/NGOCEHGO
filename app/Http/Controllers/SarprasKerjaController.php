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

        // 🔹 Hitung skor per keluarga
        $skorTotal = [];
        foreach ($data as $row) {
            $skor = 0;
            for ($i = 1; $i <= 25; $i++) {
                $skor += (int) $row->{"sarpraskerja_$i"};
            }
            $skorTotal[] = $skor;
        }

        $skorRataRata = round(array_sum($skorTotal) / $totalKeluarga, 2);

        // 🔹 Kategori (0–25 rendah, 26–50 sedang, >50 tinggi)
        $rendah = $sedang = $tinggi = 0;
        foreach ($skorTotal as $skor) {
            if ($skor >= 50) $tinggi++;
            elseif ($skor >= 25) $sedang++;
            else $rendah++;
        }

        $persenRendah = round(($rendah / $totalKeluarga) * 100, 1);
        $persenSedang = round(($sedang / $totalKeluarga) * 100, 1);
        $persenTinggi = round(($tinggi / $totalKeluarga) * 100, 1);

        $kategori = ['Rendah' => $rendah, 'Sedang' => $sedang, 'Tinggi' => $tinggi];
        arsort($kategori);
        $dominan = array_key_first($kategori);

        // 🔹 Hitung rata-rata tiap indikator sarpras kerja
        $master = MasterSarprasKerja::pluck('sarpraskerja', 'kdsarpraskerja')->toArray();
        $indikator = [];
        foreach ($master as $kode => $nama) {
            $rata = round($data->avg("sarpraskerja_$kode"), 2);
            $indikator[] = [
                'kode'  => $kode,
                'nama'  => Str::replaceFirst('Ketersediaan ', '', $nama),
                'nilai' => $rata,
            ];
        }

        // 🔹 Interpretasi kondisi
        $interpretasi = match ($dominan) {
            'Tinggi' => 'Sebagian besar keluarga memiliki sarana dan prasarana kerja yang memadai.',
            'Sedang' => 'Keluarga memiliki sebagian sarana kerja, namun belum sepenuhnya optimal.',
            default  => 'Keluarga umumnya kekurangan sarana dan prasarana kerja yang mendukung produktivitas.',
        };

        $rekomendasi = [
            'Meningkatkan bantuan peralatan kerja bagi keluarga dengan kategori rendah.',
            'Mengembangkan pusat pelatihan kerja dan kewirausahaan berbasis desa.',
            'Mendorong kolaborasi antar keluarga untuk penggunaan sarana produksi bersama.',
            'Melakukan survei berkala untuk memantau perkembangan sarana kerja masyarakat.',
        ];

        // 🔹 Generate PDF
       // 🔹 Generate PDF
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
            'dominan'        => $dominan,
            'indikator'      => $indikator,
            'interpretasi'   => $interpretasi,
            'rekomendasi'    => $rekomendasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Aset-Ternak.pdf');
    }
}