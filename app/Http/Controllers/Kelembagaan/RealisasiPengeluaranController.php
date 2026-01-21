<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Http\Controllers\Controller;
use App\Models\PencairanDana;
use App\Models\RealisasiPengeluaran;
use Illuminate\Http\Request;

class RealisasiPengeluaranController extends Controller
{
    public function create($id)
    {
        $pencairan = PencairanDana::with('kegiatan', 'realisasi')->findOrFail($id);

        $sudah = $pencairan->realisasi->sum('jumlah');
        $sisa = $pencairan->jumlah - $sudah;

        return view('kelembagaan.realisasi.create', compact('pencairan', 'sisa'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:realisasi_pengeluaran,id',
            'tanggal' => 'required|date',
            'uraian' => 'required|string',
            'jumlah' => 'required|numeric|min:1'
        ]);

        $realisasi = RealisasiPengeluaran::findOrFail($request->id);
        $pencairan = $realisasi->pencairan;

        $totalDipakai = $pencairan->realisasi()
            ->where('id', '!=', $realisasi->id)
            ->sum('jumlah');

        $sisa = $pencairan->jumlah - $totalDipakai;

        if ($request->jumlah > $sisa) {
            return back()->withErrors(['jumlah' => 'Melebihi sisa dana pencairan']);
        }

        $realisasi->update([
            'tanggal' => $request->tanggal,
            'uraian' => $request->uraian,
            'jumlah' => $request->jumlah
        ]);

        return redirect()
            ->route('kelembagaan.pencairan.show', $pencairan->id)
            ->with('success', 'Realisasi berhasil diperbarui');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pencairan_id' => 'required',
            'tanggal' => 'required|date',
            'uraian' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $pencairan = PencairanDana::findOrFail($request->pencairan_id);

        $sudah = $pencairan->realisasi()->sum('jumlah');
        $sisa = $pencairan->jumlah - $sudah;

        if ($data['jumlah'] > $sisa) {
            return back()->withErrors(['jumlah' => 'Melebihi sisa dana pencairan']);
        }

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti-realisasi', 'public');
        }

        RealisasiPengeluaran::create($data);

        return redirect()
            ->route('kelembagaan.pencairan.show', $pencairan->id)
            ->with('success', 'Realisasi berhasil disimpan');
    }

    public function destroy($id)
    {
        RealisasiPengeluaran::findOrFail($id)->delete();
        return back()->with('success', 'Realisasi dihapus');
    }
}
