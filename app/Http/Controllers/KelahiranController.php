<?php

namespace App\Http\Controllers;

use App\Models\DataKelahiran;
use App\Models\DataPenduduk;
use App\Models\MasterTempatPersalinan;
use App\Models\MasterJenisKelahiran;
use App\Models\MasterPertolonganPersalinan;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\MasterPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KelahiranController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = DataKelahiran::with(['penduduk', 'ibu', 'ayah', 'tempatPersalinan', 'jenisKelahiran', 'pertolonganPersalinan', 'user']);
    //     if ($request->has('search')) {
    //         $search = $request->search;
    //         $query->whereHas('penduduk', function ($q) use ($search) {
    //             $q->where('penduduk_namalengkap', 'like', "%{$search}%");
    //         });
    //     }

    //     //$kelahirans = $query->orderBy('created_at', 'desc')->paginate(5);
    //     return view('penduduk.kelahiran.index');
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $kelahirans = DataKelahiran::with(['penduduk', 'pertolonganPersalinan', 'jenisKelahiran', 'tempatPersalinan'])
            ->when($search, function ($query, $search) {
                $query->where('nama_bayi', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
            })
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('penduduk.kelahiran.index', compact('kelahirans', 'search', 'perPage'));
    }


    //  public function create()
    // {
    //     return view('penduduk.kelahiran.create', [
    //         'penduduks' => DataPenduduk::all(),
    //         'tempat_persalinans' => MasterTempatPersalinan::all(),
    //         'jenis_kelahirans' => MasterJenisKelahiran::all(),
    //         'pertolongan_persalinans' => MasterPertolonganPersalinan::all(),
    //         'provinsis' => MasterProvinsi::all(),
    //         'kabupatens' => MasterKabupaten::all(),
    //         'kecamatans' => MasterKecamatan::all(),
    //         'desas' => MasterDesa::all(),
    //     ]);
        
    // }

    public function create()
    {
        $tempat_persalinans = MasterTempatPersalinan::all();
        $jenis_kelahirans = MasterJenisKelahiran::all();
        $pertolongan_persalinans = MasterPertolonganPersalinan::all();
        $penduduks = DataPenduduk::all();
        $provinsis = MasterProvinsi::all();
        $kabupatens = MasterKabupaten::all();
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();

        return view('penduduk.kelahiran.create', compact(
            'tempat_persalinans', 
            'jenis_kelahirans', 
            'pertolongan_persalinans', 
            'penduduks', 
            'provinsis', 'kabupatens', 'kecamatans', 'desas'
        ));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_kelahiran,nik|exists:data_penduduk,nik',
            'kdtempatpersalinan' => 'nullable|integer|exists:master_tempatpersalinan,kdtempatpersalinan',
            'kdjeniskelahiran' => 'nullable|integer|exists:master_jeniskelahiran,kdjeniskelahiran',
            'kdpertolonganpersalinan' => 'nullable|integer|exists:master_pertolonganpersalinan,kdpertolonganpersalinan',
            'kelahiran_jamkelahiran' => 'nullable|date_format:H:i',
            'kelahiran_kelahiranke' => 'nullable|integer|min:1',
            'kelahiran_berat' => 'nullable|integer|min:0',
            'kelahiran_panjang' => 'nullable|integer|min:0',
            'kelahiran_nikibu' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_nikayah' => 'nullable|string|size:16|exists:data_penduduk,nik',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        // $data['created_by'] = Auth::id();
        DataKelahiran::create($data);

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil ditambahkan.');
    }

    public function edit($kdkelahiran)
    {
        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        return view('penduduk.kelahiran.edit', [
            'kelahiran' => $kelahiran,
            'penduduks' => DataPenduduk::all(),
            'tempat_persalinans' => MasterTempatPersalinan::all(),
            'jenis_kelahirans' => MasterJenisKelahiran::all(),
            'pertolongan_persalinans' => MasterPertolonganPersalinan::all(),
        ]);
    }

    public function update(Request $request, $kdkelahiran)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|exists:data_penduduk,nik',
            'kdtempatpersalinan' => 'nullable|integer|exists:master_tempatpersalinan,kdtempatpersalinan',
            'kdjeniskelahiran' => 'nullable|integer|exists:master_jeniskelahiran,kdjeniskelahiran',
            'kdpertolonganpersalinan' => 'nullable|integer|exists:master_pertolonganpersalinan,kdpertolonganpersalinan',
            'kelahiran_jamkelahiran' => 'nullable',
            'kelahiran_kelahiranke' => 'nullable|integer|min:1',
            'kelahiran_berat' => 'nullable|integer|min:0',
            'kelahiran_panjang' => 'nullable|integer|min:0',
            'kelahiran_nikibu' => 'nullable|string|size:16|exists:data_penduduk,nik',
            'kelahiran_nikayah' => 'nullable|string|size:16|exists:data_penduduk,nik',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        $kelahiran->update($request->all());

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil diperbarui.');
    }

    public function destroy($kdkelahiran)
    {
        $kelahiran = DataKelahiran::findOrFail($kdkelahiran);
        $kelahiran->delete();

        return redirect()->route('penduduk.kelahiran.index')->with('success', 'Data kelahiran berhasil dihapus.');
    }

    public function getPendudukDetails($nik)
    {
        $penduduk = DataPenduduk::with(['pekerjaan', 'provinsi', 'kabupaten', 'kecamatan', 'desa'])->findOrFail($nik);
        return response()->json([
            'nik' => $penduduk->nik,
            'nama' => $penduduk->penduduk_namalengkap,
            'tanggal_lahir' => $penduduk->penduduk_tanggallahir ? \Carbon\Carbon::parse($penduduk->penduduk_tanggallahir)->format('d/m/Y') : '-',
            'pekerjaan' => $penduduk->pekerjaan->pekerjaan ?? '-',
            'kewarganegaraan' => $penduduk->penduduk_kewarganegaraan ?? '-',
        ]);
    }
        /**
         * Export laporan analisis data kelahiran ke PDF
         */
        public function exportPdf()
    {
        $totalKelahiran = DB::table('data_kelahiran')->count();

        if ($totalKelahiran === 0) {
            return back()->with('error', 'Tidak ada data kelahiran untuk dianalisis.');
        }

        // 1. Statistik Jenis Kelamin
        $laki = DB::table('data_kelahiran')
            ->join('data_penduduk', 'data_kelahiran.nik', '=', 'data_penduduk.nik')
            ->where('data_penduduk.kdjeniskelamin', 1) // 1 = Laki-laki
            ->count();

        $perempuan = $totalKelahiran - $laki;

        $persenLaki = round(($laki / $totalKelahiran) * 100, 1);
        $persenPerempuan = round(($perempuan / $totalKelahiran) * 100, 1);

        // 2. Tempat Persalinan
        $tempatStat = DB::table('master_tempatpersalinan')
            ->leftJoin('data_kelahiran', 'master_tempatpersalinan.kdtempatpersalinan', '=', 'data_kelahiran.kdtempatpersalinan')
            ->select('master_tempatpersalinan.tempatpersalinan as nama', DB::raw('COALESCE(COUNT(data_kelahiran.nik), 0) as jumlah'))
            ->groupBy('master_tempatpersalinan.kdtempatpersalinan', 'master_tempatpersalinan.tempatpersalinan')
            ->get()
            ->map(fn($row) => [
                'nama'   => $row->nama,
                'jumlah' => $row->jumlah,
                'persen' => round(($row->jumlah / $totalKelahiran) * 100, 1),
            ])->toArray();

        // 3. Jenis Kelahiran
        $jenisStat = DB::table('master_jeniskelahiran')
            ->leftJoin('data_kelahiran', 'master_jeniskelahiran.kdjeniskelahiran', '=', 'data_kelahiran.kdjeniskelahiran')
            ->select('master_jeniskelahiran.jeniskelahiran as nama', DB::raw('COALESCE(COUNT(data_kelahiran.nik), 0) as jumlah'))
            ->groupBy('master_jeniskelahiran.kdjeniskelahiran', 'master_jeniskelahiran.jeniskelahiran')
            ->get()
            ->map(fn($row) => [
                'nama'   => $row->nama,
                'jumlah' => $row->jumlah,
                'persen' => round(($row->jumlah / $totalKelahiran) * 100, 1),
            ])->toArray();

        // 4. Pertolongan Persalinan
        $tolongStat = DB::table('master_pertolonganpersalinan')
            ->leftJoin('data_kelahiran', 'master_pertolonganpersalinan.kdpertolonganpersalinan', '=', 'data_kelahiran.kdpertolonganpersalinan')
            ->select('master_pertolonganpersalinan.pertolonganpersalinan as nama', DB::raw('COALESCE(COUNT(data_kelahiran.nik), 0) as jumlah'))
            ->groupBy('master_pertolonganpersalinan.kdpertolonganpersalinan', 'master_pertolonganpersalinan.pertolonganpersalinan')
            ->get()
            ->map(fn($row) => [
                'nama'   => $row->nama,
                'jumlah' => $row->jumlah,
                'persen' => round(($row->jumlah / $totalKelahiran) * 100, 1),
            ])->toArray();

        // 5. Statistik Lengkap Berat & Panjang Lahir + Persentase
        $statsLahir = DB::table('data_kelahiran')
            ->selectRaw("
                AVG(kelahiran_berat) as rata_berat,
                AVG(kelahiran_panjang) as rata_panjang,

                -- Berat Lahir
                COUNT(CASE WHEN kelahiran_berat < 2500 AND kelahiran_berat IS NOT NULL THEN 1 END) as jumlah_bblr,
                COUNT(CASE WHEN kelahiran_berat >= 2500 AND kelahiran_berat IS NOT NULL THEN 1 END) as jumlah_normal_berat,
                COUNT(CASE WHEN kelahiran_berat IS NOT NULL THEN 1 END) as total_berat_terisi,

                -- Panjang Lahir
                COUNT(CASE WHEN kelahiran_panjang < 48 AND kelahiran_panjang IS NOT NULL THEN 1 END) as jumlah_pendek,
                COUNT(CASE WHEN kelahiran_panjang >= 48 AND kelahiran_panjang IS NOT NULL THEN 1 END) as jumlah_normal_panjang,
                COUNT(CASE WHEN kelahiran_panjang IS NOT NULL THEN 1 END) as total_panjang_terisi
            ")
            ->first();

        // Hitung nilai
        $rataBerat            = $statsLahir->rata_berat ? round($statsLahir->rata_berat, 1) : 0;
        $rataPanjang          = $statsLahir->rata_panjang ? round($statsLahir->rata_panjang, 1) : 0;

        $jumlahBBLR           = $statsLahir->jumlah_bblr ?? 0;
        $jumlahNormalBerat    = $statsLahir->jumlah_normal_berat ?? 0;
        $jumlahPendek         = $statsLahir->jumlah_pendek ?? 0;
        $jumlahNormalPanjang  = $statsLahir->jumlah_normal_panjang ?? 0;

        $totalBeratTerisi     = $statsLahir->total_berat_terisi ?? 0;
        $totalPanjangTerisi   = $statsLahir->total_panjang_terisi ?? 0;

        // Hitung persentase (berdasarkan total kelahiran tercatat)
        $persenBBLR           = $totalKelahiran > 0 ? round(($jumlahBBLR / $totalKelahiran) * 100, 1) : 0;
        $persenNormalBerat    = $totalKelahiran > 0 ? round(($jumlahNormalBerat / $totalKelahiran) * 100, 1) : 0;
        $persenPendek         = $totalKelahiran > 0 ? round(($jumlahPendek / $totalKelahiran) * 100, 1) : 0;
        $persenNormalPanjang  = $totalKelahiran > 0 ? round(($jumlahNormalPanjang / $totalKelahiran) * 100, 1) : 0;

        // Persentase kelengkapan data
        $persenDataBeratTerisi    = $totalKelahiran > 0 ? round(($totalBeratTerisi / $totalKelahiran) * 100, 1) : 0;
        $persenDataPanjangTerisi  = $totalKelahiran > 0 ? round(($totalPanjangTerisi / $totalKelahiran) * 100, 1) : 0;

        // 6. Urutan Kelahiran (ke-)
        $kelahiranKeStat = DB::table('data_kelahiran')
            ->select('kelahiran_kelahiranke', DB::raw('count(*) as jumlah'))
            ->groupBy('kelahiran_kelahiranke')
            ->orderBy('kelahiran_kelahiranke')
            ->get()
            ->map(function ($row) use ($totalKelahiran) {
                $ke = $row->kelahiran_kelahiranke ?? 'Tidak diisi';
                return [
                    'ke'     => $ke,
                    'jumlah' => $row->jumlah,
                    'persen' => round(($row->jumlah / $totalKelahiran) * 100, 1),
                ];
            })->toArray();

        // 7. Distribusi Kelahiran per Wilayah → dari data_penduduk (kdprovinsi, kdkabupaten, dll)
        $kelahiranPerWilayah = DB::table('data_kelahiran')
            ->join('data_penduduk', 'data_kelahiran.nik', '=', 'data_penduduk.nik')
            ->leftJoin('master_desa', 'data_penduduk.kddesa', '=', 'master_desa.kddesa')
            ->leftJoin('master_kecamatan', 'data_penduduk.kdkecamatan', '=', 'master_kecamatan.kdkecamatan')
            ->selectRaw("
                COALESCE(
                    master_desa.desa,
                    master_kecamatan.kecamatan,
                    'Wilayah Tidak Tercantum'
                ) as nama_wilayah,
                COUNT(*) as jumlah
            ")
            ->groupBy('data_penduduk.kddesa', 'master_desa.desa', 'master_kecamatan.kecamatan')
            ->orderByDesc('jumlah')
            ->get()
            ->map(function ($row) use ($totalKelahiran) {
                return [
                    'wilayah' => $row->nama_wilayah,
                    'jumlah'  => $row->jumlah,
                    'persen'  => round(($row->jumlah / $totalKelahiran) * 100, 1),
                ];
            })->toArray();

        // Pastikan periode dan tanggal
        $periode = Carbon::now()->translatedFormat('F Y');
        $tanggal = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('laporan.kelahiran', [
    'periode'                  => $periode,
    'tanggal'                  => $tanggal,
    'totalKelahiran'           => $totalKelahiran,
    'laki'                     => $laki,
    'perempuan'                => $perempuan,
    'persenLaki'               => $persenLaki,
    'persenPerempuan'          => $persenPerempuan,
    'tempatStat'               => $tempatStat,
    'jenisStat'                => $jenisStat,
    'tolongStat'               => $tolongStat,

    // === Tambahkan semua variabel baru ini ===
    'rataBerat'                => $rataBerat,
    'rataPanjang'              => $rataPanjang,
    'jumlahBBLR'               => $jumlahBBLR,
    'persenBBLR'               => $persenBBLR,
    'jumlahNormalBerat'        => $jumlahNormalBerat,
    'persenNormalBerat'        => $persenNormalBerat,
    'jumlahPendek'             => $jumlahPendek,
    'persenPendek'             => $persenPendek,
    'jumlahNormalPanjang'      => $jumlahNormalPanjang,
    'persenNormalPanjang'      => $persenNormalPanjang,
    'persenDataBeratTerisi'    => $persenDataBeratTerisi,
    'persenDataPanjangTerisi'  => $persenDataPanjangTerisi,

    'kelahiranKeStat'          => $kelahiranKeStat,
    'kelahiranPerWilayah'      => $kelahiranPerWilayah,
])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Kelahiran-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}

    //  public function getKabupatens($kdprovinsi)
    // {
    //     $kabupaten = DB::table('master_kabupaten')
    //                     ->where('kdprovinsi', $kdprovinsi)
    //                     ->select('kdkabupaten', 'kabupaten')
    //                     ->get();
    //     return response()->json($kabupaten);
    // }

    // public function getKecamatans($kdkabupaten)
    // {
    //     $kecamatan = DB::table('master_kecamatan')
    //                     ->where('kdkabupaten', $kdkabupaten)
    //                     ->select('kdkecamatan', 'kecamatan')
    //                     ->get();
    //     return response()->json($kecamatan);
    // }

    // public function getDesas($kdkecamatan)
    // {
    //     $desa = DB::table('master_desa')
    //             ->where('kdkecamatan', $kdkecamatan)
    //             ->select('kddesa', 'desa')
    //             ->get();
    //     return response()->json($desa);
    // }
