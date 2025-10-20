<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Data Bangun Keluarga</h3>

                    <a href="{{ route('keluarga.bangunkeluarga.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-200">
                        + Tambah Data
                    </a>
                </div>

                <!-- Tabel Data -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 rounded-lg">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">No</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Nama Kepala Keluarga</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">No. KK</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Status Bangun Keluarga</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @forelse($dataBangunKeluarga as $index => $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $data->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $data->no_kk }}</td>

                                    <!-- Contoh menampilkan status dari salah satu pertanyaan -->
                                    @php
                                        $field = 'bangunkeluarga_1';
                                        $status = $data->$field ?? null;
                                        $jawaban = $masterJawab->firstWhere('kdjawabbangun', $status)->jawabbangun ?? '-';
                                    @endphp
                                    <td class="px-4 py-2 text-sm font-medium text-gray-800">
                                        {{ $jawaban }}
                                    </td>

                                    <!-- Tombol aksi -->
                                    <td class="px-4 py-2 text-sm text-center space-x-2">
                                        <a href="{{ route('keluarga.bangunkeluarga.edit', $data->no_kk) }}"
                                           class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                                        <form action="{{ route('keluarga.bangunkeluarga.destroy', $data->no_kk) }}"
                                              method="POST" class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                        Tidak ada data Bangun Keluarga.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $dataBangunKeluarga->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
