<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Penduduk</h3>

                <form action="{{ route('penduduk.update', $penduduk->nik) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Pribadi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Pribadi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik', $penduduk->nik) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="16">
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="penduduk_namalengkap" value="{{ old('penduduk_namalengkap', $penduduk->penduduk_namalengkap) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namalengkap')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="penduduk_tempatlahir" value="{{ old('penduduk_tempatlahir', $penduduk->penduduk_tempatlahir) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_tempatlahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="penduduk_tanggallahir" value="{{ old('penduduk_tanggallahir', $penduduk->penduduk_tanggallahir) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_tanggallahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                                <select name="penduduk_goldarah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Golongan Darah --</option>
                                    <option value="A" {{ old('penduduk_goldarah', $penduduk->penduduk_goldarah) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('penduduk_goldarah', $penduduk->penduduk_goldarah) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('penduduk_goldarah', $penduduk->penduduk_goldarah) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('penduduk_goldarah', $penduduk->penduduk_goldarah) == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                                @error('penduduk_goldarah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No Akta Lahir</label>
                                <input type="text" name="penduduk_noaktalahir" value="{{ old('penduduk_noaktalahir', $penduduk->penduduk_noaktalahir) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_noaktalahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                                <input type="text" name="penduduk_kewarganegaraan" value="{{ old('penduduk_kewarganegaraan', $penduduk->penduduk_kewarganegaraan) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_kewarganegaraan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="kdjeniskelamin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach($jenis_kelamins as $item)
                                        <option value="{{ $item->kdjeniskelamin }}" {{ old('kdjeniskelamin', $penduduk->kdjeniskelamin) == $item->kdjeniskelamin ? 'selected' : '' }}>{{ $item->jeniskelamin }}</option>
                                    @endforeach
                                </select>
                                @error('kdjeniskelamin')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Agama</label>
                                <select name="kdagama" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach($agamas as $item)
                                        <option value="{{ $item->kdagama }}" {{ old('kdagama', $penduduk->kdagama) == $item->kdagama ? 'selected' : '' }}>{{ $item->agama }}</option>
                                    @endforeach
                                </select>
                                @error('kdagama')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Relasi Keluarga -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Relasi Keluarga</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No KK</label>
                                <select name="no_kk" id="no_kk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Keluarga --</option>
                                    @foreach($keluargas as $kel)
                                        <option value="{{ $kel->no_kk }}" {{ old('no_kk', $penduduk->no_kk) == $kel->no_kk ? 'selected' : '' }}>
                                            {{ $kel->keluarga_kepalakeluarga ?? '-' }} ({{ $kel->no_kk }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('no_kk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No Urut KK</label>
                                <input type="text" name="penduduk_nourutkk" value="{{ old('penduduk_nourutkk', $penduduk->penduduk_nourutkk) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="4">
                                @error('penduduk_nourutkk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hubungan Keluarga</label>
                                <select name="kdhubungankeluarga" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Hubungan --</option>
                                    @foreach($hubungan_keluargas as $item)
                                        <option value="{{ $item->kdhubungankeluarga }}" {{ old('kdhubungankeluarga', $penduduk->kdhubungankeluarga) == $item->kdhubungankeluarga ? 'selected' : '' }}>{{ $item->hubungankeluarga }}</option>
                                    @endforeach
                                </select>
                                @error('kdhubungankeluarga')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hubungan Kepala Keluarga</label>
                                <select name="kdhubungankepalakeluarga" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Hubungan --</option>
                                    @foreach($hubungan_kepala_keluargas as $item)
                                        <option value="{{ $item->kdhubungankepalakeluarga }}" {{ old('kdhubungankepalakeluarga', $penduduk->kdhubungankepalakeluarga) == $item->kdhubungankepalakeluarga ? 'selected' : '' }}>{{ $item->hubungankepalakeluarga }}</option>
                                    @endforeach
                                </select>
                                @error('kdhubungankepalakeluarga')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Kawin</label>
                                <select name="kdstatuskawin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($status_kawins as $item)
                                        <option value="{{ $item->kdstatuskawin }}" {{ old('kdstatuskawin', $penduduk->kdstatuskawin) == $item->kdstatuskawin ? 'selected' : '' }}>{{ $item->statuskawin }}</option>
                                    @endforeach
                                </select>
                                @error('kdstatuskawin')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Akta Nikah</label>
                                <select name="kdaktanikah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Akta --</option>
                                    @foreach($akta_nikahs as $item)
                                        <option value="{{ $item->kdaktanikah }}" {{ old('kdaktanikah', $penduduk->kdaktanikah) == $item->kdaktanikah ? 'selected' : '' }}>{{ $item->aktanikah }}</option>
                                    @endforeach
                                </select>
                                @error('kdaktanikah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tercantum dalam KK</label>
                                <select name="kdtercantumdalamkk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($tercantum_dalam_kks as $item)
                                        <option value="{{ $item->kdtercantumdalamkk }}" {{ old('kdtercantumdalamkk', $penduduk->kdtercantumdalamkk) == $item->kdtercantumdalamkk ? 'selected' : '' }}>{{ $item->tercantumdalamkk }}</option>
                                    @endforeach
                                </select>
                                @error('kdtercantumdalamkk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Tinggal</label>
                                <select name="kdstatustinggal" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($status_tinggals as $item)
                                        <option value="{{ $item->kdstatustinggal }}" {{ old('kdstatustinggal', $penduduk->kdstatustinggal) == $item->kdstatustinggal ? 'selected' : '' }}>{{ $item->statustinggal }}</option>
                                    @endforeach
                                </select>
                                @error('kdstatustinggal')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                                <input type="text" name="penduduk_namaayah" value="{{ old('penduduk_namaayah', $penduduk->penduduk_namaayah) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namaayah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                                <input type="text" name="penduduk_namaibu" value="{{ old('penduduk_namaibu', $penduduk->penduduk_namaibu) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namaibu')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pekerjaan -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Pekerjaan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                                <select name="kdpekerjaan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    @foreach($pekerjaans as $item)
                                        <option value="{{ $item->kdpekerjaan }}" {{ old('kdpekerjaan', $penduduk->kdpekerjaan) == $item->kdpekerjaan ? 'selected' : '' }}>{{ $item->pekerjaan }}</option>
                                    @endforeach
                                </select>
                                @error('kdpekerjaan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Tempat Bekerja</label>
                                <input type="text" name="penduduk_namatempatbekerja" value="{{ old('penduduk_namatempatbekerja', $penduduk->penduduk_namatempatbekerja) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namatempatbekerja')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kartu Identitas</label>
                                <select name="kdkartuidentitas" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kartu --</option>
                                    @foreach($kartu_identitass as $item)
                                        <option value="{{ $item->kdkartuidentitas }}" {{ old('kdkartuidentitas', $penduduk->kdkartuidentitas) == $item->kdkartuidentitas ? 'selected' : '' }}>{{ $item->kartuidentitas }}</option>
                                    @endforeach
                                </select>
                                @error('kdkartuidentitas')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Wilayah Mutasi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Wilayah Mutasi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mutasi Masuk</label>
                                <select name="kdmutasimasuk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Mutasi --</option>
                                    @foreach($mutasi_masuks as $item)
                                        <option value="{{ $item->kdmutasimasuk }}" {{ old('kdmutasimasuk', $penduduk->kdmutasimasuk) == $item->kdmutasimasuk ? 'selected' : '' }}>{{ $item->mutasimasuk }}</option>
                                    @endforeach
                                </select>
                                @error('kdmutasimasuk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mutasi</label>
                                <input type="date" name="penduduk_tanggalmutasi" value="{{ old('penduduk_tanggalmutasi', $penduduk->penduduk_tanggalmutasi) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_tanggalmutasi')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <select name="kdprovinsi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsis as $item)
                                        <option value="{{ $item->kdprovinsi }}" {{ old('kdprovinsi', $penduduk->kdprovinsi) == $item->kdprovinsi ? 'selected' : '' }}>{{ $item->provinsi }}</option>
                                    @endforeach
                                </select>
                                @error('kdprovinsi')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kabupaten</label>
                                <select name="kdkabupaten" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kabupaten --</option>
                                    @foreach($kabupatens as $item)
                                        <option value="{{ $item->kdkabupaten }}" {{ old('kdkabupaten', $penduduk->kdkabupaten) == $item->kdkabupaten ? 'selected' : '' }}>{{ $item->kabupaten }}</option>
                                    @endforeach
                                </select>
                                @error('kdkabupaten')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                <select name="kdkecamatan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($kecamatans as $item)
                                        <option value="{{ $item->kdkecamatan }}" {{ old('kdkecamatan', $penduduk->kdkecamatan) == $item->kdkecamatan ? 'selected' : '' }}>{{ $item->kecamatan }}</option>
                                    @endforeach
                                </select>
                                @error('kdkecamatan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Desa</label>
                                <select name="kddesa" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $item)
                                        <option value="{{ $item->kddesa }}" {{ old('kddesa', $penduduk->kddesa) == $item->kddesa ? 'selected' : '' }}>{{ $item->desa }}</option>
                                    @endforeach
                                </select>
                                @error('kddesa')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('penduduk.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
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
        </style>
    @endpush
</x-app-layout>