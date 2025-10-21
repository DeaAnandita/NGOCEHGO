<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Aset Perikanan</h3>

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('keluarga.asetperikanan.update', $asetperikanan->no_kk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Keluarga -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Keluarga</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Kartu Keluarga</label>
                                <select name="no_kk" id="no_kk"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Keluarga --</option>
                                    @foreach($keluargas as $kel)
                                        <option value="{{ $kel->no_kk }}" {{ old('no_kk', $asetperikanan->no_kk) == $kel->no_kk ? 'selected' : '' }}>
                                            {{ $kel->keluarga_kepalakeluarga ?? '-' }} ({{ $kel->no_kk }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('no_kk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Detail Aset Ternak -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Detail Aset Perikanan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($masterAset as $index => $aset)
                                @php $field = 'asetperikanan_' . ($index + 1); @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $aset->asetperikanan }}</label>
                                    <input type="text" name="{{ $field }}"
                                           value="{{ old($field, $asetperikanan->$field) }}"
                                           placeholder="Tulis uraian aset..."
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                    @error($field)
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Simpan & Batal -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                            Simpan
                        </button>
                        <a href="{{ route('keluarga.asetperikanan.index') }}"
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
                $('#no_kk').select2({
                    placeholder: "-- Pilih Keluarga --",
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
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type="number"] {
                -moz-appearance: textfield;
            }
        </style>
    @endpush
</x-app-layout>
