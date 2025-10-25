<x-app-layout> 
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Kelahiran</h3>

                    <div class="flex flex-wrap items-center gap-2">
                        <form method="GET" action="{{ route('penduduk.kelahiran.index') }}" class="flex items-center gap-2">
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

                        <form method="GET" action="{{ route('penduduk.kelahiran.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari Nik / No KK"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('penduduk.kelahiran.create') }}"
                           class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="kelahiranTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-blue-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr></tr>
                                <th class="border border-gray-200 px-4 py-3 text-left">NO</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Nama Lengkap</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Tempat Persalinan</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Jenis Kelahiran</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Pertolongan Persalinan</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Jam Kelahiran</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Kelahiran Ke</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Berat (gram)</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Panjang (cm)</th>
                                <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kelahirans as $kelahiran)
                                <tr>
                                    <!-- Nomor urut otomatis -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>

                                    <!-- NIK -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $kelahiran->nik }}</td>

                                    <!-- Nama Penduduk -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->penduduk?->penduduk_namalengkap ?? '-' }}
                                    </td>

                                    <!-- Tempat Persalinan -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->tempatPersalinan?->tempatpersalinan ?? '-' }}
                                    </td>

                                    <!-- Jenis Kelahiran -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->jenisKelahiran?->jeniskelahiran ?? '-' }}
                                    </td>

                                    <!-- Pertolongan Persalinan -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->pertolonganPersalinan?->pertolonganpersalinan ?? '-' }}
                                    </td>

                                    <!-- Jam Kelahiran -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->kelahiran_jamkelahiran ? \Carbon\Carbon::parse($kelahiran->kelahiran_jamkelahiran)->format('H:i') : '-' }}
                                    </td>

                                    <!-- Kelahiran ke -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_kelahiranke ?? '-' }}</td>

                                    <!-- Berat -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_berat ?? '-' }}</td>

                                    <!-- Panjang -->
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_panjang ?? '-' }}</td>

                                    <!-- Aksi -->
                                    <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('penduduk.kelahiran.edit', $kelahiran->nik) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('penduduk.kelahiran.destroy', $kelahiran->nik) }}" method="POST"
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
                                    <td colspan="11" class="px-4 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data kelahiran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $kelahirans->firstItem() }} - {{ $kelahirans->lastItem() }} dari {{ $kelahirans->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $kelahirans->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>