<x-app-layout>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-2xl font-semibold mb-6">Edit Data Master {{ ucfirst($master) }}</h2>

            <form action="{{ route('master.update', [$master, $item->{$item->getKeyName()}]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                @php
                    $model = strtolower($master);
                    $primaryKey = match($model) {
                        'asetkeluarga' => 'kdasetkeluarga',
                        'asetlahan' => 'kdasetlahan',
                        'asetternak' => 'kdasetternak',
                        'asetperikanan' => 'kdasetperikanan',
                        'bahanbakarmemasak' => 'kdbahanbakarmemasak',
                        'carapembuangansampah' => 'kdcarapembuangansampah',
                        'caraperolehanair' => 'kdcaraperolehanair',
                        'desa' => 'kddesa',
                        'dusun' => 'kddusun',
                        'fasilitastempatbab' => 'kdfasilitastempatbab',
                        'hubungankeluarga' => 'kdhubungankeluarga',
                        'jasahterakhir' => 'kdjasahterakhir',
                        'imunisasi' => 'kdimunisasi',
                        'inventaris' => 'kdinventaris',
                        'jabatan' => 'kdjabatan',
                        'jawab' => 'kdjawab',
                        'jawabbangun' => 'kdjawabbangun',
                        'jawabkonflik' => 'kdjawabkonflik',
                        'jawabkualitasbayi' => 'kdjawabkualitasbayi',
                        'jawabkualitasibuhamil' => 'kdjawabkualitasibuhamil',
                        'jawablemdes' => 'kdjawablemdes',
                        'jawablemek' => 'kdjawablemek',
                        'jawablemmas' => 'kdjawablemmas',
                        'jawabsarpras' => 'kdjawabsarpras',
                        'jawabtempatpersalinan' => 'kdjawabtempatpersalinan',
                        'jenisatapbangunan' => 'kdjenisatapbangunan',
                        'jenisbahangalian' => 'kdjenisbahangalian',
                        'jenisdindingbangunan' => 'kdjenisdindingbangunan',
                        'jenisdisabilitas' => 'kdjenisdisabilitas',
                        'jenisfisikbangunan' => 'kdjenisfisikbangunan',
                        'jeniskelahiran' => 'kdjeniskelahiran',
                        'jeniskelamin' => 'kdjeniskelamin',
                        'jenislantaibangunan' => 'kdjenislantaibangunan',
                        'jenislembaga' => 'kdjenislembaga',
                        'provinsi' => 'kdprovinsi',
                        'kabupaten' => 'kdkabupaten',
                        'kecamatan' => 'kdkecamatan',
                        'kondisiatapbangunan' => 'kdkondisiatapbangunan',
                        'kondisidindingbangunan' => 'kdkondisidindingbangunan',
                        'kondisilantaibangunan' => 'kdkondisilantaibangunan',
                        'kondisilapanganusaha' => 'kdkondisilapanganusaha',
                        'kondisisumberair' => 'kdkondisisumberair',
                        'konfliksosial' => 'kdkonfliksosial',
                        'kualitasbayi' => 'kdkualitasbayi',
                        'kualitasblhamil' => 'kdkualitasblhamil',
                        'lembaga' => 'kdlembaga',
                        'manfaatmataair' => 'kdmanfaatmataair',
                        'mutasikeluar' => 'kdmutasikeluar',
                        'mutasimasuk' => 'kdmutasimasuk',
                        'omsetusaha' => 'kdomsetusaha',
                        'partisipasisekolah' => 'kdpartisipasisekolah',
                        'pekerjaan' => 'kdpekerjaan',
                        'pembangunankeluarga' => 'kdpembangunankeluarga',
                        'pembuanganakhirtinja' => 'kdpembuanganakhirtinja',
                        'pendapatanperbulan' => 'kdpendapatanperbulan',
                        'penyakitkronis' => 'kdpenyakitkronis',
                        'pertolonganpersalinan' => 'kdpertolonganpersalinan',
                        'programserta' => 'kdprogramserta',
                        'sarpraskerja' => 'kdsarpraskerja',
                        'statuskawin' => 'kdstatuskawin',
                        'statuskedudukankerja' => 'kdstatuskedudukankerja',
                        'statuspemilikbangunan' => 'kdstatuspemilikbangunan',
                        'statuspemiliklahan' => 'kdstatuspemiliklahan',
                        'sumberairminum' => 'kdsumberairminum',
                        'sumberdayaterpasang' => 'kdsumberdayaterpasang',
                        'sumberpeneranganutama' => 'kdsumberpeneranganutama',
                        'tempatpersalinan' => 'kdtempatpersalinan',
                        'tempatusaha' => 'kdtempatusaha',
                        'tercantumdalamkk' => 'kdtercantumdalamkk',
                        'tingkatsulitdisabilitas' => 'kdtingkatsulitdisabilitas',
                        'typejawab' => 'kdtypejawab',
                        default => 'id',
                    };

                    $namaField = match($model) {
                        'asetkeluarga' => 'asetkeluarga',
                        'asetlahan' => 'asetlahan',
                        'asetternak' => 'asetternak',
                        'bahanbakarmemasak' => 'bahanbakarmemasak',
                        'carapembuangansampah' => 'carapembuangansampah',
                        'caraperolehanair' => 'caraperolehanair',
                        'desa' => 'desa',
                        'dusun' => 'dusun',
                        'fasilitastempatbab' => 'fasilitastempatbab',
                        'hubungankeluarga' => 'hubungankeluarga',
                        'jasahterakhir' => 'jasahterakhir',
                        'imunisasi' => 'imunisasi',
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
                        'provinsi' => 'provinsi',
                        'kabupaten' => 'kabupaten',
                        'kecamatan' => 'kecamatan',
                        'kondisiatapbangunan' => 'kondisiatapbangunan',
                        'kondisidindingbangunan' => 'kondisidindingbangunan',
                        'kondisilantaibangunan' => 'kondisilantaibangunan',
                        'kondisilapanganusaha' => 'kondisilapanganusaha',
                        'kondisisumberair' => 'kondisisumberair',
                        'konfliksosial' => 'konfliksosial',
                        'kualitasbayi' => 'kualitasbayi',
                        'kualitasblhamil' => 'kualitasblhamil',
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
                        'sumberairminum' => 'sumberairminum',
                        'sumberdayaterpasang' => 'sumberdayaterpasang',
                        'sumberpeneranganutama' => 'sumberpeneranganutama',
                        'tempatpersalinan' => 'tempatpersalinan',
                        'tempatusaha' => 'tempatusaha',
                        'tercantumdalamkk' => 'tercantumdalamkk',
                        'tingkatsulitdisabilitas' => 'tingkatsulitdisabilitas',
                        'typejawab' => 'typejawab',
                        default => 'name',
                    };
                @endphp


                <div class="flex flex-col">
                    <label class="mb-1 font-medium">Kode</label>
                    <input type="text" name="{{ $primaryKey }}" value="{{ old($primaryKey, $item->{$primaryKey}) }}"
                           class="border p-2 rounded w-full @error($primaryKey) border-red-500 @enderror">
                    @error($primaryKey)
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium">Nama</label>
                    <input type="text" name="{{ $namaField }}" value="{{ old($namaField, $item->{$namaField}) }}"
                           class="border p-2 rounded w-full @error($namaField) border-red-500 @enderror">
                    @error($namaField)
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if(!empty($typejawab))
                    <div class="mb-4">
                        <label for="kdtypejawab" class="block font-medium text-gray-700">Type Jawab</label>
                        <select name="kdtypejawab" id="kdtypejawab" class="w-full border rounded-md p-2">
                            <option value="">-- Pilih Type Jawab --</option>
                            @foreach ($typejawab as $t)
                                <option value="{{ $t->kdtypejawab }}" {{ $data->kdtypejawab == $t->kdtypejawab ? 'selected' : '' }}>
                                    {{ $t->typejawab }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex space-x-2 mt-4">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        Update
                    </button>
                    <a href="{{ route('master.index', $master) }}" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
