<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// MODELS PEMBANGUNAN
use App\Models\BukuProyek;
use App\Models\BukuKader;
use App\Models\BukuBantuan;

// MASTER
use App\Models\MasterKegiatan;
use App\Models\MasterPelaksana;
use App\Models\MasterLokasi;
use App\Models\MasterSumberDana;
use App\Models\MasterPendidikan;
use App\Models\MasterKaderBidang;
use App\Models\MasterStatusKader;
use App\Models\MasterSasaran;
use App\Models\MasterBantuan;

class VoicePembangunanController extends Controller
{
    /**
     * Halaman Voice Administrasi Pembangunan
     */
    public function index()
    {
        $masters = [
            // ===== MODUL 1 : PROYEK =====
            'kegiatan'  => MasterKegiatan::pluck('kegiatan', 'kdkegiatan'),
            'pelaksana' => MasterPelaksana::pluck('pelaksana', 'kdpelaksana'),
            'lokasi'    => MasterLokasi::pluck('lokasi', 'kdlokasi'),
            'sumber'    => MasterSumberDana::pluck('sumber_dana', 'kdsumber'),

            // ===== MODUL 2 : KADER =====
            'pendidikan' => MasterPendidikan::pluck('pendidikan', 'kdpendidikan'),
            'bidang'     => MasterKaderBidang::pluck('bidang', 'kdbidang'),
            'status_kader' => MasterStatusKader::pluck('statuskader', 'kdstatuskader'),

            // ===== MODUL 3 : BANTUAN =====
            'sasaran' => MasterSasaran::pluck('sasaran', 'kdsasaran'),
            'bantuan' => MasterBantuan::pluck('bantuan', 'kdbantuan'),
        ];

        return view('voice.pembangunan', compact('masters'));
    }

    /**
     * Simpan data Voice berdasarkan modul
     */
    public function storeAll(Request $request)
    {
        DB::beginTransaction();

        try {

            /* ======================================
               MODUL 1 — PROYEK PEMBANGUNAN
            ====================================== */
            if ($request->modul == 1) {

                $request->validate([
                    'kdproyek' => 'required',
                    'proyek_tanggal' => 'required|date',
                    'kdkegiatan' => 'required',
                    'kdpelaksana' => 'required',
                    'kdlokasi' => 'required',
                    'kdsumber' => 'required',
                    'proyek_nominal' => 'required|numeric',
                ]);

                BukuProyek::create([
                    'kdproyek' => $request->kdproyek,
                    'proyek_tanggal' => $request->proyek_tanggal,
                    'kdkegiatan' => $request->kdkegiatan,
                    'kdpelaksana' => $request->kdpelaksana,
                    'kdlokasi' => $request->kdlokasi,
                    'kdsumber' => $request->kdsumber,
                    'proyek_nominal' => $request->proyek_nominal,
                    'proyek_manfaat' => $request->proyek_manfaat,
                    'proyek_keterangan' => $request->proyek_keterangan,
                    'userinput' => Auth::user()->name ?? 'system',
                    'inputtime' => now(),
                ]);
            }

            /* ======================================
               MODUL 2 — KADER PEMBANGUNAN
            ====================================== */ else if ($request->modul == 2) {

                $request->validate([
                    'kdkader' => 'required|numeric',
                    'kader_tanggal' => 'required|date',
                    'kdpenduduk' => 'required|string|size:16',
                    'kdpendidikan' => 'required',
                    'kdbidang' => 'required',
                    'kdstatuskader' => 'required',
                ]);

                BukuKader::create([
                    'kdkader' => $request->kdkader,
                    'kader_tanggal' => $request->kader_tanggal,
                    'kdpenduduk' => $request->kdpenduduk,
                    'kdpendidikan' => $request->kdpendidikan,
                    'kdbidang' => $request->kdbidang,
                    'kdstatuskader' => $request->kdstatuskader,
                    'kader_keterangan' => $request->kader_keterangan,
                    'userinput' => Auth::user()->name ?? 'system',
                    'inputtime' => now(),
                ]);
            }

            /* ======================================
               MODUL 3 — BANTUAN PEMBANGUNAN
            ====================================== */ else if ($request->modul == 3) {

                $request->validate([
                    'kdsasaran' => 'required',
                    'kdbantuan' => 'required',
                    'bantuan_nama' => 'required',
                    'bantuan_awal' => 'required|date',
                    'bantuan_jumlah' => 'required|numeric',
                    'kdsumber' => 'required',
                ]);

                BukuBantuan::create([
                    'kdsasaran' => $request->kdsasaran,
                    'kdbantuan' => $request->kdbantuan,
                    'bantuan_nama' => $request->bantuan_nama,
                    'bantuan_awal' => $request->bantuan_awal,
                    'bantuan_akhir' => $request->bantuan_akhir,
                    'bantuan_jumlah' => $request->bantuan_jumlah,
                    'bantuan_keterangan' => $request->bantuan_keterangan,
                    'kdsumber' => $request->kdsumber,
                    'userinput' => Auth::user()->name ?? 'system',
                    'inputtime' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
