<x-app-layout>
    <div class="flex">
        {{-- Sidebar --}}
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-md p-6">

                <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Data Keluarga</h2>

                {{-- Form Tambah Keluarga --}}
                <form 
                    action="{{ route('dasar-keluarga.store') }}" 
                    method="POST"
                    x-data="{
                        isDatang: false,
                        checkMutasi(event) { 
                            const text = event.target.options[event.target.selectedIndex].text.toLowerCase();
                            this.isDatang = text.includes('datang');
                        }
                    }"
                    x-init="isDatang = false"
                    @submit="$el.submit()"
                    class="space-y-4"
                >
                    @csrf

                    {{-- Jenis Mutasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                        <select name="kdmutasimasuk" @change="checkMutasi($event)"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Mutasi --</option>
                            @foreach($mutasis as $m)
                                <option value="{{ $m->kdmutasimasuk }}">{{ $m->mutasimasuk }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Mutasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mutasi</label>
                        <input type="date" name="keluarga_tanggalmutasi"
                            value="{{ old('keluarga_tanggalmutasi', date('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Nomor KK --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KK</label>
                        <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                            value="{{ old('no_kk') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan 16 digit nomor KK">
                        @error('no_kk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kepala Rumah Tangga --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Rumah Tangga</label>
                        <input type="text" name="keluarga_kepalakeluarga" required
                            value="{{ old('keluarga_kepalakeluarga') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama kepala rumah tangga">
                    </div>

                    {{-- Dusun, RW, RT --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dusun/Lingkungan</label>
                            <select name="kddusun" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Silahkan Pilih --</option>
                                @foreach($dusuns as $d)
                                    <option value="{{ $d->kddusun }}">{{ $d->dusun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW (3 digit)</label>
                            <input type="text" name="keluarga_rw" maxlength="3" required
                                   value="{{ old('keluarga_rw') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT (3 digit)</label>
                            <input type="text" name="keluarga_rt" maxlength="3" required
                                   value="{{ old('keluarga_rt') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 002">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="keluarga_alamatlengkap" rows="3" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Masukkan alamat lengkap">{{ old('keluarga_alamatlengkap') }}</textarea>
                    </div>

                    {{-- Wilayah Asal (Datang Dari) - CREATE & EDIT Compatible --}}
<div x-show="isDatang" x-transition>
    <h3 class="text-gray-700 font-semibold mb-4 mt-6">Wilayah Asal (Datang Dari)</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
         x-data="wilayahDatang()"
         x-init="init()">

        {{-- Ambil old() dulu, baru fallback ke data model (hanya ada saat edit) --}}
        @php
            $oldProv = old('kdprovinsi', $mutasi->kdprovinsi ?? '');
            $oldKab  = old('kdkabupaten', $mutasi->kdkabupaten ?? '');
            $oldKec  = old('kdkecamatan', $mutasi->kdkecamatan ?? '');
            $oldDesa = old('kddesa', $mutasi->kddesa ?? '');
        @endphp

        async loadKabupaten() {
    if (!this.provinsi) {
        this.kabupatens = []; this.kabupaten = ''; this.kecamatans = []; this.kecamatan = ''; this.desas = []; this.desa = '';
        return;
    }

    this.loadingKab = true;
    try {
        const res = await fetch(`/get-kabupaten/${this.provinsi}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        this.kabupatens = await res.json();

    } catch (e) {
        console.error(e);
        this.kabupatens = [];
        alert('Gagal memuat kabupaten. Cek console.');
    } finally {
        this.loadingKab = false;
    }
},

async loadKecamatan() {
    if (!this.kabupaten) return;
    this.loadingKec = true;
    try {
        const res = await fetch(`/get-kecamatan/${this.kabupaten}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        this.kecamatans = await res.json();
    } catch (e) {
        console.error(e);
        this.kecamatans = [];
    } finally {
        this.loadingKec = false;
    }
},

async loadDesa() {
    if (!this.kecamatan) return;
    this.loadingDesa = true;
    try {
        const res = await fetch(`/get-desa/${this.kecamatan}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        this.desas = await res.json();
    } catch (e) {
        console.error(e);
        this.desas = [];
    } finally {
        this.loadingDesa = false;
    }
},

        <!-- PROVINSI -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <select x-model="provinsi" @change="loadKabupaten()"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinsis as $p)
                    <option value="{{ $p->kdprovinsi }}">{{ $p->provinsi }}</option>
                @endforeach
            </select>
            <input type="hidden" name="kdprovinsi" :value="provinsi">
            @error('kdprovinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- KABUPATEN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <select x-model="kabupaten" @change="loadKecamatan()"
                    :disabled="!provinsi"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Kabupaten/Kota --</option>
                <template x-if="loadingKab">
                    <option disabled>Loading kabupaten...</option>
                </template>
                <template x-for="kab in kabupatens" :key="kab.kdkabupaten">
                    <option :value="kab.kdkabupaten" x-text="kab.kabupaten"></option>
                </template>
            </select>
            <input type="hidden" name="kdkabupaten" :value="kabupaten">
            @error('kdkabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- KECAMATAN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
            <select x-model="kecamatan" @change="loadDesa()"
                    :disabled="!kabupaten"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Kecamatan --</option>
                <template x-if="loadingKec">
                    <option disabled>Loading kecamatan...</option>
                </template>
                <template x-for="kec in kecamatans" :key="kec.kdkecamatan">
                    <option :value="kec.kdkecamatan" x-text="kec.kecamatan"></option>
                </template>
            </select>
            <input type="hidden" name="kdkecamatan" :value="kecamatan">
            @error('kdkecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- DESA -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan <span class="text-red-500">*</span></label>
            <select x-model="desa"
                    :disabled="!kecamatan"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Desa/Kelurahan --</option>
                <template x-if="loadingDesa">
                    <option disabled>Loading desa...</option>
                </template>
                <template x-for="d in desas" :key="d.kddesa">
                    <option :value="d.kddesa" x-text="d.desa"></option>
                </template>
            </select>
            <input type="hidden" name="kddesa" :value="desa">
            @error('kddesa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end gap-3 pt-6">
                        <a href="{{ route('dasar-keluarga.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
