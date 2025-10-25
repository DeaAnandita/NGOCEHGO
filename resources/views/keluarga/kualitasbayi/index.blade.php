<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Kualitas Bayi</h3>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('keluarga.kualitasbayi.index') }}" 
                              class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan</label>
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-md px-2 py-1 text-xs sm:px-3 sm:py-1.5 sm:text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-20 sm:w-24">
                                <option value="5" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="10" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="15" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="20" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>

                            <!-- Hidden search agar tetap nyantol -->
                            @if(!empty($search))
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                        </form>

                        <!-- Form Search -->
                        <form method="GET" action="{{ route('keluarga.kualitasbayi.index') }}" 
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
                        <a href="{{ route('keluarga.kualitasbayi.create') }}"
                            class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel bisa di-scroll -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="kualitasBayiTable" class="table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">No</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Kepala Keluarga</th>
                                    @for ($i = 1; $i <= 7; $i++)
                                        <th class="border border-gray-200 px-4 py-3 text-left">
                                            <span class="" title="{{ $masterKualitas[$i] ?? 'Indikator '.$i }}">
                                                {{ Str::limit(Str::replace('Kualitas ', '', $masterKualitas[$i] ?? 'Indikator '.$i)) }}
                                            </span>
                                        </th>
                                    @endfor
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($kualitasbayis as $bayi)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $bayi->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $bayi->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <td class="border border-gray-200 px-4 py-4">
                                                <span class="text-xs px-2 py-1 rounded-full">
                                                    {{ $masterJawab[$bayi->{"kualitasbayi_$i"}] ?? '-' }}
                                                </span>
                                            </td>
                                        @endfor
                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('keluarga.kualitasbayi.edit', $bayi->no_kk) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('keluarga.kualitasbayi.destroy', $bayi->no_kk) }}" method="POST"
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
                                        <td colspan="23" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Belum ada data kualitas bayi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $kualitasbayis->firstItem() ?? 0 }} - {{ $kualitasbayis->lastItem() ?? 0 }} dari {{ $kualitasbayis->total() ?? 0 }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $kualitasbayis->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
