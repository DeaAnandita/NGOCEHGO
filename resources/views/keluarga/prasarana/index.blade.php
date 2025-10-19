<x-app-layout>
    <div class="flex h-screen">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-2 sm:px-6 lg:px-8 overflow-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Data Prasarana Dasar</h3>
                    <a href="{{ route('keluarga.prasarana.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                        + Tambah Data
                    </a>
                </div>

                <div class="relative">
                    <div class="overflow-x-auto w-full">
                        <table id="prasaranaTable" class="min-w-[2000px] table-auto border-collapse text-sm">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-3 text-left">No KK</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Kepala Keluarga</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Status Bangunan</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jenis Bangunan</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Sumber Air</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Penerangan & Daya</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Fasilitas Sanitasi</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Bahan Bakar & Mata Air</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Luas Lantai (m²)</th>
                                    <th class="border border-gray-200 px-4 py-3 text-left">Jumlah Kamar</th>
                                    <th class="border border-gray-200 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                @forelse($prasaranas as $pras)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->no_kk }}</td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->statuspemilikbangunan->statuspemilikbangunan ?? '-' }} /
                                            {{ $pras->statuspemiliklahan->statuspemiliklahan ?? '-' }}
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->jenisfisikbangunan->jenisfisikbangunan ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Lantai: {{ $pras->jenislantaibangunan->jenislantaibangunan ?? '-' }} ({{ $pras->kondisilantaibangunan->kondisilantaibangunan ?? '-' }})<br>
                                                Dinding: {{ $pras->jenisdindingbangunan->jenisdindingbangunan ?? '-' }} ({{ $pras->kondisidindingbangunan->kondisidindingbangunan ?? '-' }})<br>
                                                Atap: {{ $pras->jenisatapbangunan->jenisatapbangunan ?? '-' }} ({{ $pras->kondisiatapbangunan->kondisiatapbangunan ?? '-' }})
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->sumberairminum->sumberairminum ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Kondisi: {{ $pras->kondisisumberair->kondisisumberair ?? '-' }}<br>
                                                Cara: {{ $pras->caraperolehanair->caraperolehanair ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->sumberpeneranganutama->sumberpeneranganutama ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Daya: {{ $pras->sumberdayaterpasang->sumberdayaterpasang ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            BAB: {{ $pras->fasilitastempatbab->fasilitastempatbab ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Tinja: {{ $pras->pembuanganakhirtinja->pembuanganakhirtinja ?? '-' }}<br>
                                                Sampah: {{ $pras->carapembuangansampah->carapembuangansampah ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">
                                            {{ $pras->bahanbakarmemasak->bahanbakarmemasak ?? '-' }}<br>
                                            <span class="text-gray-500 text-xs">
                                                Mata Air: {{ $pras->manfaatmataair->manfaatmataair ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->prasdas_luaslantai ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-3">{{ $pras->prasdas_jumlahkamar ?? '-' }}</td>
                                        <td class="border border-gray-200 px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('keluarga.prasarana.edit', $pras->no_kk) }}"
                                               class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                            <form action="{{ route('keluarga.prasarana.destroy', $pras->no_kk) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                        class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Belum ada data prasarana dasar.</td>
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