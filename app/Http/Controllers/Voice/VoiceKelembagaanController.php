<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use App\Models\AgendaKelembagaan;
use App\Models\AnggaranKelembagaan;
use App\Models\kegiatan;
use App\Models\KegiatanAnggaran;
use App\Models\Keputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

// MASTER
use App\Models\MasterJabatanKelembagaan;
use App\Models\MasterUnitKelembagaan;
use App\Models\MasterPeriodeKelembagaan;
use App\Models\MasterPeriodeKelembagaanAkhir;
use App\Models\MasterStatusPengurusKelembagaan;
use App\Models\MasterJenisSkKelembagaan;
use App\Models\PencairanDana;
// DATA
use App\Models\PengurusKelembagaan;
use App\Models\RealisasiPengeluaran;

class VoiceKelembagaanController extends Controller
{
    /**
     * Halaman Voice Input Kelembagaan
     */
    public function index()
    {
        $masters = [
            // ===== Modul 1 — Pengurus =====
            'jabatan'       => MasterJabatanKelembagaan::pluck('jabatan', 'kdjabatan'),
            'unit'          => MasterUnitKelembagaan::pluck('nama_unit', 'kdunit'),
            'periode'       => MasterPeriodeKelembagaan::pluck('tahun_awal', 'kdperiode'),
            'periode_akhir' => MasterPeriodeKelembagaanAkhir::pluck('akhir', 'kdperiode'),
            'status'        => MasterStatusPengurusKelembagaan::pluck('status_pengurus', 'kdstatus'),
            'jenis_sk'      => MasterJenisSkKelembagaan::pluck('jenis_sk', 'kdjenissk'),

            // ===== Modul 2 — Keputusan (INI YANG KURANG) =====
            'jenis'   => \App\Models\MasterJenisKeputusan::pluck('jenis_keputusan', 'kdjenis'),
            'metode'  => \App\Models\MasterMetodeKeputusan::pluck('metode', 'kdmetode'),
            'status_keputusan' => \App\Models\MasterStatusKeputusan::pluck('status_keputusan', 'kdstatus'),
            'unit_keputusan'   => \App\Models\MasterUnitKeputusan::pluck('unit_keputusan', 'kdunit'),

            // ===== Modul 3 — Kegiatan =====
            'jenis_kegiatan' => \App\Models\MasterJenisKegiatan::pluck('jenis_kegiatan', 'kdjenis'),
            'status_kegiatan' => \App\Models\MasterStatusKegiatan::pluck('status_kegiatan', 'kdstatus'),
            'sumber_dana' => \App\Models\MasterSumberDana::pluck('sumber_dana', 'kdsumber'),
            'keputusan' => \App\Models\Keputusan::orderBy('tanggal_keputusan', 'desc')
                ->pluck('judul_keputusan', 'id'),
            // ===== Modul 4 — Agenda =====
            'jenis_agenda'  => \App\Models\MasterJenisAgenda::pluck('jenis_agenda', 'kdjenis'),
            'status_agenda' => \App\Models\MasterStatusAgenda::pluck('status_agenda', 'kdstatus'),
            'tempat_agenda' => \App\Models\MasterTempatAgenda::pluck('tempat_agenda', 'kdtempat'),
            'kegiatan' => \App\Models\Kegiatan::pluck('nama_kegiatan', 'id'),

        ];

        return view('voice.kelembagaan', compact('masters'));
    }
    private function parseFlexibleDate(?string $value): ?string
    {
        if (!$value) return null;

        $value = trim($value);

        // sudah ISO
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // format MM/DD/YYYY (contoh 01/20/2026)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        }

        // fallback Carbon parse (misal "20 january 2026")
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Simpan semua data dari Voice UI
     */
    public function storeAll(Request $request)
    {

        DB::beginTransaction();

        try {

            /* =====================================================
         MODUL 1 — PENGURUS
        ===================================================== */
            if ($request->modul == 1) {

                $request->validate([
                    'nomor_induk' => 'required|digits:16|unique:pengurus_kelembagaan,nomor_induk',
                    'nama_lengkap' => 'required',
                    'jenis_kelamin' => 'required|in:L,P',
                    'no_hp' => 'required',
                    'email' => 'required|email',

                    'kdjabatan' => 'required',
                    'kdunit' => 'required',
                    'kdperiode' => 'required',
                    'kdperiode_akhir' => 'required',
                    'kdstatus' => 'required',
                    'kdjenissk' => 'required',

                    'no_sk' => 'required',
                    'tanggal_sk' => 'required',

                    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                ]);

                $data = [
                    'nomor_induk' => $request->nomor_induk,
                    'nama_lengkap' => $request->nama_lengkap,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'no_hp' => $request->no_hp,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'kdjabatan' => $request->kdjabatan,
                    'kdunit' => $request->kdunit,
                    'kdperiode' => $request->kdperiode,
                    'kdperiode_akhir' => $request->kdperiode_akhir,
                    'kdstatus' => $request->kdstatus,
                    'kdjenissk' => $request->kdjenissk,
                    'no_sk' => $request->no_sk,
                    'tanggal_sk' => $this->parseFlexibleDate($request->tanggal_sk),
                    'keterangan' => $request->keterangan,
                ];

                if ($request->hasFile('foto')) {
                    $data['foto'] = $request->file('foto')->store('pengurus/foto', 'public');
                }

                if ($request->hasFile('tanda_tangan')) {
                    $data['tanda_tangan'] = $request->file('tanda_tangan')->store('pengurus/ttd', 'public');
                }

                PengurusKelembagaan::create($data);
            }

            /* =====================================================
         MODUL 2 — KEPUTUSAN
        ===================================================== */ else if ($request->modul == 2) {

                $request->validate([
                    'nomor_sk' => 'required',
                    'judul_keputusan' => 'required',
                    'kdjenis' => 'required',
                    'kdunit' => 'required',
                    'kdperiode' => 'required',
                    'kdjabatan' => 'required',
                    'tanggal_keputusan' => 'required|date',
                    'kdstatus' => 'required',
                    'kdmetode' => 'required',
                ]);

                Keputusan::create([
                    'nomor_sk' => $request->nomor_sk,
                    'judul_keputusan' => $request->judul_keputusan,
                    'kdjenis' => $request->kdjenis,
                    'kdunit' => $request->kdunit,
                    'kdperiode' => $request->kdperiode,
                    'kdjabatan' => $request->kdjabatan,
                    'tanggal_keputusan' => $request->tanggal_keputusan,
                    'kdstatus' => $request->kdstatus,
                    'kdmetode' => $request->kdmetode,
                ]);
            }

            /* =====================================================
         MODUL 3 — KEGIATAN
        ===================================================== */ else if ($request->modul == 3) {

                $request->validate([
                    'nama_kegiatan' => 'required',
                    'kdjenis' => 'required',
                    'kdunit' => 'required',
                    'kdperiode' => 'required',
                    'kdstatus' => 'required',
                    'kdsumber' => 'required',
                    'pagu_anggaran' => 'required|numeric',
                    'tgl_mulai' => 'required|date',
                ]);

                Kegiatan::create([
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'kdjenis' => $request->kdjenis,
                    'kdunit' => $request->kdunit,
                    'kdperiode' => $request->kdperiode,
                    'kdstatus' => $request->kdstatus,
                    'kdsumber' => $request->kdsumber,
                    'pagu_anggaran' => $request->pagu_anggaran,
                    'tgl_mulai' => $request->tgl_mulai,
                    'lokasi' => $request->lokasi,
                    'keputusan_id' => $request->keputusan_id,
                ]);
            }

            /* =====================================================
         MODUL 4 — AGENDA
        ===================================================== */ else if ($request->modul == 4) {

                $request->validate([
                    'judul_agenda' => 'required',
                    'kdjenis' => 'required',
                    'kdunit' => 'required',
                    'kdstatus' => 'required',
                    'kdtempat' => 'required',
                    'kdperiode' => 'required',
                    'tanggal' => 'required|date',
                    'jam_mulai' => 'required',
                ]);

                AgendaKelembagaan::create([
                    'judul_agenda' => $request->judul_agenda,
                    'kdjenis' => $request->kdjenis,
                    'kdunit' => $request->kdunit,
                    'kdstatus' => $request->kdstatus,
                    'kdtempat' => $request->kdtempat,
                    'kdperiode' => $request->kdperiode,
                    'tanggal' => $request->tanggal,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'uraian_agenda' => $request->uraian_agenda,
                ]);
            }

            /* =====================================================
         MODUL 5 — ANGGARAN
        ===================================================== */ else if ($request->modul == 5) {

                $request->validate([
                    'kdunit' => 'required',
                    'kdperiode' => 'required',
                    'kdsumber' => 'required',
                    'total_anggaran' => 'required|numeric|min:1',
                    'items' => 'required',
                ]);

                $items = json_decode($request->items, true);

                $anggaran = AnggaranKelembagaan::create([
                    'kdunit' => $request->kdunit,
                    'kdperiode' => $request->kdperiode,
                    'kdsumber' => $request->kdsumber,
                    'total_anggaran' => $request->total_anggaran,
                    'keterangan' => $request->keterangan,
                ]);

                foreach ($items as $row) {
                    KegiatanAnggaran::create([
                        'anggaran_id' => $anggaran->id,
                        'kegiatan_id' => $row['kegiatan_id'],
                        'kdsumber' => $row['kdsumber'],
                        'nilai_anggaran' => $row['nilai'],
                    ]);
                }
            }

            /* =====================================================
         MODUL 6 — PENCAIRAN + REALISASI
        ===================================================== */ else if ($request->modul == 6) {

                $request->validate([
                    'kegiatan_id' => 'required',
                    'tanggal_cair' => 'required|date',
                    'jumlah' => 'required|numeric|min:1',
                    'items' => 'required',
                ]);

                $kegiatan = Kegiatan::findOrFail($request->kegiatan_id);

                $sudah = $kegiatan->pencairanDana()->sum('jumlah');
                $sisa = $kegiatan->pagu_anggaran - $sudah;

                if ($request->jumlah > $sisa) {
                    return response()->json(['message' => 'Melebihi sisa anggaran kegiatan'], 422);
                }

                $pencairan = PencairanDana::create([
                    'kegiatan_id' => $request->kegiatan_id,
                    'tanggal_cair' => $request->tanggal_cair,
                    'jumlah' => $request->jumlah,
                    'no_sp2d' => $request->no_sp2d,
                ]);

                $items = json_decode($request->items, true);

                foreach ($items as $i => $row) {

                    $total = RealisasiPengeluaran::where('pencairan_id', $pencairan->id)->sum('jumlah');

                    if ($total + $row['jumlah'] > $pencairan->jumlah) {
                        throw new \Exception('Realisasi melebihi dana pencairan');
                    }

                    $data = [
                        'pencairan_id' => $pencairan->id,
                        'tanggal' => $row['tanggal'],
                        'uraian' => $row['uraian'],
                        'jumlah' => $row['jumlah'],
                    ];

                    if ($request->hasFile("bukti_$i")) {
                        $data['bukti'] = $request->file("bukti_$i")->store('bukti-realisasi', 'public');
                    }

                    RealisasiPengeluaran::create($data);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function storeAnggaranDetail(Request $request)
    {
        $request->validate([
            'anggaran_id' => 'required|exists:anggaran_kelembagaan,id',
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'kdsumber' => 'required|exists:master_sumber_dana,kdsumber',
            'nilai_anggaran' => 'required|numeric|min:0'
        ]);

        // ambil total anggaran
        $anggaran = AnggaranKelembagaan::findOrFail($request->anggaran_id);

        // hitung yang sudah dipakai
        $used = KegiatanAnggaran::where('anggaran_id', $request->anggaran_id)
            ->sum('nilai_anggaran');

        if ($used + $request->nilai_anggaran > $anggaran->total_anggaran) {
            return response()->json([
                'message' => 'Nilai melebihi sisa anggaran',
                'sisa' => $anggaran->total_anggaran - $used
            ], 422);
        }

        KegiatanAnggaran::create([
            'anggaran_id' => $request->anggaran_id,
            'kegiatan_id' => $request->kegiatan_id,
            'kdsumber' => $request->kdsumber,
            'nilai_anggaran' => $request->nilai_anggaran,
        ]);

        return response()->json([
            'success' => true,
            'sisa' => $anggaran->total_anggaran - ($used + $request->nilai_anggaran)
        ]);
    }


    /**
     * Detail Pengurus (untuk view / ajax)
     */
    public function show($id)
    {
        $pengurus = \App\Models\PengurusKelembagaan::with([
            'jabatan',
            'unit',
            'periodeAwal',
            'periodeAkhir',
            'status',
            'jenisSk'
        ])->findOrFail($id);

        // Ambil semua keputusan yang berkaitan dengan pengurus ini
        // (jika belum ada FK, ambil semua keputusan terbaru saja)
        $keputusan = \App\Models\Keputusan::with([
            'jenis',
            'unit',
            'periode',
            'status',
            'metode',
            'jabatan'
        ])->latest()->get();

        return response()->json([
            'pengurus' => $pengurus,
            'keputusan' => $keputusan
        ]);
    }
}
