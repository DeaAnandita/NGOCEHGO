<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Konflik Sosial</h3>

                    <div class="flex flex-wrap items-center gap-2">

                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('keluarga.konfliksosial.index') }}" class="flex items-center gap-2">
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
                        <form method="GET" action="{{ route('keluarga.konfliksosial.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari No KK / Kepala Keluarga"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('keluarga.konfliksosial.create') }}"
                            class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="konflikTable" class="min-w-full table-fixed border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No</th>
                                    <th class="border border-gray-200 px-4 py-3 w-36 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 w-44 text-left">Kepala Keluarga</th>
                                    @for ($i = 1; $i <= count($masterKonflik); $i++)
                                        <th class="border border-gray-200 px-4 py-3 text-left">
                                            <span title="{{ $masterKonflik[$i] }}">
                                                {{ $masterKonflik[$i] }}
                                            </span>
                                        </th>
                                    @endfor
                                    <th class="border border-gray-200 px-4 py-3 w-[80px] text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($konfliksosials as $konflik)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-200 px-4 py-2 truncate">{{ $konflik->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-2 truncate">{{ $konflik->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>

                                        @for ($i = 1; $i <= count($masterKonflik); $i++)
                                            <td class="border border-gray-200 px-2 py-2 text-left truncate">
                                                <span class="text-xs px-2 py-1 inline-block max-w-[100%]" title="{{ $masterJawab[$konflik->{"konfliksosial_$i"}] ?? 'Tidak Ada' }}">
                                                    {{ $masterJawab[$konflik->{"konfliksosial_$i"}] ?? 'Tidak Ada' }}
                                                </span>
                                            </td>
                                        @endfor

                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('keluarga.konfliksosial.edit', $konflik->no_kk) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('keluarga.konfliksosial.destroy', $konflik->no_kk) }}" method="POST"
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
                                        <td colspan="{{ count($masterKonflik) + 3 }}" class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data konflik sosial.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $konfliksosials->firstItem() }} - {{ $konfliksosials->lastItem() }} dari {{ $konfliksosials->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $konfliksosials->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
