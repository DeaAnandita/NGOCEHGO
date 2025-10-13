<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">CRUD Master: {{ Str::title($master) }}</h2>
            <a href="{{ route('master.create', $master) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah Data</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    @foreach ($data->first()?->toArray() ?? [] as $key => $value)
                        <th class="border px-3 py-2">{{ $key }}</th>
                    @endforeach
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        @foreach ($item->toArray() as $value)
                            <td class="border px-3 py-2">{{ $value }}</td>
                        @endforeach
                        <td class="border px-3 py-2">
                            <a href="{{ route('master.edit', [$master, $item->getKey()]) }}" class="text-blue-600">Edit</a> |
                            <form action="{{ route('master.destroy', [$master, $item->getKey()]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
