<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Buku Inventaris
                    </h2>
                    <a href="{{ route('admin-umum.inventaris.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- FOTO --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center">
                        @if ($data->inventaris_foto && file_exists(public_path('storage/' . $data->inventaris_foto)))
                            <img src="{{ asset('storage/' . $data->inventaris_foto) }}"
                                class="w-48 h-48 object-cover rounded-xl border mb-4">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $data->kdinventaris }}&size=256"
                                class="w-48 h-48 object-cover rounded-xl border mb-4">
                        @endif
                        <p class="text-sm text-gray-600">Foto Barang</p>
                    </div>

                    {{-- DATA UTAMA --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Kode Inventaris</span>
                            <div class="font-semibold">{{ $data->kdinventaris }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal</span>
                            <div class="font-semibold">
                                {{ \Carbon\Carbon::parse($data->inventaris_tanggal)->format('d-m-Y') }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Identitas Barang</span>
                            <div class="font-semibold">{{ $data->inventaris_identitas }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Pengguna</span>
                            <div class="font-semibold">{{ $data->pengguna->pengguna ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Volume</span>
                            <div class="font-semibold">
                                {{ $data->inventaris_volume ?? '-' }}
                                {{ $data->satuan->satuanbarang ?? '' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Asal Barang</span>
                            <div class="font-semibold">
                                {{ $data->asalBarang->asalbarang ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Asal Perolehan (Detail)</span>
                            <div class="font-semibold">{{ $data->barangasal ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Sub Kode / Anak</span>
                            <div class="font-semibold">{{ $data->anak ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Harga</span>
                            <div class="font-semibold">
                                Rp {{ number_format($data->inventaris_harga ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Status</span>
                            <div class="font-semibold">
                                {{ $data->inventaris_hapus == 1 ? 'Dihapus' : 'Aktif' }}
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KETERANGAN --}}
                <div class="mt-8">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">
                            {{ $data->inventaris_keterangan ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-umum.inventaris.edit', $data->kdinventaris) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
