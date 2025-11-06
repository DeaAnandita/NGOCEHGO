<x-app-layout>
    <div class="flex flex-row min-h-screen overflow-hidden">
        {{-- Sidebar kiri (tetap tampil di semua ukuran layar) --}}
        <div class="">
            @include('penduduk.sidebar')
        </div>

       <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Penduduk</h3>

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
                                <a href="{{ route('export.penduduk') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                                <a href=
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Form Search + Dropdown --}}
                        <form method="GET" action="{{ route('dasar-penduduk.index') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, NIK, atau No KK..."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full sm:w-56">

                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full sm:w-50">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="50" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                <option value="100" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                            </select>

                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition duration-200 w-full sm:w-auto">
                                Cari
                            </button>
                        </form>

                        {{-- Tombol Tambah Data --}}
                        <a href="{{ route('dasar-penduduk.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-green-700 transition duration-200 shadow-sm w-full sm:w-auto text-center">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Wrapper tabel -->
                <div class="relative">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-3 py-3 text-left">No</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">Kartu Keluarga</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">No. Urut</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">Nama Lengkap</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">Tempat/Tgl Lahir</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">L/P</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">Agama</th>
                                    <th class="border border-gray-200 px-3 py-3 text-left">Hub. Keluarga</th>
                                    <th class="border border-gray-200 px-3 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
    @forelse($kkPaginated as $kkItem)
        @php
            $no_kk = $kkItem->no_kk;
            $group = $penduduksGrouped[$no_kk] ?? collect();
            $rowspan = $group->count() ?: 1;
            // nomor urut KK di tabel (terus berlanjut antar halaman)
            $kkNumber = $kkPaginated->firstItem() + $loop->index;
        @endphp

        {{-- Untuk setiap KK, tampilkan baris pertama (dengan rowspan) --}}
        @if($group->isNotEmpty())
            @foreach($group->sortBy('penduduk_nourutkk')->values() as $index => $penduduk)
                <tr class="hover:bg-gray-50 transition">
                    @if($index === 0)
                        <td class="border border-gray-200 px-3 py-3 text-gray-900" rowspan="{{ $rowspan }}">{{ $kkNumber }}</td>
                        <td class="border border-gray-200 px-3 py-3 text-gray-900 break-all" rowspan="{{ $rowspan }}">{{ $no_kk }}</td>
                    @endif

                    {{-- kolom individu --}}
                    <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $penduduk->penduduk_nourutkk }}</td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900 break-all">{{ $penduduk->nik }}</td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900 break-words">{{ $penduduk->penduduk_namalengkap }}</td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900 break-words">
                        {{ $penduduk->penduduk_tempatlahir }},
                        {{ \Carbon\Carbon::parse($penduduk->penduduk_tanggallahir)->format('d-m-Y') }}
                    </td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $penduduk->jeniskelamin->jeniskelamin ?? '-' }}</td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $penduduk->agama->agama ?? '-' }}</td>
                    <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $penduduk->hubungankeluarga->hubungankeluarga ?? '-' }}</td>
                    <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                        <div class="flex justify-center gap-1">
                            <a href="{{ route('dasar-penduduk.edit', $penduduk->nik) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                            </a>
                            <form action="{{ route('dasar-penduduk.destroy', $penduduk->nik) }}" method="POST"
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
            @endforeach
        @else
            {{-- safety fallback: bila group kosong tetap tampilkan baris placeholder --}}
            <tr>
                <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $kkPaginated->firstItem() + $loop->index }}</td>
                <td class="border border-gray-200 px-3 py-3 text-gray-900">{{ $no_kk }}</td>
                <td colspan="8" class="px-3 py-3 text-gray-500">Tidak ada data penduduk untuk KK ini.</td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="10" class="px-3 py-3 text-center text-gray-500">Tidak ada data penduduk.</td>
        </tr>
    @endforelse
</tbody>

                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $kkPaginated->firstItem() }} - {{ $kkPaginated->lastItem() }} dari {{ $kkPaginated->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $kkPaginated->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
