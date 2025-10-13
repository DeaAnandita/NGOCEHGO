<x-app-layout>
    <h2 class="text-xl font-semibold mb-4">Master Data: {{ Str::title($table) }}</h2>

    @if($data->isEmpty())
        <p class="text-gray-500">Data kosong.</p>
    @else
    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                @foreach(array_keys($data->first()->toArray()) as $col)
                    <th class="border px-2 py-1">{{ $col }}</th>
                @endforeach
                <th class="border px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                @foreach($row->toArray() as $val)
                    <td class="border px-2 py-1">{{ $val }}</td>
                @endforeach
                <td class="border px-2 py-1">
                    <a href="{{ route('menu-masterdata.show', ['table' => $table, 'id' => $row->{$row->getKeyName()}]) }}" class="text-blue-500">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</x-app-layout>
