<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Kegiatan
                    </h2>
                    <a href="{{ route('kelembagaan.kegiatan.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                {{-- DATA UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Nama Kegiatan</span>
                        <div class="font-semibold">{{ $kegiatan->nama_kegiatan }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jenis Kegiatan</span>
                        <div class="font-semibold">{{ $kegiatan->jenis->jenis_kegiatan ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Unit</span>
                        <div class="font-semibold">{{ $kegiatan->unit->unit_keputusan ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Periode</span>
                        <div class="font-semibold">{{ $kegiatan->periode->tahun_awal ?? '-' }}</div>
                    </div>

                </div>

                {{-- DATA LANJUTAN --}}
                <div class="mt-8 border-t pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Status</span>
                        <div class="font-semibold">{{ $kegiatan->status->status_kegiatan ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Sumber Dana</span>
                        <div class="font-semibold">{{ $kegiatan->sumberDana->sumber_dana ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Pagu Anggaran</span>
                        <div class="font-semibold">
                            Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">Tanggal Mulai</span>
                        <div class="font-semibold">
                            {{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">Tanggal Selesai</span>
                        <div class="font-semibold">
                            {{ $kegiatan->tgl_selesai ? \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d-m-Y') : '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">Lokasi</span>
                        <div class="font-semibold">{{ $kegiatan->lokasi ?? '-' }}</div>
                    </div>

                    <div class="sm:col-span-3">
                        <span class="text-gray-500">Dasar Keputusan</span>
                        <div class="font-semibold">
                            {{ $kegiatan->keputusan->nomor_sk ?? '-' }}
                            {{ $kegiatan->keputusan ? '– ' . $kegiatan->keputusan->judul_keputusan : '' }}
                        </div>
                    </div>

                </div>

                {{-- FOOTER ACTION --}}
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('kelembagaan.kegiatan.edit', $kegiatan->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
