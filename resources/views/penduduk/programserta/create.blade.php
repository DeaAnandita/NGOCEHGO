<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Program Serta</h3>

                <form action="{{ route('penduduk.programserta.store') }}" method="POST">
                    @csrf

                    <!-- Relasi Penduduk -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Relasi Penduduk</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Penduduk --</option>
                                    @foreach($penduduks as $p)
                                        <option value="{{ $p->nik }}" {{ old('nik') == $p->nik ? 'selected' : '' }}>
                                            {{ $p->penduduk_namalengkap }} ({{ $p->nik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
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
                                    <select name="programserta_{{ $i }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $key => $val)
                                            <option value="{{ $key }}" {{ old("programserta_$i") == $key ? 'selected' : '' }}>
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
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.programserta.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#nik').select2({
                    placeholder: "-- Pilih Penduduk --",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
</x-app-layout>
