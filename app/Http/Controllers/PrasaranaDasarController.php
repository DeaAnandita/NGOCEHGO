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
    // Ambil semua data dengan relasi yang dibutuhkan
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
        'fasilitastempatbab',
        'pembuanganakhirtinja',
        'carapembuangansampah',
        'bahanbakarmemasak',
        'manfaatmataair'
    ])->get();

    // Ganti $records → $prasaranas
    $pdf = Pdf::loadView('keluarga.prasarana.export_pdf', compact('prasaranas'))
        ->setPaper('a4', 'landscape');

    return $pdf->download('data_prasarana.pdf');
}
}
