<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.pencairan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Edit Pencairan Dana
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

                <form action="{{ route('kelembagaan.pencairan.update', $pencairan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- KEGIATAN --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Kegiatan</label>
                        <input type="text" value="{{ $pencairan->kegiatan->nama_kegiatan }}"
                            class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                    </div>

                    {{-- RINGKASAN ANGGARAN --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Pagu Anggaran</p>
                            <p class="font-bold text-blue-700">
                                Rp {{ number_format((int) $pencairan->kegiatan->pagu_anggaran, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sudah Dicairkan</p>
                            <p class="font-bold text-orange-600">
                                Rp {{ number_format((int) $sudahCair, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sisa Anggaran</p>
                            <p class="font-bold text-green-700">
                                Rp {{ number_format((int) $sisaAnggaran, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>

                    {{-- FORM --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

                        <div>
                            <label class="text-sm font-medium">Tanggal Cair</label>
                            <input type="date" name="tanggal_cair"
                                value="{{ old('tanggal_cair', $pencairan->tanggal_cair) }}"
                                class="w-full rounded-lg border-gray-300" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Jumlah Dicairkan</label>
                            <input type="number" name="jumlah" value="{{ old('jumlah', (int) $pencairan->jumlah) }}"
                                max="{{ (int) $sisaAnggaran }}" class="w-full rounded-lg border-gray-300" required>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium">No SP2D</label>
                            <input type="text" name="no_sp2d" value="{{ old('no_sp2d', $pencairan->no_sp2d) }}"
                                class="w-full rounded-lg border-gray-300">
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan Perubahan
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
</x-app-layout>
