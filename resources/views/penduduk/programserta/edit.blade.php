<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Program Serta</h3>

                <form action="{{ route('penduduk.programserta.update', $programserta->nik) }}" method="POST">
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
                                    <option value="{{ $p->nik }}" {{ old('nik', $programserta->nik) == $p->nik ? 'selected' : '' }}>
                                        {{ $p->penduduk_namalengkap }} ({{ $p->nik }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Program Serta -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Program Bantuan yang Diterima</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                1 => 'KKS/KPS',
                                2 => 'KIP',
                                3 => 'KIS',
                                4 => 'BPJS Non PBI',
                                5 => 'Jamsostek',
                                6 => 'Asuransi Lainnya',
                                7 => 'PKH',
                                8 => 'Raskin'
                            ] as $i => $label)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <select name="programserta_{{ $i }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $key => $val)
                                            <option value="{{ $key }}" {{ old("programserta_$i", $programserta["programserta_$i"]) == $key ? 'selected' : '' }}>
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
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.programserta.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
