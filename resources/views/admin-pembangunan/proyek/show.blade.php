<x-app-layout>
    <div class="flex">
        {{-- SIDEBAR --}}
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        Detail Buku Proyek
                    </h3>

                    <a href="{{ route('admin-pembangunan.proyek.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        ← Kembali
                    </a>
                </div>

                {{-- DATA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Kegiatan --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Kegiatan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->kegiatan->kegiatan ?? '-' }}
                        </p>
                    </div>

                    {{-- Pelaksana --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Pelaksana</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->pelaksana->pelaksana ?? '-' }}
                        </p>
                    </div>

                    {{-- Lokasi --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Lokasi</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->lokasi->lokasi ?? '-' }}
                        </p>
                    </div>

                    {{-- Sumber Dana --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Sumber Dana</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->sumber->sumber_dana ?? '-' }}
                        </p>
                    </div>

                    {{-- Tanggal --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Tanggal Proyek</p>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($data->proyek_tanggal)->format('d-m-Y') }}
                        </p>
                    </div>

                    {{-- Nominal --}}
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-500">Nilai Proyek</p>
                        <p class="font-semibold text-gray-800">
                            Rp {{ number_format($data->proyek_nominal, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Manfaat --}}
                    <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                        <p class="text-xs text-gray-500">Manfaat</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->proyek_manfaat ?? '-' }}
                        </p>
                    </div>

                    {{-- Keterangan --}}
                    <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                        <p class="text-xs text-gray-500">Keterangan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->proyek_keterangan ?? '-' }}
                        </p>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin-pembangunan.proyek.edit', $data->reg) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                        Edit
                    </a>
                    <a href="{{ route('admin-pembangunan.proyek.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-lg">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
