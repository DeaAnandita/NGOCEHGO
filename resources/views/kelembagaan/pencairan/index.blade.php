<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Data Pencairan Anggaran</h3>

                        {{-- Export --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute mt-2 w-40 bg-white border rounded-lg shadow z-50">
                                <a href="{{ route('kelembagaan.pencairan.export') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">Export Excel</a>
                                <a href="{{ route('kelembagaan.pencairan.exportPdf') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">Export PDF</a>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <form method="GET" action="{{ route('kelembagaan.pencairan.index') }}"
                        class="flex flex-wrap lg:flex-nowrap gap-2 items-center w-full lg:w-auto">

                        {{-- SEARCH --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama kegiatan..."
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full lg:w-64">

                        {{-- FILTER BULAN --}}
                        <input type="month" name="bulan" value="{{ request('bulan') }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-lg">
                            Cari
                        </button>

                        <a href="{{ route('kelembagaan.pencairan.create') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 text-sm font-medium rounded-lg whitespace-nowrap">
                            + Pencairan
                        </a>

                    </form>

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
                                <th>Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse ($data as $d)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2">{{ $loop->iteration }}</td>

                                    <td class="text-left px-2 font-medium">
                                        {{ $d->kegiatan->nama_kegiatan }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($d->tanggal_cair)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-right px-2 font-semibold">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('kelembagaan.pencairan.show', $d->id) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow"
                                                title="Detail">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            <a href="{{ route('kelembagaan.pencairan.edit', $d->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow"
                                                title="Edit">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>


                                            {{-- DELETE --}}
                                            <form action="{{ route('kelembagaan.pencairan.destroy', $d->id) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal', {
          form: $el,
          name: '{{ $d->kegiatan->nama_kegiatan }}'
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
                                    <td colspan="5" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <x-heroicon-o-currency-dollar class="w-10 h-10 text-gray-400" />
                                            <p class="font-medium text-gray-600">Data pencairan belum ada</p>
                                            <p class="text-sm text-gray-400">
                                                Silakan klik tombol <b>+ Pencairan</b> untuk menambahkan data.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
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
                Yakin ingin menghapus pencairan untuk
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
