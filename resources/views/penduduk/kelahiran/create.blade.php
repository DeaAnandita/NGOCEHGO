<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Kelahiran</h3>

                <form action="{{ route('penduduk.kelahiran.store') }}" method="POST">
                    @csrf

                    <!-- Informasi Kelahiran -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Kelahiran</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih NIK --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}">{{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})</option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Persalinan</label>
                                <select name="kdtempatpersalinan" id="kdtempatpersalinan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($tempat_persalinans as $item)
                                        <option value="{{ $item->kdtempatpersalinan }}" {{ old('kdtempatpersalinan') == $item->kdtempatpersalinan ? 'selected' : '' }}>{{ $item->tempatpersalinan }}</option>
                                    @endforeach
                                </select>
                                @error('kdtempatpersalinan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelahiran</label>
                                <select name="kdjeniskelahiran" id="kdjeniskelahiran" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($jenis_kelahirans as $item)
                                        <option value="{{ $item->kdjeniskelahiran }}" {{ old('kdjeniskelahiran') == $item->kdjeniskelahiran ? 'selected' : '' }}>{{ $item->jeniskelahiran }}</option>
                                    @endforeach
                                </select>
                                @error('kdjeniskelahiran')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pertolongan Persalinan</label>
                                <select name="kdpertolonganpersalinan" id="kdpertolonganpersalinan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($pertolongan_persalinans as $item)
                                        <option value="{{ $item->kdpertolonganpersalinan }}" {{ old('kdpertolonganpersalinan') == $item->kdpertolonganpersalinan ? 'selected' : '' }}>{{ $item->pertolonganpersalinan }}</option>
                                    @endforeach
                                </select>
                                @error('kdpertolonganpersalinan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jam Kelahiran</label>
                                <input type="time" name="kelahiran_jamkelahiran" value="{{ old('kelahiran_jamkelahiran') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('kelahiran_jamkelahiran')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kelahiran Ke</label>
                                <input type="number" name="kelahiran_kelahiranke" value="{{ old('kelahiran_kelahiranke') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="1">
                                @error('kelahiran_kelahiranke')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                <input type="number" name="kelahiran_berat" value="{{ old('kelahiran_berat') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                                @error('kelahiran_berat')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Panjang (cm)</label>
                                <input type="number" name="kelahiran_panjang" value="{{ old('kelahiran_panjang') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                                @error('kelahiran_panjang')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Keluarga Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Identitas Orang Tua</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             <!-- Pilih Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Identitas Ibu</label>
                                <select name="kelahiran_nikibu" id="kelahiran_nikibu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}">{{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})</option>
                                    @endforeach
                                </select>
                                @error('kelahiran_nikibu')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Identitas Ayah</label>
                                <select name="kelahiran_nikayah" id="kelahiran_nikayah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}">{{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})</option>
                                    @endforeach
                                </select>
                                @error('kelahiran_nikayah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                                
                            </div>
                        </div>
                    </div>

                    {{-- <!-- Wilayah -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Wilayah</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <select id="kdprovinsi" name="kdprovinsi" class="form-select">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinsis as $provinsi)
                                    <option value="{{ $provinsi->kdprovinsi }}">{{ $provinsi->provinsi }}</option>
                                @endforeach
                            </select>
                                @error('kdprovinsi')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kabupaten</label>
                                <select name="kdkabupaten" id="kdkabupaten" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Provinsi Dahulu --</option>
                                </select>
                                @error('kdkabupaten')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>  
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                <select name="kdkecamatan" id="kdkecamatan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kabupaten Dahulu --</option>
                                </select>
                                @error('kdkecamatan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                                <select name="kddesa" id="kddesa" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kecamatan Dahulu --</option>
                                </select>
                                @error('kddesa')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RW</label>
                                <input type="text" name="kelahiran_rw" value="{{ old('kelahiran_rw') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                                @error('kelahiran_rw')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RT</label>
                                <input type="text" name="kelahiran_rt" value="{{ old('kelahiran_rt') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                                @error('kelahiran_rt')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div> --}}
                    {{-- Wilayah Datang --}}
                <div>
                    <h3 class="text-gray-700 font-semibold mb-2">Wilayah Datang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <select name="kdprovinsi" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinsis as $d)
                                    <option value="{{ $d->kdprovinsi }}">{{ $d->provinsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten</label>
                            <select name="kdkabupaten" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Provinsi Dahulu --</option>
                                @foreach($kabupatens as $d)
                                    <option value="{{ $d->kdkabupaten }}">{{ $d->kabupaten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <select name="kdkecamatan" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kabupaten Dahulu --</option>
                                @foreach($kecamatans as $d)
                                    <option value="{{ $d->kdkecamatan }}">{{ $d->kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                            <select name="kddesa" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kecamatan Dahulu --</option>
                                @foreach($desas as $d)
                                    <option value="{{ $d->kddesa }}">{{ $d->desa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                                <label class="block text-sm font-medium text-gray-700">RW</label>
                                <input type="text" name="kelahiran_rw" value="{{ old('kelahiran_rw') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                                @error('kelahiran_rw')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RT</label>
                                <input type="text" name="kelahiran_rt" value="{{ old('kelahiran_rt') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                                @error('kelahiran_rt')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('penduduk.kelahiran.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
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
                    placeholder: "-- Silahkan Pilih --",
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

    {{-- @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#nik, #nik_ibu, #nik_ayah, #kdtempatpersalinan, #kdjeniskelahiran, #kdpertolonganpersalinan, #kdprovinsi, #kdkabupaten, #kdkecamatan, #kddesa').select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    width: '100%'
                });

                function fetchPendudukDetails(nik, targetElement) {
                    if (nik) {
                        $.ajax({
                            url: '{{ route('kelahiran.get-penduduk') }}/' + nik,
                            method: 'GET',
                            success: function(data) {
                                $(targetElement).html(`
                                    <p><strong>NIK:</strong> ${data.nik}</p>
                                    <p><strong>Nama:</strong> ${data.nama}</p>
                                    <p><strong>Tanggal Lahir:</strong> ${data.tanggal_lahir}</p>
                                    <p><strong>Pekerjaan:</strong> ${data.pekerjaan}</p>
                                    <p><strong>Kewarganegaraan:</strong> ${data.kewarganegaraan}</p>
                                    <p><strong>Alamat:</strong> ${data.alamat}</p>
                                    <p><strong>RW:</strong> ${data.rw}</p>
                                    <p><strong>RT:</strong> ${data.rt}</p>
                                `);
                            },
                            error: function(xhr) {
                                $(targetElement).html('<p class="text-red-500">Gagal memuat data: ' + (xhr.responseJSON?.message || 'NIK tidak ditemukan') + '</p>');
                            }
                        });
                    } else {
                        $(targetElement).html('');
                    }
                }

                $('#nik_ibu').on('change', function() {
                    fetchPendudukDetails($(this).val(), '#ibu-details');
                });

                $('#nik_ayah').on('change', function() {
                    fetchPendudukDetails($(this).val(), '#ayah-details');
                });

                // Cascading dropdowns for wilayah
                $('#kdprovinsi').on('change', function() {
                    const kdprovinsi = $(this).val();
                    $('#kdkabupaten').empty().append('<option value="">-- Pilih Kabupaten Dahulu --</option>');
                    $('#kdkecamatan').empty().append('<option value="">-- Pilih Kabupaten Dahulu --</option>');
                    $('#kddesa').empty().append('<option value="">-- Pilih Kecamatan Dahulu --</option>');
                    if (kdprovinsi) {
                        $.get('{{ route('kelahiran.getKabupatens') }}/' + kdprovinsi, function(data) {
                            $.each(data, function(index, item) {
                                $('#kdkabupaten').append(`<option value="${item.kdkabupaten}">${item.kabupaten}</option>`);
                            });
                        });
                    }
                });

                $('#kdkabupaten').on('change', function() {
                    const kdkabupaten = $(this).val();
                    $('#kdkecamatan').empty().append('<option value="">-- Pilih Kabupaten Dahulu --</option>');
                    $('#kddesa').empty().append('<option value="">-- Pilih Kecamatan Dahulu --</option>');
                    if (kdkabupaten) {
                        $.get('{{ route('kelahiran.getKecamatans') }}/' + kdkabupaten, function(data) {
                            $.each(data, function(index, item) {
                                $('#kdkecamatan').append(`<option value="${item.kdkecamatan}">${item.kecamatan}</option>`);
                            });
                        });
                    }
                });

                $('#kdkecamatan').on('change', function() {
                    const kdkecamatan = $(this).val();
                    $('#kddesa').empty().append('<option value="">-- Pilih Kecamatan Dahulu --</option>');
                    if (kdkecamatan) {
                        $.get('{{ route('kelahiran.getDesas') }}/' + kdkecamatan, function(data) {
                            $.each(data, function(index, item) {
                                $('#kddesa').append(`<option value="${item.kddesa}">${item.desa}</option>`);
                            });
                        });
                    }
                });
            });
        </script>
    @endpush --}}
</x-app-layout>