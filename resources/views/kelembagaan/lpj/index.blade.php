<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-800">
                            Laporan Pertanggungjawaban (LPJ)
                        </h3>

                        {{-- EXPORT --}}
                        @if (in_array(auth()->user()->role->slug, ['admin', 'super_admin', 'dev']))
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg flex items-center gap-2 hover:bg-indigo-700">
                                    <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                    Export
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open=false"
                                    class="absolute mt-2 bg-white border rounded-lg shadow w-40 z-50">
                                    <a href="{{ route('kelembagaan.lpj.export') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                        📊 Excel
                                    </a>
                                    <a href="{{ route('kelembagaan.lpj.exportPdf') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                        📄 PDF
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex gap-2 w-full lg:w-auto">

                        {{-- SEARCH --}}
                        <form method="GET" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari kegiatan..." class="border rounded-lg px-3 py-2 text-sm">

                            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Diajukan</option>
                                <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Disetujui</option>
                                <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Ditolak</option>
                            </select>

                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Cari
                            </button>
                        </form>

                        {{-- AJUKAN --}}
                        @if (in_array(auth()->user()->role->slug, ['user', 'admin', 'super_admin']))
                            <a href="{{ route('kelembagaan.lpj.create') }}"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg whitespace-nowrap">
                                + Ajukan LPJ
                            </a>
                        @endif

                    </div>
                </div>

                {{-- ================= FLASH ================= --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ================= TABLE ================= --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="p-2">No</th>
                                <th class="text-left p-2">Kegiatan</th>
                                <th class="p-2">Total</th>
                                <th class="p-2">Realisasi</th>
                                <th class="p-2">Sisa</th>
                                <th class="p-2">Status</th>
                                <th class="p-2 w-32">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @forelse($data as $d)
                                <tr class="border-t hover:bg-gray-50">

                                    <td class="p-2">{{ $loop->iteration }}</td>

                                    <td class="text-left px-2 font-medium">
                                        {{ $d->kegiatan->nama_kegiatan }}
                                    </td>

                                    <td class="text-right px-2">
                                        Rp {{ number_format($d->total_anggaran, 0, ',', '.') }}
                                    </td>

                                    <td class="text-right px-2">
                                        Rp {{ number_format($d->total_realisasi, 0, ',', '.') }}
                                    </td>

                                    <td
                                        class="text-right px-2 font-semibold
                                        {{ $d->sisa_anggaran < 0 ? 'text-red-600' : 'text-green-700' }}">
                                        Rp {{ number_format($d->sisa_anggaran, 0, ',', '.') }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if ($d->status == 1)
                                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                                Diajukan
                                            </span>
                                        @elseif($d->status == 2)
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($d->status == 3)
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ================= AKSI ================= --}}
                                    <td class="border px-2 py-2">
                                        @php
                                            $role = auth()->user()->role->slug;
                                        @endphp

                                        <div class="flex justify-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('kelembagaan.lpj.show', $d->id) }}"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow"
                                                title="Detail">
                                                <x-heroicon-o-eye class="w-4 h-4" />
                                            </a>

                                            {{-- EDIT --}}
                                            @if (($role === 'user' && in_array($d->status, [1, 3])) || in_array($role, ['admin', 'super_admin']))
                                                <a href="{{ route('kelembagaan.lpj.edit', $d->id) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow"
                                                    title="Edit">
                                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                </a>
                                            @endif

                                            {{-- APPROVE & REJECT --}}
                                            @if (in_array($role, ['super_admin', 'dev']) && $d->status == 1)
                                                {{-- SETUJUI --}}
                                                <form action="{{ route('kelembagaan.lpj.update', $d->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="2">
                                                    <button
                                                        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg shadow"
                                                        title="Setujui">
                                                        <x-heroicon-o-check class="w-4 h-4" />
                                                    </button>
                                                </form>

                                                {{-- TOLAK --}}
                                                <form action="{{ route('kelembagaan.lpj.update', $d->id) }}"
                                                    method="POST" onsubmit="return confirm('Tolak LPJ ini?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="3">
                                                    <button
                                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg shadow"
                                                        title="Tolak">
                                                        <x-heroicon-o-x-mark class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <x-heroicon-o-document-text class="w-10 h-10 text-gray-400" />
                                            <p class="font-medium">Belum ada LPJ</p>
                                            <p class="text-sm text-gray-400">
                                                Silakan ajukan LPJ untuk kegiatan yang sudah selesai.
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
</x-app-layout>
