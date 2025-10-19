<div class="bg-gray-50 p-4 rounded-md">
    <h4 class="text-md font-semibold text-gray-700 mb-2">Data Kelahiran untuk NIK: {{ $penduduk->nik }}</h4>
    <a href="{{ route('penduduk.kelahiran.create', $penduduk->nik) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition mb-4 inline-block">Tambah Kelahiran</a>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kelahiran</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat Kelahiran</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($kelahirans as $kelahiran)
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">{{ $kelahiran->tanggal_kelahiran }}</td>
                <td class="px-4 py-2">{{ $kelahiran->tempat_kelahiran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>