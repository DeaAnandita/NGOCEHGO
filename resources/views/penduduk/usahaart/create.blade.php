<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Usaha</h3>

                <form action="{{ route('penduduk.usahaart.store') }}" method="POST">
                    @csrf

                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Usaha</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Penduduk --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}" {{ old('nik') == $penduduk->nik ? 'selected' : '' }}>
                                            {{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lapangan Usaha</label>
                                <select name="kdlapanganusaha" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($lapangan_usahas as $item)
                                        <option value="{{ $item->kdlapanganusaha }}" {{ old('kdlapanganusaha') == $item->kdlapanganusaha ? 'selected' : '' }}>
                                            {{ $item->lapanganusaha }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdlapanganusaha')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Pekerja</label>
                                <input type="number" name="usahaart_jumlahpekerja" value="{{ old('usahaart_jumlahpekerja') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0">
                                @error('usahaart_jumlahpekerja')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Usaha</label>
                                <input type="text" name="usahaart_namausaha" value="{{ old('usahaart_namausaha') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('usahaart_namausaha')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kepemilikan Tempat Usaha</label>
                                <select name="kdtempatusaha" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($tempat_usahas as $item)
                                        <option value="{{ $item->kdtempatusaha }}" {{ old('kdtempatusaha') == $item->kdtempatusaha ? 'selected' : '' }}>
                                            {{ $item->tempatusaha }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdtempatusaha')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Omset Usaha Per Bulan</label>
                                <select name="kdomsetusaha" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($omset_usahas as $item)
                                        <option value="{{ $item->kdomsetusaha }}" {{ old('kdomsetusaha') == $item->kdomsetusaha ? 'selected' : '' }}>
                                            {{ $item->omsetusaha }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdomsetusaha')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('penduduk.usahaart.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
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
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 28px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }
            .select2-container--default .select2-selection--single:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
            }
        </style>
    @endpush
</x-app-layout>