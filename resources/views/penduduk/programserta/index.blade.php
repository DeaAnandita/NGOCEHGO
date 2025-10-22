<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Program Serta</h3>
                    <a href="{{ route('penduduk.programserta.create') }}" class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="min-w-full table-auto divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">NIK</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Nama Penduduk</th>
                                @foreach(['KKS/KPS', 'KIP', 'KIS', 'BPJS Non PBI', 'Jamsostek', 'Asuransi Lainnya', 'PKH', 'Raskin'] as $label)
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ $label }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($programsertas as $item)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->nik }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ $item->penduduk->penduduk_namalengkap ?? '-' }}</td>
                                    @for($i = 1; $i <= 8; $i++)
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $item["programserta_$i"] ? ($masterJawab[$item["programserta_$i"]] ?? '-') : '-' }}</td>
                                    @endfor
                                    <td class="px-4 py-4 text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('penduduk.programserta.edit', $item->nik) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                            <form action="{{ route('penduduk.programserta.destroy', $item->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data program serta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
