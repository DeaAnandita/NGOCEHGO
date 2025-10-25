<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Data Lembaga Ekonomi</h3>

                    <div class="flex flex-wrap items-center gap-2">
                        <form method="GET" action="{{ route('penduduk.lembagaekonomi.index') }}" class="flex items-center gap-2">
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

                        <form method="GET" action="{{ route('penduduk.lembagaekonomi.index') }}" class="flex items-center">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari Nik / No KK"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <button type="submit"
                                class="ml-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('penduduk.lembagaekonomi.create') }}"
                           class="bg-green-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                            + Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Tabel scroll horizontal -->
                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="lemekTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NO</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">NIK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Nama Penduduk</th>
                                    @foreach ($masterLembaga as $kd => $nama)
                                        <th class="border border-gray-200 px-4 py-3 text-left">
                                            <span class="truncate max-w-[100px]" title="{{ $nama }}">
                                                {{ Str::limit(Str::replace('Lembaga ', '', $nama), 15) }}
                                            </span>
                                        </th>
                                    @endforeach
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse ($lembagaEkonomis as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- ✅ Ganti $data->nO dengan $loop->iteration -->
                                        <td class="border border-gray-200 px-4 py-4 text-center">{{ $loop->iteration }}</td>

                                        <td class="border border-gray-200 px-4 py-4">{{ $data->nik }}</td>
                                        <td class="border border-gray-200 px-4 py-4">{{ $data->penduduk->penduduk_namalengkap ?? '-' }}</td>

                                        @php
                                            $totalKolom = count($masterLembaga);
                                        @endphp
                                        @for ($i = 1; $i <= $totalKolom; $i++)
                                            @php
                                                $value = $data->{"lemek_$i"} ?? 0;
                                            @endphp
                                            <td class="border border-gray-200 px-4 py-4 text-center">
                                                <span class="text-xs px-2 py-1 rounded-full">
                                                    {{ $masterJawabLemek[$value] ?? '-' }}
                                                </span>
                                            </td>
                                        @endfor
                                        <td class="border border-gray-200 px-2 py-2 text-center w-[80px]">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('penduduk.lembagaekonomi.edit', $data->nik) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center justify-center">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                                <form action="{{ route('penduduk.lembagaekonomi.destroy', $data->nik) }}" method="POST"
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
                                        <td colspan="{{ count($masterLembaga) + 3 }}"
                                            class="border border-gray-200 px-4 py-4 text-center text-gray-500">
                                            Belum ada data lembaga ekonomi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2 text-sm text-gray-600">
                        <div>
                            Menampilkan {{ $lembagaEkonomis->firstItem() }} - {{ $lembagaEkonomis->lastItem() }} dari {{ $lembagaEkonomis->total() }} data
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $lembagaEkonomis->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
