<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataKeluarga;
use App\Models\DataPrasaranadasar;
use App\Models\DataAsetKeluarga;
use App\Models\MasterAsetKeluarga;
use App\Models\VoiceSession;
use App\Models\VoiceAnswer;
use Illuminate\Support\Facades\Storage;

class VoiceController extends Controller
{
    /** Halaman utama Voice Input */
    public function index()
    {
        return view('voice.index');
    }

    /** Halaman Voice Input Keluarga */
    public function keluarga()
    {
        $pertanyaanAset = MasterAsetKeluarga::pluck('asetkeluarga')->toArray();
        $mutasis = DB::table('master_mutasimasuk')->get();
        $dusuns = DB::table('master_dusun')->get();
        $provinsis = DB::table('master_provinsi')->get();
        $kabupatens = DB::table('master_kabupaten')->get();
        $kecamatans = DB::table('master_kecamatan')->get();
        $desas = DB::table('master_desa')->get();

        return view('voice.keluarga', compact(
            'pertanyaanAset',
            'mutasis',
            'dusuns',
            'provinsis',
            'kabupatens',
            'kecamatans',
            'desas'
        ));
    }

    /** Membuat sesi voice input */
    public function createSession(Request $request)
    {
        $session = VoiceSession::create([
            'session_type' => $request->type,
            'user_id' => auth()->id() ?? 1,
        ]);
        return response()->json(['session_id' => $session->id]);
    }

    /** Menyimpan jawaban suara */
    public function storeAnswer(Request $request)
    {
        $audio = $request->file('audio');
        $path = $audio ? $audio->store('voice/audio', 'public') : null;

        VoiceAnswer::create([
            'voice_session_id' => $request->session_id,
            'module' => $request->module,
            'field' => $request->field,
            'answer_text' => $request->answer_text,
            'answer_audio' => $path,
        ]);

        return response()->json(['success' => true]);
    }

    /** Simpan hasil akhir per modul */
    public function finalSave(Request $request)
    {
        $session = VoiceSession::with('answers')->findOrFail($request->session_id);
        $type = $request->module_type ?? $session->session_type;
        $answers = $session->answers->pluck('answer_text', 'field')->toArray();

        // Simpan ke tabel sesuai modul aktif
        switch ($type) {
            case 'keluarga':
                DataKeluarga::updateOrCreate(
                    ['no_kk' => $answers['no_kk'] ?? null],
                    [
                        'keluarga_kepalakeluarga' => $answers['kepala_keluarga'] ?? '',
                        'kddusun' => $answers['kddusun'] ?? null,
                        'keluarga_rw' => $answers['keluarga_rw'] ?? '',
                        'keluarga_rt' => $answers['keluarga_rt'] ?? '',
                        'keluarga_alamatlengkap' => $answers['keluarga_alamatlengkap'] ?? '',
                        'kdmutasimasuk' => $answers['kdmutasimasuk'] ?? null,
                        'keluarga_tanggalmutasi' => now(),
                    ]
                );
                break;

            case 'prasaranadasar':
                DataPrasaranadasar::updateOrCreate(
                    ['no_kk' => $answers['no_kk'] ?? null],
                    [
                        'kdstatuspemilikbangunan' => $answers['kdstatuspemilikbangunan'] ?? null,
                        'kdstatuspemiliklahan' => $answers['kdstatuspemiliklahan'] ?? null,
                        'prasdas_luaslantai' => $answers['prasdas_luaslantai'] ?? null,
                        'prasdas_jumlahkamar' => $answers['prasdas_jumlahkamar'] ?? null,
                    ]
                );
                break;

            case 'asetkeluarga':
                $noKK = $answers['no_kk'] ?? null;
                $data = ['no_kk' => $noKK];
                $asetList = MasterAsetKeluarga::pluck('kdasetkeluarga', 'asetkeluarga')->toArray();

                foreach ($answers as $q => $ans) {
                    foreach ($asetList as $aset => $kode) {
                        if (strtolower($q) === strtolower($aset)) {
                            $data["asetkeluarga_" . $kode] = in_array(strtolower($ans), ['ya', 'iya', 'betul']) ? 1 : 2;
                        }
                    }
                }

                DataAsetKeluarga::updateOrCreate(['no_kk' => $noKK], $data);
                break;
        }

        $session->update(['finished_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => "Data modul $type berhasil disimpan."
        ]);
    }
}
