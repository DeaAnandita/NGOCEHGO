<?php

namespace App\Http\Controllers;

use App\Models\DataSosialEkonomi;
use App\Models\DataPenduduk;
use App\Models\MasterPartisipasiSekolah;
use App\Models\MasterIjasahTerakhir;
use App\Models\MasterJenisDisabilitas;
use App\Models\MasterTingkatSulitDisabilitas;
use App\Models\MasterPenyakitKronis;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterStatusKedudukanKerja;
use App\Models\MasterPendapatanPerbulan;
use App\Models\MasterImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SosialEkonomiController extends Controller
{
        public function index(Request $request)
    {
        // Ambil semua data sosialekonomi dengan relasi penduduk
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // default 10

        $sosialekonomis = DataSosialEkonomi::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]); // agar pagination tetap membawa parameter

        return view('penduduk.sosialekonomi.index', compact('sosialekonomis', 'search', 'perPage'));
    }

    public function create()
    {
        return view('penduduk.sosialekonomi.create', [
            'penduduks' => DataPenduduk::all(),
            'partisipasi_sekolahs' => MasterPartisipasiSekolah::all(),
            'ijasah_terakhirs' => MasterIjasahTerakhir::all(),
            'jenis_disabilitass' => MasterJenisDisabilitas::all(),
            'tingkat_sulit_disabilitass' => MasterTingkatSulitDisabilitas::all(),
            'penyakit_kroniss' => MasterPenyakitKronis::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'status_kedudukan_kerjas' => MasterStatusKedudukanKerja::all(),
            'pendapatan_perbulans' => MasterPendapatanPerbulan::all(),
            'imunisis' => MasterImunisasi::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:data_sosialekonomi,nik|exists:data_penduduk,nik',
            'kdpartisipasisekolah' => 'nullable|integer|exists:master_partisipasisekolah,kdpartisipasisekolah',
            'kdijasahterakhir' => 'nullable|integer|exists:master_ijasahterakhir,kdijasahterakhir',
            'kdjenisdisabilitas' => 'nullable|integer|exists:master_jenisdisabilitas,kdjenisdisabilitas',
            'kdtingkatsulitdisabilitas' => 'nullable|integer|exists:master_tingkatsulitdisabilitas,kdtingkatsulitdisabilitas',
            'kdpenyakitkronis' => 'nullable|integer|exists:master_penyakitkronis,kdpenyakitkronis',
            'kdlapanganusaha' => 'nullable|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'kdstatuskedudukankerja' => 'nullable|integer|exists:master_statuskedudukankerja,kdstatuskedudukankerja',
            'kdpendapatanperbulan' => 'nullable|integer|exists:master_pendapatanperbulan,kdpendapatanperbulan',
            'kdimunisasi' => 'nullable|integer|exists:master_imunisasi,kdimunisasi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DataSosialEkonomi::create($request->all());

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil ditambahkan.');
    }

    public function edit($nik)
    {
        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        return view('penduduk.sosialekonomi.edit', [
            'sosialekonomi' => $sosialekonomi,
            'penduduks' => DataPenduduk::all(),
            'partisipasi_sekolahs' => MasterPartisipasiSekolah::all(),
            'ijasah_terakhirs' => MasterIjasahTerakhir::all(),
            'jenis_disabilitass' => MasterJenisDisabilitas::all(),
            'tingkat_sulit_disabilitass' => MasterTingkatSulitDisabilitas::all(),
            'penyakit_kroniss' => MasterPenyakitKronis::all(),
            'lapangan_usahas' => MasterLapanganUsaha::all(),
            'status_kedudukan_kerjas' => MasterStatusKedudukanKerja::all(),
            'pendapatan_perbulans' => MasterPendapatanPerbulan::all(),
            'imunisis' => MasterImunisasi::all(),
        ]);
    }

    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16',
            'kdpartisipasisekolah' => 'nullable|integer|exists:master_partisipasisekolah,kdpartisipasisekolah',
            'kdijasahterakhir' => 'nullable|integer|exists:master_ijasahterakhir,kdijasahterakhir',
            'kdjenisdisabilitas' => 'nullable|integer|exists:master_jenisdisabilitas,kdjenisdisabilitas',
            'kdtingkatsulitdisabilitas' => 'nullable|integer|exists:master_tingkatsulitdisabilitas,kdtingkatsulitdisabilitas',
            'kdpenyakitkronis' => 'nullable|integer|exists:master_penyakitkronis,kdpenyakitkronis',
            'kdlapanganusaha' => 'nullable|integer|exists:master_lapanganusaha,kdlapanganusaha',
            'kdstatuskedudukankerja' => 'nullable|integer|exists:master_statuskedudukankerja,kdstatuskedudukankerja',
            'kdpendapatanperbulan' => 'nullable|integer|exists:master_pendapatanperbulan,kdpendapatanperbulan',
            'kdimunisasi' => 'nullable|integer|exists:master_imunisasi,kdimunisasi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        $sosialekonomi->update($request->all());

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil diperbarui.');
    }

    public function destroy($nik)
    {
        $sosialekonomi = DataSosialEkonomi::findOrFail($nik);
        $sosialekonomi->delete();

        return redirect()->route('penduduk.sosialekonomi.index')->with('success', 'Data sosial ekonomi berhasil dihapus.');
    }

private function getLaporanData()
{
    $data = DB::table('data_sosialekonomi as se')
        ->join('data_penduduk as p', 'se.nik', '=', 'p.nik')
        ->leftJoin('master_partisipasisekolah as mps', 'se.kdpartisipasisekolah', '=', 'mps.kdpartisipasisekolah')
        ->leftJoin('master_tingkatsulitdisabilitas as mtd', 'se.kdtingkatsulitdisabilitas', '=', 'mtd.kdtingkatsulitdisabilitas')
        ->leftJoin('master_statuskedudukankerja as msk', 'se.kdstatuskedudukankerja', '=', 'msk.kdstatuskedudukankerja')
        ->leftJoin('master_ijasahterakhir as mij', 'se.kdijasahterakhir', '=', 'mij.kdijasahterakhir')
        ->leftJoin('master_penyakitkronis as mpk', 'se.kdpenyakitkronis', '=', 'mpk.kdpenyakitkronis')
        ->leftJoin('master_pendapatanperbulan as mpp', 'se.kdpendapatanperbulan', '=', 'mpp.kdpendapatanperbulan')
        ->leftJoin('master_jenisdisabilitas as mjd', 'se.kdjenisdisabilitas', '=', 'mjd.kdjenisdisabilitas')
        ->leftJoin('master_lapanganusaha as mlu', 'se.kdlapanganusaha', '=', 'mlu.kdlapanganusaha')
        ->leftJoin('master_imunisasi as mi', 'se.kdimunisasi', '=', 'mi.kdimunisasi')
        ->select(
            'se.kdpartisipasisekolah', 'mps.partisipasisekolah',
            'se.kdtingkatsulitdisabilitas', 'mtd.tingkatsulitdisabilitas',
            'se.kdstatuskedudukankerja', 'msk.statuskedudukankerja',
            'se.kdijasahterakhir', 'mij.ijasahterakhir',
            'se.kdpenyakitkronis', 'mpk.penyakitkronis',
            'se.kdpendapatanperbulan', 'mpp.pendapatanperbulan',
            'se.kdjenisdisabilitas', 'mjd.jenisdisabilitas',
            'se.kdlapanganusaha', 'mlu.lapanganusaha',
            'se.kdimunisasi', 'mi.imunisasi'
        )
        ->get();

    $total = $data->count();
    if ($total == 0) {
        return [
            'data' => [],
            'summary' => [],
            'analysis' => [],
            'auto_summary' => '',
            'error' => 'Belum ada data sosial ekonomi.'
        ];
    }

    $groupCount = fn($field, $labelField) => $data
        ->groupBy($field)
        ->map(fn($group) => [
            'kode' => $group->first()->$field,
            'label' => $group->first()->$labelField,
            'jumlah' => $group->count(),
            'persen' => round($group->count() / $total * 100, 1),
        ])
        ->sortBy('kode')
        ->values()
        ->toArray();

    // === RANGKUMAN (summary per field)
    $summary = [
        'total_penduduk' => $total,
        'partisipasisekolah' => $groupCount('kdpartisipasisekolah','partisipasisekolah'),
        'tingkat_sulit' => $groupCount('kdtingkatsulitdisabilitas','tingkatsulitdisabilitas'),
        'status_kerja' => $groupCount('kdstatuskedudukankerja','statuskedudukankerja'),
        'ijasah_terakhir' => $groupCount('kdijasahterakhir','ijasahterakhir'),
        'penyakit_kronis' => $groupCount('kdpenyakitkronis','penyakitkronis'),
        'pendapatan' => $groupCount('kdpendapatanperbulan','pendapatanperbulan'),
        'jenis_disabilitas' => $groupCount('kdjenisdisabilitas','jenisdisabilitas'),
        'lapangan_usaha' => $groupCount('kdlapanganusaha','lapanganusaha'),
        'imunisasi' => $groupCount('kdimunisasi','imunisasi'),
    ];

    $getCount = fn($arr,$label) => collect($arr)->firstWhere('label',$label)['jumlah'] ?? 0;
    $sumItems = fn($arr) => collect($arr)->sum('jumlah');

    // Analisis interpretatif 9 field

$analysis = [];

foreach ($summary as $field => $items) {
    if ($field === 'total_penduduk') continue;
    if (empty($items)) continue;

    // Cari item dengan jumlah terbesar
    $max = collect($items)->sortByDesc('jumlah')->first();

    $kode = $max['kode'] ?? null;
    $nama = $max['label'] ?? '-';
    $persen = $max['persen'] ?? 0;
    $jumlah = $max['jumlah'] ?? 0;

    $interpretasi = 'Data tidak tersedia.';
    $rekom = '-';

    switch ($field) {

        // ======================= 1. Partisipasi Sekolah =======================
        case 'partisipasisekolah':
            if (str_contains(strtolower($nama), 'sd')) {
                $interpretasi = "Mayoritas penduduk masih di jenjang SD/MI, menandakan kebutuhan pendidikan dasar yang kuat.";
                $rekom = "Subsidi pendidikan dasar, beasiswa siswa miskin, program gizi anak sekolah, pendampingan literasi & numerasi dasar.";
            } elseif (str_contains(strtolower($nama), 'smp')) {
                $interpretasi = "Sebagian besar penduduk berpendidikan SMP/MTs.";
                $rekom = "Beasiswa prestasi, bantuan seragam & alat sekolah, program literasi digital untuk remaja, pemberdayaan remaja produktif.";
            } elseif (str_contains(strtolower($nama), 'sma') || str_contains(strtolower($nama), 'smk')) {
                $interpretasi = "Mayoritas berpendidikan SMA/SMK, siap menuju dunia kerja.";
                $rekom = "Pelatihan vokasi, program wirausaha muda, bimbingan karier, link and match sekolah & industri.";
            } elseif (str_contains(strtolower($nama), 'kuliah') || str_contains(strtolower($nama), 'sarjana')) {
                $interpretasi = "Sebagian kecil berpendidikan tinggi.";
                $rekom = "Beasiswa KIP-Kuliah, program magang desa, pengabdian masyarakat kampus.";
            } else {
                $interpretasi = "Sebagian tidak menempuh pendidikan formal.";
                $rekom = "Program kejar paket, pelatihan keaksaraan, pendidikan masyarakat desa.";
            }
            break;

        // ======================= 2. Tingkat Kesulitan / Disabilitas =======================
        case 'tingkat_sulit':
            if (str_contains(strtolower($nama), 'tidak')) {
                $interpretasi = "Mayoritas tidak memiliki disabilitas berat.";
                $rekom = "Tidak termasuk target intervensi disabilitas.";
            } elseif (str_contains(strtolower($nama), 'ringan')) {
                $interpretasi = "Sebagian mengalami disabilitas ringan.";
                $rekom = "Pelatihan kerja inklusif & bantuan alat bantu sederhana.";
            } else {
                $interpretasi = "Sebagian mengalami kesulitan berat dalam aktivitas sehari-hari.";
                $rekom = "Program bantuan alat bantu, pelatihan adaptif, tunjangan sosial.";
            }
            break;

            // ======================= 3. Ijasah terakhir =======================
 case 'ijazah_terakhir':

    $namaLower = strtolower($nama);

    if (str_contains($namaLower, 'tidak memiliki')) {
        // kode 0
        $interpretasi = "Sebagian masyarakat tidak memiliki ijazah formal.";
        $rekom = "Program keaksaraan fungsional, kejar paket A, serta penyuluhan pentingnya pendidikan dasar.";

    } elseif (str_contains($namaLower, 'sd')) {
        // kode 1
        $interpretasi = "Mayoritas masyarakat berijazah SD atau sederajat.";
        $rekom = "Program kejar paket B & C, pelatihan keterampilan dasar, dan pemberdayaan ekonomi produktif.";

    } elseif (str_contains($namaLower, 'smp')) {
        // kode 2
        $interpretasi = "Sebagian besar masyarakat berijazah SMP atau sederajat.";
        $rekom = "Pelatihan vokasional, beasiswa kejar paket C, dan wirausaha muda produktif.";

    } elseif (str_contains($namaLower, 'sma') || str_contains($namaLower, 'smk')) {
        // kode 3
        $interpretasi = "Mayoritas masyarakat berpendidikan SMA/SMK sederajat.";
        $rekom = "Program pelatihan kerja terapan, magang industri, dan peningkatan softskill tenaga muda.";

    } elseif (str_contains($namaLower, 'd1')) {
        // kode 4
        $interpretasi = "Sebagian masyarakat berijazah D1.";
        $rekom = "Sertifikasi profesi, peningkatan keterampilan teknis, dan penempatan kerja lokal.";

    } elseif (str_contains($namaLower, 'd2')) {
        // kode 5
        $interpretasi = "Sebagian masyarakat berijazah D2.";
        $rekom = "Pelatihan profesional lanjutan dan program inkubasi kewirausahaan terapan.";

    } elseif (str_contains($namaLower, 'd3')) {
        // kode 6
        $interpretasi = "Sebagian masyarakat berijazah D3.";
        $rekom = "Program digitalisasi UMKM, pelatihan produktivitas, dan kemitraan dunia usaha.";

    } elseif (str_contains($namaLower, 'd4') || str_contains($namaLower, 's1')) {
        // kode 7
        $interpretasi = "Sebagian masyarakat berijazah D4/S1.";
        $rekom = "Program inovasi desa berbasis sarjana, riset terapan, dan insentif wirausaha muda.";

    } elseif (str_contains($namaLower, 's2')) {
        // kode 8
        $interpretasi = "Sebagian kecil masyarakat berijazah S2.";
        $rekom = "Pelibatan akademisi lokal dalam perencanaan kebijakan desa dan pengembangan SDM unggul.";

    } elseif (str_contains($namaLower, 's3')) {
        // kode 9
        $interpretasi = "Sebagian sangat kecil masyarakat berijazah S3.";
        $rekom = "Kolaborasi riset, mentoring inovasi sosial, dan transfer pengetahuan kepada masyarakat.";

    } else {
        $interpretasi = "Data ijazah terakhir belum tersedia atau belum valid.";
        $rekom = "Lakukan verifikasi dan pembaruan data pendidikan masyarakat.";
    }

    break;


        // ======================= 4. Jenis Disabilitas =======================
        case 'jenis_disabilitas':
            if (str_contains(strtolower($nama), 'tidak')) {
                $interpretasi = "Mayoritas masyarakat tanpa disabilitas.";
                $rekom = "Tidak termasuk prioritas program inklusi.";
            } elseif (str_contains(strtolower($nama), 'fisik')) {
                $interpretasi = "Sebagian memiliki disabilitas fisik.";
                $rekom = "Program aksesibilitas fasilitas umum, bantuan alat bantu jalan, rehabilitasi medis.";
            } elseif (str_contains(strtolower($nama), 'mental') || str_contains(strtolower($nama), 'psikososial')) {
                $interpretasi = "Sebagian memiliki disabilitas mental atau psikososial.";
                $rekom = "Pendampingan psikologis, edukasi keluarga, serta bantuan sosial rutin.";
            } elseif (str_contains(strtolower($nama), 'sensorik')) {
                $interpretasi = "Sebagian masyarakat mengalami gangguan sensorik.";
                $rekom = "Bantuan alat bantu dengar dan visual, pelatihan komunikasi inklusif.";
            } else {
                $interpretasi = "Data jenis disabilitas tidak terdeteksi dengan jelas.";
                $rekom = "Pemetaan dan validasi data penyandang disabilitas.";
            }
            break;

        // ======================= 5. Status Kedudukan Kerja =======================
        case 'status_kerja':
            if (str_contains(strtolower($nama), 'wirausaha') || str_contains(strtolower($nama), 'usaha sendiri')) {
                $interpretasi = "Sebagian besar bekerja mandiri atau wirausaha.";
                $rekom = "Program permodalan UMKM, pelatihan manajemen usaha, digitalisasi pemasaran.";
            } elseif (str_contains(strtolower($nama), 'buruh') || str_contains(strtolower($nama), 'pekerja')) {
                $interpretasi = "Mayoritas merupakan buruh atau pekerja lepas.";
                $rekom = "Program pelatihan skill kerja, jaminan sosial ketenagakerjaan, peningkatan kesejahteraan pekerja.";
            } elseif (str_contains(strtolower($nama), 'tidak bekerja')) {
                $interpretasi = "Sebagian penduduk belum bekerja.";
                $rekom = "Program padat karya, pelatihan kerja, serta fasilitasi penyaluran tenaga kerja lokal.";
            } else {
                $interpretasi = "Data status kerja beragam.";
                $rekom = "Pemantauan kondisi ekonomi rumah tangga dan diversifikasi sumber penghasilan.";
            }
            break;

        // ======================= 6. Pendapatan =======================
        case 'pendapatan':
            if (str_contains($nama, '≤') || str_contains($nama, '1 juta')) {
                $interpretasi = "Sebagian besar penduduk tergolong sangat miskin.";
                $rekom = "Program BLT, subsidi pangan, bantuan usaha mikro.";
            } elseif (str_contains($nama, '1-2')) {
                $interpretasi = "Penduduk rentan miskin dengan penghasilan rendah.";
                $rekom = "Pelatihan keterampilan, akses kredit mikro, pemberdayaan keluarga produktif.";
            } elseif (str_contains($nama, '3')) {
                $interpretasi = "Penduduk menengah ke bawah dengan penghasilan moderat.";
                $rekom = "Diversifikasi usaha & akses permodalan usaha kecil.";
            } else {
                $interpretasi = "Pendapatan masyarakat relatif stabil.";
                $rekom = "Edukasi investasi, penguatan UMKM.";
            }
            break;

        // ======================= 7. Lapangan Usaha =======================
        case 'lapangan_usaha':
            if (str_contains(strtolower($nama), 'tani') || str_contains(strtolower($nama), 'pertanian')) {
                $interpretasi = "Mayoritas bekerja di sektor pertanian.";
                $rekom = "Pelatihan teknologi pertanian, akses pupuk & irigasi, modernisasi alat tani.";
            } elseif (str_contains(strtolower($nama), 'dagang') || str_contains(strtolower($nama), 'umkm')) {
                $interpretasi = "Sebagian besar bekerja di sektor perdagangan dan UMKM.";
                $rekom = "Digitalisasi UMKM, pelatihan pemasaran online, dukungan modal usaha.";
            } else {
                $interpretasi = "Sektor kerja masyarakat beragam.";
                $rekom = "Pendampingan usaha sesuai potensi dominan.";
            }
            break;

        // ======================= 8. Penyakit Kronis =======================
        case 'penyakit_kronis':
            if (str_contains(strtolower($nama), 'hipertensi')) {
                $interpretasi = "Kasus terbanyak adalah hipertensi.";
                $rekom = "Program pemeriksaan tekanan darah rutin, edukasi diet rendah garam, aktivitas fisik.";
            } elseif (str_contains(strtolower($nama), 'diabetes')) {
                $interpretasi = "Kasus terbanyak adalah diabetes melitus.";
                $rekom = "Edukasi pola makan sehat, program deteksi dini diabetes, subsidi obat kronis.";
            } elseif (str_contains(strtolower($nama), 'asma')) {
                $interpretasi = "Kasus terbanyak adalah asma atau gangguan pernapasan.";
                $rekom = "Kampanye udara bersih, kontrol lingkungan, dan layanan pengobatan berkelanjutan.";
            } elseif (str_contains(strtolower($nama), 'jantung')) {
                $interpretasi = "Kasus penyakit jantung cukup tinggi.";
                $rekom = "Program skrining jantung, edukasi berhenti merokok, promosi olahraga rutin.";
            } else {
                $interpretasi = "Sebagian masyarakat mengalami penyakit kronis umum lainnya.";
                $rekom = "Pemeriksaan kesehatan rutin & edukasi gaya hidup sehat.";
            }
            break;

        // ======================= 9. Imunisasi =======================
        case 'imunisasi':
            if (str_contains(strtolower($nama), 'lengkap')) {
                $interpretasi = "Mayoritas anak telah mendapat imunisasi lengkap.";
                $rekom = "Pertahankan program imunisasi rutin dan edukasi posyandu.";
            } else {
                $interpretasi = "Sebagian anak belum lengkap imunisasi.";
                $rekom = "Program imunisasi gratis & edukasi kesehatan masyarakat.";
            }
            break;

        default:
            $interpretasi = "Data tidak tersedia.";
            $rekom = "-";
    }

    $analysis[$field] = [
        'max_kode' => $kode,
        'max_nama' => $nama,
        'jumlah' => $jumlah,
        'persen' => $persen,
        'analisis' => $interpretasi,
        'rekomendasi' => $rekom
    ];

}
    // Hitung persen & rekomendasi otomatis
    $analysis['partisipasisekolah']['persen'] = round($analysis['partisipasisekolah']['jumlah']/$total*100,1);
    $analysis['partisipasisekolah']['tinggi'] = $analysis['partisipasisekolah']['persen'] >= 10;
    $analysis['partisipasisekolah']['rekomendasi'] = $analysis['partisipasisekolah']['tinggi'] ? 'Program kejar paket, subsidi pendidikan, beasiswa, literasi orang tua.' : 'Pemantauan rutin partisipasi sekolah.';

    $analysis['tingkat_sulit']['persen'] = round($analysis['tingkat_sulit']['jumlah']/$total*100,1);
    $analysis['tingkat_sulit']['tinggi'] = $analysis['tingkat_sulit']['persen'] >= 2;
    $analysis['tingkat_sulit']['rekomendasi'] = $analysis['tingkat_sulit']['tinggi'] ? 'Bantuan alat bantu, pelatihan kerja inklusif, tunjangan sosial.' : 'Pemantauan akses layanan disabilitas.';

    // ======================= STATUS KERJA =======================
    $tidak_dibayar = $analysis['status_kerja']['tidak_dibayar'] ?? 0;
    $wira = $analysis['status_kerja']['wira'] ?? 0;

    $analysis['status_kerja']['p_tidak_dibayar'] = $total > 0 ? round($tidak_dibayar / $total * 100, 1) : 0;
    $analysis['status_kerja']['p_wira'] = $total > 0 ? round($wira / $total * 100, 1) : 0;

    $analysis['status_kerja']['tinggi'] = $analysis['status_kerja']['p_tidak_dibayar'] >= 15;
    $analysis['status_kerja']['rekomendasi'] = $analysis['status_kerja']['tinggi']
        ? 'Pelatihan kewirausahaan, akses modal, diversifikasi usaha, jaminan sosial.'
        : 'Pemantauan kesejahteraan pekerja.';


    $analysis['ijasah_terakhir']['persen'] = round($analysis['ijasah_terakhir']['jumlah']/$total*100,1);
    $analysis['ijasah_terakhir']['tinggi'] = $analysis['ijasah_terakhir']['persen'] >= 30;
    $analysis['ijasah_terakhir']['rekomendasi'] = $analysis['ijasah_terakhir']['tinggi'] ? 'Pelatihan skill dasar, literasi, beasiswa, program pendidikan nonformal.' : 'Pemantauan akses pendidikan.';

    $analysis['penyakit_kronis']['persen'] = round($analysis['penyakit_kronis']['jumlah']/$total*100,1);
    $analysis['penyakit_kronis']['tinggi'] = $analysis['penyakit_kronis']['persen'] >= 8;
    $analysis['penyakit_kronis']['rekomendasi'] = $analysis['penyakit_kronis']['tinggi'] ? 'Program kesehatan preventif, subsidi obat, screening rutin, edukasi hidup sehat.' : 'Pemantauan kesehatan rutin.';

    $analysis['pendapatan']['persen'] = round($analysis['pendapatan']['jumlah']/$total*100,1);
    $analysis['pendapatan']['tinggi'] = $analysis['pendapatan']['persen'] >= 25;
    $analysis['pendapatan']['rekomendasi'] = $analysis['pendapatan']['tinggi'] ? 'BLT, subsidi pangan, bantuan modal, pelatihan skill, akses kredit mikro.' : 'Pemantauan kesejahteraan ekonomi.';

    $analysis['jenis_disabilitas']['persen'] = round($analysis['jenis_disabilitas']['jumlah']/$total*100,1);
    $analysis['jenis_disabilitas']['tinggi'] = $analysis['jenis_disabilitas']['persen'] >= 5;
    $analysis['jenis_disabilitas']['rekomendasi'] = $analysis['jenis_disabilitas']['tinggi'] ? 'Pelatihan kerja sesuai kemampuan, akses fasilitas disabilitas.' : 'Pemantauan inklusi disabilitas.';

    // Lapangan usaha
    $lapMax = collect($summary['lapangan_usaha'])->sortByDesc('jumlah')->first();
    if($lapMax){
        $analysis['lapangan_usaha']['max_nama'] = $lapMax['label'];
        $analysis['lapangan_usaha']['jumlah'] = $lapMax['jumlah'];
        $analysis['lapangan_usaha']['persen'] = round($lapMax['jumlah']/$total*100,1);
        $analysis['lapangan_usaha']['pertanian_dominan'] = str_contains(strtoupper($lapMax['label']),'PERTANIAN') || str_contains(strtoupper($lapMax['label']),'PERIKANAN');
        $analysis['lapangan_usaha']['rekomendasi'] = $analysis['lapangan_usaha']['pertanian_dominan'] ? 'Modernisasi pertanian, pelatihan usaha, akses pasar, diversifikasi usaha.' : 'Penguatan sektor dominan melalui pelatihan dan akses pasar.';
    }

    // Imunisasi
    $analysis['imunisasi']['persen'] = round($analysis['imunisasi']['jumlah']/$total*100,1);
    $analysis['imunisasi']['tinggi'] = $analysis['imunisasi']['persen'] >= 15;
    $analysis['imunisasi']['rekomendasi'] = $analysis['imunisasi']['tinggi'] ? 'Program imunisasi gratis, edukasi orang tua, integrasi layanan kesehatan dan sosial.' : 'Pemantauan cakupan imunisasi.';

$auto_summary = [];
foreach ($analysis as $key => $val) {
    $judul = ucwords(str_replace('_',' ', $key));

    if ($key === 'lapangan_usaha') {
        $auto_summary[] = "Lapangan usaha didominasi oleh sektor **{$val['max_nama']} ({$val['persen']}%)**, disarankan kebijakan: {$val['rekomendasi']}.";
    } elseif ($key === 'status_kerja') {
        $auto_summary[] = "**Status Kerja**: pekerja tidak dibayar sebesar {$val['p_tidak_dibayar']}% dan wirausaha sebesar {$val['p_wira']}%. Rekomendasi: {$val['rekomendasi']}.";
    } else {
        $persen = $val['persen'] ?? 0; // antisipasi jika tidak ada
        $auto_summary[] = "**{$judul}**: sebesar {$persen}%, rekomendasi: {$val['rekomendasi']}.";
    }
}

    

    $auto_summary_text = "Berdasarkan hasil analisis data sosial ekonomi penduduk, diperoleh gambaran sebagai berikut:\n\n" .
        implode("\n", $auto_summary) .
        "\n\nRangkuman ini dapat digunakan sebagai dasar perencanaan intervensi pemerintah berbasis data.";

    return compact('data','summary','analysis') + ['auto_summary' => $auto_summary_text];
}

public function laporan()  // <-- TAMBAHKAN public
{
    $laporan = $this->getLaporanData();

    if (isset($laporan['error'])) {
        return back()->with('error', $laporan['error']);
    }

    return view('laporan.sosialekonomi', $laporan);
}

/**
 * Export laporan ke PDF
 */
public function exportPdf()  // <-- TAMBAHKAN public
{
    $laporan = $this->getLaporanData();

    if (isset($laporan['error'])) {
        return back()->with('error', $laporan['error']);
    }

    $tanggal_cetak = Carbon::now('Asia/Singapore')->translatedFormat('d F Y');

    $pdf = Pdf::loadView('laporan.sosialekonomi', array_merge($laporan, [
        'tanggal_cetak' => $tanggal_cetak
    ]))
        ->setPaper('A4', 'portrait')
        ->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
        ]);

    return $pdf->stream('Laporan-Sosial-Ekonomi' . now('Asia/Singapore')->format('Y-m-d') . '.pdf');        
}
}