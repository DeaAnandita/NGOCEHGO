    <x-app-layout>
        <div class="flex">
            @include('keluarga.sidebar')

            <div class="flex-1 py-6 px-6">
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah sData Keluarga</h2>

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
                        class="space-y-4"
                    >
                        @csrf

                        <!-- Jenis Mutasi -->
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

                        <!-- Tanggal Mutasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mutasi</label>
                            <input type="date" name="keluarga_tanggalmutasi"
                                value="{{ old('keluarga_tanggalmutasi', date('Y-m-d')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Nomor KK -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KK</label>
                            <input type="text" name="no_kk" maxlength="16" pattern="\d{16}" required
                                value="{{ old('no_kk') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan 16 digit nomor KK">
                            @error('no_kk') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kepala Rumah Tangga -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Rumah Tangga</label>
                            <input type="text" name="keluarga_kepalakeluarga" required
                                value="{{ old('keluarga_kepalakeluarga') }}"
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

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="keluarga_alamatlengkap" rows="3" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan alamat lengkap">{{ old('keluarga_alamatlengkap') }}</textarea>
                        </div>

                        <!-- Wilayah Asal (Datang Dari) -->
                        <div x-show="isDatang" x-transition>
                            <h3 class="text-gray-700 font-semibold mb-4 mt-6">Wilayah Asal (Datang Dari)</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                x-data="wilayahDatang()"
                                x-init="init()">

                                @php
                                    $oldProv = old('kdprovinsi');
                                    $oldKab  = old('kdkabupaten');
                                    $oldKec  = old('kdkecamatan');
                                    $oldDesa = old('kddesa');
                                @endphp

                                <script>
                                function wilayahDatang() {
                                    return {
                                        provinsi: '{{ $oldProv }}',
                                        kabupaten: '{{ $oldKab }}',
                                        kecamatan: '{{ $oldKec }}',
                                        desa: '{{ $oldDesa }}',
                                        kabupatens: {},
                                        kecamatans: {},
                                        desas: {},
                                        loadingKab: false,
                                        loadingKec: false,
                                        loadingDesa: false,

                                        async loadKabupaten() {
                                            if (!this.provinsi) {
                                                this.kabupatens = {}; this.kabupaten = ''; this.kecamatans = {}; this.kecamatan = ''; this.desas = {}; this.desa = '';
                                                return;
                                            }
                                            this.loadingKab = true;
                                            try {
                                                const res = await fetch(`/admin/get-kabupaten/${this.provinsi}`, {
                                                    headers: {
                                                        'Accept': 'application/json',
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                                    }
                                                });
                                                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                                                const data = await res.json();
                                                this.kabupatens = data;

                                                // *** FIX UTAMA: Paksa render ulang ***
                                                this.$nextTick(() => {
                                                    console.log('Kabupaten loaded:', data); // untuk debug
                                                });

                                                // Reset lower levels
                                                this.kabupaten = ''; this.kecamatans = {}; this.kecamatan = ''; this.desas = {}; this.desa = '';
                                            } catch (e) {
                                                console.error('Gagal memuat kabupaten:', e);
                                                this.kabupatens = {};
                                                alert('Gagal memuat kabupaten.');
                                            } finally {
                                                this.loadingKab = false;
                                            }
                                        },

                                        async loadKecamatan() {
                                            if (!this.kabupaten) return;
                                            this.loadingKec = true;
                                            try {
                                                const res = await fetch(`/admin/get-kecamatan/${this.kabupaten}`, {
                                                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                                                });
                                                const data = await res.json();
                                                this.kecamatans = data;

                                                this.$nextTick(() => {
                                                    console.log('Kecamatan loaded:', data);
                                                });

                                                this.kecamatan = ''; this.desas = {}; this.desa = '';
                                            } catch (e) {
                                                console.error(e);
                                                this.kecamatans = {};
                                            } finally {
                                                this.loadingKec = false;
                                            }
                                        },

                                        async loadDesa() {
                                            if (!this.kecamatan) return;
                                            this.loadingDesa = true;
                                            try {
                                                const res = await fetch(`/admin/get-desa/${this.kecamatan}`, {
                                                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                                                });
                                                const data = await res.json();
                                                this.desas = data;

                                                this.$nextTick(() => {
                                                    console.log('Desa loaded:', data);
                                                });
                                            } catch (e) {
                                                console.error(e);
                                                this.desas = {};
                                            } finally {
                                                this.loadingDesa = false;
                                            }
                                        },

                                        init() {
                                            if (this.provinsi) {
                                                this.loadKabupaten().then(() => {
                                                    if (this.kabupaten) this.loadKecamatan().then(() => {
                                                        if (this.kecamatan) this.loadDesa();
                                                    });
                                                });
                                            }
                                        }
                                    }
                                }
                            </script>

                                <!-- PROVINSI -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                    <select x-model="provinsi" @change="loadKabupaten()"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsis as $p)
                                            <option value="{{ $p->kdprovinsi }}"
                                                    x-bind:selected="provinsi === '{{ $p->kdprovinsi }}'">
                                                {{ $p->provinsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="kdprovinsi" x-model="provinsi">
                                    @error('kdprovinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- KABUPATEN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                                    <select x-model="kabupaten" @change="loadKecamatan()"
                                            :disabled="!provinsi || loadingKab"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                        <template x-if="loadingKab"><option disabled>Memuat...</option></template>
                                        <template x-for="kab in kabupatens" :key="kab.kdkabupaten">
                                            <option :value="kab.kdkabupaten" x-text="kab.kabupaten"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="kdkabupaten" x-model="kabupaten">
                                </div>

                                <!-- KECAMATAN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                    <select x-model="kecamatan" @change="loadDesa()"
                                            :disabled="!kabupaten || loadingKec"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <template x-if="loadingKec"><option disabled>Memuat...</option></template>
                                        <template x-for="kec in kecamatans" :key="kec.kdkecamatan">
                                            <option :value="kec.kdkecamatan" x-text="kec.kecamatan"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="kdkecamatan" x-model="kecamatan">
                                </div>

                                <!-- DESA -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan <span class="text-red-500">*</span></label>
                                    <select x-model="desa"
                                            :disabled="!kecamatan || loadingDesa"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Desa/Kelurahan --</option>
                                        <template x-if="loadingDesa"><option disabled>Memuat...</option></template>
                                        <template x-for="d in desas" :key="d.kddesa">
                                            <option :value="d.kddesa" x-text="d.desa"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="kddesa" x-model="desa">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
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