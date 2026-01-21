<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- ================= HEADER ================= --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Pengurus Lembaga</h3>

                        {{-- Dropdown Export --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export Data
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden">
                                <a href="{{ route('kelembagaan.pengurus.export', ['periode' => request('periode')]) }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4 text-green-600" />
                                    Export Excel
                                </a>
                                <a href="{{ route('kelembagaan.pengurus.exportPdf', ['periode' => request('periode')]) }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full lg:w-auto">

                        {{-- SEARCH --}}
                        <form method="GET" action="{{ route('kelembagaan.pengurus.index') }}"
                            class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full">

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau Nomor Induk..."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 w-full lg:w-64">

                            {{-- PER PAGE --}}
                            <select name="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 w-24">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>

                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700">
                                Cari
                            </button>
                        </form>

                        {{-- TAMBAH --}}
                        <a href="{{ route('kelembagaan.pengurus.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 text-sm font-medium rounded-lg hover:bg-green-700 whitespace-nowrap">
                            + Tambah Data
                        </a>

                    </div>
                </div>



                {{-- FLASH MESSAGE --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2">No</th>
                                <th>Nomor Induk</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Unit</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Jenis SK</th>
                                <th>Tgl SK</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse ($pengurus as $p)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2">{{ $pengurus->firstItem() + $loop->index }}</td>
                                    <td>{{ $p->nomor_induk }}</td>
                                    <td class="font-medium">{{ $p->nama_lengkap }}</td>
                                    <td>{{ $p->jabatan->jabatan ?? '-' }}</td>
                                    <td>{{ $p->unit->nama_unit ?? '-' }}</td>
                                    <td>
                                        {{ $p->periodeAwal->tahun_awal ?? '-' }}
                                        –
                                        {{ $p->periodeAkhir->akhir ?? '-' }}
                                    </td>
                                    <td>{{ $p->status->status_pengurus ?? '-' }}</td>
                                    <td>{{ $p->jenisSk->jenis_sk ?? '-' }}</td>
                                    <td>{{ $p->tanggal_sk }}</td>

                                    <td>
                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('kelembagaan.pengurus.show', $p->id_pengurus) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow"
                                                title="Detail">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('kelembagaan.pengurus.edit', $p->id_pengurus) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow"
                                                title="Edit">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>

                                            {{-- DELETE --}}
                                            <form action="{{ route('kelembagaan.pengurus.destroy', $p->id_pengurus) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal', {
                            form: $el,
                            name: '{{ $p->nama_lengkap }}'
                        })">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg shadow"
                                                    title="Hapus">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <x-heroicon-o-user-group class="w-10 h-10 text-gray-400" />
                                            <p class="font-medium text-gray-600">Data pengurus belum ada</p>
                                            <p class="text-sm text-gray-400">Silakan klik tombol <b>+ Tambah</b> untuk
                                                menambahkan pengurus.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="mt-4">
                    {{ $pengurus->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- ================= MODAL KONFIRMASI HAPUS ================= --}}
    <div x-data="{
        open: false,
        form: null,
        name: '',
    }"
        x-on:open-delete-modal.window="
            open = true;
            form = $event.detail.form;
            name = $event.detail.name;
        "
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-2">
                Konfirmasi Hapus
            </h3>

            <p class="text-gray-600 mb-6">
                Yakin ingin menghapus pengurus
                <span class="font-semibold text-red-600" x-text="name"></span>?
                <br>
                Data yang dihapus tidak dapat dikembalikan.
            </p>

            <div class="flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                    Batal
                </button>

                <button @click="form.submit()" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
