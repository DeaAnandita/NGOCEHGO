<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Tanah Desa
                    </h2>
                    <a href="{{ route('admin-umum.tanahdesa.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- FOTO --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center">
                        <img src="{{ asset('storage/' . $data->fototanahdesa) }}"
                            class="w-40 h-40 object-cover rounded-xl border mb-4"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ $data->kdtanahdesa }}'">
                        <p class="text-sm text-gray-600">Foto Tanah</p>
                    </div>

                    {{-- DATA UTAMA --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Kode Tanah</span>
                            <div class="font-semibold">{{ $data->kdtanahdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Pencatatan</span>
                            <div class="font-semibold">{{ $data->tanggaltanahdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Pemilik</span>
                            <div class="font-semibold">{{ $data->pemiliktanahdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Kode Pemilik</span>
                            <div class="font-semibold">{{ $data->kdpemilik ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Jenis Pemilik</span>
                            <div class="font-semibold">{{ $data->jenisPemilik->jenispemilik ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Status Hak</span>
                            <div class="font-semibold">{{ $data->statusHak->statushaktanah ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Penggunaan</span>
                            <div class="font-semibold">{{ $data->penggunaan->penggunaantanah ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Mutasi</span>
                            <div class="font-semibold">{{ $data->mutasi->mutasitanah ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Mutasi</span>
                            <div class="font-semibold">{{ $data->tanggalmutasitanahdesa ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Luas Tanah</span>
                            <div class="font-semibold">{{ $data->luastanahdesa ?? '-' }}</div>
                        </div>

                    </div>
                </div>

                {{-- KETERANGAN --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">
                            {{ $data->keterangantanahdesa ?? '-' }}
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-umum.tanahdesa.edit', $data->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
