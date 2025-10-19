<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Penduduk</h3>
                    <a href="{{ route('penduduk.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="w-full">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[40px]">No.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Kartu Keluarga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">No. Urut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">NIK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Tempat/Tgl Lahir</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">L/P</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Agama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Hub. Keluarga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $groupedPenduduks = $penduduks->groupBy('no_kk');
                                $rowNumber = 1;
                            @endphp
                            @foreach($groupedPenduduks as $no_kk => $pendudukGroup)
                                @php
                                    $rowspan = $pendudukGroup->count();
                                    $isFirst = true;
                                @endphp
                                @foreach($pendudukGroup->sortBy('penduduk_nourutkk') as $penduduk)
                                    <tr>
                                        @if($isFirst)
                                            <td class="px-4 py-4 text-sm text-gray-900" rowspan="{{ $rowspan }}">{{ $rowNumber }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-900" rowspan="{{ $rowspan }}">{{ $no_kk }}</td>
                                            @php $isFirst = false; @endphp
                                        @endif
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $penduduk->penduduk_nourutkk }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $penduduk->nik }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-wrap">{{ $penduduk->penduduk_namalengkap }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-wrap">
                                            {{ $penduduk->penduduk_tempatlahir }}, {{ \Carbon\Carbon::parse($penduduk->penduduk_tanggallahir)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $penduduk->jeniskelamin ? $penduduk->jeniskelamin->jeniskelamin : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $penduduk->agama ? $penduduk->agama->agama : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $penduduk->hubungankeluarga ? $penduduk->hubungankeluarga->hubungankeluarga : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium">
                                            <a href="{{ route('penduduk.edit', $penduduk->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                            <form action="{{ route('penduduk.destroy', $penduduk->nik) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @php $rowNumber++; @endphp
                            @endforeach
                            @if($groupedPenduduks->isEmpty())
                                <tr>
                                    <td colspan="10" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data penduduk.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>