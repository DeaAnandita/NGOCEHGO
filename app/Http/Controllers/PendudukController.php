<?php

namespace App\Http\Controllers;

use App\Models\DataPenduduk;
use App\Models\DataKeluarga;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterJenisKelamin;
use App\Models\MasterAgama;
use App\Models\MasterHubunganKeluarga;
use App\Models\MasterHubunganKepalaKeluarga;
use App\Models\MasterStatusKawin;
use App\Models\MasterAktaNikah;
use App\Models\MasterTercantumDalamKK;
use App\Models\MasterStatusTinggal;
use App\Models\MasterKartuIdentitas;
use App\Models\MasterPekerjaan;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10); // default 50

        // Query untuk mendapatkan distinct no_kk (sbb: hanya pilih kolom no_kk)
        $kkQuery = \App\Models\DataPenduduk::select('no_kk')
            ->when($search, function($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                ->orWhere('penduduk_namalengkap', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%");
            })
            ->distinct()
            ->orderBy('no_kk');

        // Paginate hasil distinct KK
        $kkPaginated = $kkQuery->paginate($perPage)->appends([
            'search' => $search,
            'per_page' => $perPage,
        ]);

        // Ambil semua penduduk yang masuk ke page ini (semua NIK untuk no_kk yang ada)
        $noKks = $kkPaginated->pluck('no_kk')->toArray();

        // Eager load relasi yang diperlukan (adjust relasi sesuai modelmu)
        $penduduksForPage = \App\Models\DataPenduduk::with(['jeniskelamin','agama','hubungankeluarga'])
            ->whereIn('no_kk', $noKks)
            ->get()
            ->groupBy('no_kk');

        // Kirim ke view
        return view('penduduk.index', [
            'kkPaginated' => $kkPaginated,
            'penduduksGrouped' => $penduduksForPage,
            'perPage' => $perPage,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penduduk.create', [
            'keluargas' => DataKeluarga::all(),
            'mutasi_masuks' => MasterMutasiMasuk::all(),
            'jenis_kelamins' => MasterJenisKelamin::all(),
            'agamas' => MasterAgama::all(),
            'hubungan_keluargas' => MasterHubunganKeluarga::all(),
            'hubungan_kepala_keluargas' => MasterHubunganKepalaKeluarga::all(),
            'status_kawins' => MasterStatusKawin::all(),
            'akta_nikahs' => MasterAktaNikah::all(),
            'tercantum_dalam_kks' => MasterTercantumDalamKK::all(),
            'status_tinggals' => MasterStatusTinggal::all(),
            'kartu_identitass' => MasterKartuIdentitas::all(),
            'pekerjaans' => MasterPekerjaan::all(),
            'provinsis' => MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get(), // Hanya provinsi seperti keluarga
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_penduduk,nik',
            'no_kk' => 'required|string|size:16|exists:data_keluarga,no_kk',
            'penduduk_nourutkk' => 'required|string|max:4',
            'penduduk_goldarah' => 'required|string|max:2',
            'penduduk_noaktalahir' => 'required|string|max:255',
            'penduduk_namalengkap' => 'required|string|max:255',
            'penduduk_tempatlahir' => 'required|string|max:255',
            'penduduk_tanggallahir' => 'required|date',
            'kdmutasimasuk' => 'nullable|integer|exists:master_mutasimasuk,kdmutasimasuk',
            'penduduk_tanggalmutasi' => 'required|date',
            'penduduk_kewarganegaraan' => 'required|string|max:255',
            'kdjeniskelamin' => 'nullable|integer|exists:master_jeniskelamin,kdjeniskelamin',
            'kdagama' => 'nullable|integer|exists:master_agama,kdagama',
            'kdhubungankeluarga' => 'nullable|integer|exists:master_hubungankeluarga,kdhubungankeluarga',
            'kdhubungankepalakeluarga' => 'nullable|integer|exists:master_hubungankepalakeluarga,kdhubungankepalakeluarga',
            'kdstatuskawin' => 'nullable|integer|exists:master_statuskawin,kdstatuskawin',
            'kdaktanikah' => 'nullable|integer|exists:master_aktanikah,kdaktanikah',
            'kdtercantumdalamkk' => 'nullable|integer|exists:master_tercantumdalamkk,kdtercantumdalamkk',
            'kdstatustinggal' => 'nullable|integer|exists:master_statustinggal,kdstatustinggal',
            'kdkartuidentitas' => 'nullable|integer|exists:master_kartuidentitas,kdkartuidentitas',
            'kdpekerjaan' => 'nullable|integer|exists:master_pekerjaan,kdpekerjaan',
            'penduduk_namaayah' => 'nullable|string|max:255',
            'penduduk_namaibu' => 'nullable|string|max:255',
            'penduduk_namatempatbekerja' => 'nullable|string|max:255',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi', // Nullable seperti keluarga
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataPenduduk::create($request->all());

        return redirect()->route('dasar-penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $penduduk = DataPenduduk::findOrFail($nik);
        return view('penduduk.edit', [
            'penduduk' => $penduduk,
            'keluargas' => DataKeluarga::all(),
            'mutasi_masuks' => MasterMutasiMasuk::all(),
            'jenis_kelamins' => MasterJenisKelamin::all(),
            'agamas' => MasterAgama::all(),
            'hubungan_keluargas' => MasterHubunganKeluarga::all(),
            'hubungan_kepala_keluargas' => MasterHubunganKepalaKeluarga::all(),
            'status_kawins' => MasterStatusKawin::all(),
            'akta_nikahs' => MasterAktaNikah::all(),
            'tercantum_dalam_kks' => MasterTercantumDalamKK::all(),
            'status_tinggals' => MasterStatusTinggal::all(),
            'kartu_identitass' => MasterKartuIdentitas::all(),
            'pekerjaans' => MasterPekerjaan::all(),
            'provinsis' => MasterProvinsi::select('kdprovinsi', 'provinsi')->orderBy('provinsi')->get(),
            'kabupatens' => $penduduk->kdprovinsi ? MasterKabupaten::where('kdprovinsi', $penduduk->kdprovinsi)->orderBy('kabupaten')->get() : collect(),
            'kecamatans' => $penduduk->kdkabupaten ? MasterKecamatan::where('kdkabupaten', $penduduk->kdkabupaten)->orderBy('kecamatan')->get() : collect(),
            'desas' => $penduduk->kdkecamatan ? MasterDesa::where('kdkecamatan', $penduduk->kdkecamatan)->orderBy('desa')->get() : collect(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16',
            'no_kk' => 'required|string|size:16|exists:data_keluarga,no_kk',
            'penduduk_nourutkk' => 'required|string|max:4',
            'penduduk_goldarah' => 'required|string|max:2',
            'penduduk_noaktalahir' => 'required|string|max:255',
            'penduduk_namalengkap' => 'required|string|max:255',
            'penduduk_tempatlahir' => 'required|string|max:255',
            'penduduk_tanggallahir' => 'required|date',
            'kdmutasimasuk' => 'nullable|integer|exists:master_mutasimasuk,kdmutasimasuk',
            'penduduk_tanggalmutasi' => 'required|date',
            'penduduk_kewarganegaraan' => 'required|string|max:255',
            'kdjeniskelamin' => 'nullable|integer|exists:master_jeniskelamin,kdjeniskelamin',
            'kdagama' => 'nullable|integer|exists:master_agama,kdagama',
            'kdhubungankeluarga' => 'nullable|integer|exists:master_hubungankeluarga,kdhubungankeluarga',
            'kdhubungankepalakeluarga' => 'nullable|integer|exists:master_hubungankepalakeluarga,kdhubungankepalakeluarga',
            'kdstatuskawin' => 'nullable|integer|exists:master_statuskawin,kdstatuskawin',
            'kdaktanikah' => 'nullable|integer|exists:master_aktanikah,kdaktanikah',
            'kdtercantumdalamkk' => 'nullable|integer|exists:master_tercantumdalamkk,kdtercantumdalamkk',
            'kdstatustinggal' => 'nullable|integer|exists:master_statustinggal,kdstatustinggal',
            'kdkartuidentitas' => 'nullable|integer|exists:master_kartuidentitas,kdkartuidentitas',
            'kdpekerjaan' => 'nullable|integer|exists:master_pekerjaan,kdpekerjaan',
            'penduduk_namaayah' => 'nullable|string|max:255',
            'penduduk_namaibu' => 'nullable|string|max:255',
            'penduduk_namatempatbekerja' => 'nullable|string|max:255',
            'kdprovinsi' => 'nullable|integer|exists:master_provinsi,kdprovinsi',
            'kdkabupaten' => 'nullable|integer|exists:master_kabupaten,kdkabupaten',
            'kdkecamatan' => 'nullable|integer|exists:master_kecamatan,kdkecamatan',
            'kddesa' => 'nullable|integer|exists:master_desa,kddesa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $penduduk = DataPenduduk::findOrFail($nik);
        $penduduk->update($request->all());

        return redirect()->route('dasar-penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $penduduk = DataPenduduk::findOrFail($nik);
        $penduduk->delete();

        return redirect()->route('dasar-penduduk.index')->with('success', 'Data penduduk berhasil dihapus.');
    }

    /**
     * Export Laporan Lengkap Penduduk & Intervensi Kemiskinan ke PDF
     */
    public function exportPdf()
    {
        $totalPenduduk = DB::table('data_penduduk')->count();
        if ($totalPenduduk === 0) {
            return back()->with('error', 'Tidak ada data penduduk untuk dianalisis.');
        }

        // ==========================
        // Jenis Kelamin
        // ==========================
        $laki = DB::table('data_penduduk')->where('kdjeniskelamin', 1)->count();
        $perempuan = DB::table('data_penduduk')->where('kdjeniskelamin', 2)->count();
        $persenLaki = round(($laki / $totalPenduduk) * 100, 1);
        $persenPerempuan = round(($perempuan / $totalPenduduk) * 100, 1);

        // ==========================
        // Pekerjaan
        // ==========================
        $tidakBekerja = DB::table('data_penduduk')->where('kdpekerjaan', 1)->count();
        $lakiTidakBekerja = DB::table('data_penduduk')
            ->where('kdpekerjaan', 1)
            ->where('kdjeniskelamin', 1)
            ->count();
        $perempuanTidakBekerja = DB::table('data_penduduk')
            ->where('kdpekerjaan', 1)
            ->where('kdjeniskelamin', 2)
            ->count();
        $persenTidakBekerja = round(($tidakBekerja / $totalPenduduk) * 100, 1);
        $persenLakiTidakBekerja = round(($lakiTidakBekerja / $laki) * 100, 1);
        $persenPerempuanTidakBekerja = round(($perempuanTidakBekerja / $perempuan) * 100, 1);

        // ==========================
        // Detail Alasan Tidak Bekerja (sesuai master_pekerjaan dari rancangan)
        // ==========================

        // Kode sesuai rancangan: 
        // 3 → PELAJAR/MAHASISWA (masih sekolah)
        // 4 → PENSIUNAN
        // 2 → MENGURUS RUMAH TANGGA
        $kdSekolah     = 3; // PELAJAR/MAHASISWA
        $kdPensiunan   = 4; // PENSIUNAN
        $kdRumahTangga = 2; // MENGURUS RUMAH TANGGA

        // Hitung per jenis kelamin untuk masing-masing alasan
        $sekolahLaki = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdSekolah)
            ->where('kdjeniskelamin', 1)
            ->count();

        $sekolahPerempuan = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdSekolah)
            ->where('kdjeniskelamin', 2)
            ->count();

        $pensiunanLaki = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdPensiunan)
            ->where('kdjeniskelamin', 1)
            ->count();

        $pensiunanPerempuan = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdPensiunan)
            ->where('kdjeniskelamin', 2)
            ->count();

        $rumahTanggaLaki = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdRumahTangga)
            ->where('kdjeniskelamin', 1)
            ->count();

        $rumahTanggaPerempuan = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdRumahTangga)
            ->where('kdjeniskelamin', 2)
            ->count();

        // Total masing-masing alasan
        $totalSekolah     = $sekolahLaki + $sekolahPerempuan;
        $totalPensiunan   = $pensiunanLaki + $pensiunanPerempuan;
        $totalRumahTangga = $rumahTanggaLaki + $rumahTanggaPerempuan;

        // ==========================
        // Breakdown Tidak Bekerja Murni & Lainnya
        // ==========================

        // Kode 1 = BELUM/TIDAK BEKERJA (pengangguran murni, bukan karena alasan lain)
        $kdBelumBekerja = 1;

        $belumBekerjaLaki = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdBelumBekerja)
            ->where('kdjeniskelamin', 1)
            ->count();

        $belumBekerjaPerempuan = DB::table('data_penduduk')
            ->where('kdpekerjaan', $kdBelumBekerja)
            ->where('kdjeniskelamin', 2)
            ->count();

        $totalBelumBekerja = $belumBekerjaLaki + $belumBekerjaPerempuan;

        // Hitung "Lainnya" = total tidak bekerja (kode 1) dikurangi 3 alasan utama di atas
        // Catatan: kode 1 adalah kategori umum "BELUM/TIDAK BEKERJA" yang mencakup pengangguran murni
        // Sedangkan kode 2,3,4 adalah alasan spesifik yang juga termasuk tidak bekerja
        // Di beberapa sistem, kode 1 digunakan untuk pengangguran murni, dan 2-4 terpisah.
        // Karena di master_pekerjaan rancanganmu kode 1 adalah BELUM/TIDAK BEKERJA (umum),
        // maka kita anggap kode 1 = pengangguran murni.

        $totalLainnya = $tidakBekerja - ($totalSekolah + $totalPensiunan + $totalRumahTangga);
        $lainnyaLaki = $lakiTidakBekerja - ($sekolahLaki + $pensiunanLaki + $rumahTanggaLaki);
        $lainnyaPerempuan = $perempuanTidakBekerja - ($sekolahPerempuan + $pensiunanPerempuan + $rumahTanggaPerempuan);
        // ==========================
        // Mutasi
        // ==========================
        $dataMutasi = DB::table('master_mutasimasuk')->pluck('mutasimasuk', 'kdmutasimasuk');
        $mutasiStat = [];
        foreach ($dataMutasi as $kode => $nama) {
            $jumlah = DB::table('data_penduduk')->where('kdmutasimasuk', $kode)->count();
            $mutasiStat[] = [
                'nama' => $nama,
                'jumlah' => $jumlah,
                'persen' => round(($jumlah / $totalPenduduk) * 100, 1),
            ];
        }

        // ==========================
        // Agama
        // ==========================
        $dataAgama = DB::table('master_agama')->pluck('agama', 'kdagama');
        $agamaStat = [];
        foreach ($dataAgama as $kode => $nama) {
            $jumlah = DB::table('data_penduduk')->where('kdagama', $kode)->count();
            $agamaStat[] = [
                'nama' => $nama,
                'jumlah' => $jumlah,
                'persen' => round(($jumlah / $totalPenduduk) * 100, 1),
            ];
        }

        // ==========================
        // Hubungan Dalam Keluarga
        // ==========================
        $dataHubungan = DB::table('master_hubungankeluarga')->pluck('hubungankeluarga', 'kdhubungankeluarga');
        $hubunganStat = [];
        foreach ($dataHubungan as $kode => $nama) {
            $jumlah = DB::table('data_penduduk')->where('kdhubungankeluarga', $kode)->count();
            $hubunganStat[] = ['hubungan' => $nama, 'jumlah' => $jumlah];
        }

        // ==========================
        // Sebaran Keluarga per Desa
        // ==========================
        $dataKK = DB::table('data_keluarga')
            ->join('master_desa', 'data_keluarga.kddesa', '=', 'master_desa.kddesa')
            ->join('master_kecamatan', 'master_desa.kdkecamatan', '=', 'master_kecamatan.kdkecamatan')
            ->leftJoin('data_penduduk', 'data_penduduk.no_kk', '=', 'data_keluarga.no_kk')
            ->select(
                'master_desa.desa',
                'master_kecamatan.kecamatan',
                DB::raw('COUNT(DISTINCT data_keluarga.no_kk) as keluarga'),
                DB::raw('COUNT(data_penduduk.nik) as penduduk')
            )
            ->groupBy('master_desa.desa', 'master_kecamatan.kecamatan')
            ->get()
            ->map(function ($row) {
                return [
                    'wilayah' => "{$row->desa}, {$row->kecamatan}",
                    'keluarga' => $row->keluarga,
                    'penduduk' => $row->penduduk,
                    'rata' => $row->keluarga > 0 ? round($row->penduduk / $row->keluarga, 1) : 0,
                ];
            })
            ->toArray();

        // ==========================
        // Buat PDF
        // ==========================
        $pdf = Pdf::loadView('laporan.penduduk', [
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'totalPenduduk' => $totalPenduduk,
            'laki' => $laki,
            'perempuan' => $perempuan,
            'persenLaki' => $persenLaki,
            'persenPerempuan' => $persenPerempuan,
            'persenTidakBekerja' => $persenTidakBekerja,
            'persenLakiTidakBekerja' => $persenLakiTidakBekerja,
            'persenPerempuanTidakBekerja' => $persenPerempuanTidakBekerja,
            'mutasiStat' => $mutasiStat,
            'agamaStat' => $agamaStat,
            'hubunganStat' => $hubunganStat,
            'dataKK' => $dataKK,
            // Tambahkan variabel baru
            'sekolahLaki'          => $sekolahLaki,
            'sekolahPerempuan'     => $sekolahPerempuan,
            'totalSekolah'         => $totalSekolah,
            'pensiunanLaki'        => $pensiunanLaki,
            'pensiunanPerempuan'   => $pensiunanPerempuan,
            'totalPensiunan'       => $totalPensiunan,
            'rumahTanggaLaki'      => $rumahTanggaLaki,
            'rumahTanggaPerempuan' => $rumahTanggaPerempuan,
            'totalRumahTangga'     => $totalRumahTangga,
            
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Data-Penduduk.pdf');
    }

    public function kabupaten($kdprovinsi)
    {
        $kabupatens = MasterKabupaten::where('kdprovinsi', $kdprovinsi)
            ->select('kdkabupaten', 'kabupaten')
            ->orderBy('kabupaten')
            ->get();

        return response()->json($kabupatens);
    }

    public function kecamatan($kdkabupaten)
    {
        $kecamatans = MasterKecamatan::where('kdkabupaten', $kdkabupaten)
            ->select('kdkecamatan', 'kecamatan')
            ->orderBy('kecamatan')
            ->get();

        return response()->json($kecamatans);
    }

    public function desa($kdkecamatan)
    {
        $desas = MasterDesa::where('kdkecamatan', $kdkecamatan)
            ->select('kddesa', 'desa')
            ->orderBy('desa')
            ->get();

        return response()->json($desas);
    }
}