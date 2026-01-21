<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Pengurus Kelembagaan
                    </h2>
                    <a href="{{ route('kelembagaan.pengurus.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center">
                        <img src="{{ asset('storage/' . $pengurus->foto) }}"
                            class="w-40 h-40 object-cover rounded-xl border mb-4"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ $pengurus->nama_lengkap }}'">

                        <p class="text-sm text-gray-600">Foto Pengurus</p>
                    </div>

                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Nomor Induk</span>
                            <div class="font-semibold">{{ $pengurus->nomor_induk }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Nama Lengkap</span>
                            <div class="font-semibold">{{ $pengurus->nama_lengkap }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Jenis Kelamin</span>
                            <div class="font-semibold">{{ $pengurus->jenis_kelamin }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">No HP</span>
                            <div class="font-semibold">{{ $pengurus->no_hp }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Email</span>
                            <div class="font-semibold">{{ $pengurus->email }}</div>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-gray-500">Alamat</span>
                            <div class="font-semibold">{{ $pengurus->alamat }}</div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 border-t pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Jabatan</span>
                        <div class="font-semibold">{{ $pengurus->jabatan->jabatan ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Unit</span>
                        <div class="font-semibold">{{ $pengurus->unit->nama_unit ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Status</span>
                        <div class="font-semibold">{{ $pengurus->status->status_pengurus ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Periode Awal</span>
                        <div class="font-semibold">{{ $pengurus->periodeAwal->tahun_awal ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Periode Akhir</span>
                        <div class="font-semibold">{{ $pengurus->periodeAkhir->akhir ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jenis SK</span>
                        <div class="font-semibold">{{ $pengurus->jenisSk->jenis_sk ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Nomor SK</span>
                        <div class="font-semibold">{{ $pengurus->no_sk }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Tanggal SK</span>
                        <div class="font-semibold">{{ $pengurus->tanggal_sk }}</div>
                    </div>

                </div>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-600 mb-2">Scan Tanda Tangan</p>
                        <img src="{{ asset('storage/' . $pengurus->tanda_tangan) }}" class="h-32 mx-auto"
                            onerror="this.style.display='none'">
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">{{ $pengurus->keterangan }}</div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('kelembagaan.pengurus.edit', $pengurus->id_pengurus) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
