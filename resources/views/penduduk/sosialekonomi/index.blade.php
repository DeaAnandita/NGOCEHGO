<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <!-- Kiri: Judul + Tombol Report -->
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">Data Sosial Ekonomi</h3>
                        <a href="{{ route('penduduk.sosialekonomi.report') }}"
                           class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-1">
                            <x-heroicon-o-document-text class="w-4 h-4" />
                            Report
                        </a>
                    </div>

                    <!-- Kanan: Tampilkan + Cari + Tambah -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Dropdown Per Page -->
                        <form method="GET" action="{{ route('penduduk.sosialekonomi.index') }}" class="flex items-center gap-2">
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
                        <form method="GET" action="{{ route('penduduk.sosialekonomi.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari Disini.."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('penduduk.sosialekonomi.create') }}"
                           class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="sosialekonomiTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No.</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Lengkap</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Partisipasi Sekolah</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Ijazah Terakhir</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jenis Disabilitas</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Tingkat Kesulitan Disabilitas</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Penyakit Kronis</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Lapangan Usaha</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Status Kedudukan Kerja</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Pendapatan/Bulan</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Imunisasi</th>
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sosialekonomis as $sosialekonomi)
                                    <tr>
                                        <td class="border border-gray-200 px-4 py-3">{{ $sosialekonomis->firstItem() + $loop->index }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->penduduk->penduduk_namalengkap ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->partisipasisekolah->partisipasisekolah ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->ijasahterakhir->ijasahterakhir ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->jenisdisabilitas->jenisdisabilitas ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->tingkatsulitdisabilitas->tingkatsulitdisabilitas ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->penyakitkronis->penyakitkronis ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->lapanganusaha->lapanganusaha ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->statuskedudukankerja->statuskedudukankerja ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->pendapatanperbulan->pendapatanperbulan ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->imunisasi->imunisasi ?? '-' }}</td>
                                        <td class="border border-gray-200 px-2 py-2 text-center">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('penduduk.sosialekonomi.edit', $sosialekonomi->nik) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('penduduk.sosialekonomi.destroy', $sosialekonomi->nik) }}" method="POST"
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

                                @if($sosialekonomis->isEmpty())
                                    <tr>
                                        <td colspan="13" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data sosial ekonomi.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $sosialekonomis->firstItem() }} - {{ $sosialekonomis->lastItem() }} dari {{ $sosialekonomis->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $sosialekonomis->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
