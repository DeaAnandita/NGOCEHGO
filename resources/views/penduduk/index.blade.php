<x-app-layout>
    <div class="flex flex-row min-h-screen overflow-hidden">
        {{-- Sidebar kiri (tetap tampil di semua ukuran layar) --}}
        <div class="">
            @include('penduduk.sidebar')
        </div>

        {{-- Konten utama --}}
        <div class="flex-1 py-6 px-3 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Penduduk</h3>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Form Search + Dropdown --}}
                        <form method="GET" action="{{ route('dasar-penduduk.index') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, NIK, atau No KK..."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full sm:w-56">

                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full sm:w-50">
                                <option value="10" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="25" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                <option value="50" {{ $perPage == 150 ? 'selected' : '' }}>150</option>
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
                                @php
                                    $groupedPenduduks = $penduduks->groupBy('no_kk');
                                    $rowNumber = 1;
                                @endphp
                                @foreach($groupedPenduduks as $no_kk => $pendudukGroup)
                                    @php
                                        $rowspan = $pendudukGroup->count();
                                        $isFirst = true;
                                    @endphp
                                    @foreach($pendudukGroup->sortBy('penduduk_nourutkk') as $penduduk)
                                        <tr class="hover:bg-gray-50 transition">
                                            @if($isFirst)
                                                <td class="border border-gray-200 px-3 py-3 text-gray-900" rowspan="{{ $rowspan }}">{{ $rowNumber }}</td>
                                                <td class="border border-gray-200 px-3 py-3 text-gray-900 break-all" rowspan="{{ $rowspan }}">{{ $no_kk }}</td>
                                                @php $isFirst = false; @endphp
                                            @endif
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
                                    @php $rowNumber++; @endphp
                                @endforeach

                                @if($groupedPenduduks->isEmpty())
                                    <tr>
                                        <td colspan="10" class="px-3 py-3 text-center text-gray-500">Tidak ada data penduduk.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $penduduks->firstItem() }} - {{ $penduduks->lastItem() }} dari {{ $penduduks->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $penduduks->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
