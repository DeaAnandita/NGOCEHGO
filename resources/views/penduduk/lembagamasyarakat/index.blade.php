<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

            <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Lembaga Masyarakat</h3>

                        <!-- Dropdown Export -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-1">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export Data
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition
                                class="absolute mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                <a href="{{ route('export.lembagamasyarakat') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                                <a href="{{ route('lembagamasyarakat.exportAnalisisPDF') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Tampilkan + Cari + Tambah -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">

                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('penduduk.lemmas.index') }}" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm w-full sm:w-32 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>

                            <!-- Hidden search agar tetap nyantol -->
                            @if(!empty($search))
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                        </form>

                        <!-- Form Search -->
                        <form method="GET" action="{{ route('penduduk.lemmas.index') }}" 
                              class="flex items-center w-full sm:w-auto">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari NIK / Nama"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full sm:w-56 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('penduduk.lemmas.create') }}"
                        class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel scroll horizontal -->
                <div class="relative">
                    <div class="overflow-x-auto">
                        <table id="lemmasTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NO</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Penduduk</th>
                                    @foreach ($masterLembaga as $kd => $nama)
                                        <th class="border border-gray-200 px-4 py-3 text-left">
                                            <span class="truncate max-w-[100px]" title="{{ $nama }}">
                                                {{ Str::limit(Str::replace('Lembaga ', '', $nama), 15) }}
                                            </span>
                                        </th>
                                    @endforeach
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse ($lembagamasyarakats as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- ✅ Kolom NO diperbaiki -->
                                        <td class="border border-gray-200 px-4 py-3">{{ $lembagamasyarakats->firstItem() + $loop->index }}</td>

                                        <td class="border border-gray-200 px-4 py-4">{{ $data->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4">
                                            {{ $data->penduduk->penduduk_namalengkap ?? '-' }}
                                        </td>

                                        @php
                                            $totalKolom = count($masterLembaga);
                                        @endphp
                                        @for ($i = 1; $i <= $totalKolom; $i++)
                                            @php
                                                $value = $data->{"lemmas_$i"} ?? 0;
                                            @endphp
                                            <td class="border border-gray-200 px-4 py-4 text-center">
                                                <span class="text-xs px-2 py-1 rounded-full">
                                                    {{ $masterJawabLemmas[$value] ?? '-' }}
                                                </span>
                                            </td>
                                        @endfor

                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('penduduk.lemmas.edit', $data->nik) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('penduduk.lemmas.destroy', $data->nik) }}" method="POST"
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
                                        <td colspan="{{ count($masterLembaga) + 3 }}"
                                            class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data lembaga masyarakat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $lembagamasyarakats->firstItem() }} - {{ $lembagamasyarakats->lastItem() }} dari {{ $lembagamasyarakats->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $lembagamasyarakats->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
