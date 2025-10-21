<x-app-layout> 
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Kelahiran</h3>
                    <a href="{{ route('penduduk.kelahiran.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="w-full">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">NIK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Tempat Persalinan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Jenis Kelahiran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Pertolongan Persalinan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Jam Kelahiran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Kelahiran Ke</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Berat (gram)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Panjang (cm)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelahirans as $kelahiran)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->nik }}</td>

                                    {{-- Nama Penduduk (gunakan belongsTo, bukan hasMany) --}}
                                    <td class="px-4 py-4 text-sm text-gray-900 text-wrap">
                                        {{ $kelahiran->penduduk?->penduduk_namalengkap ?? '-' }}
                                    </td>

                                    {{-- Tempat Persalinan --}}
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->tempatPersalinan?->tempatpersalinan ?? '-' }}
                                    </td>

                                    {{-- Jenis Kelahiran --}}
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->jenisKelahiran?->jeniskelahiran ?? '-' }}
                                    </td>

                                    {{-- Pertolongan Persalinan --}}
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->pertolonganPersalinan?->pertolonganpersalinan ?? '-' }}
                                    </td>

                                    {{-- Jam Kelahiran --}}
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $kelahiran->kelahiran_jamkelahiran ? \Carbon\Carbon::parse($kelahiran->kelahiran_jamkelahiran)->format('H:i') : '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_kelahiranke ?? '-' }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_berat ?? '-' }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $kelahiran->kelahiran_panjang ?? '-' }}</td>

                                    <td class="px-4 py-4 text-sm font-medium">
                                        <a href="{{ route('penduduk.kelahiran.edit', $kelahiran->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('penduduk.kelahiran.destroy', $kelahiran->nik) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if($kelahirans->isEmpty())
                                <tr>
                                    <td colspan="12" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data kelahiran.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{-- <div class="mt-6">
                    {{ $kelahirans->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
