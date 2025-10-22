<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Sarana dan Prasarana Kerja</h3>
                    <a href="{{ route('keluarga.sarpraskerja.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">No</th>
                                <th class="border border-gray-200 text-xs font-medium text-gray-600">No. KK</th>
                                <th class="border border-gray-200 text-xs font-medium text-gray-600">Kepala Keluarga</th>

                                {{-- Kolom uraian sarpras --}}
                                @foreach($masterSarpras as $sarpras)
                                    <th class="border border-gray-200 text-xs font-medium text-gray-600">
                                        {{ $sarpras }}
                                    </th>
                                @endforeach

                                <th class="border border-gray-200 text-xs font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($sarpraskerjas as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="border border-gray-200 px-4 py-3">{{ $data->no_kk }}</td>
                                    <td class="border border-gray-200 px-4 py-3">{{ $data->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>

                                    {{-- Tampilkan jawaban label --}}
                                    @foreach($masterSarpras as $key => $sarpras)
                                        @php 
                                            $field = 'sarpraskerja_' . ($key + 1);
                                            $kodeJawab = $data->$field;
                                            $jawabLabel = $masterJawab[$kodeJawab] ?? '-';
                                        @endphp
                                        <td class="border border-gray-200 px-4 py-3 text-center">{{ $jawabLabel }}</td>
                                    @endforeach

                                    <td class="border border-gray-200 px-4 py-4 text-center space-x-3">
                                        <a href="{{ route('keluarga.sarpraskerja.edit', $data->no_kk) }}"
                                           class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                        <form action="{{ route('keluarga.sarpraskerja.destroy', $data->no_kk) }}" method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + count($masterSarpras) }}" class="px-4 py-4 text-center text-gray-500">
                                        Belum ada data sarana dan prasarana kerja.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
