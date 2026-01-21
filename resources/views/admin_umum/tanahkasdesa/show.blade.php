<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Tanah Kas Desa
                    </h2>
                    <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- FOTO --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center">
                        <img src="{{ asset('storage/' . $data->fototanahkasdesa) }}"
                            class="w-40 h-40 object-cover rounded-xl border mb-4"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ $data->kdtanahkasdesa }}'">
                        <p class="text-sm text-gray-600">Foto Tanah</p>
                    </div>

                    {{-- DATA UTAMA --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Kode Tanah</span>
                            <div class="font-semibold">{{ $data->kdtanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Pencatatan</span>
                            <div class="font-semibold">{{ $data->tanggaltanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Asal Tanah</span>
                            <div class="font-semibold">{{ $data->asaltanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Nomor Sertifikat</span>
                            <div class="font-semibold">{{ $data->sertifikattanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Luas Tanah</span>
                            <div class="font-semibold">{{ $data->luastanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Kelas Tanah</span>
                            <div class="font-semibold">{{ $data->kelastanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Perolehan</span>
                            <div class="font-semibold">{{ $data->perolehan->perolehantkd ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Jenis TKD</span>
                            <div class="font-semibold">{{ $data->jenis->jenistkd ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Patok</span>
                            <div class="font-semibold">{{ $data->patok->patok ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Papan Nama</span>
                            <div class="font-semibold">{{ $data->papanNama->papannama ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Lokasi</span>
                            <div class="font-semibold">{{ $data->lokasitanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Peruntukan</span>
                            <div class="font-semibold">{{ $data->peruntukantanahkasdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Mutasi</span>
                            <div class="font-semibold">{{ $data->mutasitanahkasdesa ?? '-' }}</div>
                        </div>

                    </div>
                </div>

                {{-- KETERANGAN --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">
                            {{ $data->keterangantanahkasdesa ?? '-' }}
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-umum.tanahkasdesa.edit', $data->kdtanahkasdesa) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
