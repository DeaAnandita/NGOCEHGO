<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.tanahdesa.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Tanah Desa</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTanah" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin-umum.tanahdesa.store') }}">
                    @csrf

                    {{-- DATA TANAH --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Tanah</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Kode Tanah</label>
                                <input type="text" name="kdtanahdesa" id="kode" value="{{ old('kdtanahdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="kodeError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Pencatatan</label>
                                <input type="date" name="tanggaltanahdesa" value="{{ old('tanggaltanahdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Pemilik</label>
                                <input type="text" name="pemiliktanahdesa" id="pemilik"
                                    value="{{ old('pemiliktanahdesa') }}" class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="pemilikError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Kode Pemilik</label>
                                <input type="text" name="kdpemilik" value="{{ old('kdpemilik') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Pemilik</label>
                                <select name="kdjenispemilik" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenisPemilik as $j)
                                        <option value="{{ $j->kdjenispemilik }}"
                                            {{ old('kdjenispemilik') == $j->kdjenispemilik ? 'selected' : '' }}>
                                            {{ $j->jenispemilik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Status Hak</label>
                                <select name="kdstatushaktanah" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($statusHak as $s)
                                        <option value="{{ $s->kdstatushaktanah }}"
                                            {{ old('kdstatushaktanah') == $s->kdstatushaktanah ? 'selected' : '' }}>
                                            {{ $s->statushaktanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Penggunaan</label>
                                <select name="kdpenggunaantanah" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($penggunaan as $p)
                                        <option value="{{ $p->kdpenggunaantanah }}"
                                            {{ old('kdpenggunaantanah') == $p->kdpenggunaantanah ? 'selected' : '' }}>
                                            {{ $p->penggunaantanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Mutasi</label>
                                <select name="kdmutasitanah" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($mutasi as $m)
                                        <option value="{{ $m->kdmutasitanah }}"
                                            {{ old('kdmutasitanah') == $m->kdmutasitanah ? 'selected' : '' }}>
                                            {{ $m->mutasitanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Mutasi</label>
                                <input type="date" name="tanggalmutasitanahdesa"
                                    value="{{ old('tanggalmutasitanahdesa') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Luas Tanah (m²)</label>
                                <input type="number" step="0.01" name="luastanahdesa"
                                    value="{{ old('luastanahdesa') }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Foto Tanah</label>
                                <input type="file" name="fototanahdesa" id="fileInput"
                                    accept="image/png,image/jpg,image/jpeg" class="w-full rounded-lg border-gray-300">
                                <p id="fileError" class="text-red-600 text-xs mt-1"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="keterangantanahdesa" class="w-full rounded-lg border-gray-300">{{ old('keterangantanahdesa') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>

                        <a href="{{ route('admin-umum.tanahdesa.index') }}"
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
        const pemilik = document.getElementById('pemilik');
        const file = document.getElementById('fileInput');

        function validate() {
            document.getElementById('kodeError').innerText =
                kode.value.trim() === '' ? 'Kode wajib diisi' : '';

            document.getElementById('pemilikError').innerText =
                pemilik.value.trim() === '' ? 'Pemilik wajib diisi' : '';

            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                document.getElementById('fileError').innerText =
                    (!['image/jpeg', 'image/png', 'image/jpg'].includes(f.type)) ? 'Foto harus JPG / PNG' :
                    (size > 2) ? 'Ukuran maksimal 2MB' : '';
            }
        }

        [kode, pemilik, file].forEach(el => el.addEventListener('input', validate));
    </script>

</x-app-layout>
