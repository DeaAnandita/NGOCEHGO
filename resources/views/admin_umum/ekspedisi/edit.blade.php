<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.ekspedisi.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Buku Ekspedisi</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formEkspedisi" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin-umum.ekspedisi.update', $data->kdekspedisi) }}">
                    @csrf
                    @method('PUT')

                    {{-- DATA EKSPEDISI --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Ekspedisi</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Kode Ekspedisi</label>
                                <input type="text" value="{{ $data->kdekspedisi }}"
                                    class="w-full rounded-lg border-gray-300 bg-gray-100" readonly>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Ekspedisi</label>
                                <input type="date" name="ekspedisi_tanggal" value="{{ $data->ekspedisi_tanggal }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nomor Surat</label>
                                <input type="text" name="ekspedisi_nomorsurat"
                                    value="{{ $data->ekspedisi_nomorsurat }}" class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Surat</label>
                                <input type="date" name="ekspedisi_tanggalsurat"
                                    value="{{ $data->ekspedisi_tanggalsurat }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Identitas Surat</label>
                                <input type="text" name="ekspedisi_identitassurat"
                                    value="{{ $data->ekspedisi_identitassurat }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Isi Surat</label>
                                <textarea name="ekspedisi_isisurat" class="w-full rounded-lg border-gray-300">{{ $data->ekspedisi_isisurat }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Keterangan</label>
                                <textarea name="ekspedisi_keterangan" class="w-full rounded-lg border-gray-300">{{ $data->ekspedisi_keterangan }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">File Surat</label>
                                <input type="file" name="ekspedisi_file" id="fileInput"
                                    class="w-full rounded-lg border-gray-300">

                                @if ($data->ekspedisi_file)
                                    <p class="text-sm mt-2">
                                        File saat ini:
                                        <a href="{{ asset('storage/' . $data->ekspedisi_file) }}" target="_blank"
                                            class="text-blue-600 underline">
                                            Lihat File
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

                        <a href="{{ route('admin-umum.ekspedisi.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- JS VALIDASI --}}
    <script>
        const file = document.getElementById('fileInput');

        function validate() {
            if (file.files.length > 0) {
                const f = file.files[0];
                const size = f.size / 1024 / 1024;
                document.getElementById('fileError').innerText =
                    (!['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'].includes(f.type)) ?
                    'File harus PDF / JPG / PNG' :
                    (size > 5) ? 'Ukuran maksimal 5MB' : '';
            }
        }

        file.addEventListener('change', validate);
    </script>

</x-app-layout>
