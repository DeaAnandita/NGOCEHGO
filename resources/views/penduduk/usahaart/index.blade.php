<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                 <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <!-- Kiri: Judul + Tombol Report -->
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">Data Usaha Art</h3>
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
                                <a href="{{ route('export.usahaart') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- Kanan: Tampilkan + Cari + Tambah -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('penduduk.usahaart.index') }}" class="flex items-center gap-2">
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

                        <!-- Pencarian -->
                        <form method="GET" action="{{ route('penduduk.usahaart.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari Disini.."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('penduduk.usahaart.create') }}"
                           class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="usahaartTable" class="min-w-[1500px] table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No.</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Lengkap</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Usaha</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Lapangan Usaha</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Tempat Usaha</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Omset Usaha</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jumlah Pekerja</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usahaarts as $usaha)
                                    <tr>
                                        <td class="border border-gray-200 px-4 py-3">{{ $usahaarts->firstItem() + $loop->index }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->penduduk->penduduk_namalengkap ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->usahaart_namausaha  ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->lapanganusaha->lapanganusaha ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->tempatusaha->tempatusaha ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->omsetusaha->omsetusaha ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $usaha->usahaart_jumlahpekerja ?? '-' }}</td>
                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('penduduk.usahaart.edit', $usaha->nik) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('penduduk.usahaart.destroy', $usaha->nik) }}" method="POST"
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

                                @if($usahaarts->isEmpty())
                                    <tr>
                                        <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data usaha ART.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $usahaarts->firstItem() }} - {{ $usahaarts->lastItem() }} dari {{ $usahaarts->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $usahaarts->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
