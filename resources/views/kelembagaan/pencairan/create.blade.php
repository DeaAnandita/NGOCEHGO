<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.pencairan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Tambah Pencairan Dana
                </h3>

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kelembagaan.pencairan.store') }}" method="POST">
                    @csrf

                    {{-- PILIH KEGIATAN --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Kegiatan</label>
                        <select name="kegiatan_id" required class="w-full rounded-lg border-gray-300"
                            id="kegiatanSelect">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach ($kegiatan as $k)
                                <option value="{{ $k->id }}" data-pagu="{{ $k->pagu_anggaran }}"
                                    data-terpakai="{{ $k->pencairanDana->sum('jumlah') }}">
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- INFO ANGGARAN --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Pagu Anggaran</p>
                            <p id="pagu" class="font-bold text-blue-700">-</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sudah Dicairkan</p>
                            <p id="terpakai" class="font-bold text-orange-600">-</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sisa Anggaran</p>
                            <p id="sisa" class="font-bold text-green-700">-</p>
                        </div>
                    </div>

                    {{-- FORM --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

                        <div>
                            <label class="text-sm font-medium">Tanggal Cair</label>
                            <input type="date" name="tanggal_cair" class="w-full rounded-lg border-gray-300"
                                required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Jumlah Dicairkan</label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="w-full rounded-lg border-gray-300" required>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium">No SP2D</label>
                            <input type="text" name="no_sp2d" class="w-full rounded-lg border-gray-300">
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>

                        <a href="{{ route('kelembagaan.pencairan.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const select = document.getElementById('kegiatanSelect');
        const pagu = document.getElementById('pagu');
        const terpakai = document.getElementById('terpakai');
        const sisa = document.getElementById('sisa');
        const jumlah = document.getElementById('jumlah');

        select.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];

            const p = parseInt(opt.dataset.pagu || 0);
            const t = parseInt(opt.dataset.terpakai || 0);
            const s = p - t;

            pagu.innerText = 'Rp ' + p.toLocaleString('id-ID');
            terpakai.innerText = 'Rp ' + t.toLocaleString('id-ID');
            sisa.innerText = 'Rp ' + s.toLocaleString('id-ID');

            jumlah.max = s;
        });
    </script>

</x-app-layout>
