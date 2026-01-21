<?php

namespace App\Http\Controllers\Voice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PelayananSurat;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoicePelayananController extends Controller
{
    public function index()
    {
        return view('voice.pelayanan'); // resources/views/voice/pelayanan.blade.php
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik'              => 'required|digits:16',
            'nama'             => 'required|string|max:100',
            'tempat_lahir'     => 'required|string|max:100',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'kewarganegaraan'  => 'required|string|max:50',
            'agama'            => 'required|string|max:50',
            'pekerjaan'        => 'required|string|max:100',
            'alamat'           => 'required|string',
            'keperluan'        => 'required|string',
            'keterangan_lain'  => 'nullable|string',
        ]);

        $data['status'] = 'menunggu';
        $data['user_id'] = Auth::id();

        Surat::create($data);

        return redirect()
            ->route('pelayanan.surat.index')
            ->with('success', 'Pengajuan surat berhasil dibuat.');
    }
}
