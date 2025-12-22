<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Data Keluarga</h2>

                @php
                    $mutasiText     = optional($keluarga->mutasi)->mutasimasuk ?? '';
                    $initialIsDatang = str_contains(strtolower($mutasiText), 'datang');
                @endphp

                <form action="{{ route('dasar-keluarga.update', $keluarga->no_kk) }}" method="POST" class="space-y-4"
                      x-data="{ isDatang: {{ $initialIsDatang ? 'true' : 'false' }} }"
                      x-init="$watch('isDatang', value => console.log('isDatang changed:', value))">

                    @csrf
                    @method('PUT')

                    <!-- Jenis Mutasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                        <select name="kdmutasimasuk" 
                                @change="isDatang = $event.target.selectedOptions[0].text.toLowerCase().includes('datang')"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Mutasi --</option>
                            @foreach($mutasis as $m)
                                <option value="{{ $m->kdmutasimasuk }}"
                                    {{ old('kdmutasimasuk', $keluarga->kdmutasimasuk) == $m->kdmutasimasuk ? 'selected' : '' }}>
                                    {{ $m->mutasimasuk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Mutasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mutasi</label>
                        <input type="date" name="keluarga_tanggalmutasi"
                            value="{{ old('keluarga_tanggalmutasi', $keluarga->keluarga_tanggalmutasi) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Nomor KK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KK</label>
                        <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                            value="{{ old('no_kk', $keluarga->no_kk) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan 16 digit nomor KK">
                        @error('no_kk') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kepala Rumah Tangga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Rumah Tangga</label>
                        <input type="text" name="keluarga_kepalakeluarga" required
                            value="{{ old('keluarga_kepalakeluarga', $keluarga->keluarga_kepalakeluarga) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama kepala rumah tangga">
                    </div>

                    <!-- Dusun, RW, RT -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dusun/Lingkungan</label>
                            <select name="kddusun" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Silahkan Pilih --</option>
                                @foreach($dusuns as $d)
                                    <option value="{{ $d->kddusun }}"
                                        {{ old('kddusun', $keluarga->kddusun) == $d->kddusun ? 'selected' : '' }}>
                                        {{ $d->dusun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW (3 digit)</label>
                            <input type="text" name="keluarga_rw" maxlength="3" required
                                   value="{{ old('keluarga_rw', $keluarga->keluarga_rw) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT (3 digit)</label>
                            <input type="text" name="keluarga_rt" maxlength="3" required
                                   value="{{ old('keluarga_rt', $keluarga->keluarga_rt) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 002">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="keluarga_alamatlengkap" rows="3" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Masukkan alamat lengkap">{{ old('keluarga_alamatlengkap', $keluarga->keluarga_alamatlengkap) }}</textarea>
                    </div>

                    <!-- Wilayah Asal (Datang Dari) - DINAMIS -->
                    <div x-show="isDatang" x-transition>
                        <div class="mt-6">
                            <h3 class="text-gray-700 font-semibold mb-4">Wilayah Asal (Datang Dari)</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Provinsi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                    <select name="kdprovinsi" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsis as $p)
                                            <option value="{{ $p->kdprovinsi }}"
                                                {{ old('kdprovinsi', $keluarga->kdprovinsi) == $p->kdprovinsi ? 'selected' : '' }}>
                                                {{ $p->provinsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kdprovinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Kabupaten/Kota -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                                    <select name="kdkabupaten" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                        @foreach($kabupatens as $kab)
                                            <option value="{{ $kab->kdkabupaten }}"
                                                {{ old('kdkabupaten', $keluarga->kdkabupaten) == $kab->kdkabupaten ? 'selected' : '' }}>
                                                {{ $kab->kabupaten }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kdkabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Kecamatan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                    <select name="kdkecamatan" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->kdkecamatan }}"
                                                {{ old('kdkecamatan', $keluarga->kdkecamatan) == $kec->kdkecamatan ? 'selected' : '' }}>
                                                {{ $kec->kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kdkecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Desa/Kelurahan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan <span class="text-red-500">*</span></label>
                                    <select name="kddesa" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Desa/Kelurahan --</option>
                                        @foreach($desas as $d)
                                            <option value="{{ $d->kddesa }}"
                                                {{ old('kddesa', $keluarga->kddesa) == $d->kddesa ? 'selected' : '' }}>
                                                {{ $d->desa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kddesa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end gap-3 pt-6">
                        <a href="{{ route('dasar-keluarga.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>