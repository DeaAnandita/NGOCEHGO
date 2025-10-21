<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Lembaga Desa</h3>

                <form action="{{ route('penduduk.lemdes.store') }}" method="POST">
                    @csrf

                    <!-- Relasi Penduduk -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Relasi Penduduk</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                    <select name="lemdes_{{ $i }}"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $key => $val)
                                            <option value="{{ $key }}" {{ old("lemdes_$i") == $key ? 'selected' : '' }}>
                                                {{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("lemdes_$i")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.lemdes.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">
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
        <style>
            .select2-container--default .select2-selection--single {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                height: 38px;
                padding: 0.5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush
</x-app-layout>
