<?php

namespace App\Http\Controllers;

use App\Models\DataUsahaArt;
use App\Models\DataPenduduk;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterTempatUsaha;
use App\Models\MasterOmsetUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class UsahaArtController extends Controller
{
    /* ============================================================
     *  🔹 INDEX – LIST DATA USAHA ART
     * ============================================================
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $usahaarts = DataUsahaArt::with(['penduduk', 'lapanganusaha', 'tempatusaha', 'omsetusaha'])
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        return view('penduduk.usahaart.index', compact(
            'usahaarts',
            'search',
            'perPage'
        ));
    }

    /* ============================================================
     *  🔹 CREATE – FORM INPUT USAHA ART
     * ============================================================
     */
    public function create()
    {
        return view('penduduk.usahaart.create', [
            'penduduks'        => DataPenduduk::orderBy('penduduk_namalengkap')->get(),
            'lapangan_usahas'  => MasterLapanganUsaha::orderBy('lapanganusaha')->get(),
            'tempat_usahas'   => MasterTempatUsaha::all(),
            'omset_usahas'    => MasterOmsetUsaha::all(),
        ]);
    }

    /* ============================================================
     *  🔹 STORE – SIMPAN DATA USAHA ART
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik'                     => 'required|size:16|exists:data_penduduk,nik|unique:data_usahaart,nik',
            'kdlapanganusaha'         => 'required|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_namausaha'      => 'required|string|max:255',
            'usahaart_jumlahpekerja'  => 'required|integer|min:0',
            'kdtempatusaha'           => 'required|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha'            => 'required|exists:master_omsetusaha,kdomsetusaha',
        ]);

        DataUsahaArt::create([
            'nik'                    => $request->nik,
            'kdlapanganusaha'        => $request->kdlapanganusaha,
            'usahaart_namausaha'     => $request->usahaart_namausaha,
            'usahaart_jumlahpekerja' => $request->usahaart_jumlahpekerja,
            'kdtempatusaha'          => $request->kdtempatusaha,
            'kdomsetusaha'           => $request->kdomsetusaha,
        ]);

        return redirect()
            ->route('penduduk.usahaart.index')
            ->with('success', 'Data usaha rumah tangga berhasil ditambahkan.');
    }

    /* ============================================================
     *  🔹 EDIT – FORM EDIT USAHA ART
     * ============================================================
     */
    public function edit($nik)
    {
        $usahaart = DataUsahaArt::with(['penduduk'])->where('nik', $nik)->firstOrFail();

        return view('penduduk.usahaart.edit', [
            'usahaart'         => $usahaart,
            'penduduks'        => DataPenduduk::orderBy('penduduk_namalengkap')->get(),
            'lapangan_usahas'  => MasterLapanganUsaha::orderBy('lapanganusaha')->get(),
            'tempat_usahas'   => MasterTempatUsaha::all(),
            'omset_usahas'    => MasterOmsetUsaha::all(),
        ]);
    }

    /* ============================================================
     *  🔹 UPDATE – UPDATE DATA USAHA ART
     * ============================================================
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik'                     => 'required|size:16|exists:data_penduduk,nik|unique:data_usahaart,nik,' . $nik . ',nik',
            'kdlapanganusaha'         => 'required|exists:master_lapanganusaha,kdlapanganusaha',
            'usahaart_namausaha'      => 'required|string|max:255',
            'usahaart_jumlahpekerja'  => 'required|integer|min:0',
            'kdtempatusaha'           => 'required|exists:master_tempatusaha,kdtempatusaha',
            'kdomsetusaha'            => 'required|exists:master_omsetusaha,kdomsetusaha',
        ]);

        $usahaart = DataUsahaArt::where('nik', $nik)->firstOrFail();

        $usahaart->update([
            'nik'                    => $request->nik,
            'kdlapanganusaha'        => $request->kdlapanganusaha,
            'usahaart_namausaha'     => $request->usahaart_namausaha,
            'usahaart_jumlahpekerja' => $request->usahaart_jumlahpekerja,
            'kdtempatusaha'          => $request->kdtempatusaha,
            'kdomsetusaha'           => $request->kdomsetusaha,
        ]);

        return redirect()
            ->route('penduduk.usahaart.index')
            ->with('success', 'Data usaha rumah tangga berhasil diperbarui.');
    }

    /* ============================================================
     *  🔹 DESTROY – HAPUS DATA USAHA ART
     * ============================================================
     */
    public function destroy($nik)
    {
        $usahaart = DataUsahaArt::where('nik', $nik)->firstOrFail();
        $usahaart->delete();

        return redirect()
            ->route('penduduk.usahaart.index')
            ->with('success', 'Data usaha rumah tangga berhasil dihapus.');
    }

    /* ============================================================
     *  📊 EXPORT PDF – ANALISIS USAHA RUMAH TANGGA (REAL DATA)
     * ============================================================
     */
public function exportPdf()
{
    $data = DB::table('data_usahaart')
        ->join('master_lapanganusaha', 'data_usahaart.kdlapanganusaha', '=', 'master_lapanganusaha.kdlapanganusaha')
        ->join('master_tempatusaha', 'data_usahaart.kdtempatusaha', '=', 'master_tempatusaha.kdtempatusaha')
        ->join('master_omsetusaha', 'data_usahaart.kdomsetusaha', '=', 'master_omsetusaha.kdomsetusaha')
        ->select(
            'master_lapanganusaha.lapanganusaha',
            'master_tempatusaha.tempatusaha',
            'master_omsetusaha.omsetusaha',
            'data_usahaart.usahaart_jumlahpekerja'
        )
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Tidak ada data usaha rumah tangga untuk dianalisis.');
    }

    $totalUsaha = $data->count();

    // Distribusi Lapangan Usaha
    $lapanganUsaha = $data->groupBy('lapanganusaha')
        ->map(fn($i) => [
            'jumlah' => $i->count(),
            'persen' => round(($i->count() / $totalUsaha) * 100, 1)
        ])
        ->sortByDesc('jumlah');

    // Distribusi Omzet
    $omsetUsaha = $data->groupBy('omsetusaha')
        ->map(fn($i) => [
            'jumlah' => $i->count(),
            'persen' => round(($i->count() / $totalUsaha) * 100, 1)
        ])
        ->sortByDesc('jumlah'); // Urutkan dari terbanyak

    // Distribusi Tempat Usaha
    $tempatUsaha = $data->groupBy('tempatusaha')
        ->map(fn($i) => [
            'jumlah' => $i->count(),
            'persen' => round(($i->count() / $totalUsaha) * 100, 1)
        ])
        ->sortByDesc('jumlah');

    $rataPekerja = round($data->avg('usahaart_jumlahpekerja'), 2);

    // Data dominan
    $lapanganDominan = $lapanganUsaha->keys()->first();
    $persenLapanganDominan = $lapanganUsaha->first()['persen'] ?? 0;

    $omsetDominan = $omsetUsaha->keys()->first();
    $persenOmsetDominan = $omsetUsaha->first()['persen'] ?? 0;

    $tempatDominan = $tempatUsaha->keys()->first();
    $persenTempatDominan = $tempatUsaha->first()['persen'] ?? 0;

    // Omset terendah (untuk analisis skala mikro)
    $omsetTerendah = $omsetUsaha->sortBy('jumlah')->keys()->first();
    $persenOmsetTerendah = $omsetUsaha->sortBy('jumlah')->first()['persen'] ?? 0;

    // Cek apakah mayoritas milik sendiri
    $persenMilikSendiri = $tempatUsaha->firstWhere(fn($v, $k) => str_contains(strtolower($k), 'milik sendiri'))['persen']
                          ?? $tempatUsaha->get('Milik Sendiri')['persen'] ?? 0;

    $viewData = [
        'periode'               => Carbon::now()->translatedFormat('F Y'),
        'tanggal'               => Carbon::now()->translatedFormat('d F Y'),
        'totalUsaha'            => $totalUsaha,
        'rataPekerja'           => $rataPekerja,
        'lapanganUsaha'         => $lapanganUsaha,
        'omsetUsaha'            => $omsetUsaha,
        'tempatUsaha'           => $tempatUsaha,

        // Untuk analisis dinamis
        'lapanganDominan'       => $lapanganDominan,
        'persenLapanganDominan' => $persenLapanganDominan,
        'omsetDominan'          => $omsetDominan,
        'persenOmsetDominan'    => $persenOmsetDominan,
        'omsetTerendah'         => $omsetTerendah,
        'persenOmsetTerendah'   => $persenOmsetTerendah,
        'tempatDominan'         => $tempatDominan,
        'persenTempatDominan'   => $persenTempatDominan,
        'persenMilikSendiri'    => $persenMilikSendiri,
    ];

    $pdf = Pdf::loadView('laporan.usahaart', $viewData)
        ->setPaper('A4', 'portrait');

    return $pdf->stream('Laporan-Analisis-Usaha-Rumah-Tangga-' . now()->format('Y-m-d') . '.pdf');
}
}