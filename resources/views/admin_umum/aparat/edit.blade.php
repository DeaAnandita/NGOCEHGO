<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.aparat.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Edit Aparat Desa
                </h3>

                {{-- ERROR SERVER GLOBAL --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $tgl = old('tanggalpengangkatan', $data->tanggalpengangkatan);
                    if (!empty($tgl)) {
                        try {
                            $tgl = \Carbon\Carbon::parse($tgl)->format('Y-m-d');
                        } catch (\Exception $e) {
                            // biarkan
                        }
                    }
                @endphp

                <form id="formAparat" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin-umum.aparat.update', $data->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Aparat</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Jenis Aparat (Master) --}}
                            <div>
                                <label class="text-sm font-medium">Jenis Aparat</label>
                                <select name="kdaparat" id="kdaparat"
                                    class="w-full rounded-lg border-gray-300 @error('kdaparat') border-red-500 @enderror">
                                    <option value="">-- Pilih Aparat --</option>
                                    @foreach ($masterAparat as $a)
                                        <option value="{{ $a->kdaparat }}"
                                            {{ old('kdaparat', $data->kdaparat) == $a->kdaparat ? 'selected' : '' }}>
                                            {{ $a->aparat }}
                                        </option>
                                    @endforeach
                                </select>
                                <p id="err_kdaparat" class="text-red-600 text-xs mt-1">
                                    @error('kdaparat')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- ✅ Nama Aparat (Nama Orang) --}}
                            <div>
                                <label class="text-sm font-medium">Nama Aparat</label>
                                <input type="text" name="namaaparat" id="namaaparat"
                                    value="{{ old('namaaparat', $data->namaaparat) }}"
                                    class="w-full rounded-lg border-gray-300 @error('namaaparat') border-red-500 @enderror"
                                    placeholder="Contoh: Budi Santoso">
                                <p id="err_namaaparat" class="text-red-600 text-xs mt-1">
                                    @error('namaaparat')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- NIP --}}
                            <div>
                                <label class="text-sm font-medium">NIP</label>
                                <input type="text" name="nipaparat" id="nipaparat"
                                    value="{{ old('nipaparat', $data->nipaparat) }}"
                                    class="w-full rounded-lg border-gray-300 @error('nipaparat') border-red-500 @enderror">
                                <p id="err_nipaparat" class="text-red-600 text-xs mt-1">
                                    @error('nipaparat')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label class="text-sm font-medium">NIK</label>
                                <input type="text" name="nik" id="nik"
                                    value="{{ old('nik', $data->nik) }}"
                                    class="w-full rounded-lg border-gray-300 @error('nik') border-red-500 @enderror">
                                <p id="err_nik" class="text-red-600 text-xs mt-1">
                                    @error('nik')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Pangkat --}}
                            <div>
                                <label class="text-sm font-medium">Pangkat</label>
                                <input type="text" name="pangkataparat"
                                    value="{{ old('pangkataparat', $data->pangkataparat) }}"
                                    class="w-full rounded-lg border-gray-300 @error('pangkataparat') border-red-500 @enderror">
                                <p class="text-red-600 text-xs mt-1">
                                    @error('pangkataparat')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Nomor SK --}}
                            <div>
                                <label class="text-sm font-medium">Nomor SK</label>
                                <input type="text" name="nomorpengangkatan"
                                    value="{{ old('nomorpengangkatan', $data->nomorpengangkatan) }}"
                                    class="w-full rounded-lg border-gray-300 @error('nomorpengangkatan') border-red-500 @enderror">
                                <p class="text-red-600 text-xs mt-1">
                                    @error('nomorpengangkatan')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Tanggal Pengangkatan --}}
                            <div>
                                <label class="text-sm font-medium">Tanggal Pengangkatan</label>
                                <input type="date" name="tanggalpengangkatan" id="tanggalpengangkatan"
                                    value="{{ $tgl }}"
                                    class="w-full rounded-lg border-gray-300 @error('tanggalpengangkatan') border-red-500 @enderror">
                                <p class="text-red-600 text-xs mt-1">
                                    @error('tanggalpengangkatan')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-sm font-medium">Status</label>
                                <select name="statusaparatdesa" id="statusaparatdesa"
                                    class="w-full rounded-lg border-gray-300 @error('statusaparatdesa') border-red-500 @enderror">
                                    <option value="Aktif"
                                        {{ old('statusaparatdesa', $data->statusaparatdesa) == 'Aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="Nonaktif"
                                        {{ old('statusaparatdesa', $data->statusaparatdesa) == 'Nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                                <p class="text-red-600 text-xs mt-1">
                                    @error('statusaparatdesa')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Foto --}}
                            <div>
                                <label class="text-sm font-medium">Foto Pengangkatan</label>

                                @if ($data->fotopengangkatan)
                                    <p class="text-sm mb-2">
                                        <a href="{{ asset('storage/' . $data->fotopengangkatan) }}" target="_blank"
                                            class="text-blue-600 underline">
                                            Lihat Foto Lama
                                        </a>
                                    </p>
                                @endif

                                <input type="file" name="fotopengangkatan" id="fotopengangkatan"
                                    accept="image/png,image/jpg,image/jpeg"
                                    class="w-full rounded-lg border-gray-300 @error('fotopengangkatan') border-red-500 @enderror">
                                <p id="err_fotopengangkatan" class="text-red-600 text-xs mt-1">
                                    @error('fotopengangkatan')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- Keterangan --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="keteranganaparatdesa" id="keteranganaparatdesa"
                                    class="w-full rounded-lg border-gray-300 @error('keteranganaparatdesa') border-red-500 @enderror" rows="3">{{ old('keteranganaparatdesa', $data->keteranganaparatdesa) }}</textarea>
                                <p class="text-red-600 text-xs mt-1">
                                    @error('keteranganaparatdesa')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Update
                        </button>

                        <a href="{{ route('admin-umum.aparat.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('formAparat');

        const kdaparat = document.getElementById('kdaparat');
        const namaaparat = document.getElementById('namaaparat');
        const nip = document.getElementById('nipaparat');
        const nik = document.getElementById('nik');
        const file = document.getElementById('fotopengangkatan');

        function setErr(id, msg) {
            const el = document.getElementById(id);
            if (el) el.innerText = msg || "";
        }

        function validateClient() {
            let ok = true;

            // Jenis aparat wajib dipilih
            if (kdaparat) {
                const msg = kdaparat.value === "" ? "Aparat wajib dipilih" : "";
                setErr("err_kdaparat", msg);
                if (msg) ok = false;
            }

            // Nama aparat wajib + tidak boleh angka
            if (namaaparat) {
                const v = namaaparat.value.trim();
                let msg = "";
                if (v === "") msg = "Nama aparat wajib diisi";
                else if (/\d/.test(v)) msg = "Nama tidak boleh mengandung angka";
                setErr("err_namaaparat", msg);
                if (msg) ok = false;
            }

            // NIK: boleh kosong, kalau diisi harus 16 digit
            if (nik) {
                const v = nik.value.trim();
                const msg = (v === "" || /^\d{16}$/.test(v)) ? "" : "NIK harus 16 digit";
                setErr("err_nik", msg);
                if (msg) ok = false;
            }

            // NIP: boleh kosong, kalau diisi 8-18 digit
            if (nip) {
                const v = nip.value.trim();
                const msg = (v === "" || /^\d{8,18}$/.test(v)) ? "" : "NIP minimal 8 digit";
                setErr("err_nipaparat", msg);
                if (msg) ok = false;
            }

            // Foto: validasi type/size kalau ada
            if (file) {
                let msg = "";
                if (file.files.length > 0) {
                    const f = file.files[0];
                    if (!["image/jpeg", "image/png", "image/jpg"].includes(f.type)) {
                        msg = "Foto harus JPG / PNG";
                    } else if (f.size > 2 * 1024 * 1024) {
                        msg = "Ukuran maksimal 2MB";
                    }
                }
                setErr("err_fotopengangkatan", msg);
                if (msg) ok = false;
            }

            return ok;
        }

        [kdaparat, namaaparat, nip, nik, file].forEach(el => {
            if (!el) return;
            el.addEventListener("input", validateClient);
            el.addEventListener("change", validateClient);
        });

        form.addEventListener("submit", (e) => {
            if (!validateClient()) e.preventDefault();
        });

        // run on load
        validateClient();
    </script>
</x-app-layout>
