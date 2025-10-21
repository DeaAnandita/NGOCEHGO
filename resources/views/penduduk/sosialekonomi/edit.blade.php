<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Sosial Ekonomi</h3>

                <form action="{{ route('penduduk.sosialekonomi.update', $sosialekonomi->nik) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Sosial Ekonomi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Sosial Ekonomi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" disabled>
                                    <option value="{{ $sosialekonomi->nik }}">
                                        {{ $sosialekonomi->penduduk->penduduk_namalengkap ?? 'Tidak ditemukan' }} ({{ $sosialekonomi->nik }})
                                    </option>
                                </select>
                                <input type="hidden" name="nik" value="{{ $sosialekonomi->nik }}">
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Partisipasi Sekolah</label>
                                <select name="kdpartisipasisekolah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($partisipasi_sekolahs as $item)
                                        <option value="{{ $item->kdpartisipasisekolah }}" {{ old('kdpartisipasisekolah', $sosialekonomi->kdpartisipasisekolah) == $item->kdpartisipasisekolah ? 'selected' : '' }}>
                                            {{ $item->partisipasisekolah }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdpartisipasisekolah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ijasah Terakhir</label>
                                <select name="kdijasahterakhir" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($ijasah_terakhirs as $item)
                                        <option value="{{ $item->kdijasahterakhir }}" {{ old('kdijasahterakhir', $sosialekonomi->kdijasahterakhir) == $item->kdijasahterakhir ? 'selected' : '' }}>
                                            {{ $item->ijasahterakhir }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdijasahterakhir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Disabilitas</label>
                                <select name="kdjenisdisabilitas" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($jenis_disabilitass as $item)
                                        <option value="{{ $item->kdjenisdisabilitas }}" {{ old('kdjenisdisabilitas', $sosialekonomi->kdjenisdisabilitas) == $item->kdjenisdisabilitas ? 'selected' : '' }}>
                                            {{ $item->jenisdisabilitas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdjenisdisabilitas')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tingkat Kesulitan Disabilitas</label>
                                <select name="kdtingkatsulitdisabilitas" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($tingkat_sulit_disabilitass as $item)
                                        <option value="{{ $item->kdtingkatsulitdisabilitas }}" {{ old('kdtingkatsulitdisabilitas', $sosialekonomi->kdtingkatsulitdisabilitas) == $item->kdtingkatsulitdisabilitas ? 'selected' : '' }}>
                                            {{ $item->tingkatsulitdisabilitas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdtingkatsulitdisabilitas')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penyakit Kronis/Menahun</label>
                                <select name="kdpenyakitkronis" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($penyakit_kroniss as $item)
                                        <option value="{{ $item->kdpenyakitkronis }}" {{ old('kdpenyakitkronis', $sosialekonomi->kdpenyakitkronis) == $item->kdpenyakitkronis ? 'selected' : '' }}>
                                            {{ $item->penyakitkronis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdpenyakitkronis')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lapangan Usaha dari Pekerjaan Utama</label>
                                <select name="kdlapanganusaha" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($lapangan_usahas as $item)
                                        <option value="{{ $item->kdlapanganusaha }}" {{ old('kdlapanganusaha', $sosialekonomi->kdlapanganusaha) == $item->kdlapanganusaha ? 'selected' : '' }}>
                                            {{ $item->lapanganusaha }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdlapanganusaha')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Kedudukan Dalam Kerja</label>
                                <select name="kdstatuskedudukankerja" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($status_kedudukan_kerjas as $item)
                                        <option value="{{ $item->kdstatuskedudukankerja }}" {{ old('kdstatuskedudukankerja', $sosialekonomi->kdstatuskedudukankerja) == $item->kdstatuskedudukankerja ? 'selected' : '' }}>
                                            {{ $item->statuskedudukankerja }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdstatuskedudukankerja')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pendapatan Per Bulan</label>
                                <select name="kdpendapatanperbulan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($pendapatan_perbulans as $item)
                                        <option value="{{ $item->kdpendapatanperbulan }}" {{ old('kdpendapatanperbulan', $sosialekonomi->kdpendapatanperbulan) == $item->kdpendapatanperbulan ? 'selected' : '' }}>
                                            {{ $item->pendapatanperbulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdpendapatanperbulan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cakupan Imunisasi</label>
                                <select name="kdimunisasi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan pilih --</option>
                                    @foreach($imunisis as $item)
                                        <option value="{{ $item->kdimunisasi }}" {{ old('kdimunisasi', $sosialekonomi->kdimunisasi) == $item->kdimunisasi ? 'selected' : '' }}>
                                            {{ $item->imunisasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdimunisasi')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan Perubahan</button>
                        <a href="{{ route('penduduk.sosialekonomi.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
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
                    width: '100%',
                    disabled: true
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
