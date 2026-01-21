<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.peraturan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Tambah Buku Peraturan
                </h3>

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

                <form id="formPeraturan" method="POST" action="{{ route('admin-umum.peraturan.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- DATA PERATURAN --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Peraturan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Kode Peraturan</label>
                                <input type="text" name="kdperaturan" id="kode" value="{{ old('kdperaturan') }}"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="kodeError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Peraturan</label>
                                <select name="kdjenisperaturandesa" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenisperaturandesa }}"
                                            {{ old('kdjenisperaturandesa') == $j->kdjenisperaturandesa ? 'selected' : '' }}>
                                            {{ $j->jenisperaturandesa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nomor Peraturan</label>
                                <input type="text" name="nomorperaturan" id="nomor"
                                    value="{{ old('nomorperaturan') }}" class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="nomorError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Judul Peraturan</label>
                                <input type="text" name="judulpengaturan" id="judul"
                                    value="{{ old('judulpengaturan') }}" class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="judulError"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Uraian</label>
                                <textarea name="uraianperaturan" class="w-full rounded-lg border-gray-300">{{ old('uraianperaturan') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Kesepakatan</label>
                                <textarea name="kesepakatanperaturan" class="w-full rounded-lg border-gray-300">{{ old('kesepakatanperaturan') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="keteranganperaturan" class="w-full rounded-lg border-gray-300">{{ old('keteranganperaturan') }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- FILE --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">File Peraturan (PDF)</label>
                        <input type="file" name="filepengaturan" id="fileInput" accept="application/pdf"
                            class="w-full rounded-lg border-gray-300">
                        <p id="fileError" class="text-red-600 text-xs mt-1"></p>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>
                        <a href="{{ route('admin-umum.peraturan.index') }}"
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
        const nomor = document.getElementById('nomor');
        const judul = document.getElementById('judul');
        const file = document.getElementById('fileInput');

        function validate() {
            document.getElementById('kodeError').innerText =
                kode.value.trim() === '' ? 'Kode wajib diisi' : '';

            document.getElementById('nomorError').innerText =
                nomor.value.trim() === '' ? 'Nomor wajib diisi' : '';

            document.getElementById('judulError').innerText =
                judul.value.trim() === '' ? 'Judul wajib diisi' : '';

            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                document.getElementById('fileError').innerText =
                    (f.type !== 'application/pdf') ? 'File harus PDF' :
                    (size > 2) ? 'Ukuran maksimal 2MB' : '';
            }
        }

        [kode, nomor, judul, file].forEach(i => i.addEventListener('input', validate));
    </script>

</x-app-layout>
