<x-app-layout>
    <div class="flex">
        {{-- Sidebar --}}
        @include('keluarga.sidebar')

     <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-md p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Edit Data Keluarga</h2>

            <form action="{{ route('dasar-keluarga.update', $keluarga->no_kk) }}" method="POST" 
                  x-data="{ 
                    isDatang: {{ Str::contains(strtolower($keluarga->mutasi->mutasimasuk ?? ''), 'datang') ? 'true' : 'false' }},
                    checkMutasi(event) { 
                        const text = event.target.options[event.target.selectedIndex].text.toLowerCase();
                        this.isDatang = text.includes('datang'); 
                    }
                  }">
                @csrf
                @method('PUT')

                {{-- Jenis Mutasi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                    <select name="kdmutasimasuk" @change="checkMutasi($event)"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Mutasi --</option>
                        @foreach($mutasis as $m)
                            <option value="{{ $m->kdmutasimasuk }}" 
                                {{ $keluarga->kdmutasimasuk == $m->kdmutasimasuk ? 'selected' : '' }}>
                                {{ $m->mutasimasuk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Mutasi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mutasi</label>
                    <input type="date" name="keluarga_tanggalmutasi"
                           value="{{ old('keluarga_tanggalmutasi', $keluarga->keluarga_tanggalmutasi) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Nomor KK --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KK</label>
                    <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                           value="{{ old('no_kk', $keluarga->no_kk) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masukkan 16 digit nomor KK">
                    @error('no_kk')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kepala Rumah Tangga --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Rumah Tangga</label>
                    <input type="text" name="keluarga_kepalakeluarga" required
                           value="{{ old('keluarga_kepalakeluarga', $keluarga->keluarga_kepalakeluarga) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nama kepala rumah tangga">
                </div>

                {{-- Dusun --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dusun/Lingkungan</label>
                        <select name="kddusun" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Silahkan Pilih --</option>
                            @foreach($dusuns as $d)
                                <option value="{{ $d->kddusun }}" 
                                    {{ $keluarga->kddusun == $d->kddusun ? 'selected' : '' }}>
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

                {{-- Alamat --}}
                <div class="mt-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="keluarga_alamatlengkap" rows="3" required
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Masukkan alamat lengkap">{{ old('keluarga_alamatlengkap', $keluarga->keluarga_alamatlengkap) }}</textarea>
                </div>

                {{-- Wilayah Datang --}}
                <div x-show="isDatang" x-transition>
                    <h3 class="text-gray-700 font-semibold mb-2">Wilayah Datang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <select name="kdprovinsi" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinsis as $d)
                                    <option value="{{ $d->kdprovinsi }}" 
                                        {{ $keluarga->kdprovinsi == $d->kdprovinsi ? 'selected' : '' }}>
                                        {{ $d->provinsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten</label>
                            <select name="kdkabupaten" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Provinsi Dahulu --</option>
                                @foreach($kabupatens as $d)
                                    <option value="{{ $d->kdkabupaten }}" 
                                        {{ $keluarga->kdkabupaten == $d->kdkabupaten ? 'selected' : '' }}>
                                        {{ $d->kabupaten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <select name="kdkecamatan"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kabupaten Dahulu --</option>
                                @foreach($kecamatans as $d)
                                    <option value="{{ $d->kdkecamatan }}" 
                                        {{ $keluarga->kdkecamatan == $d->kdkecamatan ? 'selected' : '' }}>
                                        {{ $d->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                            <select name="kddesa" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kecamatan Dahulu --</option>
                                @foreach($desas as $d)
                                    <option value="{{ $d->kddesa }}" 
                                        {{ $keluarga->kddesa == $d->kddesa ? 'selected' : '' }}>
                                        {{ $d->desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('dasar-keluarga.index') }}"
                       class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition">Batal</a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
