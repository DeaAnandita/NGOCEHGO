<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Aparat Desa
                    </h2>
                    <a href="{{ route('admin-umum.aparat.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- FOTO --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center">
                        <img src="{{ asset('storage/' . $data->fotopengangkatan) }}"
                            class="w-40 h-40 object-cover rounded-xl border mb-4"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ $data->masterAparat->aparat ?? 'Aparat' }}'">
                        <p class="text-sm text-gray-600">Foto Pengangkatan</p>
                    </div>

                    {{-- DATA UTAMA --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Nama Aparat</span>
                            <div class="font-semibold">{{ $data->masterAparat->aparat ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">NIP</span>
                            <div class="font-semibold">{{ $data->nipaparat ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">NIK</span>
                            <div class="font-semibold">{{ $data->nik ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Pangkat</span>
                            <div class="font-semibold">{{ $data->pangkataparat ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Nomor SK</span>
                            <div class="font-semibold">{{ $data->nomorpengangkatan ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Pengangkatan</span>
                            <div class="font-semibold">{{ $data->tanggalpengangkatan ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Status</span>
                            <div class="font-semibold">{{ $data->statusaparatdesa ?? '-' }}</div>
                        </div>

                    </div>
                </div>

                {{-- KETERANGAN --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">
                            {{ $data->keteranganaparatdesa ?? '-' }}
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-umum.aparat.edit', $data->kdaparat) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
