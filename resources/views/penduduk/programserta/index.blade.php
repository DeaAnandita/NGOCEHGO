<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Data Program Serta</h3>
                    <a href="{{ route('penduduk.programserta.create') }}"
                       class="bg-blue-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        + Tambah Data
                    </a>
                </div>

                <!-- Table -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-[1800px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-center w-12">No.</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Penduduk</th>
                                    @foreach ($masterProgramSerta as $kd => $nama)
                                        <th class="border border-gray-200 px-4 py-3 text-left">{{ $nama }}</th>
                                    @endforeach
                                    <th class="border border-gray-200 px-4 py-3 text-center w-28">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse ($programSertas as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-4 text-center">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $data->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $data->penduduk->penduduk_namalengkap ?? '-' }}</td>

                                        @php $totalKolom = count($masterProgramSerta); @endphp
                                        @for ($i = 1; $i <= $totalKolom; $i++)
                                            @php $value = $data->{"programserta_$i"} ?? 0; @endphp
                                            <td class="border border-gray-200 px-4 py-4 text-center">
                                                {{ $masterJawab[$value] ?? '-' }}
                                            </td>
                                        @endfor

                                        <!-- Tombol Aksi -->
                                        <td class="border border-gray-200 px-4 py-4 text-center">
                                            <div class="flex justify-center items-center space-x-4">
                                                <a href="{{ route('penduduk.programserta.edit', $data->nik) }}"
                                                   class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition duration-150">
                                                    Edit
                                                </a>
                                                <form action="{{ route('penduduk.programserta.destroy', $data->nik) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                            class="text-red-600 hover:text-red-800 font-semibold text-sm transition duration-150">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($masterProgramSerta) + 4 }}"
                                            class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data program serta.
                                        </td>
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
