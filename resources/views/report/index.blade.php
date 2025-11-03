<x-app-layout>
    <div class="p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-2xl font-semibold mb-4">📊 Laporan Data Aset Keluarga</h2>

        <form method="GET" action="{{ route('report.index') }}" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" placeholder="Cari No KK / Nama Kepala Keluarga"
                   value="{{ $search }}" class="border p-2 rounded-lg flex-grow">

            <input type="date" name="from" value="{{ $from }}" class="border p-2 rounded-lg">
            <input type="date" name="to" value="{{ $to }}" class="border p-2 rounded-lg">

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">🔍 Filter</button>
            <a href="{{ route('report.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Reset</a>
        </form>

        <div class="flex justify-end mb-3 space-x-2">
            <a href="{{ route('report.export.pdf', ['from' => $from, 'to' => $to]) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-lg">📄 PDF</a>
            <a href="{{ route('report.export.excel', ['from' => $from, 'to' => $to]) }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg">📊 Excel</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">No KK</th>
                        <th class="border p-2">Nama Kepala Keluarga</th>
                        <th class="border p-2">Jenis Aset</th>
                        <th class="border p-2">Jumlah</th>
                        <th class="border p-2">Nilai (Rp)</th>
                        <th class="border p-2">Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border p-2">{{ $item->keluarga->no_kk ?? '-' }}</td>
                            <td class="border p-2">{{ $item->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                            <td class="border p-2">{{ $item->jenis_aset }}</td>
                            <td class="border p-2">{{ $item->jumlah }}</td>
                            <td class="border p-2">{{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="border p-2">{{ $item->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-3">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>
</x-app-layout>
