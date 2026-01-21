<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/* =======================
   MODELS DATA
======================= */
use App\Models\BukuAgendaLembaga;
use App\Models\BukuAparat;
use App\Models\BukuEkspedisi;
use App\Models\BukuInventaris;
use App\Models\BukuKeputusan;
use App\Models\BukuPeraturan;
use App\Models\BukuTanahDesa;
use App\Models\BukuTanahKasDesa;

/* =======================
   MASTER DATA
======================= */
use App\Models\MasterAparat;
use App\Models\MasterAsalBarang;
use App\Models\MasterJenisAgendaUmum;
use App\Models\MasterJenisKeputusanUmum;
use App\Models\MasterJenisPemilik;
use App\Models\MasterJenisPeraturanDesa;
use App\Models\MasterJenisTKD;
use App\Models\MasterMutasiTanah;
use App\Models\MasterPapanNama;
use App\Models\MasterPatok;
use App\Models\MasterPengguna;
use App\Models\MasterPenggunaanTanah;
use App\Models\MasterPerolehanTKD;
use App\Models\MasterSatuanBarang;
use App\Models\MasterStatusHakTanah;
use Illuminate\Support\Facades\Storage;

class VoiceUmumController extends Controller
{
    public function index()
    {
        return view('voice.umum', [
            'masters' => [

                // ===== APARAT =====
                'aparat' => MasterAparat::pluck('aparat', 'kdaparat'),

                // ===== PERATURAN / AGENDA / KEPUTUSAN =====
                'jenis_peraturan' => MasterJenisPeraturanDesa::pluck('jenisperaturandesa', 'kdjenisperaturandesa'),
                'jenis_agenda' => MasterJenisAgendaUmum::pluck('jenisagenda_umum', 'kdjenisagenda_umum'),
                'jenis_keputusan' => MasterJenisKeputusanUmum::pluck('jeniskeputusan_umum', 'kdjeniskeputusan_umum'),

                // ===== TANAH KAS DESA =====
                'perolehantkd' => MasterPerolehanTKD::pluck('perolehantkd', 'kdperolehantkd'),
                'jenistkd' => MasterJenisTKD::pluck('jenistkd', 'kdjenistkd'),
                'patok' => MasterPatok::pluck('patok', 'kdpatok'),
                'papannama' => MasterPapanNama::pluck('papannama', 'kdpapannama'),

                // ===== TANAH DESA =====
                'jenispemilik' => MasterJenisPemilik::pluck('jenispemilik', 'kdjenispemilik'),
                'statushak_tanah' => MasterStatusHakTanah::pluck('statushaktanah', 'kdstatushaktanah'),
                'penggunaan_tanah' => MasterPenggunaanTanah::pluck('penggunaantanah', 'kdpenggunaantanah'),
                'mutasi_tanah' => MasterMutasiTanah::pluck('mutasitanah', 'kdmutasitanah'),

                // ===== INVENTARIS =====
                'pengguna' => MasterPengguna::pluck('pengguna', 'kdpengguna'),
                'satuanbarang' => MasterSatuanBarang::pluck('satuanbarang', 'kdsatuanbarang'),
                'asalbarang' => MasterAsalBarang::pluck('asalbarang', 'kdasalbarang'),
            ]
        ]);
    }


    public function storeAll(Request $request)
    {
        DB::beginTransaction();

        // biar bisa dihapus kalau transaksi gagal
        $uploadedPaths = [];

        try {
            switch ((int) $request->modul) {

                /* =======================
               1. PERATURAN
            ======================= */
                case 1:
                    $request->validate([
                        'kdperaturan' => 'required|unique:buku_peraturans,kdperaturan',
                        'kdjenisperaturandesa' => 'required',
                        'nomorperaturan' => 'required',
                        'judulpengaturan' => 'required',
                    ]);

                    BukuPeraturan::create([
                        'kdperaturan' => $request->kdperaturan,
                        'kdjenisperaturandesa' => $request->kdjenisperaturandesa,
                        'nomorperaturan' => $request->nomorperaturan,
                        'judulpengaturan' => $request->judulpengaturan,
                        'uraianperaturan' => $request->uraianperaturan,
                        'kesepakatanperaturan' => $request->kesepakatanperaturan,
                        'keteranganperaturan' => $request->keteranganperaturan,
                        'filepengaturan' => null, // kalau nanti ada upload, tinggal ditambah
                        'userinput' => Auth::user()->name ?? 'system',
                        'inputtime' => now(),
                    ]);
                    break;

                /* =======================
               2. KEPUTUSAN  ✅ FIX
            ======================= */
                case 2:
                    $request->validate([
                        'kd_keputusan' => 'required|unique:buku_keputusan,kd_keputusan',
                        'nomor_keputusan' => 'required',
                        'tanggal_keputusan' => 'required|date',
                        'judul_keputusan' => 'required',
                        'kdjeniskeputusan_umum' => [
                            'required',
                            \Illuminate\Validation\Rule::exists((new \App\Models\MasterJenisKeputusanUmum)->getTable(), 'kdjeniskeputusan_umum'),
                        ],
                        'file_keputusan' => 'nullable|mimes:pdf|max:2048',
                    ]);

                    $filePath = null;
                    if ($request->hasFile('file_keputusan')) {
                        $filePath = $request->file('file_keputusan')->store('keputusan', 'public');
                        $uploadedPaths[] = $filePath;
                    }

                    BukuKeputusan::create([
                        'kd_keputusan' => $request->kd_keputusan,
                        'nomor_keputusan' => $request->nomor_keputusan,
                        'tanggal_keputusan' => $request->tanggal_keputusan,
                        'judul_keputusan' => $request->judul_keputusan,
                        'kdjeniskeputusan_umum' => $request->kdjeniskeputusan_umum,
                        'uraian_keputusan' => $request->uraian_keputusan,
                        'keterangan_keputusan' => $request->keterangan_keputusan,
                        'file_keputusan' => $filePath,
                        'userinput' => Auth::user()->name ?? 'system',
                        'inputtime' => now(),
                    ]);
                    break;

                /* =======================
               3. APARAT ✅ FIX (namaaparat + foto)
            ======================= */
                case 3:
                    $request->validate([
                        'kdaparat' => 'required|exists:master_aparat,kdaparat',
                        'namaaparat' => 'required|string|max:150',
                        'nipaparat' => 'nullable|string|max:50',
                        'nik' => 'nullable|string|max:20',
                        'pangkataparat' => 'nullable|string|max:100',
                        'nomorpengangkatan' => 'nullable|string|max:100',
                        'tanggalpengangkatan' => 'nullable|date',
                        'keteranganaparatdesa' => 'nullable|string',
                        'statusaparatdesa' => 'required|in:Aktif,Nonaktif',
                        'fotopengangkatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    ]);

                    $foto = null;
                    if ($request->hasFile('fotopengangkatan')) {
                        $foto = $request->file('fotopengangkatan')->store('aparat', 'public');
                        $uploadedPaths[] = $foto;
                    }

                    BukuAparat::create([
                        'kdaparat' => $request->kdaparat,
                        'namaaparat' => $request->namaaparat,
                        'nipaparat' => $request->nipaparat,
                        'nik' => $request->nik,
                        'pangkataparat' => $request->pangkataparat,
                        'nomorpengangkatan' => $request->nomorpengangkatan,
                        'tanggalpengangkatan' => $request->tanggalpengangkatan,
                        'keteranganaparatdesa' => $request->keteranganaparatdesa,
                        'statusaparatdesa' => $request->statusaparatdesa,
                        'fotopengangkatan' => $foto,
                        'userinput' => Auth::user()->name ?? 'system',
                        'inputtime' => now(),
                    ]);
                    break;

                /* =======================
               4. TANAH KAS DESA
            ======================= */
                case 4:
                    $request->validate([
                        'kdtanahkasdesa' => 'required|unique:buku_tanah_kas_desa,kdtanahkasdesa',
                    ]);

                    BukuTanahKasDesa::create(array_merge(
                        $request->only([
                            'kdtanahkasdesa',
                            'asaltanahkasdesa',
                            'sertifikattanahkasdesa',
                            'luastanahkasdesa',
                            'kelastanahkasdesa',
                            'tanggaltanahkasdesa',
                            'kdperolehantkd',
                            'kdjenistkd',
                            'kdpatok',
                            'kdpapannama',
                            'lokasitanahkasdesa',
                            'peruntukantanahkasdesa',
                            'mutasitanahkasdesa',
                            'keterangantanahkasdesa'
                        ]),
                        [
                            'fototanahkasdesa' => null,
                            'userinput' => Auth::user()->name ?? 'system',
                            'inputtime' => now(),
                        ]
                    ));
                    break;

                /* =======================
               5. TANAH DESA
            ======================= */
                case 5:
                    $request->validate([
                        'kdtanahdesa' => 'required|unique:buku_tanahdesa,kdtanahdesa',
                        'tanggaltanahdesa' => 'required|date',
                        'pemiliktanahdesa' => 'required',
                    ]);

                    BukuTanahDesa::create(array_merge(
                        $request->only([
                            'kdtanahdesa',
                            'tanggaltanahdesa',
                            'kdjenispemilik',
                            'pemiliktanahdesa',
                            'kdpemilik',
                            'luastanahdesa',
                            'kdstatushaktanah',
                            'kdpenggunaantanah',
                            'kdmutasitanah',
                            'tanggalmutasitanahdesa',
                            'keterangantanahdesa'
                        ]),
                        [
                            'fototanahdesa' => null,
                            'userinput' => Auth::user()->name ?? 'system',
                            'inputtime' => now(),
                        ]
                    ));
                    break;

                /* =======================
               6. AGENDA LEMBAGA
            ======================= */
                case 6:
                    $request->validate([
                        'kdagendalembaga' => 'required|unique:buku_agendalembaga,kdagendalembaga',
                        'kdjenisagenda_umum' => 'required',
                        'agendalembaga_tanggal' => 'required|date',
                        'agendalembaga_identitassurat' => 'required',
                    ]);

                    BukuAgendaLembaga::create(array_merge(
                        $request->only([
                            'kdagendalembaga',
                            'kdjenisagenda_umum',
                            'agendalembaga_tanggal',
                            'agendalembaga_nomorsurat',
                            'agendalembaga_tanggalsurat',
                            'agendalembaga_identitassurat',
                            'agendalembaga_isisurat',
                            'agendalembaga_keterangan'
                        ]),
                        [
                            'agendalembaga_file' => null,
                            'userinput' => Auth::user()->name ?? 'system',
                            'inputtime' => now(),
                        ]
                    ));
                    break;

                /* =======================
               7. EKSPEDISI
            ======================= */
                case 7:
                    $request->validate([
                        'kdekspedisi' => 'required|unique:buku_ekspedisi,kdekspedisi',
                        'ekspedisi_tanggal' => 'required|date',
                        'ekspedisi_identitassurat' => 'required',
                    ]);

                    BukuEkspedisi::create(array_merge(
                        $request->only([
                            'kdekspedisi',
                            'ekspedisi_tanggal',
                            'ekspedisi_tanggalsurat',
                            'ekspedisi_nomorsurat',
                            'ekspedisi_identitassurat',
                            'ekspedisi_isisurat',
                            'ekspedisi_keterangan'
                        ]),
                        [
                            'ekspedisi_file' => null,
                            'userinput' => Auth::user()->name ?? 'system',
                            'inputtime' => now(),
                        ]
                    ));
                    break;

                /* =======================
               8. INVENTARIS
            ======================= */
                case 8:
                    $request->validate([
                        'kdinventaris' => 'required|unique:buku_inventaris,kdinventaris',
                        'inventaris_tanggal' => 'required|date',
                        'inventaris_identitas' => 'required',
                    ]);

                    BukuInventaris::create(array_merge(
                        $request->only([
                            'kdinventaris',
                            'inventaris_tanggal',
                            'kdpengguna',
                            'anak',
                            'inventaris_volume',
                            'kdsatuanbarang',
                            'inventaris_identitas',
                            'kdasalbarang',
                            'barangasal',
                            'inventaris_harga',
                            'inventaris_keterangan'
                        ]),
                        [
                            'inventaris_foto' => null,
                            'inventaris_hapus' => 0,
                            'userinput' => Auth::user()->name ?? 'system',
                            'inputtime' => now(),
                        ]
                    ));
                    break;

                default:
                    throw new \Exception("Modul tidak dikenal: " . (int)$request->modul);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // hapus file yang terlanjur keupload kalau transaksi gagal
            foreach ($uploadedPaths as $path) {
                try {
                    Storage::disk('public')->delete($path);
                } catch (\Throwable $ignored) {
                }
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
