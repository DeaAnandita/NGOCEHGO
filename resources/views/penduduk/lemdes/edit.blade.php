<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Lembaga Desa</h3>

                <form action="{{ route('penduduk.lemdes.update', $lembagadesa->nik) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Relasi Penduduk -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Relasi Penduduk</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIK</label>
                            <select name="nik" id="nik" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                <option value="">-- Pilih Penduduk --</option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->nik }}" {{ old('nik', $lembagadesa->nik) == $p->nik ? 'selected' : '' }}>
                                        {{ $p->penduduk_namalengkap }} ({{ $p->nik }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Lembaga Desa -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Keterlibatan Dalam Lembaga Desa</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                1 => 'KEPALA DESA/LURAH',
                                2 => 'SEKRETARIS DESA/LURAH',
                                3 => 'KEPALA URUSAN',
                                4 => 'KEPALA DUSUN/LINGKUNGAN',
                                5 => 'STAF DESA/KELURAHAN',
                                6 => 'KETUA BPD',
                                7 => 'WAKIL KETUA BPD',
                                8 => 'SEKRETARIS BPD',
                                9 => 'ANGGOTA BPD'
                            ] as $i => $label)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <select name="lemdes_{{ $i }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $key => $val)
                                            <option value="{{ $key }}" {{ old("lemdes_$i", $lembagadesa["lemdes_$i"]) == $key ? 'selected' : '' }}>
                                                {{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.lemdes.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
