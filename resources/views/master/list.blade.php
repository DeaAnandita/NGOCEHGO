<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-semibold">Master {{ ucfirst($master) }}</h2>
            <a href="{{ route('master.create', $master) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Tambah Data
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        @if(in_array($master, ['pembangunankeluarga', 'lembaga']))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relasi</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $index => $item)
                        <tr>
                            @php
                                $kode = $item->getKey(); // primary key
                                $nama = 
                                    $item->name ??
                                    $item->nama ??
                                    $item->pembangunankeluarga ??
                                    $item->typejawab ??
                                    $item->asetkeluarga ??
                                    $item->asetlahan ??
                                    $item->asetternak ??
                                    $item->bahanbakarmemasak ??
                                    $item->carapembuangansampah ??
                                    $item->caraperolehanair ??
                                    $item->desa ??
                                    $item->dusun ??
                                    $item->fasilitastempatbab ??
                                    $item->hubungankeluarga ??
                                    $item->jasahterakhir ??
                                    $item->imunisasi ??
                                    $item->inventaris ??
                                    $item->jabatan ??
                                    $item->jawab ??
                                    $item->jawabbangun ??
                                    $item->jawabkonflik ??
                                    $item->jawabkualitasbayi ??
                                    $item->jawabkualitasibuhamil ??
                                    $item->jawablemdes ??
                                    $item->jawablemek ??
                                    $item->jawablemmas ??
                                    $item->jawabsarpras ??
                                    $item->jawabtempatpersalinan ??
                                    $item->jenisatapbangunan ??
                                    $item->jenisbahangalian ??
                                    $item->jenisdindingbangunan ??
                                    $item->jenisdisabilitas ??
                                    $item->jenisfisikbangunan ??
                                    $item->jeniskelahiran ??
                                    $item->jeniskelamin ??
                                    $item->jenislantaibangunan ??
                                    $item->lembaga ??
                                    $item->jenislembaga ??
                                    $item->provinsi ??
                                    $item->kabupaten ??
                                    $item->kecamatan ??
                                    $item->kondisiatapbangunan ??
                                    $item->kondisidindingbangunan ??
                                    $item->kondisilantaibangunan ??
                                    $item->kondisilapanganusaha ??
                                    $item->kondisisumberair ??
                                    $item->konfliksosial ??
                                    $item->kualitasbayi ??
                                    $item->kualitasblhamil ??
                                    $item->manfaatmataair ??
                                    $item->mutasikeluar ??
                                    $item->mutasimasuk ??
                                    $item->omsetusaha ??
                                    $item->partisipasisekolah ??
                                    $item->pekerjaan ??
                                    $item->pembuanganakhirtinja ??
                                    $item->pendapatanperbulan ??
                                    $item->penyakitkronis ??
                                    $item->pertolonganpersalinan ??
                                    $item->programserta ??
                                    $item->sarpraskerja ??
                                    $item->statuskawin ??
                                    $item->statuskedudukankerja ??
                                    $item->statuspemilikbangunan ??
                                    $item->statuspemiliklahan ??
                                    $item->sumberairminum ??
                                    $item->sumberdayaterpasang ??
                                    $item->sumberpeneranganutama ??
                                    $item->tempatpersalinan ??
                                    $item->tempatusaha ??
                                    $item->tercantumdalamkk ??
                                    $item->tingkatsulitdisabilitas ??
                                    
                                    '-';
                            @endphp

                            <td class="px-6 py-4">{{ $kode }}</td>
                            <td class="px-6 py-4">{{ $nama }}</td>

                            @if($master === 'pembangunankeluarga')
                                <td class="px-6 py-4">{{ $item->typejawab->kdtypejawab ??  '-' }} </td>
                            @elseif($master === 'lembaga')
                                <td class="px-6 py-4">{{ $item->jenislembaga->kdjenislembaga ?? '-' }}</td>
                            @endif

                            <td class="px-6 py-4">
                                <a href="{{ route('master.edit', [$master, $kode]) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                <form action="{{ route('master.destroy', [$master, $kode]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
