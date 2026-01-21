<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">Buku Aparat Desa</h3>

                        {{-- Export --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export
                            </button>

                            <div x-show="open" @click.away="open=false" x-transition
                                class="absolute mt-2 w-40 bg-white border rounded-lg shadow-lg z-50">
                                <a href="{{ route('admin-umum.aparat.export') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export Excel
                                </a>
                                <a href="{{ route('admin-umum.aparat.exportPdf') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-wrap gap-2">
                        <form method="GET" action="{{ route('admin-umum.aparat.index') }}" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari NIP / NIK..." class="border rounded-lg px-3 py-2 text-sm">

                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('admin-umum.aparat.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm">
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

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Aparat</th>
                                <th>NIP</th>
                                <th>NIK</th>
                                <th>Pangkat</th>
                                <th>Status</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse($data as $d)
                                <tr class="border-t hover:bg-gray-50">
                                    <td>{{ $data->firstItem() + $loop->index }}</td>
                                    <td>{{ $d->masterAparat->aparat ?? '-' }}</td>
                                    <td>{{ $d->nipaparat ?? '-' }}</td>
                                    <td>{{ $d->nik ?? '-' }}</td>
                                    <td>{{ $d->pangkataparat ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="px-2 py-1 text-xs rounded text-white
                                            {{ $d->statusaparatdesa == 'Aktif' ? 'bg-green-600' : 'bg-gray-500' }}">
                                            {{ $d->statusaparatdesa }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('admin-umum.aparat.show', $d->id) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('admin-umum.aparat.edit', $d->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>

                                            {{-- DELETE --}}
                                            <form action="{{ route('admin-umum.aparat.destroy', $d->id) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal', {
                                                    form: $el,
                                                    name: '{{ $d->masterAparat->aparat ?? '' }}'
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
                                    <td colspan="7" class="py-10 text-gray-500">
                                        Belum ada data aparat
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

    {{-- MODAL DELETE --}}
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
                Yakin ingin menghapus aparat
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
