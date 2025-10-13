<x-app-layout>
    <h2 class="text-xl font-semibold mb-4">Detail Master Data: {{ Str::title($table) }}</h2>

    <table class="table-auto w-full border border-gray-300">
        <tbody>
            @foreach($item->toArray() as $col => $val)
            <tr>
                <td class="border px-2 py-1 font-semibold">{{ $col }}</td>
                <td class="border px-2 py-1">{{ $val }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('menu-masterdata.index', ['table' => $table]) }}" class="mt-4 inline-block text-blue-500">Kembali</a>
</x-app-layout>
