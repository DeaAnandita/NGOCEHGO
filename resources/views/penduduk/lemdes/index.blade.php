<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Data Lembaga Desa</h3>
                    <a href="{{ route('penduduk.lemdes.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-[1800px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No.</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Penduduk</th>
                                    @foreach ($masterLembaga as $kd => $nama)
                                        <th class="border border-gray-200 px-4 py-3 text-left">{{ $nama }}</th>
                                    @endforeach
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse ($lembagaDesas as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-4 text-center">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $data->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $data->penduduk->penduduk_namalengkap ?? '-' }}</td>
                                        @php $totalKolom = count($masterLembaga); @endphp
                                        @for ($i = 1; $i <= $totalKolom; $i++)
                                            @php $value = $data->{"lemdes_$i"} ?? 0; @endphp
                                            <td class="border border-gray-200 px-4 py-4 text-center">
                                                {{ $masterJawabLemdes[$value] ?? '-' }}
                                            </td>
                                        @endfor
                                        <td class="border border-gray-200 px-4 py-4 text-center space-x-3">
                                            <a href="{{ route('penduduk.lemdes.edit', $data->nik) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</a>
                                            <form action="{{ route('penduduk.lemdes.destroy', $data->nik) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($masterLembaga) + 3 }}" class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data lembaga desa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
