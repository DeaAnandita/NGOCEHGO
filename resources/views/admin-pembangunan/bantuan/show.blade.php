<x-app-layout>
    <div class="flex">
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        Detail Buku Bantuan
                    </h3>

                    <a href="{{ route('admin-pembangunan.bantuan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        ← Kembali
                    </a>
                </div>

                {{-- KARTU DATA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Nama Bantuan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->bantuan_nama }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Sasaran</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->sasaran->sasaran ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Jenis Bantuan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->bantuan->bantuan ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Sumber Dana</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->sumber->sumber_dana ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($item->bantuan_awal)->format('d-m-Y') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Tanggal Selesai</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->bantuan_akhir
                                ? \Carbon\Carbon::parse($item->bantuan_akhir)->format('d-m-Y')
                                : '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Jumlah Bantuan</p>
                        <p class="font-semibold text-gray-800">
                            {{ number_format($item->bantuan_jumlah, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                        <p class="text-xs text-gray-500">Keterangan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $item->bantuan_keterangan ?? '-' }}
                        </p>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin-pembangunan.bantuan.edit', $item->reg) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                        Edit
                    </a>
                    <a href="{{ route('admin-pembangunan.bantuan.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
