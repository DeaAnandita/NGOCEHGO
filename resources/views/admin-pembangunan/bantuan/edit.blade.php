<x-app-layout>
    <div class="flex">
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-pembangunan.bantuan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Buku Bantuan</h3>

                {{-- ERROR SERVER --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formBantuan" action="{{ route('admin-pembangunan.bantuan.update', $item->reg) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="sm:col-span-2">
                            <label>Nama Bantuan</label>
                            <input type="text" id="nama" name="bantuan_nama"
                                value="{{ old('bantuan_nama', $item->bantuan_nama) }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="namaError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Sasaran</label>
                            <select id="sasaran" name="kdsasaran" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($sasaran as $s)
                                    <option value="{{ $s->kdsasaran }}"
                                        {{ old('kdsasaran', $item->kdsasaran) == $s->kdsasaran ? 'selected' : '' }}>
                                        {{ $s->sasaran }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="sasaranError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Jenis Bantuan</label>
                            <select id="jenis" name="kdbantuan" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($bantuan as $b)
                                    <option value="{{ $b->kdbantuan }}"
                                        {{ old('kdbantuan', $item->kdbantuan) == $b->kdbantuan ? 'selected' : '' }}>
                                        {{ $b->bantuan }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="jenisError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Sumber Dana</label>
                            <select id="sumber" name="kdsumber" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($sumber as $s)
                                    <option value="{{ $s->kdsumber }}"
                                        {{ old('kdsumber', $item->kdsumber) == $s->kdsumber ? 'selected' : '' }}>
                                        {{ $s->sumber_dana }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="sumberError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Tanggal Mulai</label>
                            <input type="date" id="awal" name="bantuan_awal"
                                value="{{ old('bantuan_awal', $item->bantuan_awal) }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="awalError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Tanggal Akhir</label>
                            <input type="date" id="akhir" name="bantuan_akhir"
                                value="{{ old('bantuan_akhir', $item->bantuan_akhir) }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="akhirError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Jumlah</label>
                            <input type="number" id="jumlah" name="bantuan_jumlah"
                                value="{{ old('bantuan_jumlah', $item->bantuan_jumlah) }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="jumlahError" class="text-red-600 text-xs"></p>
                        </div>

                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Update</button>
                        <a href="{{ route('admin-pembangunan.bantuan.index') }}"
                            class="bg-gray-200 px-6 py-2 rounded-lg">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('formBantuan');
        const nama = document.getElementById('nama');
        const sasaran = document.getElementById('sasaran');
        const jenis = document.getElementById('jenis');
        const sumber = document.getElementById('sumber');
        const awal = document.getElementById('awal');
        const akhir = document.getElementById('akhir');
        const jumlah = document.getElementById('jumlah');

        function validate() {
            let valid = true;

            namaError.innerText = nama.value.length < 3 ? "Nama minimal 3 karakter" : "";
            sasaranError.innerText = sasaran.value ? "" : "Pilih sasaran";
            jenisError.innerText = jenis.value ? "" : "Pilih jenis bantuan";
            sumberError.innerText = sumber.value ? "" : "Pilih sumber dana";
            awalError.innerText = awal.value ? "" : "Tanggal awal wajib";
            jumlahError.innerText = jumlah.value > 0 ? "" : "Jumlah harus lebih dari 0";

            if (akhir.value && akhir.value < awal.value) {
                akhirError.innerText = "Tanggal akhir tidak boleh sebelum tanggal awal";
                valid = false;
            } else {
                akhirError.innerText = "";
            }

            return valid;
        }

        [nama, sasaran, jenis, sumber, awal, akhir, jumlah].forEach(el => {
            el.addEventListener('input', validate);
            el.addEventListener('change', validate);
        });

        form.addEventListener('submit', function(e) {
            if (!validate()) {
                e.preventDefault();
            }
        });
    </script>

</x-app-layout>
