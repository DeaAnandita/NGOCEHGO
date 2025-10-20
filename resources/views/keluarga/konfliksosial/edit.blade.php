<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Konflik Sosial</h3>

                <form action="{{ route('keluarga.konfliksosial.update', $konfliksosial->no_kk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Keluarga -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Keluarga</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Kartu Keluarga</label>
                                <select id="no_kk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" disabled>
                                    <option value="">-- Pilih Keluarga --</option>
                                    @foreach($keluargas as $kel)
                                        <option value="{{ $kel->no_kk }}" {{ $kel->no_kk == $konfliksosial->no_kk ? 'selected' : '' }}>
                                            {{ $kel->keluarga_kepalakeluarga ?? '-' }} ({{ $kel->no_kk }})
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden agar tetap dikirim -->
                                <input type="hidden" name="no_kk" value="{{ $konfliksosial->no_kk }}">
                            </div>
                        </div>
                    </div>

                    <!-- Detail Konflik Sosial -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Detail Konflik Sosial</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($masterKonflik as $konflik)
                                @php
                                    $field = 'konfliksosial_' . $konflik->kdkonfliksosial;
                                    $selectedValue = old($field, $konfliksosial->$field ?? '');
                                @endphp
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $konflik->kdkonfliksosial }}. {{ $konflik->konfliksosial }}
                                    </label>
                                    <select name="{{ $field }}"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawabkonflik }}"
                                                {{ $selectedValue == $jawab->kdjawabkonflik ? 'selected' : '' }}>
                                                {{ $jawab->jawabkonflik }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                            Update
                        </button>
                        <a href="{{ route('keluarga.konfliksosial.index') }}"
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
        </style>
    @endpush
</x-app-layout>
