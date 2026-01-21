<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Http\Controllers\Controller;
use App\Models\KegiatanAnggaran;
use Illuminate\Http\Request;

class KegiatanAnggaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'anggaran_id' => 'required|exists:anggaran_kelembagaan,id',
            'kdsumber' => 'required|exists:master_sumber_dana,kdsumber',
            'nilai_anggaran' => 'required|numeric|min:1',
            'kegiatan_id' => 'nullable|exists:kegiatan,id'
        ]);

        // UPDATE
        if ($request->id) {
            $data = KegiatanAnggaran::findOrFail($request->id);
            $data->update([
                'kdsumber' => $request->kdsumber,
                'nilai_anggaran' => $request->nilai_anggaran,
            ]);
        }
        // CREATE
        else {
            $request->validate([
                'kegiatan_id' => 'required'
            ]);

            KegiatanAnggaran::create([
                'anggaran_id' => $request->anggaran_id,
                'kegiatan_id' => $request->kegiatan_id,
                'kdsumber' => $request->kdsumber,
                'nilai_anggaran' => $request->nilai_anggaran,
            ]);
        }

        return back()->with('success','Data anggaran kegiatan tersimpan');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        KegiatanAnggaran::findOrFail($id)->delete();
        return back()->with('success','Anggaran kegiatan dihapus');
    }

}
