<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Kelahiran</h3>

                <form action="{{ route('penduduk.kelahiran.update', $kelahiran->nik) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Kelahiran -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Kelahiran</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <select name="nik" id="nik"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih NIK --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}" {{ $kelahiran->nik == $penduduk->nik ? 'selected' : '' }}>
                                            {{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Persalinan</label>
                                <select name="kdtempatpersalinan" id="kdtempatpersalinan"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($tempat_persalinans as $item)
                                        <option value="{{ $item->kdtempatpersalinan }}" {{ $kelahiran->kdtempatpersalinan == $item->kdtempatpersalinan ? 'selected' : '' }}>
                                            {{ $item->tempatpersalinan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdtempatpersalinan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelahiran</label>
                                <select name="kdjeniskelahiran" id="kdjeniskelahiran"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($jenis_kelahirans as $item)
                                        <option value="{{ $item->kdjeniskelahiran }}" {{ $kelahiran->kdjeniskelahiran == $item->kdjeniskelahiran ? 'selected' : '' }}>
                                            {{ $item->jeniskelahiran }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdjeniskelahiran')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pertolongan Persalinan</label>
                                <select name="kdpertolonganpersalinan" id="kdpertolonganpersalinan"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($pertolongan_persalinans as $item)
                                        <option value="{{ $item->kdpertolonganpersalinan }}" {{ $kelahiran->kdpertolonganpersalinan == $item->kdpertolonganpersalinan ? 'selected' : '' }}>
                                            {{ $item->pertolonganpersalinan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kdpertolonganpersalinan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jam Kelahiran</label>
                                <input type="time" name="kelahiran_jamkelahiran"
                                    value="{{ old('kelahiran_jamkelahiran', $kelahiran->kelahiran_jamkelahiran) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('kelahiran_jamkelahiran')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kelahiran Ke</label>
                                <input type="number" name="kelahiran_kelahiranke"
                                    value="{{ old('kelahiran_kelahiranke', $kelahiran->kelahiran_kelahiranke) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                <input type="number" name="kelahiran_berat"
                                    value="{{ old('kelahiran_berat', $kelahiran->kelahiran_berat) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Panjang (cm)</label>
                                <input type="number" name="kelahiran_panjang"
                                    value="{{ old('kelahiran_panjang', $kelahiran->kelahiran_panjang) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                            </div>
                        </div>
                    </div>

                    <!-- Identitas Orang Tua -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Identitas Orang Tua</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Identitas Ibu</label>
                                <select name="kelahiran_nikibu" id="kelahiran_nikibu"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}" {{ $kelahiran->kelahiran_nikibu == $penduduk->nik ? 'selected' : '' }}>
                                            {{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Identitas Ayah</label>
                                <select name="kelahiran_nikayah" id="kelahiran_nikayah"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Silahkan Pilih --</option>
                                    @foreach($penduduks as $penduduk)
                                        <option value="{{ $penduduk->nik }}" {{ $kelahiran->kelahiran_nikayah == $penduduk->nik ? 'selected' : '' }}>
                                            {{ $penduduk->penduduk_namalengkap }} ({{ $penduduk->nik }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Wilayah Datang -->
                    <div>
                        <h3 class="text-gray-700 font-semibold mb-2">Wilayah Datang</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                <select name="kdprovinsi" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsis as $d)
                                        <option value="{{ $d->kdprovinsi }}" {{ $kelahiran->kdprovinsi == $d->kdprovinsi ? 'selected' : '' }}>{{ $d->provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten</label>
                                <select name="kdkabupaten" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kabupaten --</option>
                                    @foreach($kabupatens as $d)
                                        <option value="{{ $d->kdkabupaten }}" {{ $kelahiran->kdkabupaten == $d->kdkabupaten ? 'selected' : '' }}>{{ $d->kabupaten }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                <select name="kdkecamatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($kecamatans as $d)
                                        <option value="{{ $d->kdkecamatan }}" {{ $kelahiran->kdkecamatan == $d->kdkecamatan ? 'selected' : '' }}>{{ $d->kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                                <select name="kddesa" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $d)
                                        <option value="{{ $d->kddesa }}" {{ $kelahiran->kddesa == $d->kddesa ? 'selected' : '' }}>{{ $d->desa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RW</label>
                                <input type="text" name="kelahiran_rw" value="{{ old('kelahiran_rw', $kelahiran->kelahiran_rw) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RT</label>
                                <input type="text" name="kelahiran_rt" value="{{ old('kelahiran_rt', $kelahiran->kelahiran_rt) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="3">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                            Update
                        </button>
                        <a href="{{ route('penduduk.kelahiran.index') }}"
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
        </style>
    @endpush
</x-app-layout>
