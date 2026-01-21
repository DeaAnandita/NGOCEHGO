<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- TOMBOL KEMBALI --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.lpj.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Ajukan LPJ Kegiatan
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

                <form method="POST" enctype="multipart/form-data" action="{{ route('kelembagaan.lpj.store') }}">
                    @csrf

                    {{-- PILIH KEGIATAN --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatanSelect" class="w-full rounded-lg border-gray-300"
                            required>
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach ($kegiatan as $k)
                                <option value="{{ $k->id }}" data-anggaran="{{ $k->pagu_anggaran }}"
                                    data-realisasi="{{ $k->pencairanDana->flatMap->realisasi->sum('jumlah') }}">
                                    {{ $k->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- INFO ANGGARAN --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Total Anggaran</p>
                            <p id="total_anggaran" class="font-bold text-blue-700">-</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Total Realisasi</p>
                            <p id="total_realisasi" class="font-bold text-orange-600">-</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sisa Anggaran</p>
                            <p id="sisa" class="font-bold text-green-700">-</p>
                        </div>
                    </div>

                    {{-- UPLOAD LPJ --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Upload File LPJ (PDF)</label>
                        <input type="file" name="file_lpj" accept="application/pdf"
                            class="w-full rounded-lg border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">
                            Format yang diperbolehkan: PDF
                        </p>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Ajukan LPJ
                        </button>

                        <a href="{{ route('kelembagaan.lpj.index') }}"
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
        const anggaran = document.getElementById('total_anggaran');
        const realisasi = document.getElementById('total_realisasi');
        const sisa = document.getElementById('sisa');

        select.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const a = parseInt(opt.dataset.anggaran || 0);
            const r = parseInt(opt.dataset.realisasi || 0);
            const s = a - r;

            anggaran.innerText = 'Rp ' + a.toLocaleString('id-ID');
            realisasi.innerText = 'Rp ' + r.toLocaleString('id-ID');
            sisa.innerText = 'Rp ' + s.toLocaleString('id-ID');

            if (s < 0) {
                sisa.classList.remove('text-green-700');
                sisa.classList.add('text-red-600');
            } else {
                sisa.classList.remove('text-red-600');
                sisa.classList.add('text-green-700');
            }
        });
    </script>

</x-app-layout>
