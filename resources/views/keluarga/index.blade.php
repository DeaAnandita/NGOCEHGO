<x-app-layout>
    <div class="flex">
        {{-- Sidebar --}}
        @include('keluarga.sidebar')

        <!-- Konten Utama -->
        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Keluarga</h3>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full sm:w-auto">
                        {{-- Form Search + Dropdown --}}
                        <form method="GET" action="{{ route('dasar-keluarga.index') }}" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau No KK..."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full sm:w-56 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm w-full sm:w-32 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition w-full sm:w-auto">
                                Cari
                            </button>
                        </form>

                        {{-- Tombol Tambah Data --}}
                        <a href="{{ route('dasar-keluarga.create') }}"
                            class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm w-full sm:w-auto text-center">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table id="keluargaTable" class="min-w-full table-auto border-collapse text-sm">
                        <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left">No</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">NO KK</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Kepala RT</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Mutasi</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Tanggal</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Dusun</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">RW</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">RT</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Alamat</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Wilayah Datang</th>
                                <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($keluargas as $keluarga)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="border border-gray-200 px-3 py-2 truncate">{{ $keluarga->no_kk }}</td>
                                    <td class="border border-gray-200 px-3 py-2 truncate">{{ $keluarga->keluarga_kepalakeluarga }}</td>
                                    <td class="border border-gray-200 px-3 py-2 truncate">{{ $keluarga->mutasi->mutasimasuk ?? '-' }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->keluarga_tanggalmutasi ? \Carbon\Carbon::parse($keluarga->keluarga_tanggalmutasi)->format('d-m-Y') : '-' }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->dusun->dusun ?? '-' }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->keluarga_rw }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->keluarga_rt }}</td>
                                    <td class="border border-gray-200 px-3 py-2 truncate max-w-[150px]">{{ $keluarga->keluarga_alamatlengkap }}</td>
                                    <td class="border border-gray-200 px-3 py-2 truncate max-w-[180px]">
                                        {{ $keluarga->provinsi->provinsi ?? '-' }},
                                        {{ $keluarga->kabupaten->kabupaten ?? '-' }},
                                        {{ $keluarga->kecamatan->kecamatan ?? '-' }},
                                        {{ $keluarga->desa->desa ?? '-' }}
                                    </td>
                                    <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('dasar-keluarga.edit', $keluarga->no_kk) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('dasar-keluarga.destroy', $keluarga->no_kk) }}" method="POST"
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
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                    <div>
                        Menampilkan {{ $keluargas->firstItem() }} - {{ $keluargas->lastItem() }} dari {{ $keluargas->total() }} data
                    </div>
                    <div class="w-full sm:w-auto">
                        {{ $keluargas->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
