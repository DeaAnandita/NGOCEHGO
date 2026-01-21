<x-app-layout>
    <div class="flex">
        @include('admin-pembangunan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Buku Kader</h3>

                        {{-- Export --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open=false" x-transition
                                class="absolute mt-2 w-40 bg-white border rounded-lg shadow-lg z-50">
                                <a href="{{ route('admin-pembangunan.kader.export') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export Excel
                                </a>
                                <a href="{{ route('admin-pembangunan.kader.exportPdf') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full lg:w-auto">

                        <form method="GET" action="{{ route('admin-pembangunan.kader.index') }}"
                            class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full">

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama kader..."
                                class="border rounded-lg px-3 py-2 text-sm w-full lg:w-64">

                            <select name="per_page" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-2 text-sm w-24">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>

                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('admin-pembangunan.kader.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm whitespace-nowrap">
                            + Tambah
                        </a>

                    </div>
                </div>

                {{-- FLASH --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="p-2">No</th>
                                <th>Nama Penduduk</th>
                                <th>Pendidikan</th>
                                <th>Bidang</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse ($data as $i => $k)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2">{{ $data->firstItem() + $i }}</td>

                                    <td class="font-medium">
                                        {{ $k->penduduk->nama ?? $k->kdpenduduk }}
                                    </td>

                                    <td>{{ $k->pendidikan->pendidikan ?? '-' }}</td>
                                    <td>{{ $k->bidang->bidang ?? '-' }}</td>

                                    <td>
                                        <span
                                            class="px-2 py-1 rounded text-xs text-white
                                            {{ ($k->status->statuskader ?? '') == 'Aktif' ? 'bg-green-600' : 'bg-gray-500' }}">
                                            {{ $k->status->statuskader ?? '-' }}
                                        </span>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($k->kader_tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $k->kader_keterangan }}</td>

                                    {{-- AKSI --}}
                                    <td>
                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('admin-pembangunan.kader.show', $k->reg) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow"
                                                title="Detail">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('admin-pembangunan.kader.edit', $k->reg) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow"
                                                title="Edit">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>

                                            {{-- DELETE --}}
                                            <form action="{{ route('admin-pembangunan.kader.destroy', $k->reg) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal', {
                                                    form: $el,
                                                    name: '{{ $k->penduduk->nama ?? 'Kader' }}'
                                                })">
                                                @csrf
                                                @method('DELETE')

                                                <button
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
                                    <td colspan="8" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center space-y-2">
                                            <x-heroicon-o-users class="w-10 h-10 text-gray-400" />
                                            <p class="font-medium">Belum ada kader</p>
                                            <p class="text-sm">Klik <b>+ Tambah</b> untuk menambah kader</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $data->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div x-data="{
        open: false,
        form: null,
        name: ''
    }"
        x-on:open-delete-modal.window="
            open = true;
            form = $event.detail.form;
            name = $event.detail.name;
        "
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Hapus</h3>

            <p class="text-gray-600 mb-6">
                Yakin ingin menghapus
                <span class="font-semibold text-red-600" x-text="name"></span>?
            </p>

            <div class="flex justify-end gap-3">
                <button @click="open=false" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Batal
                </button>

                <button @click="form.submit()" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
