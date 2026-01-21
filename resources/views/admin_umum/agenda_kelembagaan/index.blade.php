<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">Buku Agenda Kelembagaan</h3>

                        {{-- Export --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                Export
                            </button>


                            <div x-show="open" @click.away="open=false" x-transition
                                class="absolute mt-2 w-44 bg-white border rounded-lg shadow-lg z-50 overflow-hidden">

                                <a href="{{ route('admin-umum.agenda.export', ['search' => request('search')]) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    📗 Export Excel
                                </a>

                                <a href="{{ route('admin-umum.agenda.exportPdf', ['search' => request('search')]) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">
                                    📕 Export PDF
                                </a>

                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <form method="GET" action="{{ route('admin-umum.agenda.index') }}" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nomor / identitas surat..."
                                class="border rounded-lg px-3 py-2 text-sm">

                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                Cari
                            </button>
                        </form>

                        <a href="{{ route('admin-umum.agenda.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm">
                            + Tambah
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Nomor Surat</th>
                                <th>Identitas Surat</th>
                                <th>Jenis Agenda</th>
                                <th class="w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse($data as $d)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-3 py-2">
                                        {{ $data->firstItem() + $loop->index }}
                                    </td>
                                    <td>{{ $d->kdagendalembaga }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->agendalembaga_tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $d->agendalembaga_nomorsurat }}</td>
                                    <td>{{ $d->agendalembaga_identitassurat }}</td>
                                    <td>{{ $d->jenisAgenda->jenisagenda_umum ?? '-' }}</td>

                                    <td>
                                        <div class="flex justify-center gap-2">

                                            <a href="{{ route('admin-umum.agenda.show', $d->kdagendalembaga) }}"
                                                class="bg-indigo-600 text-white p-2 rounded-lg">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            <a href="{{ route('admin-umum.agenda.edit', $d->kdagendalembaga) }}"
                                                class="bg-blue-600 text-white p-2 rounded-lg">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </a>

                                            <form
                                                action="{{ route('admin-umum.agenda.destroy', $d->kdagendalembaga) }}"
                                                method="POST" x-data
                                                @submit.prevent="$dispatch('open-delete-modal',{form:$el,name:'{{ $d->kdagendalembaga }}'})">
                                                @csrf
                                                @method('DELETE')

                                                <button class="bg-red-600 text-white p-2 rounded-lg">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-gray-500">
                                        Belum ada data Agenda Kelembagaan
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
        x-on:open-delete-modal.window="open=true; form=$event.detail.form; name=$event.detail.name" x-show="open"
        x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-full max-w-md">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Hapus</h3>

            <p class="mb-6">
                Yakin hapus agenda
                <span class="font-semibold text-red-600" x-text="name"></span>?
            </p>

            <div class="flex justify-end gap-3">
                <button @click="open=false" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Batal
                </button>
                <button @click="form.submit()" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Hapus
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
