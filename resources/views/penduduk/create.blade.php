<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Penduduk</h3>

                <form action="{{ route('dasar-penduduk.store') }}" method="POST"
                      x-data="{
                          isDatang: false,
                          checkMutasi(event) {
                              const text = event.target.options[event.target.selectedIndex].text.toLowerCase();
                              this.isDatang = text.includes('datang');
                          }
                      }"
                      x-init="isDatang = false"
                      class="space-y-8">

                    @csrf

                    <!-- Informasi Pribadi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Pribadi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="16">
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="penduduk_namalengkap" value="{{ old('penduduk_namalengkap') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namalengkap')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="penduduk_tempatlahir" value="{{ old('penduduk_tempatlahir') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_tempatlahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="penduduk_tanggallahir" value="{{ old('penduduk_tanggallahir') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_tanggallahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                                <select name="penduduk_goldarah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Golongan Darah --</option>
                                    <option value="A" {{ old('penduduk_goldarah') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('penduduk_goldarah') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('penduduk_goldarah') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('penduduk_goldarah') == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                                @error('penduduk_goldarah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No Akta Lahir</label>
                                <input type="text" name="penduduk_noaktalahir" value="{{ old('penduduk_noaktalahir') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_noaktalahir')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                                <input type="text" name="penduduk_kewarganegaraan" value="{{ old('penduduk_kewarganegaraan', 'INDONESIA') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_kewarganegaraan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="kdjeniskelamin" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach($jenis_kelamins as $item)
                                        <option value="{{ $item->kdjeniskelamin }}" {{ old('kdjeniskelamin') == $item->kdjeniskelamin ? 'selected' : '' }}>{{ $item->jeniskelamin }}</option>
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
                                        <option value="{{ $item->kdagama }}" {{ old('kdagama') == $item->kdagama ? 'selected' : '' }}>{{ $item->agama }}</option>
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
                                        <option value="{{ $kel->no_kk }}" {{ old('no_kk') == $kel->no_kk ? 'selected' : '' }}>
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
                                <input type="text" name="penduduk_nourutkk" value="{{ old('penduduk_nourutkk') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" maxlength="4">
                                @error('penduduk_nourutkk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hubungan Keluarga</label>
                                <select name="kdhubungankeluarga" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Hubungan --</option>
                                    @foreach($hubungan_keluargas as $item)
                                        <option value="{{ $item->kdhubungankeluarga }}" {{ old('kdhubungankeluarga') == $item->kdhubungankeluarga ? 'selected' : '' }}>{{ $item->hubungankeluarga }}</option>
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
                                        <option value="{{ $item->kdhubungankepalakeluarga }}" {{ old('kdhubungankepalakeluarga') == $item->kdhubungankepalakeluarga ? 'selected' : '' }}>{{ $item->hubungankepalakeluarga }}</option>
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
                                        <option value="{{ $item->kdstatuskawin }}" {{ old('kdstatuskawin') == $item->kdstatuskawin ? 'selected' : '' }}>{{ $item->statuskawin }}</option>
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
                                        <option value="{{ $item->kdaktanikah }}" {{ old('kdaktanikah') == $item->kdaktanikah ? 'selected' : '' }}>{{ $item->aktanikah }}</option>
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
                                        <option value="{{ $item->kdtercantumdalamkk }}" {{ old('kdtercantumdalamkk') == $item->kdtercantumdalamkk ? 'selected' : '' }}>{{ $item->tercantumdalamkk }}</option>
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
                                        <option value="{{ $item->kdstatustinggal }}" {{ old('kdstatustinggal') == $item->kdstatustinggal ? 'selected' : '' }}>{{ $item->statustinggal }}</option>
                                    @endforeach
                                </select>
                                @error('kdstatustinggal')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                                <input type="text" name="penduduk_namaayah" value="{{ old('penduduk_namaayah') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namaayah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                                <input type="text" name="penduduk_namaibu" value="{{ old('penduduk_namaibu') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                        <option value="{{ $item->kdpekerjaan }}" {{ old('kdpekerjaan') == $item->kdpekerjaan ? 'selected' : '' }}>{{ $item->pekerjaan }}</option>
                                    @endforeach
                                </select>
                                @error('kdpekerjaan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Tempat Bekerja</label>
                                <input type="text" name="penduduk_namatempatbekerja" value="{{ old('penduduk_namatempatbekerja') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('penduduk_namatempatbekerja')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kartu Identitas</label>
                                <select name="kdkartuidentitas" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kartu --</option>
                                    @foreach($kartu_identitass as $item)
                                        <option value="{{ $item->kdkartuidentitas }}" {{ old('kdkartuidentitas') == $item->kdkartuidentitas ? 'selected' : '' }}>{{ $item->kartuidentitas }}</option>
                                    @endforeach
                                </select>
                                @error('kdkartuidentitas')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Mutasi Masuk & Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mutasi Masuk</label>
                            <select name="kdmutasimasuk" @change="checkMutasi($event)"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Mutasi --</option>
                                @foreach($mutasi_masuks as $item)
                                    <option value="{{ $item->kdmutasimasuk }}">{{ $item->mutasimasuk }}</option>
                                @endforeach
                            </select>
                            @error('kdmutasimasuk') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mutasi</label>
                            <input type="date" name="penduduk_tanggalmutasi"
                                   value="{{ old('penduduk_tanggalmutasi', date('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('penduduk_tanggalmutasi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- ===== WILAYAH DATANG (sama persis dengan form keluarga) ===== --}}
                    <div x-show="isDatang" x-transition class="mt-8">
                        <h3 class="text-gray-700 font-semibold mb-4">Wilayah Asal (Datang Dari)</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
                             x-data="{
                                 provinsi: '{{ old('kdprovinsi') }}',
                                 kabupaten: '{{ old('kdkabupaten') }}',
                                 kecamatan: '{{ old('kdkecamatan') }}',
                                 desa: '{{ old('kddesa') }}',

                                 kabupatens: [],
                                 kecamatans: [],
                                 desas: [],

                                 async loadKabupaten() {
                                     if (!this.provinsi) {
                                         this.kabupatens = []; this.kabupaten = ''; this.resetLower(); return;
                                     }
                                     const res = await fetch(`/api/wilayah/kabupaten/${this.provinsi}`);
                                     this.kabupatens = await res.json();
                                     this.kabupaten = '';
                                     this.resetLower();
                                 },
                                 async loadKecamatan() {
                                     if (!this.kabupaten) {
                                         this.kecamatans = []; this.kecamatan = ''; this.desas = []; this.desa = ''; return;
                                     }
                                     const res = await fetch(`/api/wilayah/kecamatan/${this.kabupaten}`);
                                     this.kecamatans = await res.json();
                                     this.kecamatan = '';
                                     this.desas = []; this.desa = '';
                                 },
                                 async loadDesa() {
                                     if (!this.kecamatan) { this.desas = []; this.desa = ''; return; }
                                     const res = await fetch(`/api/wilayah/desa/${this.kecamatan}`);
                                     this.desas = await res.json();
                                 },
                                 resetLower() {
                                     this.kecamatans = []; this.kecamatan = '';
                                     this.desas = []; this.desa = '';
                                 }
                             }"
                             x-init="provinsi && loadKabupaten(); kabupaten && loadKecamatan(); kecamatan && loadDesa();">

                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                <select x-model="provinsi" @change="loadKabupaten()"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsis as $p)
                                        <option value="{{ $p->kdprovinsi }}">{{ $p->provinsi }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kdprovinsi" :value="provinsi">
                                @error('kdprovinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota</label>
                                <select x-model="kabupaten" @change="loadKecamatan()"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="!provinsi">
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                    <template x-for="kab in kabupatens" :key="kab.kdkabupaten">
                                        <option :value="kab.kdkabupaten" x-text="kab.kabupaten"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="kdkabupaten" :value="kabupaten">
                                @error('kdkabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                <select x-model="kecamatan" @change="loadDesa()"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="!kabupaten">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    <template x-for="kec in kecamatans" :key="kec.kdkecamatan">
                                        <option :value="kec.kdkecamatan" x-text="kec.kecamatan"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="kdkecamatan" :value="kecamatan">
                                @error('kdkecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Desa/Kelurahan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                                <select x-model="desa"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="!kecamatan">
                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                    <template x-for="d in desas" :key="d.kddesa">
                                        <option :value="d.kddesa" x-text="d.desa"></option>
                                    </template>
                                </select>
                                <input type="hidden" name="kddesa" :value="desa">
                                @error('kddesa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('dasar-penduduk.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
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