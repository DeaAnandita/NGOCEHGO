<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Lembaga Ekonomi</h3>

                <form action="{{ route('penduduk.lembagaekonomi.update', $lembagaEkonomi->nik) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Pilih Penduduk -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penduduk</label>
                        <select name="nik" id="nik"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($penduduks as $p)
                                <option value="{{ $p->nik }}" {{ $lembagaEkonomi->nik == $p->nik ? 'selected' : '' }}>
                                    {{ $p->nama }} ({{ $p->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lembaga Ekonomi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Keikutsertaan Lembaga Ekonomi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($masterLembaga as $index => $lembaga)
                                @php $col = "lemek_" . $loop->iteration; @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $lembaga->lembaga }}</label>
                                    <select name="{{ $col }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawabLemek as $jawab)
                                            <option value="{{ $jawab->kdjawablemek }}"
                                                {{ $lembagaEkonomi->$col == $jawab->kdjawablemek ? 'selected' : '' }}>
                                                {{ $jawab->jawablemek }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.lembagaekonomi.index') }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
