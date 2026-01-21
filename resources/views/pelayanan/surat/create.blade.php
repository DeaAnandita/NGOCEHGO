<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">

        <h2 class="text-2xl font-bold text-gray-700 mb-6">
            Buat Pengajuan Surat
        </h2>

        <div class="bg-white shadow-md rounded-xl p-6">

            {{-- ALERT ERROR --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelayanan.surat.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NIK --}}
                    <div>
                        <label class="block font-semibold">NIK</label>
                        <input id="nik" name="nik" value="{{ old('nik') }}"
                            class="w-full rounded border p-2 @error('nik') border-red-500 @enderror"
                            placeholder="16 digit NIK" autocomplete="off">
                        <small id="nikStatus" class="text-sm text-gray-500"></small>
                        @error('nik')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block font-semibold">Nama</label>
                        <input id="nama" name="nama" value="{{ old('nama') }}"
                            class="w-full rounded border p-2 @error('nama') border-red-500 @enderror"
                            placeholder="Nama lengkap">
                        @error('nama')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block font-semibold">Tempat Lahir</label>
                        <input id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            class="w-full rounded border p-2 @error('tempat_lahir') border-red-500 @enderror"
                            placeholder="Tempat lahir">
                        @error('tempat_lahir')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block font-semibold">Tanggal Lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full rounded border p-2 @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block font-semibold">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin"
                            class="w-full rounded border p-2 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">- Pilih -</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror

                        {{-- hidden backup (agar value tetap terkirim walaupun select disabled) --}}
                        <input type="hidden" id="jenis_kelamin_hidden" name="jenis_kelamin_hidden" value="">
                    </div>

                    {{-- Kewarganegaraan --}}
                    <div>
                        <label class="block font-semibold">Kewarganegaraan</label>
                        <input id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan') }}"
                            class="w-full rounded border p-2 @error('kewarganegaraan') border-red-500 @enderror"
                            placeholder="INDONESIA">
                        @error('kewarganegaraan')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Agama --}}
                    <div>
                        <label class="block font-semibold">Agama</label>
                        <input id="agama" name="agama" value="{{ old('agama') }}"
                            class="w-full rounded border p-2 @error('agama') border-red-500 @enderror"
                            placeholder="Agama">
                        @error('agama')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Pekerjaan (MANUAL) --}}
                    <div>
                        <label class="block font-semibold">Pekerjaan (Manual)</label>
                        <input id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}"
                            class="w-full rounded border p-2 @error('pekerjaan') border-red-500 @enderror"
                            placeholder="Isi manual pekerjaan">
                        @error('pekerjaan')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                {{-- Alamat (MANUAL) --}}
                <div class="mt-4">
                    <label class="block font-semibold">Alamat (Manual)</label>
                    <textarea id="alamat" name="alamat" class="w-full rounded border p-2 @error('alamat') border-red-500 @enderror"
                        placeholder="Isi manual alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Keperluan (MANUAL) --}}
                <div class="mt-4">
                    <label class="block font-semibold">Keperluan (Manual)</label>
                    <textarea id="keperluan" name="keperluan" class="w-full rounded border p-2 @error('keperluan') border-red-500 @enderror"
                        placeholder="Keperluan surat">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Keterangan Lain (MANUAL/OPSIONAL) --}}
                <div class="mt-4">
                    <label class="block font-semibold">Keterangan Lain (Opsional)</label>
                    <textarea id="keterangan_lain" name="keterangan_lain" class="w-full rounded border p-2" placeholder="Opsional">{{ old('keterangan_lain') }}</textarea>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('pelayanan.surat.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Kembali
                    </a>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        (function() {
            const nikEl = document.getElementById('nik');
            const statusEl = document.getElementById('nikStatus');

            const fields = {
                nama: document.getElementById('nama'),
                tempat_lahir: document.getElementById('tempat_lahir'),
                tanggal_lahir: document.getElementById('tanggal_lahir'),
                jenis_kelamin: document.getElementById('jenis_kelamin'),
                jenis_kelamin_hidden: document.getElementById('jenis_kelamin_hidden'),
                kewarganegaraan: document.getElementById('kewarganegaraan'),
                agama: document.getElementById('agama'),
                pekerjaan: document.getElementById('pekerjaan'), // manual
                alamat: document.getElementById('alamat'), // manual
            };

            function setReadonly(found) {
                // Yang DIKUNCI kalau penduduk ditemukan:
                // nama, tempat_lahir, tanggal_lahir, kewarganegaraan, agama
                ['nama', 'tempat_lahir', 'tanggal_lahir', 'kewarganegaraan', 'agama'].forEach(k => {
                    if (!fields[k]) return;
                    fields[k].readOnly = found;
                    fields[k].classList.toggle('bg-gray-100', found);
                });

                // Yang TETAP MANUAL (tidak dikunci):
                // pekerjaan, alamat, keperluan, keterangan_lain
                // (jadi tidak diapa-apakan)
            }

            function fill(data) {
                if (data.nama && fields.nama) fields.nama.value = data.nama;
                if (data.tempat_lahir && fields.tempat_lahir) fields.tempat_lahir.value = data.tempat_lahir;
                if (data.tanggal_lahir && fields.tanggal_lahir) fields.tanggal_lahir.value = data.tanggal_lahir;
                if (data.jenis_kelamin && fields.jenis_kelamin) {
                    fields.jenis_kelamin.value = data.jenis_kelamin;
                    fields.jenis_kelamin_hidden.value = data.jenis_kelamin;
                }
                if (data.kewarganegaraan && fields.kewarganegaraan) fields.kewarganegaraan.value = data.kewarganegaraan;
                if (data.agama && fields.agama) fields.agama.value = data.agama;

                // pekerjaan & alamat sengaja tidak di-fill karena MANUAL
                // keperluan & keterangan_lain juga MANUAL
            }

            function unlockManual() {
                setReadonly(false);
            }

            async function checkNik(nik) {
                statusEl.textContent = 'Mengecek NIK...';

                try {
                    const url = `{{ route('pelayanan.surat.cek-nik') }}?nik=${encodeURIComponent(nik)}`;
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();

                    if (!res.ok) {
                        statusEl.textContent = 'NIK tidak valid.';
                        unlockManual();
                        return;
                    }

                    if (json.found) {
                        statusEl.textContent =
                            'Data penduduk ditemukan. Identitas terisi otomatis. Pekerjaan/Alamat tetap manual.';
                        fill(json.data);
                        setReadonly(true);
                    } else {
                        statusEl.textContent = 'Data penduduk tidak ditemukan. Silakan isi semua data manual.';
                        unlockManual();
                    }
                } catch (e) {
                    statusEl.textContent = 'Gagal cek NIK. Coba lagi.';
                    unlockManual();
                }
            }

            let t = null;
            nikEl.addEventListener('input', function() {
                const nik = (nikEl.value || '').replace(/\D/g, '');
                nikEl.value = nik;

                if (t) clearTimeout(t);
                t = setTimeout(() => {
                    if (nik.length === 16) checkNik(nik);
                    else {
                        statusEl.textContent = '';
                        unlockManual();
                    }
                }, 350);
            });

            window.addEventListener('load', () => {
                const nik = (nikEl.value || '').replace(/\D/g, '');
                if (nik.length === 16) checkNik(nik);
            });
        })();
    </script>
</x-app-layout>
