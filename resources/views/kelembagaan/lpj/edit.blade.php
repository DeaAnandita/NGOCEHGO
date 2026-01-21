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
                    Edit LPJ Kegiatan
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

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('kelembagaan.lpj.update', $lpj->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- NAMA KEGIATAN --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Kegiatan</label>
                        <input type="text" value="{{ $lpj->kegiatan->nama_kegiatan }}"
                            class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                    </div>

                    {{-- INFO ANGGARAN --}}
                    <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Total Anggaran</p>
                            <p class="font-bold text-blue-700">
                                Rp {{ number_format($lpj->total_anggaran, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Total Realisasi</p>
                            <p class="font-bold text-orange-600">
                                Rp {{ number_format($lpj->total_realisasi, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500">Sisa Anggaran</p>
                            <p class="font-bold {{ $lpj->sisa_anggaran < 0 ? 'text-red-600' : 'text-green-700' }}">
                                Rp {{ number_format($lpj->sisa_anggaran, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- FILE LAMA --}}
                    @if ($lpj->file_lpj)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-1">File LPJ saat ini:</p>
                            <a href="{{ asset('storage/' . $lpj->file_lpj) }}" target="_blank"
                                class="text-blue-600 underline text-sm">
                                Lihat PDF LPJ
                            </a>
                        </div>
                    @endif

                    {{-- UPLOAD BARU --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Upload File LPJ Baru (PDF)</label>
                        <input type="file" name="file_lpj" accept="application/pdf"
                            class="w-full rounded-lg border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">
                            Upload ulang jika ingin mengganti file.
                        </p>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan Perubahan
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
</x-app-layout>
