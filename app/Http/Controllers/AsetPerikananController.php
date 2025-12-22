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
    /** 🟢 INDEX */
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

        return view('keluarga.asetperikanan.index', compact(
            'asetperikanans',
            'masterAset',
            'search',
            'perPage'
        ));
    }

    /** 🟢 CREATE */
    public function create()
    {
        return view('keluarga.asetperikanan.create', [
            'keluargas' => DataKeluarga::all(),
            'masterAset' => MasterAsetPerikanan::all()
        ]);
    }

    /** 🟢 STORE */
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

    /** 🟢 EDIT */
    public function edit($no_kk)
    {
        return view('keluarga.asetperikanan.edit', [
            'asetperikanan' => DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail(),
            'keluargas' => DataKeluarga::all(),
            'masterAset' => MasterAsetPerikanan::all(),
        ]);
    }

    /** 🟢 UPDATE */
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

    /** 🟢 DESTROY */
    public function destroy($no_kk)
    {
        DataAsetPerikanan::where('no_kk', $no_kk)->firstOrFail()->delete();

        return redirect()->route('keluarga.asetperikanan.index')
            ->with('success', 'Data aset perikanan berhasil dihapus.');
    }

    /** 🧾 EXPORT PDF (SAMA DENGAN ASET TERNAK) */
    public function exportPdf()
    {
        $data = DataAsetPerikanan::all();
        $totalKeluarga = $data->count();

        if ($totalKeluarga === 0) {
            return back()->with('error', 'Tidak ada data aset perikanan.');
        }

        $keluargaPunyaPerikanan = $data->filter(function ($row) {
            for ($i = 1; $i <= 6; $i++) {
                if ($row->{"asetperikanan_$i"} > 0) return true;
            }
            return false;
        })->count();

        $keluargaTanpaPerikanan = $totalKeluarga - $keluargaPunyaPerikanan;

        $master = MasterAsetPerikanan::pluck('asetperikanan', 'kdasetperikanan')->toArray();
        $indikator = [];

        foreach ($master as $kode => $nama) {
            $indikator[] = [
                'kode' => $kode,
                'nama' => Str::replaceFirst('Jumlah ', '', $nama),
                'jumlah_keluarga' => DataAsetPerikanan::where("asetperikanan_$kode", '>', 0)->count(),
                'total_perikanan' => DataAsetPerikanan::sum("asetperikanan_$kode"),
            ];
        }

        $interpretasi =
            $keluargaTanpaPerikanan > $totalKeluarga * 0.6
                ? 'Mayoritas keluarga belum memiliki aset perikanan produktif.'
                : ($keluargaPunyaPerikanan > $totalKeluarga * 0.6
                    ? 'Sebagian besar keluarga telah memanfaatkan aset perikanan.'
                    : 'Kepemilikan aset perikanan belum merata.');

        $rekomendasi = [
            'Pemberian bantuan sarana perikanan bagi keluarga tanpa aset.',
            'Pengembangan usaha perikanan rumah tangga.',
            'Pelatihan budidaya dan penangkapan ikan.',
            'Pendataan aset perikanan terintegrasi.'
        ];

        return Pdf::loadView('laporan.asetperikanan', [
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'totalKeluarga' => $totalKeluarga,
            'keluargaPunyaPerikanan' => $keluargaPunyaPerikanan,
            'keluargaTanpaPerikanan' => $keluargaTanpaPerikanan,
            'indikator' => $indikator,
            'interpretasi' => $interpretasi,
            'rekomendasi' => $rekomendasi,
        ])->setPaper('a4')->stream('Laporan-Aset-Perikanan.pdf');
    }
}
