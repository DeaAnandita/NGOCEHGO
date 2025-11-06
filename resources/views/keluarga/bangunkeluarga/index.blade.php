<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Bangun Keluarga</h3>

                        <!-- Dropdown Export -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-1">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export Data
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition
                                class="absolute mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                <a href="{{ route('export.bangunkeluarga') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                                <a href="{{ route('bangunkeluarga.export.pdf') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('keluarga.bangunkeluarga.index') }}" 
                              class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan</label>
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-md px-2 py-1 text-xs sm:px-3 sm:py-1.5 sm:text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-20 sm:w-24">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>

                            @if(!empty($search))
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                        </form>

                        <!-- Form Search -->
                        <form method="GET" action="{{ route('keluarga.bangunkeluarga.index') }}" 
                              class="flex items-center w-full sm:w-auto">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari No KK / Kepala Keluarga"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full sm:w-56 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('keluarga.bangunkeluarga.create') }}"
                            class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel scroll horizontal -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-full table-fixed border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">No</th>
                                    <th class="border border-gray-200 px-4 py-3 w-36 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 w-44 text-left">Kepala Keluarga</th>
                                    @foreach($masterPembangunan as $pembangunan)
                                        <th class="border border-gray-200 px-4 py-3 w-32 text-left">
                                            <span title="{{ $pembangunan->pembangunankeluarga }}">
                                                {{ Str::replace('Keluarga', '', $pembangunan->pembangunankeluarga) }}
                                            </span>
                                        </th>
                                    @endforeach
                                    <th class="border border-gray-200 px-4 py-3 w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($bangunkeluargas as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-3">{{ $bangunkeluargas->firstItem() + $loop->index }}</td>
                                        <td class="border border-gray-200 px-4 py-2 truncate">{{ $data->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-2 truncate">{{ $data->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                        @foreach($masterPembangunan as $pembangunan)
                                            @php 
                                                $field = 'bangunkeluarga_' . $pembangunan->kdpembangunankeluarga;
                                                $kodeJawab = $data->$field;
                                                $jawabLabel = $masterJawab[$kodeJawab] ?? '-';
                                            @endphp
                                            <td class="border border-gray-200 px-2 py-2 text-left truncate" title="{{ $jawabLabel }}">
                                                {{ $jawabLabel }}
                                            </td>
                                        @endforeach
                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('keluarga.bangunkeluarga.edit', $data->no_kk) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('keluarga.bangunkeluarga.destroy', $data->no_kk) }}" method="POST"
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
                                        <td colspan="{{ 3 + count($masterPembangunan) + 1 }}" class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data bangun keluarga.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $bangunkeluargas->firstItem() ?? 0 }} - {{ $bangunkeluargas->lastItem() ?? 0 }} dari {{ $bangunkeluargas->total() ?? 0 }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $bangunkeluargas->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
