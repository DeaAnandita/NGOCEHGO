<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Buku Peraturan Desa
                    </h2>
                    <a href="{{ route('admin-umum.peraturan.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                {{-- DATA UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Kode Peraturan</span>
                        <div class="font-semibold">{{ $data->kdperaturan }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jenis Peraturan</span>
                        <div class="font-semibold">
                            {{ $data->jenisPeraturanDesa->jenisperaturandesa ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">Nomor</span>
                        <div class="font-semibold">{{ $data->nomorperaturan }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Judul</span>
                        <div class="font-semibold">{{ $data->judulpengaturan }}</div>
                    </div>

                </div>

                {{-- DATA LANJUTAN --}}
                <div class="mt-8 border-t pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">

                    <div class="sm:col-span-3">
                        <span class="text-gray-500">Uraian</span>
                        <div class="font-semibold whitespace-pre-line">
                            {{ $data->uraianperaturan ?? '-' }}
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <span class="text-gray-500">Kesepakatan</span>
                        <div class="font-semibold whitespace-pre-line">
                            {{ $data->kesepakatanperaturan ?? '-' }}
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <span class="text-gray-500">Keterangan</span>
                        <div class="font-semibold whitespace-pre-line">
                            {{ $data->keteranganperaturan ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">User Input</span>
                        <div class="font-semibold">{{ $data->userinput }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Waktu Input</span>
                        <div class="font-semibold">{{ $data->inputtime }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">File PDF</span>
                        <div class="font-semibold">
                            @if ($data->filepengaturan)
                                <a href="{{ asset('storage/' . $data->filepengaturan) }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    Lihat PDF
                                </a>
                            @else
                                <span class="text-gray-400 italic">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin-umum.peraturan.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Kembali
                    </a>

                    <a href="{{ route('admin-umum.peraturan.edit', $data->kdperaturan) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
