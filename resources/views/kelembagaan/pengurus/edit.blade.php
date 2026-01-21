@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.pengurus.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Pengurus Kelembagaan</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formPengurus" action="{{ route('kelembagaan.pengurus.update', $pengurus->id_pengurus) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ================= DATA PRIBADI ================= --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Pribadi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Nomor Induk (NIK)</label>
                                <input type="text" name="nomor_induk" id="nik"
                                    value="{{ old('nomor_induk', $pengurus->nomor_induk) }}" maxlength="16"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="nikError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama"
                                    value="{{ old('nama_lengkap', $pengurus->nama_lengkap) }}"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="namaError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Pilih --</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $pengurus->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $pengurus->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">No HP</label>
                                <input type="text" name="no_hp" id="hp"
                                    value="{{ old('no_hp', $pengurus->no_hp) }}"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="hpError"></p>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Email</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $pengurus->email) }}"
                                    class="w-full rounded-lg border-gray-300">
                                <p class="text-red-600 text-xs mt-1" id="emailError"></p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Alamat</label>
                                <textarea name="alamat" class="w-full rounded-lg border-gray-300">{{ old('alamat', $pengurus->alamat) }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- ================= DATA KELEMBAGAAN ================= --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Kelembagaan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <select name="kdjabatan" class="w-full rounded-lg border-gray-300">
                                @foreach ($jabatan as $j)
                                    <option value="{{ $j->kdjabatan }}"
                                        {{ old('kdjabatan', $pengurus->kdjabatan) == $j->kdjabatan ? 'selected' : '' }}>
                                        {{ $j->jabatan }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kdunit" class="w-full rounded-lg border-gray-300">
                                @foreach ($unit as $u)
                                    <option value="{{ $u->kdunit }}"
                                        {{ old('kdunit', $pengurus->kdunit) == $u->kdunit ? 'selected' : '' }}>
                                        {{ $u->nama_unit }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kdperiode" class="w-full rounded-lg border-gray-300">
                                @foreach ($periode as $p)
                                    <option value="{{ $p->kdperiode }}"
                                        {{ old('kdperiode', $pengurus->kdperiode) == $p->kdperiode ? 'selected' : '' }}>
                                        {{ $p->tahun_awal }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kdperiode_akhir" class="w-full rounded-lg border-gray-300">
                                @foreach ($periodeAkhir as $p)
                                    <option value="{{ $p->kdperiode }}"
                                        {{ old('kdperiode_akhir', $pengurus->kdperiode_akhir) == $p->kdperiode ? 'selected' : '' }}>
                                        {{ $p->akhir }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kdstatus" class="w-full rounded-lg border-gray-300">
                                @foreach ($status as $s)
                                    <option value="{{ $s->kdstatus }}"
                                        {{ old('kdstatus', $pengurus->kdstatus) == $s->kdstatus ? 'selected' : '' }}>
                                        {{ $s->status_pengurus }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kdjenissk" class="w-full rounded-lg border-gray-300">
                                @foreach ($jenisSk as $j)
                                    <option value="{{ $j->kdjenissk }}"
                                        {{ old('kdjenissk', $pengurus->kdjenissk) == $j->kdjenissk ? 'selected' : '' }}>
                                        {{ $j->jenis_sk }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    {{-- ================= SK ================= --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="no_sk" value="{{ old('no_sk', $pengurus->no_sk) }}"
                            class="w-full rounded-lg border-gray-300">
                        <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $pengurus->tanggal_sk) }}"
                            class="w-full rounded-lg border-gray-300">
                    </div>

                    {{-- ================= KETERANGAN ================= --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full rounded-lg border-gray-300">{{ old('keterangan', $pengurus->keterangan) }}</textarea>
                    </div>

                    {{-- ================= FILE ================= --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div>
                            <label>Foto</label>
                            <input type="file" id="fotoInput" name="foto" accept="image/png,image/jpg,image/jpeg">
                            @if ($pengurus->foto)
                                <img src="{{ asset('storage/' . $pengurus->foto) }}" id="fotoPreview"
                                    class="mt-2 w-32">
                            @else
                                <img id="fotoPreview" class="mt-2 w-32 hidden">
                            @endif
                        </div>

                        <div>
                            <label>Tanda Tangan</label>
                            <input type="file" id="ttdInput" name="tanda_tangan"
                                accept="image/png,image/jpg,image/jpeg,application/pdf">

                            @if ($pengurus->tanda_tangan)
                                @if (Str::endsWith($pengurus->tanda_tangan, ['.jpg', '.jpeg', '.png']))
                                    <img src="{{ asset('storage/' . $pengurus->tanda_tangan) }}" id="ttdPreview"
                                        class="mt-2 w-32">
                                @else
                                    <a href="{{ asset('storage/' . $pengurus->tanda_tangan) }}" target="_blank"
                                        class="text-blue-600 underline">Lihat PDF</a>
                                    <img id="ttdPreview" class="hidden">
                                @endif
                            @else
                                <img id="ttdPreview" class="hidden">
                            @endif
                        </div>

                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Simpan</button>
                        <a href="{{ route('kelembagaan.pengurus.index') }}"
                            class="bg-gray-200 px-6 py-2 rounded-lg">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const nik = document.getElementById('nik');
        const nama = document.getElementById('nama');
        const hp = document.getElementById('hp');
        const email = document.getElementById('email');
        const form = document.getElementById('formPengurus');

        function validateNama() {
            const val = nama.value.trim();

            // hanya huruf, spasi, dan titik
            if (!/^[A-Za-z.\s]+$/.test(val)) {
                return 'Nama hanya boleh huruf, spasi, dan titik (.)';
            }

            // tidak boleh ada angka
            if (/\d/.test(val)) {
                return 'Nama tidak boleh mengandung angka';
            }

            // minimal 3 huruf
            if (val.replace(/\s|\./g, '').length < 3) {
                return 'Nama terlalu pendek';
            }

            return '';
        }

        function validate() {
            nikError.innerText = /^\d{16}$/.test(nik.value) ? '' : 'NIK harus 16 digit';
            hpError.innerText = /^\d{10,15}$/.test(hp.value) ? '' : 'No HP minimal 10 digit';
            emailError.innerText = email.value.includes('@') ? '' : 'Email tidak valid';

            const namaMsg = validateNama();
            namaError.innerText = namaMsg;

            return !namaMsg && !nikError.innerText && !hpError.innerText && !emailError.innerText;
        }

        // realtime
        [nik, nama, hp, email].forEach(i => i.addEventListener('input', validate));

        // cegah submit jika invalid
        form.addEventListener('submit', function(e) {
            if (!validate()) {
                e.preventDefault();
                alert('Periksa kembali data yang belum valid');
            }
        });
    </script>


</x-app-layout>
