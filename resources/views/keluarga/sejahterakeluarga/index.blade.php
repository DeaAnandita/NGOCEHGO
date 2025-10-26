<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Sejahtera Keluarga</h3>

                    <div class="flex flex-wrap items-center gap-2">
                        <form method="GET" action="{{ route('keluarga.sejahterakeluarga.index') }}" class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600">Tampilkan</label>
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-md px-2 py-1 text-xs sm:px-3 sm:py-1.5 sm:text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-20 sm:w-24">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            @if(!empty($search))
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                        </form>

                        <form method="GET" action="{{ route('keluarga.sejahterakeluarga.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari No KK / Kepala Keluarga"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('keluarga.sejahterakeluarga.create') }}"
                           class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <div class="relative">
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                        <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">No</th>
                                <th class="border border-gray-200 px-4 py-3 text-left  text-xs text-gray-600">No. KK</th>
                                <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">Kepala Keluarga</th>
                                @foreach($masterPembangunan as $pembangunan)
                                    <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">
                                        {{ $pembangunan->pembangunankeluarga }}
                                    </th>
                                @endforeach
                                <th class="border border-gray-200 px-4 py-3 text-center text-xs text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($sejahterakeluargas as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 px-4 py-3">{{ $sejahterakeluargas->firstItem() + $loop->index }}</td>
                                    <td class="border border-gray-200 px-4 py-3">{{ $data->no_kk }}</td>
                                    <td class="border border-gray-200 px-4 py-3">{{ $data->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                    @foreach($masterPembangunan as $pembangunan)
                                        @php $field = 'sejahterakeluarga_' . $pembangunan->kdpembangunankeluarga; @endphp
                                        <td class="border border-gray-200 px-4 py-3">{{ $data->$field ?? '-' }}</td>
                                    @endforeach
                                    <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('keluarga.sejahterakeluarga.edit', $data->no_kk) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('keluarga.sejahterakeluarga.destroy', $data->no_kk) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + count($masterPembangunan) }}" class="px-4 py-4 text-center text-gray-500">
                                        Belum ada data sejahtera keluarga.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $sejahterakeluargas->firstItem() }} - {{ $sejahterakeluargas->lastItem() }} dari {{ $sejahterakeluargas->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $sejahterakeluargas->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
