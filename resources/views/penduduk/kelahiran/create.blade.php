<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Tambah Data Kelahiran untuk NIK: {{ $penduduk->nik }}</h3>
            <form action="{{ route('penduduk.kelahiran.store', $penduduk->nik) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="tanggal_kelahiran" class="block text-sm font-medium text-gray-700">Tanggal Kelahiran</label>
                        <input type="date" name="tanggal_kelahiran" id="tanggal_kelahiran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('tanggal_kelahiran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="tempat_kelahiran" class="block text-sm font-medium text-gray-700">Tempat Kelahiran</label>
                        <input type="text" name="tempat_kelahiran" id="tempat_kelahiran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('tempat_kelahiran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition">Simpan</button>
                    <a href="{{ route('penduduk.kelahiran.index', $penduduk->nik) }}" class="ml-2 text-gray-600 hover:text-gray-800">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>