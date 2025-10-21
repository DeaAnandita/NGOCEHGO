<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Usaha ART</h3>
                    <a href="{{ route('penduduk.usahaart.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="w-full">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lapangan Usaha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pekerja</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Usaha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kepemilikan Tempat Usaha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omset Usaha/Bulan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @foreach($usahaarts as $usahaart)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $no++ }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $usahaart->nik }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->penduduk->penduduk_namalengkap ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->lapanganusaha->lapanganusaha ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->usahaart_jumlahpekerja ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->usahaart_namausaha ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->tempatusaha->tempatusaha ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $usahaart->omsetusaha->omsetusaha ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <a href="{{ route('penduduk.usahaart.edit', $usahaart->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('penduduk.usahaart.destroy', $usahaart->nik) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if($usahaarts->isEmpty())
                                <tr>
                                    <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data usaha ART.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
