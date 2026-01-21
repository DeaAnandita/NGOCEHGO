<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Tanah Kas Desa</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTanahKas" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin-umum.tanahkasdesa.store') }}">
                    @csrf

                    {{-- DATA TANAH KAS --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Tanah Kas Desa</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Kode Tanah Kas</label>
                                <input type="text" name="kdtanahkasdesa" id="kode"
                                    value="{{ old('kdtanahkasdesa') }}" class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="kodeError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Pencatatan</label>
                                <input type="date" name="tanggaltanahkasdesa"
                                    value="{{ old('tanggaltanahkasdesa') }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Asal Tanah</label>
                                <input type="text" name="asaltanahkasdesa" value="{{ old('asaltanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nomor Sertifikat</label>
                                <input type="text" name="sertifikattanahkasdesa"
                                    value="{{ old('sertifikattanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Luas Tanah (m²)</label>
                                <input type="number" step="0.01" name="luastanahkasdesa"
                                    value="{{ old('luastanahkasdesa') }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Kelas Tanah</label>
                                <input type="text" name="kelastanahkasdesa" value="{{ old('kelastanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Perolehan</label>
                                <select name="kdperolehantkd" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($perolehan as $p)
                                        <option value="{{ $p->kdperolehantkd }}"
                                            {{ old('kdperolehantkd') == $p->kdperolehantkd ? 'selected' : '' }}>
                                            {{ $p->perolehantkd }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis TKD</label>
                                <select name="kdjenistkd" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenistkd }}"
                                            {{ old('kdjenistkd') == $j->kdjenistkd ? 'selected' : '' }}>
                                            {{ $j->jenistkd }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Patok</label>
                                <select name="kdpatok" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($patok as $p)
                                        <option value="{{ $p->kdpatok }}"
                                            {{ old('kdpatok') == $p->kdpatok ? 'selected' : '' }}>
                                            {{ $p->patok }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Papan Nama</label>
                                <select name="kdpapannama" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($papan as $pn)
                                        <option value="{{ $pn->kdpapannama }}"
                                            {{ old('kdpapannama') == $pn->kdpapannama ? 'selected' : '' }}>
                                            {{ $pn->papannama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Lokasi</label>
                                <input type="text" name="lokasitanahkasdesa" value="{{ old('lokasitanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Peruntukan</label>
                                <input type="text" name="peruntukantanahkasdesa"
                                    value="{{ old('peruntukantanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Mutasi</label>
                                <input type="text" name="mutasitanahkasdesa" value="{{ old('mutasitanahkasdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Foto Tanah</label>
                                <input type="file" name="fototanahkasdesa" id="fileInput"
                                    accept="image/png,image/jpg,image/jpeg" class="w-full rounded-lg border-gray-300">
                                <p id="fileError" class="text-red-600 text-xs mt-1"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="keterangantanahkasdesa" class="w-full rounded-lg border-gray-300">{{ old('keterangantanahkasdesa') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>

                        <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        const kode = document.getElementById('kode');
        const file = document.getElementById('fileInput');

        function validate() {
            document.getElementById('kodeError').innerText =
                kode.value.trim() === '' ? 'Kode wajib diisi' : '';

            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                document.getElementById('fileError').innerText =
                    (!['image/jpeg', 'image/png', 'image/jpg'].includes(f.type)) ? 'Foto harus JPG / PNG' :
                    (size > 2) ? 'Ukuran maksimal 2MB' : '';
            }
        }

        [kode, file].forEach(el => el.addEventListener('input', validate));
    </script>

</x-app-layout>
