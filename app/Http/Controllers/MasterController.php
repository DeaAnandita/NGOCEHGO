<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Models master
use App\Models\{
    MasterAgama, MasterAktaNikah, MasterAsetKeluarga, MasterAsetLahan,
    MasterAsetTernak, MasterAsetPerikanan, MasterBahanBakarMemasak,
    MasterCaraPembuanganSampah, MasterCaraPerolehanAir, MasterDusun,
    MasterFasilitasTempatBab, MasterHubunganKeluarga, MasterHubunganKepalaKeluarga, MasterImunisasi,
    MasterInventaris, MasterJabatan, MasterJawab, MasterJawabBangun,
    MasterJawabKonflik, MasterJawabKualitasBayi, MasterJawabKualitasIbuHamil,
    MasterJawabLemdes, MasterJawabLemek, MasterJawabLemmas, MasterJawabSarpras, MasterJawabProgramSerta,
    MasterJawabTempatPersalinan, MasterJenisAtapBangunan, MasterJenisBahanGalian,
    MasterJenisDindingBangunan, MasterJenisDisabilitas, MasterJenisFisikBangunan,
    MasterJenisKelahiran, MasterJenisKelamin, MasterJenisLantaiBangunan,
    MasterJenisLembaga, MasterKartuIdentitas, MasterKabupaten, MasterKecamatan, MasterDesa,
    MasterKondisiAtapBangunan, MasterKondisiDindingBangunan, MasterKondisiLantaiBangunan,
    MasterKondisiLapanganUsaha, MasterLapanganUsaha, MasterKondisiSumberAir, MasterKonflikSosial,
    MasterKualitasBayi, MasterKualitasIbuHamil, MasterLembaga, MasterManfaatMataAir,
    MasterMutasiKeluar, MasterMutasiMasuk, MasterOmsetUsaha, MasterPartisipasiSekolah,
    MasterPekerjaan, MasterPembangunanKeluarga, MasterPembuanganAkhirTinja,
    MasterPendapatanPerbulan, MasterPenyakitKronis, MasterPertolonganPersalinan,
    MasterProgramSerta, MasterProvinsi, MasterSarprasKerja, MasterStatusKawin,
    MasterStatusKedudukanKerja, MasterStatusPemilikBangunan, MasterStatusPemilikLahan,
    MasterStatusTinggal, MasterSumberAirMinum, MasterSumberDayaTerpasang, MasterSumberPeneranganUtama,
    MasterTempatPersalinan, MasterTempatUsaha, MasterTercantumDalamKk,
    MasterTingkatSulitDisabilitas, MasterTypeJawab, MasterIjasahTerakhir,
};

class MasterController extends Controller
{
    // Mapping nama master ke model
    private $masterMap = [
        'agama' => MasterAgama::class,
        'aktanikah' => MasterAktaNikah::class,
        'asetkeluarga' => MasterAsetKeluarga::class,
        'asetlahan' => MasterAsetLahan::class,
        'asetternak' => MasterAsetTernak::class,
        'asetperikanan' => MasterAsetPerikanan::class,
        'bahanbakarmemasak' => MasterBahanBakarMemasak::class,
        'carapembuangansampah' => MasterCaraPembuanganSampah ::class,
        'caraperolehanair' => MasterCaraPerolehanAir::class,
        'dusun' => MasterDusun::class,
        'fasilitastempatbab' => MasterFasilitasTempatBab::class,
        'hubungankeluarga' => MasterHubunganKeluarga::class,
        'hubungankepalakeluarga' => MasterHubunganKepalaKeluarga::class,
        'ijasahterakhir' => MasterIjasahTerakhir::class,
        'imunisasi' => MasterImunisasi::class,
        'inventaris' => MasterInventaris::class,
        'jabatan' => MasterJabatan::class,
        'jawab' => MasterJawab::class,
        'jawabbangun' => MasterJawabBangun::class,
        'jawabkonflik' => MasterJawabKonflik::class,
        'jawabkualitasbayi' => MasterJawabKualitasBayi::class,
        'jawabkualitasibuhamil' => MasterJawabKualitasIbuHamil::class,
        'jawablemdes' => MasterJawabLemdes::class,
        'jawablemek' => MasterJawabLemek::class,
        'jawablemmas' => MasterJawabLemmas::class,
        'jawabsarpras' => MasterJawabSarpras::class,
        'jawabprogramserta' => MasterJawabProgramSerta::class,
        'jawabtempatpersalinan' => MasterJawabTempatPersalinan::class,
        'jenisatapbangunan' => MasterJenisAtapBangunan::class,
        'jenisbahangalian' => MasterJenisBahanGalian::class,
        'jenisdindingbangunan' => MasterJenisDindingBangunan::class,
        'jenisdisabilitas' => MasterJenisDisabilitas::class,
        'jenisfisikbangunan' => MasterJenisFisikBangunan::class,
        'jeniskelahiran' => MasterJenisKelahiran::class,
        'jeniskelamin' => MasterJenisKelamin::class,
        'jenislantaibangunan' => MasterJenisLantaiBangunan::class,
        'jenislembaga' => MasterJenisLembaga::class,
        'kartuidentitas' => MasterKartuIdentitas::class,
        'kabupaten' => MasterKabupaten::class,
        'kecamatan' => MasterKecamatan::class,
        'kondisiatapbangunan' => MasterKondisiAtapBangunan::class,
        'kondisidindingbangunan' => MasterKondisiDindingBangunan::class,
        'kondisilantaibangunan' => MasterKondisiLantaiBangunan::class,
        'kondisilapanganusaha' => MasterKondisiLapanganUsaha::class,
        'lapanganusaha' => MasterLapanganUsaha::class,
        'kondisisumberair' => MasterKondisiSumberAir::class,
        'konfliksosial' => MasterKonflikSosial::class,
        'kualitasbayi' => MasterKualitasBayi::class,
        'kualitasibuhamil' => MasterKualitasIbuHamil::class,
        'lembaga' => MasterLembaga::class,
        'manfaatmataair' => MasterManfaatMataAir::class,
        'mutasikeluar' => MasterMutasiKeluar::class,
        'mutasimasuk' => MasterMutasiMasuk::class,
        'omsetusaha' => MasterOmsetUsaha::class,
        'partisipasisekolah' => MasterPartisipasiSekolah::class,
        'pekerjaan' => MasterPekerjaan::class,
        'pembangunankeluarga' => MasterPembangunanKeluarga::class,
        'pembuanganakhirtinja' => MasterPembuanganAkhirTinja::class,
        'pendapatanperbulan' => MasterPendapatanPerbulan::class,
        'penyakitkronis' => MasterPenyakitKronis::class,
        'pertolonganpersalinan' => MasterPertolonganPersalinan::class,
        'programserta' => MasterProgramSerta::class,
        'provinsi' => MasterProvinsi::class,
        'sarpraskerja' => MasterSarprasKerja::class,
        'statuskawin' => MasterStatusKawin::class,
        'statuskedudukankerja' => MasterStatusKedudukanKerja::class,
        'statuspemilikbangunan' => MasterStatusPemilikBangunan::class,
        'statuspemiliklahan' => MasterStatusPemilikLahan::class,
        'statustinggal' => MasterStatusTinggal::class,
        'sumberairminum' => MasterSumberAirMinum::class,
        'sumberdayaterpasang' => MasterSumberDayaTerpasang::class,
        'sumberpeneranganutama' => MasterSumberPeneranganUtama::class,
        'tempatpersalinan' => MasterTempatPersalinan::class,
        'tempatusaha' => MasterTempatUsaha::class,
        'tercantumdalamkk' => MasterTercantumDalamKk::class,
        'tingkatsulitdisabilitas' => MasterTingkatSulitDisabilitas::class,
        'typejawab' => MasterTypeJawab::class,
        'pembangunankeluarga' => MasterPembangunanKeluarga::class,
        'jenislembaga' => MasterJenisLembaga::class,
        'lembaga' => MasterLembaga::class,
        'provinsi' => MasterProvinsi::class,
        'kabupaten' => MasterKabupaten::class,
        'kecamatan' => MasterKecamatan::class,
        'desa' => MasterDesa::class,
    ];

    // List master
    public function index($master)
    {
        $master = strtolower($master);

        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];
        $data = $modelClass::query();

        // load relasi-relasi
        //if ($master === 'kabupaten') $data = $data->with('provinsi');
        //if ($master === 'kecamatan') $data = $data->with('kabupaten');
        if ($master === 'desa') $data = $data->with('kecamatan');
        //if ($master === 'pembangunankeluarga') $data = $data->with('typejawab');
        //if ($master === 'lembaga') $data = $data->with('jenislembaga');

        $data = $data->get();

        // tambahkan data tambahan untuk create nanti (bukan di sini)
        $extra = [];
        if ($master === 'pembangunankeluarga') {
            $extra['typejawab'] = \App\Models\MasterTypeJawab::all();
        }
        if ($master === 'lembaga') {
            $extra['jenislembaga'] = \App\Models\MasterJenisLembaga::all();
        }
        if ($master === 'kabupaten') {
            $extra['provinsi'] = \App\Models\MasterProvinsi::all();
        }
        if ($master === 'kecamatan') {
            $extra['kabupaten'] = \App\Models\MasterKabupaten::all();
        }

        return view('master.list', compact('data', 'master') + $extra);
    }


    // Form create
    public function create($master)
    {
        $master = strtolower($master);
        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];
        $relasi = [];

        // jika master tertentu butuh relasi typejawab
        $typejawab = null;
        if ($master === 'pembangunankeluarga') {
            $typejawab = MasterTypeJawab::all();
        }
        $jenislembaga = null;
        if ($master === 'lembaga') {
            $jenislembaga = MasterJenisLembaga::all();
        }
        $provinsi = null;
        if ($master === 'kabupaten') {
            $provinsi = MasterProvinsi::all();
        }
        $kabupaten = null;
        if ($master === 'kecamatan') {
            $kabupaten = MasterKabupaten::all();
        }
        $kecamatan = null;
        if ($master === 'desa') {
            $kecamatan = MasterKecamatan::all();
        }

        //if ($master === 'pembangunankeluarga') $relasi['typejawab'] = MasterTypeJawab::all();
        //if ($master === 'lembaga') $relasi['jenislembaga'] = MasterJenisLembaga::all();
        //if ($master === 'kabupaten') $relasi['provinsi'] = Provinsi::all();
        //if ($master === 'kecamatan') $relasi['kabupaten'] = Kabupaten::all();
        //if ($master === 'desa') $relasi['kecamatan'] = Kecamatan::all();

        return view('master.create', compact('master', 'relasi', 'typejawab', 'jenislembaga', 'provinsi', 'kabupaten', 'kecamatan'));

    }

    // Simpan master baru
    public function store(Request $request, $master)
    {
        $master = strtolower($master);
        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];
        $model = new $modelClass;
        $primaryKey = $model->getKeyName();

        $table = $model->getTable();
         // ambil nama kolom dari database
        $columns = \Schema::getColumnListing($table);
        $data = $request->only($columns);

        // Tentukan field "nama" secara otomatis
        $namaField = match($master) {
            'agama' => 'agama',
            'aktanikah' => 'aktanikah',
            'asetkeluarga' => 'asetkeluarga',
            'asetlahan' => 'asetlahan',
            'asetternak' => 'asetternak',
            'asetperikanan' => 'asetperikanan',
            'bahanbakarmemasak' => 'bahanbakarmemasak',
            'carapembuangansampah' => 'carapembuangansampah',
            'caraperolehanair' => 'caraperolehanair',
            'desa' => 'desa',
            'dusun' => 'dusun',
            'fasilitastempatbab' => 'fasilitastempatbab',
            'hubungankeluarga' => 'hubungankeluarga',
            'hubungankepalakeluarga' => 'hubungankepalakeluarga',
            'jasahterakhir' => 'jasahterakhir', 
            'imunisasi' => 'imunisasi',
            'ijasahterakhir' => 'ijasahterakhir',
            'inventaris' => 'inventaris',
            'jabatan' => 'jabatan',
            'jawab' => 'jawab',
            'jawabbangun' => 'jawabbangun',
            'jawabkonflik' => 'jawabkonflik',
            'jawabkualitasbayi' => 'jawabkualitasbayi',
            'jawabkualitasibuhamil' => 'jawabkualitasibuhamil',
            'jawablemdes' => 'jawablemdes',
            'jawablemek' => 'jawablemek',
            'jawablemmas' => 'jawablemmas',
            'jawabsarpras' => 'jawabsarpras',
            'jawabprogramserta' => 'jawabprogramserta',
            'jawabtempatpersalinan' => 'jawabtempatpersalinan',
            'jenisatapbangunan' => 'jenisatapbangunan',
            'jenisbahangalian' => 'jenisbahangalian',
            'jenisdindingbangunan' => 'jenisdindingbangunan',
            'jenisdisabilitas' => 'jenisdisabilitas',
            'jenisfisikbangunan' => 'jenisfisikbangunan',
            'jeniskelahiran' => 'jeniskelahiran',
            'jeniskelamin' => 'jeniskelamin',
            'jenislantaibangunan' => 'jenislantaibangunan',
            'jenislembaga' => 'jenislembaga',            
            'kartuidentitas' => 'kartuidentitas',
            'kondisiatapbangunan' => 'kondisiatapbangunan',
            'kondisidindingbangunan' => 'kondisidindingbangunan',
            'kondisilantaibangunan' => 'kondisilantaibangunan',
            'kondisilapanganusaha' => 'kondisilapanganusaha',
            'lapanganusaha' => 'lapanganusaha',
            'kondisisumberair' => 'kondisisumberair',
            'konfliksosial' => 'konfliksosial',
            'kualitasbayi' => 'kualitasbayi',
            'kualitasibuhamil' => 'kualitasibuhamil',
            'lembaga' => 'lembaga',
            'manfaatmataair' => 'manfaatmataair',
            'mutasikeluar' => 'mutasikeluar',
            'mutasimasuk' => 'mutasimasuk',
            'omsetusaha' => 'omsetusaha',
            'partisipasisekolah' => 'partisipasisekolah',
            'pekerjaan' => 'pekerjaan',
            'pembangunankeluarga' => 'pembangunankeluarga',
            'pembuanganakhirtinja' => 'pembuanganakhirtinja',
            'pendapatanperbulan' => 'pendapatanperbulan',
            'penyakitkronis' => 'penyakitkronis',
            'pertolonganpersalinan' => 'pertolonganpersalinan',
            'programserta' => 'programserta',
            'sarpraskerja' => 'sarpraskerja',
            'statuskawin' => 'statuskawin',
            'statuskedudukankerja' => 'statuskedudukankerja',
            'statuspemilikbangunan' => 'statuspemilikbangunan',
            'statuspemiliklahan' => 'statuspemiliklahan',
            'statustinggal' => 'statustinggal',
            'sumberairminum' => 'sumberairminum',
            'sumberdayaterpasang' => 'sumberdayaterpasang',
            'sumberpeneranganutama' => 'sumberpeneranganutama',
            'tempatpersalinan' => 'tempatpersalinan',
            'tempatusaha' => 'tempatusaha',
            'tercantumdalamkk' => 'tercantumdalamkk',
            'tingkatsulitdisabilitas' => 'tingkatsulitdisabilitas',
            'typejawab' => 'typejawab',
            'jenislembaga' => 'jenislembaga',
            'lembaga' => 'lembaga',
            'provinsi' => 'provinsi',
            'kabupaten' => 'kabupaten',
            'kecamatan' => 'kecamatan',
            'desa' => 'desa',
            default => 'name',
        };

        // Validasi dinamis
        $rules = [
            $primaryKey => 'required|string|max:255|unique:' . $model->getTable() . ',' . $primaryKey,
            $namaField => 'required|string|max:255',
        ];

        $messages = [
            $primaryKey . '.unique' => 'Kode sudah ada!',
            $primaryKey . '.required' => 'Kode wajib diisi!',
            $namaField . '.required' => 'Nama wajib diisi!',
        ];

        $request->validate($rules, $messages);

        //$data = $request->all();

        // Generate UUID jika primary key string
        if (!isset($data[$primaryKey]) || !$data[$primaryKey]) {
            $data[$primaryKey] = \Str::uuid()->toString();
        }

        try {
            $modelClass::create($data);
            return redirect()->route('master.index', ['master' => $master])
                            ->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors([$primaryKey => 'Kode sudah ada atau terjadi kesalahan!']);
        }
        
    }


    // Form edit
    public function edit($master, $id)
    {
        $master = strtolower($master);
        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];

        $item = $modelClass::findOrFail($id);
        $relasi = [];

        if ($master === 'pembangunankeluarga') $relasi['typejawab'] = MasterTypeJawab::all();
        if ($master === 'lembaga') $relasi['jenislembaga'] = MasterJenisLembaga::all();
        if ($master === 'kabupaten') $relasi['provinsi'] = MasterProvinsi::all();
        if ($master === 'kecamatan') $relasi['kabupaten'] = MasterKabupaten::all();
        if ($master === 'desa') $relasi['kecamatan'] = MasterKecamatan::all();

        return view('master.edit', compact('master', 'item', 'relasi'));
    }

    // Update
    public function update(Request $request, $master, $id)
    {
        $master = strtolower($master);
        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];
        $item = $modelClass::findOrFail($id);

        $item->update($request->all());

        return redirect()->route('master.index', $master)->with('success', ucfirst($master) . ' berhasil diupdate.');
    }

    // Hapus
    public function destroy($master, $id)
    {
        $master = strtolower($master);
        if (!array_key_exists($master, $this->masterMap)) abort(404);

        $modelClass = $this->masterMap[$master];
        $item = $modelClass::findOrFail($id);
        $item->delete();

        return redirect()->route('master.index', $master)->with('success', ucfirst($master) . ' berhasil dihapus.');
    }
}
