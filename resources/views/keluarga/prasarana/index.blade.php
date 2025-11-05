<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Prasarana</h3>

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
                                <a href="{{ route('export.dataprasarana') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                                <a href="{{ route('dataprasarana.export.pdf') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('keluarga.prasarana.index') }}" class="flex items-center gap-2">
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
                        <form method="GET" action="{{ route('keluarga.prasarana.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari No KK / Kepala Keluarga"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('keluarga.prasarana.create') }}"
                        class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="prasaranaTable" class="min-w-[2000px] table-auto border-collapse text-sm h-full">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left text-xs text-gray-600">No</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Kepala Keluarga</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Status Bangunan</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jenis Bangunan</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Sumber Air</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Penerangan & Daya</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Fasilitas Sanitasi</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Bahan Bakar & Mata Air</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Luas Lantai (m²)</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jumlah Kamar</th>
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($prasaranas as $pras)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-3">{{ $prasaranas->firstItem() + $loop->index }}</td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->statuspemilikbangunan->statuspemilikbangunan ?? '-' }} /
                                            {{ $pras->statuspemiliklahan->statuspemiliklahan ?? '-' }}
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->jenisfisikbangunan->jenisfisikbangunan ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Lantai: {{ $pras->jenislantaibangunan->jenislantaibangunan ?? '-' }} ({{ $pras->kondisilantaibangunan->kondisilantaibangunan ?? '-' }})<br>
                                                Dinding: {{ $pras->jenisdindingbangunan->jenisdindingbangunan ?? '-' }} ({{ $pras->kondisidindingbangunan->kondisidindingbangunan ?? '-' }})<br>
                                                Atap: {{ $pras->jenisatapbangunan->jenisatapbangunan ?? '-' }} ({{ $pras->kondisiatapbangunan->kondisiatapbangunan ?? '-' }})
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->sumberairminum->sumberairminum ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Kondisi: {{ $pras->kondisisumberair->kondisisumberair ?? '-' }}<br>
                                                Cara: {{ $pras->caraperolehanair->caraperolehanair ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->sumberpeneranganutama->sumberpeneranganutama ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Daya: {{ $pras->sumberdayaterpasang->sumberdayaterpasang ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            BAB: {{ $pras->fasilitastempatbab->fasilitastempatbab ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Tinja: {{ $pras->pembuanganakhirtinja->pembuanganakhirtinja ?? '-' }}<br>
                                                Sampah: {{ $pras->carapembuangansampah->carapembuangansampah ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->bahanbakarmemasak->bahanbakarmemasak ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Mata Air: {{ $pras->manfaatmataair->manfaatmataair ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->prasdas_luaslantai ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->prasdas_jumlahkamar ?? '-' }}</td>
                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('keluarga.prasarana.edit', $pras->no_kk) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('keluarga.prasarana.destroy', $pras->no_kk) }}" method="POST"
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
                                        <td colspan="11" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Belum ada data prasarana dasar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                            <div>
                                Menampilkan {{ $prasaranas->firstItem() ?? 0 }} - {{ $prasaranas->lastItem() ?? 0 }} dari {{ $prasaranas->total() ?? 0 }} data
                            </div>
                            <div class="w-full sm:w-auto">
                                {{ $prasaranas->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
