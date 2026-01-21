<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Detail Agenda Kelembagaan
                    </h2>
                    <a href="{{ route('admin-umum.agenda.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- FILE --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center">
                        <div class="w-32 h-32 flex items-center justify-center bg-white rounded-xl border mb-4">
                            <x-heroicon-o-document class="w-16 h-16 text-gray-400" />
                        </div>

                        @if ($data->agendalembaga_file)
                            <a href="{{ asset('storage/' . $data->agendalembaga_file) }}" target="_blank"
                                class="text-sm text-blue-600 underline">
                                Download File
                            </a>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada file</p>
                        @endif
                    </div>

                    {{-- DATA UTAMA --}}
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                        <div>
                            <span class="text-gray-500">Kode Agenda</span>
                            <div class="font-semibold">{{ $data->kdagendalembaga ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Agenda</span>
                            <div class="font-semibold">
                                {{ \Carbon\Carbon::parse($data->agendalembaga_tanggal)->format('d-m-Y') }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Nomor Surat</span>
                            <div class="font-semibold">{{ $data->agendalembaga_nomorsurat ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Tanggal Surat</span>
                            <div class="font-semibold">
                                {{ $data->agendalembaga_tanggalsurat
                                    ? \Carbon\Carbon::parse($data->agendalembaga_tanggalsurat)->format('d-m-Y')
                                    : '-' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500">Identitas Surat</span>
                            <div class="font-semibold">{{ $data->agendalembaga_identitassurat ?? '-' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-500">Jenis Agenda</span>
                            <div class="font-semibold">
                                {{ $data->jenisAgenda->jenisagenda_umum ?? '-' }}
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ISI & KETERANGAN --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">Isi Surat</p>
                        <div class="text-sm">
                            {{ $data->agendalembaga_isisurat ?? '-' }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                        <div class="text-sm">
                            {{ $data->agendalembaga_keterangan ?? '-' }}
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-umum.agenda.edit', $data->kdagendalembaga) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
