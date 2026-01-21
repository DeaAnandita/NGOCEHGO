<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.inventaris.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Buku Inventaris</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formInventaris" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin-umum.inventaris.update', $data->kdinventaris) }}">
                    @csrf
                    @method('PUT')

                    {{-- DATA INVENTARIS --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Inventaris</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- KODE --}}
                            <div>
                                <label class="text-sm font-medium">Kode Inventaris</label>
                                <input type="text" value="{{ $data->kdinventaris }}"
                                    class="w-full rounded-lg border-gray-300 bg-gray-100" readonly>
                            </div>

                            {{-- TANGGAL --}}
                            <div>
                                <label class="text-sm font-medium">Tanggal</label>
                                <input type="date" name="inventaris_tanggal" value="{{ $data->inventaris_tanggal }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- PENGGUNA --}}
                            <div>
                                <label class="text-sm font-medium">Pengguna</label>
                                <select name="kdpengguna" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($pengguna as $p)
                                        <option value="{{ $p->kdpengguna }}"
                                            {{ $data->kdpengguna == $p->kdpengguna ? 'selected' : '' }}>
                                            {{ $p->pengguna }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- VOLUME --}}
                            <div>
                                <label class="text-sm font-medium">Volume</label>
                                <input type="number" name="inventaris_volume" value="{{ $data->inventaris_volume }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- SATUAN --}}
                            <div>
                                <label class="text-sm font-medium">Satuan</label>
                                <select name="kdsatuanbarang" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($satuan as $s)
                                        <option value="{{ $s->kdsatuanbarang }}"
                                            {{ $data->kdsatuanbarang == $s->kdsatuanbarang ? 'selected' : '' }}>
                                            {{ $s->satuanbarang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ASAL MASTER --}}
                            <div>
                                <label class="text-sm font-medium">Asal Barang</label>
                                <select name="kdasalbarang" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($asal as $a)
                                        <option value="{{ $a->kdasalbarang }}"
                                            {{ $data->kdasalbarang == $a->kdasalbarang ? 'selected' : '' }}>
                                            {{ $a->asalbarang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ASAL DETAIL --}}
                            <div>
                                <label class="text-sm font-medium">Asal Perolehan (Detail)</label>
                                <input type="text" name="barangasal" value="{{ $data->barangasal }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- ANAK --}}
                            <div>
                                <label class="text-sm font-medium">Sub Kode / Anak</label>
                                <input type="text" name="anak" value="{{ $data->anak }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- HARGA --}}
                            <div>
                                <label class="text-sm font-medium">Harga Barang (Rp)</label>
                                <input type="number" name="inventaris_harga" value="{{ $data->inventaris_harga }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- IDENTITAS --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Identitas Barang</label>
                                <input type="text" name="inventaris_identitas"
                                    value="{{ $data->inventaris_identitas }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- KETERANGAN --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="inventaris_keterangan" class="w-full rounded-lg border-gray-300">{{ $data->inventaris_keterangan }}</textarea>
                            </div>

                            {{-- FOTO --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Foto Barang</label>
                                <input type="file" name="inventaris_foto" id="fileInput"
                                    class="w-full rounded-lg border-gray-300">

                                @if ($data->inventaris_foto)
                                    <p class="text-sm mt-2">
                                        Foto saat ini:
                                        <a href="{{ asset('storage/' . $data->inventaris_foto) }}" target="_blank"
                                            class="text-blue-600 underline">
                                            Lihat Foto
                                        </a>
                                    </p>
                                @endif

                                <p id="fileError" class="text-red-600 text-xs mt-1"></p>
                            </div>

                        </div>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Update
                        </button>

                        <a href="{{ route('admin-umum.inventaris.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- VALIDASI FILE --}}
    <script>
        const file = document.getElementById('fileInput');

        function validate() {
            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                document.getElementById('fileError').innerText =
                    (!['image/jpeg', 'image/png', 'image/jpg'].includes(f.type)) ? 'Foto harus JPG / PNG' :
                    (size > 2) ? 'Ukuran maksimal 2MB' : '';
            }
        }

        file.addEventListener('change', validate);
    </script>

</x-app-layout>
