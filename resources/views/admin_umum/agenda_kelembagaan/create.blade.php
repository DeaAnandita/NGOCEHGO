<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.agenda.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Agenda Kelembagaan</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" enctype="multipart/form-data" action="{{ route('admin-umum.agenda.store') }}">
                    @csrf

                    {{-- DATA AGENDA --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Agenda</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Kode Agenda</label>
                                <input type="text" name="kdagendalembaga" value="{{ old('kdagendalembaga') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Agenda</label>
                                <select name="kdjenisagenda_umum" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenisagenda_umum }}"
                                            {{ old('kdjenisagenda_umum') == $j->kdjenisagenda_umum ? 'selected' : '' }}>
                                            {{ $j->jenisagenda_umum }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Agenda</label>
                                <input type="date" name="agendalembaga_tanggal"
                                    value="{{ old('agendalembaga_tanggal') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nomor Surat</label>
                                <input type="text" name="agendalembaga_nomorsurat"
                                    value="{{ old('agendalembaga_nomorsurat') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Surat</label>
                                <input type="date" name="agendalembaga_tanggalsurat"
                                    value="{{ old('agendalembaga_tanggalsurat') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Identitas Surat</label>
                                <input type="text" name="agendalembaga_identitassurat"
                                    value="{{ old('agendalembaga_identitassurat') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Isi Surat</label>
                                <textarea name="agendalembaga_isisurat" class="w-full rounded-lg border-gray-300">{{ old('agendalembaga_isisurat') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="agendalembaga_keterangan" class="w-full rounded-lg border-gray-300">{{ old('agendalembaga_keterangan') }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">File Surat</label>
                                <input type="file" name="agendalembaga_file"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                        </div>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>

                        <a href="{{ route('admin-umum.agenda.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <script>
        const kode = document.querySelector('input[name="kdagendalembaga"]');
        const tanggal = document.querySelector('input[name="agendalembaga_tanggal"]');
        const identitas = document.querySelector('input[name="agendalembaga_identitassurat"]');
        const file = document.querySelector('input[name="agendalembaga_file"]');

        function validateAgenda() {
            // reset pesan
            document.querySelectorAll('.error-msg').forEach(e => e.remove());

            let valid = true;

            // Kode Agenda
            if (kode.value.trim() === '') {
                showError(kode, 'Kode agenda wajib diisi');
                valid = false;
            }

            // Tanggal
            if (tanggal.value === '') {
                showError(tanggal, 'Tanggal agenda wajib diisi');
                valid = false;
            }

            // Identitas surat
            if (identitas.value.trim() === '') {
                showError(identitas, 'Identitas surat wajib diisi');
                valid = false;
            }

            // File validation (optional)
            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                const allowed = ['application/pdf', 'image/jpeg', 'image/png'];

                if (!allowed.includes(f.type)) {
                    showError(file, 'File harus PDF / JPG / PNG');
                    valid = false;
                } else if (size > 5) {
                    showError(file, 'Ukuran maksimal 5MB');
                    valid = false;
                }
            }

            return valid;
        }

        function showError(input, message) {
            const p = document.createElement('p');
            p.className = 'error-msg text-red-600 text-xs mt-1';
            p.innerText = message;
            input.parentElement.appendChild(p);
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateAgenda()) {
                e.preventDefault();
            }
        });
    </script>

</x-app-layout>
