<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Penduduk</h3>
                    <a href="{{ route('dasar-penduduk.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="pendudukTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left">No.</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Kartu Keluarga</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">No. Urut</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Nama Lengkap</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Tempat/Tgl Lahir</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">L/P</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Agama</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Hub. Keluarga</th>
                                <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
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
                                            <a href="{{ route('dasar-penduduk.edit', $penduduk->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                            <form action="{{ route('dasar-penduduk.destroy', $penduduk->nik) }}" method="POST" class="inline-block">
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