<?php

namespace App\Http\Controllers;

use App\Models\{DataPrasaranaDasar, 
MasterStatusPemilikBangunan, MasterStatusPemilikLahan, MasterJenisFisikBangunan,
MasterKondisiLantaiBangunan, MasterJenisLantaiBangunan, MasterJenisDindingBangunan, MasterKondisiDindingBangunan,
MasterJenisAtapBangunan, MasterKondisiAtapBangunan, MasterSumberAirMinum, MasterKondisiSumberAir,
MasterCaraPerolehanAir, MasterSumberPeneranganUtama, MasterSumberDayaTerpasang, MasterBahanBakarMemasak,
MasterFasilitasTempatBab,MasterPembuanganAkhirTinja, MasterCaraPembuanganSampah, MasterManfaatMataAir};
use App\Models\DataKeluarga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrasaranaDasarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10 data per halaman

        $prasaranas = DataPrasaranaDasar::with([
            'keluarga',
            'statuspemilikbangunan',
            'statuspemiliklahan',
            'jenisfisikbangunan',
            'jenislantaibangunan',
            'kondisilantaibangunan',
            'jenisdindingbangunan',
            'kondisidindingbangunan',
            'jenisatapbangunan',
            'kondisiatapbangunan',
            'sumberairminum',
            'kondisisumberair',
            'caraperolehanair',
            'sumberpeneranganutama',
            'sumberdayaterpasang',
            'bahanbakarmemasak',
            'fasilitastempatbab',
            'pembuanganakhirtinja',
            'carapembuangansampah',
            'manfaatmataair'
        ])
        ->when($search, function ($query, $search) {
            $query->where('no_kk', 'like', "%{$search}%")
                ->orWhereHas('keluarga', function ($q) use ($search) {
                    $q->where('keluarga_kepalakeluarga', 'like', "%{$search}%");
                });
        })
        ->orderBy('no_kk', 'asc')
        ->paginate($perPage)
        ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('keluarga.prasarana.index', compact('prasaranas', 'search', 'perPage'));
    }

    public function create()
    {
        $keluargas = DataKeluarga::all();
        $statuspemilikbangunan = MasterStatusPemilikBangunan::all();
        $statuspemiliklahan = MasterStatusPemilikLahan::all();
        $jenisfisikbangunan = MasterJenisFisikBangunan::all();
        $jenislantaibangunan = MasterJenisLantaiBangunan::all();
        $kondisilantaibangunan = MasterKondisiLantaiBangunan::all();
        $jenisdindingbangunan = MasterJenisDindingBangunan::all();
        $kondisidindingbangunan = MasterKondisiDindingBangunan::all();
        $jenisatapbangunan = MasterJenisAtapBangunan::all();
        $kondisiatapbangunan = MasterKondisiAtapBangunan::all();
        $sumberairminum = MasterSumberAirMinum::all();
        $kondisisumberair = MasterKondisiSumberAir::all();
        $caraperolehanair = MasterCaraPerolehanAir::all();
        $sumberpeneranganutama = MasterSumberPeneranganUtama::all();
        $sumberdayaterpasang = MasterSumberDayaTerpasang::all();
        $bahanbakarmemasak = MasterBahanBakarMemasak::all();
        $fasilitastempatbab = MasterFasilitasTempatBab::all();
        $pembuanganakhirtinja = MasterPembuanganAkhirTinja::all();
        $carapembuangansampah = MasterCaraPembuanganSampah::all();
        $manfaatmataair = MasterManfaatMataAir::all();

        return view('keluarga.prasarana.create', compact('keluargas', 
        'statuspemilikbangunan', 'statuspemiliklahan', 'jenisfisikbangunan', 'jenislantaibangunan', 'kondisilantaibangunan',
        'jenisdindingbangunan', 'kondisidindingbangunan', 'jenisatapbangunan', 'kondisiatapbangunan', 'sumberairminum','kondisisumberair',
        'caraperolehanair', 'sumberpeneranganutama', 'sumberdayaterpasang', 'bahanbakarmemasak', 'fasilitastempatbab', 
        'pembuanganakhirtinja', 'carapembuangansampah', 'manfaatmataair'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|string|unique:data_prasaranadasar,no_kk|exists:data_keluarga,no_kk',
            'kdstatuspemilikbangunan' => 'nullable|integer',
            'kdstatuspemiliklahan' => 'nullable|integer',
            'kdjenisfisikbangunan' => 'nullable|integer',
            'kdjenislantaibangunan' => 'nullable|integer',
            'kdkondisilantaibangunan' => 'nullable|integer',
            'kdjenisdindingbangunan' => 'nullable|integer',
            'kdkondisidindingbangunan' => 'nullable|integer',
            'kdjenisatapbangunan' => 'nullable|integer',
            'kdkondisiatapbangunan' => 'nullable|integer',
            'kdsumberairminum' => 'nullable|integer',
            'kdkondisisumberair' => 'nullable|integer',
            'kdcaraperolehanair' => 'nullable|integer',
            'kdsumberpeneranganutama' => 'nullable|integer',
            'kdsumberdayaterpasang' => 'nullable|integer',
            'kdbahanbakarmemasak' => 'nullable|integer',
            'kdfasilitastempatbab' => 'nullable|integer',
            'kdpembuanganakhirtinja' => 'nullable|integer',
            'kdcarapembuangansampah' => 'nullable|integer',
            'kdmanfaatmataair' => 'nullable|integer',
            'prasdas_luaslantai' => 'nullable|numeric',
            'prasdas_jumlahkamar' => 'nullable|integer',
        ]);

        DataPrasaranaDasar::create($validated);

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil ditambahkan.');
    }

    public function edit($no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();
        $keluargas = DataKeluarga::all();
        $keluargas = DataKeluarga::all();
        $statuspemilikbangunan = MasterStatusPemilikBangunan::all();
        $statuspemiliklahan = MasterStatusPemilikLahan::all();
        $jenisfisikbangunan = MasterJenisFisikBangunan::all();
        $jenislantaibangunan = MasterJenisLantaiBangunan::all();
        $kondisilantaibangunan = MasterKondisiLantaiBangunan::all();
        $jenisdindingbangunan = MasterJenisDindingBangunan::all();
        $kondisidindingbangunan = MasterKondisiDindingBangunan::all();
        $jenisatapbangunan = MasterJenisAtapBangunan::all();
        $kondisiatapbangunan = MasterKondisiAtapBangunan::all();
        $sumberairminum = MasterSumberAirMinum::all();
        $kondisisumberair = MasterKondisiSumberAir::all();
        $caraperolehanair = MasterCaraPerolehanAir::all();
        $sumberpeneranganutama = MasterSumberPeneranganUtama::all();
        $sumberdayaterpasang = MasterSumberDayaTerpasang::all();
        $bahanbakarmemasak = MasterBahanBakarMemasak::all();
        $fasilitastempatbab = MasterFasilitasTempatBab::all();
        $pembuanganakhirtinja = MasterPembuanganAkhirTinja::all();
        $carapembuangansampah = MasterCaraPembuanganSampah::all();
        $manfaatmataair = MasterManfaatMataAir::all();

        return view('keluarga.prasarana.edit', compact('prasarana', 'keluargas', 
        'statuspemilikbangunan', 'statuspemiliklahan', 'jenisfisikbangunan', 'jenislantaibangunan', 'kondisilantaibangunan',
        'jenisdindingbangunan', 'kondisidindingbangunan', 'jenisatapbangunan', 'kondisiatapbangunan', 'sumberairminum','kondisisumberair',
        'caraperolehanair', 'sumberpeneranganutama', 'sumberdayaterpasang', 'bahanbakarmemasak', 'fasilitastempatbab', 
        'pembuanganakhirtinja', 'carapembuangansampah', 'manfaatmataair'));
    }

    public function update(Request $request, $no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();

        $validated = $request->validate([
            'no_kk' => 'required|string|exists:data_keluarga,no_kk',
            'kdstatuspemilikbangunan' => 'nullable|integer',
            'kdstatuspemiliklahan' => 'nullable|integer',
            'kdjenisfisikbangunan' => 'nullable|integer',
            'kdjenislantaibangunan' => 'nullable|integer',
            'kdkondisilantaibangunan' => 'nullable|integer',
            'kdjenisdindingbangunan' => 'nullable|integer',
            'kdkondisidindingbangunan' => 'nullable|integer',
            'kdjenisatapbangunan' => 'nullable|integer',
            'kdkondisiatapbangunan' => 'nullable|integer',
            'kdsumberairminum' => 'nullable|integer',
            'kdkondisisumberair' => 'nullable|integer',
            'kdcaraperolehanair' => 'nullable|integer',
            'kdsumberpeneranganutama' => 'nullable|integer',
            'kdsumberdayaterpasang' => 'nullable|integer',
            'kdbahanbakarmemasak' => 'nullable|integer',
            'kdfasilitastempatbab' => 'nullable|integer',
            'kdpembuanganakhirtinja' => 'nullable|integer',
            'kdcarapembuangansampah' => 'nullable|integer',
            'kdmanfaatmataair' => 'nullable|integer',
            'prasdas_luaslantai' => 'nullable|numeric',
            'prasdas_jumlahkamar' => 'nullable|integer',
        ]);

        $prasarana->update($validated);

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil diperbarui.');
    }

    public function destroy($no_kk)
    {
        $prasarana = DataPrasaranaDasar::where('no_kk', $no_kk)->firstOrFail();
        $prasarana->delete();

        return redirect()->route('keluarga.prasarana.index')
                         ->with('success', 'Data prasarana dasar berhasil dihapus.');
    }

    public function exportPdf()
{
    // --- TOTAL KK TERDATA ---
    $totalKeluargaTerdata = DB::table('data_prasaranadasar')->count();

    // --- PROFIL LEGALITAS ASET (RINGKAS) ---
    $profilLegalitasRaw = DB::table('data_prasaranadasar as p')
        ->leftJoin('master_statuspemilikbangunan as sb', 'sb.kdstatuspemilikbangunan', '=', 'p.kdstatuspemilikbangunan')
        ->leftJoin('master_statuspemiliklahan as sl', 'sl.kdstatuspemiliklahan', '=', 'p.kdstatuspemiliklahan')
        ->selectRaw('
            COALESCE(sb.statuspemilikbangunan, "Tidak Terdata") as status_bangunan,
            COALESCE(sl.statuspemiliklahan, "Tidak Terdata") as status_lahan,
            COUNT(DISTINCT p.no_kk) as jumlah_kk
        ')
        ->groupBy('sb.statuspemilikbangunan', 'sl.statuspemiliklahan')
        ->orderByDesc('jumlah_kk')
        ->get();

    // Ringkas: hanya tampilkan yang ≥10 KK, sisanya jadi "Lainnya"
    $profilLegalitas = $profilLegalitasRaw->where('jumlah_kk', '>=', 10);

    $lainnya = $profilLegalitasRaw->where('jumlah_kk', '<', 10)->sum('jumlah_kk');
    if ($lainnya > 0) {
        $profilLegalitas->push((object)[
            'status_bangunan' => 'Kombinasi Lainnya',
            'status_lahan'    => '(termasuk sewa, pinjam, dll.)',
            'jumlah_kk'       => $lainnya
        ]);
    }

    // --- INDEKS KELAYAKAN RUMAH ---
    $kelayakanRumah = DB::table('data_prasaranadasar as p')
        ->leftJoin('master_jenislantaibangunan as jl', 'jl.kdjenislantaibangunan', '=', 'p.kdjenislantaibangunan')
        ->leftJoin('master_kondisilantaibangunan as kl', 'kl.kdkondisilantaibangunan', '=', 'p.kdkondisilantaibangunan')
        ->leftJoin('master_jenisdindingbangunan as jd', 'jd.kdjenisdindingbangunan', '=', 'p.kdjenisdindingbangunan')
        ->leftJoin('master_kondisidindingbangunan as kd', 'kd.kdkondisidindingbangunan', '=', 'p.kdkondisidindingbangunan')
        ->leftJoin('master_jenisatapbangunan as ja', 'ja.kdjenisatapbangunan', '=', 'p.kdjenisatapbangunan')
        ->leftJoin('master_kondisiatapbangunan as ka', 'ka.kdkondisiatapbangunan', '=', 'p.kdkondisiatapbangunan')
        ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN kl.kondisilantaibangunan = 'JELEK/KUALITAS RENDAH' OR kd.kondisidindingbangunan = 'JELEK/KUALITAS RENDAH' OR ka.kondisiatapbangunan = 'JELEK/KUALITAS RENDAH' THEN 1 ELSE 0 END) as tidak_layak,
            SUM(CASE WHEN (jl.jenislantaibangunan IN ('TANAH', 'BAMBU') OR jd.jenisdindingbangunan IN ('BAMBU', 'SENG') OR ja.jenisatapbangunan IN ('JERAMI/ IJUK/ DAUN/ RUMBIA')) THEN 1 ELSE 0 END) as bahan_rendah
        ")
        ->first();

    // --- KEPADATAN HUNIAN ---
    $kepadatanRaw = DB::table('data_prasaranadasar as p')
        ->leftJoin('data_penduduk as pend', 'pend.no_kk', '=', 'p.no_kk')
        ->selectRaw('
            p.no_kk,
            COALESCE(p.prasdas_luaslantai, 0) as prasdas_luaslantai,
            COUNT(pend.nik) as jumlah_anggota
        ')
        ->groupBy('p.no_kk', 'p.prasdas_luaslantai')
        ->havingRaw('p.prasdas_luaslantai IS NOT NULL AND p.prasdas_luaslantai > 0')
        ->get();

    $totalKkValid = $kepadatanRaw->count();

    $jumlahDiBawahStandar = 0;
    $sumLuasPerOrang = 0;

    foreach ($kepadatanRaw as $item) {
        if ($item->jumlah_anggota > 0) {
            $luasPerOrang = $item->prasdas_luaslantai / $item->jumlah_anggota;
            $sumLuasPerOrang += $luasPerOrang;

            if ($luasPerOrang < 8) {
                $jumlahDiBawahStandar++;
            }
        }
    }

    $rataLuasPerOrang = $totalKkValid > 0 ? round($sumLuasPerOrang / $totalKkValid, 1) : 0;
    $persenDiBawahStandar = $totalKkValid > 0 ? round(($jumlahDiBawahStandar / $totalKkValid) * 100, 1) : 0;
    // --- SANITASI ---
    $sanitasi = DB::table('data_prasaranadasar as p')
        ->leftJoin('master_fasilitastempatbab as fb', 'fb.kdfasilitastempatbab', '=', 'p.kdfasilitastempatbab')
        ->leftJoin('master_pembuanganakhirtinja as pt', 'pt.kdpembuanganakhirtinja', '=', 'p.kdpembuanganakhirtinja')
        ->selectRaw("
            SUM(CASE WHEN fb.fasilitastempatbab IN ('UMUM') OR pt.pembuanganakhirtinja NOT IN ('TANGKI', 'SPAL') THEN 1 ELSE 0 END) as sanitasi_buruk
        ")
        ->first();

    // --- KETAHANAN ENERGI ---
    $energi = DB::table('data_prasaranadasar as p')
        ->leftJoin('master_sumberdayaterpasang as sd', 'sd.kdsumberdayaterpasang', '=', 'p.kdsumberdayaterpasang')
        ->leftJoin('master_bahanbakarmemasak as bm', 'bm.kdbahanbakarmemasak', '=', 'p.kdbahanbakarmemasak')
        ->selectRaw("
            SUM(CASE WHEN sd.sumberdayaterpasang IN ('450 WATT', 'TANPA METERAN') THEN 1 ELSE 0 END) as daya_rendah,
            SUM(CASE WHEN bm.bahanbakarmemasak IN ('KAYU BAKAR', 'MINYAK TANAH', 'ARANG', 'BRIKET') THEN 1 ELSE 0 END) as bahan_tradisional
        ")
        ->first();

    // --- DESIL 8-10: KK MAPAN (minimal 2 dari 3 kriteria premium) ---
    $mapanQuery = DB::table('data_prasaranadasar as p')
        ->leftJoin('master_jenislantaibangunan as jl', 'jl.kdjenislantaibangunan', '=', 'p.kdjenislantaibangunan')
        ->leftJoin('master_sumberdayaterpasang as sd', 'sd.kdsumberdayaterpasang', '=', 'p.kdsumberdayaterpasang')
        ->leftJoin('master_sumberairminum as sa', 'sa.kdsumberairminum', '=', 'p.kdsumberairminum')
        ->leftJoin('master_bahanbakarmemasak as bm', 'bm.kdbahanbakarmemasak', '=', 'p.kdbahanbakarmemasak')
        ->selectRaw("
            SUM(
                CASE WHEN 
                    (
                        IF(TRIM(jl.jenislantaibangunan) IN ('MARMER/ GRANIT', 'MARMER/GRANIT', 'KERAMIK'), 1, 0) +
                        IF(TRIM(sd.sumberdayaterpasang) IN ('2.200 WATT', 'LEBIH DARI 2.200 WATT'), 1, 0) +
                        IF(TRIM(sa.sumberairminum) IN ('AIR KEMASAN BERMERK', 'LEDING METERAN'), 1, 0) +
                        IF(TRIM(bm.bahanbakarmemasak) IN ('LISTRIK', 'GAS KOTA/BIOGAS', 'GAS LEBIH DARI 3 KG'), 1, 0)
                    ) >= 3
                THEN 1 ELSE 0 END
            ) as jumlah_mapan
        ")
        ->first();

    $desil810 = $mapanQuery->jumlah_mapan ?? 0;

    // =============================
    // TEMUAN KUNCI & KATEGORI DESIL
    // =============================
    $temuanKunci = [
        'material_rendah'     => $kelayakanRumah->bahan_rendah ?? 0,
        'kondisi_jelek'       => $kelayakanRumah->tidak_layak ?? 0,
        'sanitasi_buruk'      => $sanitasi->sanitasi_buruk ?? 0,
        'bahan_bakar_trad'    => $energi->bahan_tradisional ?? 0,
        'luas_bawah_standar'  => $jumlahDiBawahStandar,
        'daya_rendah'         => $energi->daya_rendah ?? 0,
    ];

    // Estimasi Desil 1-2: max dari indikator buruk (untuk menghindari overlap)
    $desil12 = max(
        $temuanKunci['material_rendah'],
        $temuanKunci['kondisi_jelek'],
        $temuanKunci['sanitasi_buruk'],
        $temuanKunci['bahan_bakar_trad']
    );

    // Desil 3-7: total - desil1-2 - desil8-10
    $desil37 = $totalKeluargaTerdata - $desil12 - $desil810;
    if ($desil37 < 0) $desil37 = 0;

    // =============================
    // ANALISIS & CATATAN
    // =============================
    $catatan = [];

    $catatan[] = "Total keluarga dengan data prasarana dasar tercatat: {$totalKeluargaTerdata} KK.";

    if ($totalKeluargaTerdata > 0) {
        $persenTidakLayak = round(($kelayakanRumah->tidak_layak / $totalKeluargaTerdata) * 100, 1);
        $persenBahanRendah = round(($kelayakanRumah->bahan_rendah / $totalKeluargaTerdata) * 100, 1);

        $catatan[] = "Sebanyak {$persenTidakLayak}% rumah tangga memiliki setidaknya satu komponen (lantai/dinding/atap) dalam kondisi jelek/kualitas rendah.";
        if ($persenBahanRendah > 10) {
            $catatan[] = "Terdapat {$persenBahanRendah}% rumah tangga menggunakan material rendah (tanah, bambu, jerami), indikator kuat kemiskinan ekstrem.";
        }

        $catatan[] = "Rata-rata luas lantai per orang: " . round($rataLuasPerOrang, 1) . " m². Sebanyak " . round($persenDiBawahStandar, 1) . "% KK berada di bawah standar kesehatan BPS (<8 m²/orang).";

        $persenSanitasiBuruk = round(($sanitasi->sanitasi_buruk / $totalKeluargaTerdata) * 100, 1);
        $catatan[] = "Sebanyak {$persenSanitasiBuruk}% rumah tangga memiliki akses sanitasi buruk (jamban umum atau buang ke lingkungan terbuka).";

        $persenEnergiRendah = round(($energi->daya_rendah / $totalKeluargaTerdata) * 100, 1);
        $persenBahanTradisional = round(($energi->bahan_tradisional / $totalKeluargaTerdata) * 100, 1);
        $catatan[] = "Ketahanan energi: {$persenEnergiRendah}% menggunakan daya ≤450 VA atau tanpa meteran, dan {$persenBahanTradisional}% masih menggunakan kayu bakar/minyak tanah.";
    }

    // =============================
    // REKOMENDASI INTERVENSI
    // =============================
    $rekomendasi = [
        "Lakukan verifikasi dan prioritaskan program Bedah Rumah (RTLH/BSPS) bagi rumah dengan kondisi lantai/dinding/atap jelek atau material rendah.",
        "Bangun tangki septik komunal dan program jambanisasi di wilayah dengan sanitasi buruk tinggi untuk pencegahan stunting dan penyakit berbasis lingkungan.",
        "Dorong konversi bahan bakar memasak ke LPG/kompor listrik melalui subsidi tepat sasaran bagi keluarga miskin yang masih menggunakan kayu bakar.",
        "Integrasikan data ini ke dalam P3KE (Pensasaran Percepatan Penghapusan Kemiskinan Ekstrem) sebagai indikator objektif untuk ranking penerima bantuan.",
        "Lakukan program PTSL (Pendaftaran Tanah Sistematis Lengkap) bagi rumah tangga dengan status lahan bukan milik sendiri untuk mengurangi kerentanan penggusuran."
    ];

    $pdf = Pdf::loadView('laporan.prasaranadasar', [
        'totalKeluargaTerdata'   => $totalKeluargaTerdata,
        'profilLegalitas'        => $profilLegalitas,
        'temuanKunci'            => $temuanKunci,
        'desil12'               => $desil12,
        'desil37'               => $desil37,
        'desil810'              => $desil810,
        'catatan'                => $catatan,
        'rekomendasi'            => $rekomendasi,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('Laporan_Analisis_Prasarana_Dasar.pdf');
}
}