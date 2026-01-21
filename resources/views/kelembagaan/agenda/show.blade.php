<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Agenda
                    </h2>
                    <a href="{{ route('kelembagaan.agenda.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                {{-- DATA UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Judul Agenda</span>
                        <div class="font-semibold">{{ $agenda->judul_agenda }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jenis Agenda</span>
                        <div class="font-semibold">{{ $agenda->jenis->jenis_agenda ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Unit</span>
                        <div class="font-semibold">{{ $agenda->unit->unit_keputusan ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Periode</span>
                        <div class="font-semibold">{{ $agenda->periode->tahun_awal ?? '-' }}</div>
                    </div>

                </div>

                {{-- DATA LANJUTAN --}}
                <div class="mt-8 border-t pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">

                    <div>
                        <span class="text-gray-500">Status</span>
                        <div class="font-semibold">{{ $agenda->status->status_agenda ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Tempat</span>
                        <div class="font-semibold">{{ $agenda->tempat->tempat_agenda ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Tanggal</span>
                        <div class="font-semibold">
                            {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jam Mulai</span>
                        <div class="font-semibold">{{ $agenda->jam_mulai }}</div>
                    </div>

                    <div>
                        <span class="text-gray-500">Jam Selesai</span>
                        <div class="font-semibold">{{ $agenda->jam_selesai ?? '-' }}</div>
                    </div>

                    <div class="sm:col-span-3">
                        <span class="text-gray-500">Uraian Agenda</span>
                        <div class="font-semibold">
                            {{ $agenda->uraian_agenda ?? '-' }}
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('kelembagaan.agenda.edit', $agenda->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
