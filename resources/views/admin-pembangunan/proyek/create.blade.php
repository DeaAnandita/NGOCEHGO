<x-app-layout>
    <div class="flex">
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-pembangunan.proyek.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Buku Proyek</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formProyek" action="{{ route('admin-pembangunan.proyek.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label>Kode Proyek</label>
                            <input type="text" id="kode" name="kdproyek" value="{{ old('kdproyek') }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="kodeError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Tanggal</label>
                            <input type="date" id="tanggal" name="proyek_tanggal"
                                value="{{ old('proyek_tanggal') }}" class="w-full rounded-lg border-gray-300">
                            <p id="tanggalError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Kegiatan</label>
                            <select id="kegiatan" name="kdkegiatan" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($kegiatan as $k)
                                    <option value="{{ $k->kdkegiatan }}">{{ $k->kegiatan }}</option>
                                @endforeach
                            </select>
                            <p id="kegiatanError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Pelaksana</label>
                            <select id="pelaksana" name="kdpelaksana" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($pelaksana as $p)
                                    <option value="{{ $p->kdpelaksana }}">{{ $p->pelaksana }}</option>
                                @endforeach
                            </select>
                            <p id="pelaksanaError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Lokasi</label>
                            <select id="lokasi" name="kdlokasi" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->kdlokasi }}">{{ $l->lokasi }}</option>
                                @endforeach
                            </select>
                            <p id="lokasiError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Sumber Dana</label>
                            <select id="sumber" name="kdsumber" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($sumber as $s)
                                    <option value="{{ $s->kdsumber }}">{{ $s->sumber_dana }}</option>
                                @endforeach
                            </select>
                            <p id="sumberError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Nominal</label>
                            <input type="number" id="nominal" name="proyek_nominal"
                                value="{{ old('proyek_nominal') }}" class="w-full rounded-lg border-gray-300">
                            <p id="nominalError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Manfaat</label>
                            <input type="text" id="manfaat" name="proyek_manfaat"
                                value="{{ old('proyek_manfaat') }}" class="w-full rounded-lg border-gray-300">
                        </div>

                        <div class="sm:col-span-2">
                            <label>Keterangan</label>
                            <textarea name="proyek_keterangan" class="w-full rounded-lg border-gray-300">{{ old('proyek_keterangan') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Simpan</button>
                        <a href="{{ route('admin-pembangunan.proyek.index') }}"
                            class="bg-gray-200 px-6 py-2 rounded-lg">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('formProyek');
        const kode = document.getElementById('kode');
        const tanggal = document.getElementById('tanggal');
        const kegiatan = document.getElementById('kegiatan');
        const pelaksana = document.getElementById('pelaksana');
        const lokasi = document.getElementById('lokasi');
        const sumber = document.getElementById('sumber');
        const nominal = document.getElementById('nominal');

        function validate() {
            let valid = true;

            kodeError.innerText = kode.value.length > 0 ? "" : "Kode wajib diisi";
            tanggalError.innerText = tanggal.value ? "" : "Tanggal wajib diisi";
            kegiatanError.innerText = kegiatan.value ? "" : "Pilih kegiatan";
            pelaksanaError.innerText = pelaksana.value ? "" : "Pilih pelaksana";
            lokasiError.innerText = lokasi.value ? "" : "Pilih lokasi";
            sumberError.innerText = sumber.value ? "" : "Pilih sumber dana";
            nominalError.innerText = nominal.value > 0 ? "" : "Nominal harus lebih dari 0";

            if (!kode.value || !tanggal.value || !kegiatan.value || !pelaksana.value || !lokasi.value || !sumber.value ||
                nominal.value <= 0) {
                valid = false;
            }
            return valid;
        }

        [kode, tanggal, kegiatan, pelaksana, lokasi, sumber, nominal].forEach(el => {
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
