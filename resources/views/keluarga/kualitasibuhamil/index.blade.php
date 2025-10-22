<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col justify-between sm:flex-row sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Kualitas Ibu Hamil</h3>
                    <a href="{{ route('keluarga.kualitasibuhamil.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <!-- Tabel bisa di-scroll -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="kualitasIbuHamilTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Kepala Keluarga</th>
                                    @for ($i = 1; $i <= 7; $i++)
                                        <th class="border border-gray-200 px-4 py-3 text-left">
                                            <span class="truncate max-w-[100px]" title="{{ $masterKualitas[$i] ?? 'Indikator '.$i }}">
                                                {{ Str::limit(Str::replace('Kualitas ', '', $masterKualitas[$i] ?? 'Indikator '.$i), 15) }}
                                            </span>
                                        </th>
                                    @endfor
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($kualitasibuhamils as $ibu)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-4">{{ $ibu->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $ibu->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                        @for ($i = 1; $i <= 13; $i++)
                                            <td class="border border-gray-200 px-4 py-4">
                                                <span class="text-xs px-2 py-1 rounded-full
                                                    {{ $ibu->{"kualitasibuhamil_$i"} == 1 ? 'bg-green-100 text-green-800' : ($ibu->{"kualitasibuhamil_$i"} == 2 ? 'bg-yellow-100 text-yellow-800' : ($ibu->{"kualitasibuhamil_$i"} == 3 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600')) }}">
                                                    {{ $masterJawab[$ibu->{"kualitasibuhamil_$i"}] ?? '-' }}
                                                </span>
                                            </td>
                                        @endfor
                                        <td class="border border-gray-200 px-4 py-4 text-center space-x-3">
                                            <a href="{{ route('keluarga.kualitasibuhamil.edit', $ibu->no_kk) }}"
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</a>
                                            <form action="{{ route('keluarga.kualitasibuhamil.destroy', $ibu->no_kk) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                        class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="23" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Belum ada data kualitas ibu hamil.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
