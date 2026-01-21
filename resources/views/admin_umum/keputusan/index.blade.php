<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">Buku Keputusan Desa</h3>

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
                                <a href="{{ route('admin-umum.keputusan.export') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export Excel
                                </a>
                                <a href="{{ route('admin-umum.keputusan.exportPdf') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full lg:w-auto">
                        <form method="GET" action="{{ route('admin-umum.keputusan.index') }}"
                            class="flex flex-wrap lg:flex-nowrap items-center gap-2 w-full">

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nomor / judul..."
                                class="border rounded-lg px-3 py-2 text-sm w-full lg:w-64">

                            <select name="per_page" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-2 text-sm w-24">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>

                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('admin-umum.keputusan.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm whitespace-nowrap">
                            + Tambah
                        </a>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="p-2">No</th>
                                <th>Jenis</th>
                                <th>Nomor</th>
                                <th class="text-left">Judul</th>
                                <th>Tanggal</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse ($data as $d)
                                <tr class="border-t hover:bg-gray-50">

                                    <td class="p-2">
                                        {{ $data->firstItem() + $loop->index }}
                                    </td>

                                    <td>{{ $d->jenisKeputusan->jeniskeputusan_umum ?? '-' }}</td>
                                    <td>{{ $d->nomor_keputusan }}</td>

                                    <td class="text-left px-2 font-medium">
                                        {{ $d->judul_keputusan }}
                                    </td>

                                    <td>{{ $d->tanggal_keputusan }}</td>

                                    <td>
                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('admin-umum.keputusan.show', $d->kd_keputusan) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('admin-umum.keputusan.edit', $d->kd_keputusan) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>

                                            {{-- DELETE --}}
                                            <form
                                                action="{{ route('admin-umum.keputusan.destroy', $d->kd_keputusan) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal', {
                                                    form: $el,
                                                    name: '{{ $d->nomor_keputusan }}'
                                                })">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg shadow">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500">
                                        Belum ada data keputusan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $data->links() }}

            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div x-data="{ open: false, form: null, name: '' }"
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
