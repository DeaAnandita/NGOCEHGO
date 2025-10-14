<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-semibold">Master {{ ucfirst($master) }}</h2>
            <a href="{{ route('master.create', $master) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Tambah Data
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        {{-- Kolom Header Relasi --}}
                        @if (in_array($master, ['pembangunankeluarga', 'lembaga', 'kabupaten', 'kecamatan', 'desa']))
                            @php
                                $relasiHeader = match ($master) {
                                    'pembangunankeluarga' => 'Type Jawab',
                                    'lembaga' => 'Jenis Lembaga',
                                    'kabupaten' => 'Provinsi',
                                    'kecamatan' => 'Kabupaten',
                                    'desa' => 'Kecamatan',
                                    default => 'Relasi',
                                };
                            @endphp

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $relasiHeader }}
                            </th>
                            @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $index => $item)
                        <tr>
                            @php
                                $kode = $item->getKey();

                                // Ambil semua atribut model (kolom database)
                                $attributes = $item->getAttributes();

                                // Coba cari nama dari atribut string pertama
                                $nama = collect($attributes)->first(function ($value) {
                                    return is_string($value) && trim($value) !== '';
                                });

                                // Cek semua relasi model secara dinamis
                                foreach ($item->getRelations() as $relation) {
                                    if (is_object($relation)) {
                                        // Kalau relasi berupa model tunggal
                                        if (method_exists($relation, 'getAttributes')) {
                                            $namaRelasi = collect($relation->getAttributes())->first(function ($val) {
                                                return is_string($val) && trim($val) !== '';
                                            });
                                            if ($namaRelasi) {
                                                $nama = $namaRelasi;
                                                break;
                                            }
                                        }
                                        // Kalau relasi berupa koleksi (hasMany)
                                        elseif ($relation instanceof \Illuminate\Support\Collection && $relation->isNotEmpty()) {
                                            $first = $relation->first();
                                            if (method_exists($first, 'getAttributes')) {
                                                $namaRelasi = collect($first->getAttributes())->first(function ($val) {
                                                    return is_string($val) && trim($val) !== '';
                                                });
                                                if ($namaRelasi) {
                                                    $nama = $namaRelasi;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }

                                $nama = $nama ?? '-';
                            @endphp

                            <td class="px-6 py-4">{{ $kode }}</td>
                            <td class="px-6 py-4">{{ $nama }}</td>

                           {{-- Kolom relasi dinamis --}}
                            @if ($master === 'pembangunankeluarga')
                                <td class="px-6 py-4">{{ $item->typejawab->kdtypejawab ?? '-' }}</td>

                            @elseif ($master === 'lembaga')
                                <td class="px-6 py-4">{{ $item->jenislembaga->kdjenislembaga ?? '-' }}</td>

                            @elseif ($master === 'kabupaten')
                                <td class="px-6 py-4">{{ $item->provinsi->provinsi ?? '-' }}</td>

                            @elseif ($master === 'kecamatan')
                                <td class="px-6 py-4">{{ $item->kabupaten->kabupaten ?? '-' }}</td>

                            @elseif ($master === 'desa')
                                <td class="px-6 py-4">{{ $item->kecamatan->kecamatan ?? '-' }}</td>

                            @else
                                <td class="px-6 py-4">-</td>
                            @endif

                            <td class="px-6 py-4">
                                <a href="{{ route('master.edit', [$master, $kode]) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                <form action="{{ route('master.destroy', [$master, $kode]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
