<x-app-layout>
    <div class="flex">
        {{-- SIDEBAR --}}
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        Detail Buku Kader
                    </h3>

                    <a href="{{ route('admin-pembangunan.kader.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        ← Kembali
                    </a>
                </div>

                {{-- KARTU DATA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Kode Kader</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->kdkader }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Tanggal</p>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($item->kader_tanggal)->format('d-m-Y') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Penduduk</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->penduduk->nama ?? $item->kdpenduduk }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Pendidikan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->pendidikan->pendidikan ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Bidang</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->bidang->bidang ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Status</p>
                        <span
                            class="px-3 py-1 rounded text-xs text-white
                            {{ ($item->status->statuskader ?? '') == 'Aktif' ? 'bg-green-600' : 'bg-gray-500' }}">
                            {{ $item->status->statuskader ?? '-' }}
                        </span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                        <p class="text-xs text-gray-500">Keterangan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->kader_keterangan ?? '-' }}
                        </p>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin-pembangunan.kader.edit', $item->reg) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                        Edit
                    </a>
                    <a href="{{ route('admin-pembangunan.kader.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
