<x-app-layout> 
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header tetap tidak ikut scroll -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Kelahiran</h3>
                    <a href="{{ route('penduduk.kelahiran.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="kelahiranTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
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
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>

                                    <!-- NIK -->
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->nik }}</td>

                                    <!-- Nama Penduduk -->
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->penduduk?->penduduk_namalengkap ?? '-' }}
                                    </td>

                                    <!-- Tempat Persalinan -->
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->tempatPersalinan?->tempatpersalinan ?? '-' }}
                                    </td>

                                    <!-- Jenis Kelahiran -->
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->jenisKelahiran?->jeniskelahiran ?? '-' }}
                                    </td>

                                    <!-- Pertolongan Persalinan -->
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->pertolonganPersalinan?->pertolonganpersalinan ?? '-' }}
                                    </td>

                                    <!-- Jam Kelahiran -->
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->kelahiran_jamkelahiran ? \Carbon\Carbon::parse($kelahiran->kelahiran_jamkelahiran)->format('H:i') : '-' }}
                                    </td>

                                    <!-- Kelahiran ke -->
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_kelahiranke ?? '-' }}</td>

                                    <!-- Berat -->
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_berat ?? '-' }}</td>

                                    <!-- Panjang -->
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_panjang ?? '-' }}</td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <a href="{{ route('penduduk.kelahiran.edit', $kelahiran->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('penduduk.kelahiran.destroy', $kelahiran->nik) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
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

                <!-- Pagination (aktifkan jika perlu) -->
                {{-- <div class="mt-6">
                    {{ $kelahirans->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>