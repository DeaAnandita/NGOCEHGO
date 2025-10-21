<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Data Lembaga Desa</h3>
                    <a href="{{ route('penduduk.lemdes.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700">
                        + Tambah Data
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penduduk</th>
                                @foreach([
                                    'Kepala Desa/Lurah', 
                                    'Sekretaris Desa/Lurah', 
                                    'Kepala Urusan',
                                    'Kepala Dusun/Lingkungan', 
                                    'Staf Desa/Kelurahan',
                                    'Ketua BPD', 
                                    'Wakil Ketua BPD', 
                                    'Sekretaris BPD', 
                                    'Anggota BPD'
                                ] as $label)
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lembagadesas as $item)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->nik }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->penduduk->penduduk_namalengkap ?? '-' }}</td>

                                    @for($i = 1; $i <= 9; $i++)
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $item["lemdes_$i"] ? ($masterJawab[$item["lemdes_$i"]] ?? '-') : '-' }}
                                        </td>
                                    @endfor

                                    <!-- Tombol Aksi -->
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('penduduk.lemdes.edit', $item->nik) }}"
                                               class="text-blue-600 hover:text-blue-800 font-medium">
                                                Edit
                                            </a>
                                            <form action="{{ route('penduduk.lemdes.destroy', $item->nik) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="px-4 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data lembaga desa.
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
