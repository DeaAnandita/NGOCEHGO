<x-app-layout>
    <div class="flex">
        {{-- Sidebar --}}
        @include('keluarga.sidebar')

        <!-- Konten Utama -->
        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-md p-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-md font-semibold text-gray-700">Data Keluarga</h3>
                    <a href="{{ route('dasar-keluarga.create') }}"
                       class="bg-blue-500 text-white px-3 py-2 text-sm rounded-xl hover:bg-blue-600 transition">
                        + Tambah
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table id="keluargaTable" class="min-w-full table-auto border-collapse text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">NO KK</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kepala RT</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mutasi</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dusun</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">RW</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">RT</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                                <th class="border border-gray-200 px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Wilayah Datang</th>
                                <th class="border border-gray-200 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($keluargas as $keluarga)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->no_kk }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->keluarga_kepalakeluarga }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ $keluarga->mutasi->mutasimasuk ?? '-' }}</td>
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
                                    <td class="border border-gray-200 px-3 py-2 text-center">
                                        <a href="{{ route('dasar-keluarga.edit', $keluarga->no_kk) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                        <form action="{{ route('dasar-keluarga.destroy', $keluarga->no_kk) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm ml-1">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
