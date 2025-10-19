<?php

namespace App\Http\Controllers;

use App\Models\{DataPrasaranaDasar, 
MasterStatusPemilikBangunan, MasterStatusPemilikLahan, MasterJenisFisikBangunan,
MasterKondisiLantaiBangunan, MasterJenisLantaiBangunan, MasterJenisDindingBangunan, MasterKondisiDindingBangunan,
MasterJenisAtapBangunan, MasterKondisiAtapBangunan, MasterSumberAirMinum, MasterKondisiSumberAir,
MasterCaraPerolehanAir, MasterSumberPeneranganUtama, MasterSumberDayaTerpasang, MasterBahanBakarMemasak,
MasterFasilitasTempatBab,MasterPembuanganAkhirTinja, MasterCaraPembuanganSampah, MasterManfaatMataAir};
use App\Models\DataKeluarga;
use Illuminate\Http\Request;

class PrasaranaDasarController extends Controller
{
    public function index()
    {
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
        ])->get();

        return view('keluarga.prasarana.index', compact('prasaranas'));
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
}
