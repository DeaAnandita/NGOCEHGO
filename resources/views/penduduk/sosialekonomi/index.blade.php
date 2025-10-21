<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Sosial Ekonomi</h3>
                    <a href="{{ route('penduduk.sosialekonomi.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Partisipasi Sekolah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ijazah Terakhir</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Disabilitas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tingkat Kesulitan Disabilitas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyakit Kronis</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lapangan Usaha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kedudukan Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pendapatan/Bulan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imunisasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @foreach($sosialekonomis as $sosialekonomi)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $no++ }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $sosialekonomi->nik }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->penduduk->penduduk_namalengkap ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->partisipasisekolah->partisipasisekolah ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->ijasahterakhir->ijasahterakhir ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->jenisdisabilitas->jenisdisabilitas ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->tingkatsulitdisabilitas->tingkatsulitdisabilitas ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->penyakitkronis->penyakitkronis ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->lapanganusaha->lapanganusaha ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->statuskedudukankerja->statuskedudukankerja ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->pendapatanperbulan->pendapatanperbulan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $sosialekonomi->imunisasi->imunisasi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('penduduk.sosialekonomi.edit', $sosialekonomi->nik) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('penduduk.sosialekonomi.destroy', $sosialekonomi->nik) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
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
            </div>
        </div>
    </div>
</x-app-layout>
