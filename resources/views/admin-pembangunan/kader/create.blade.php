<x-app-layout>
    <div class="flex">
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-pembangunan.kader.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">←
                        Kembali</a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Buku Kader</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formKader" action="{{ route('admin-pembangunan.kader.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label>Kode Kader</label>
                            <input type="number" id="kode" name="kdkader" value="{{ old('kdkader') }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="kodeError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Tanggal</label>
                            <input type="date" id="tanggal" name="kader_tanggal" value="{{ old('kader_tanggal') }}"
                                class="w-full rounded-lg border-gray-300">
                            <p id="tanggalError" class="text-red-600 text-xs"></p>
                        </div>

                        <div class="sm:col-span-2">
                            <label>NIK Penduduk</label>
                            <input type="text" id="penduduk" name="kdpenduduk" maxlength="16"
                                value="{{ old('kdpenduduk') }}" class="w-full rounded-lg border-gray-300">
                            <p id="pendudukError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Pendidikan</label>
                            <select id="pendidikan" name="kdpendidikan" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($pendidikan as $p)
                                    <option value="{{ $p->kdpendidikan }}">{{ $p->pendidikan }}</option>
                                @endforeach
                            </select>
                            <p id="pendidikanError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Bidang</label>
                            <select id="bidang" name="kdbidang" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($bidang as $b)
                                    <option value="{{ $b->kdbidang }}">{{ $b->bidang }}</option>
                                @endforeach
                            </select>
                            <p id="bidangError" class="text-red-600 text-xs"></p>
                        </div>

                        <div>
                            <label>Status</label>
                            <select id="status" name="kdstatuskader" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach ($status as $s)
                                    <option value="{{ $s->kdstatuskader }}">{{ $s->statuskader }}</option>
                                @endforeach
                            </select>
                            <p id="statusError" class="text-red-600 text-xs"></p>
                        </div>

                        <div class="sm:col-span-2">
                            <label>Keterangan</label>
                            <textarea name="kader_keterangan" class="w-full rounded-lg border-gray-300">{{ old('kader_keterangan') }}</textarea>
                        </div>

                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Simpan</button>
                        <a href="{{ route('admin-pembangunan.kader.index') }}"
                            class="bg-gray-200 px-6 py-2 rounded-lg">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('formKader');
        const kode = document.getElementById('kode');
        const tanggal = document.getElementById('tanggal');
        const penduduk = document.getElementById('penduduk');
        const pendidikan = document.getElementById('pendidikan');
        const bidang = document.getElementById('bidang');
        const status = document.getElementById('status');

        function validate() {
            let valid = true;

            kodeError.innerText = kode.value > 0 ? "" : "Kode wajib diisi";
            tanggalError.innerText = tanggal.value ? "" : "Tanggal wajib diisi";

            // NIK 16 digit
            if (/^\d{16}$/.test(penduduk.value)) {
                pendudukError.innerText = "";
            } else {
                pendudukError.innerText = "NIK harus 16 digit";
                valid = false;
            }

            pendidikanError.innerText = pendidikan.value ? "" : "Pilih pendidikan";
            bidangError.innerText = bidang.value ? "" : "Pilih bidang";
            statusError.innerText = status.value ? "" : "Pilih status";

            if (!kode.value || !tanggal.value || !pendidikan.value || !bidang.value || !status.value) {
                valid = false;
            }

            return valid;
        }

        [kode, tanggal, penduduk, pendidikan, bidang, status].forEach(el => {
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
