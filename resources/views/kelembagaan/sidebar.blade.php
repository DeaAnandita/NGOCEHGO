<div class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4 my-6">

    @php
        function aktifIcon($r)
        {
            return request()->routeIs($r) ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-blue-600';
        }
    @endphp

    {{-- Pengurus --}}
    <a href="{{ route('kelembagaan.pengurus.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.pengurus.*') }}">
        <x-heroicon-o-user-group class="w-7 h-7" />
        <span class="text-[10px] mt-1">Pengurus</span>
    </a>

    {{-- Keputusan --}}
    <a href="{{ route('kelembagaan.keputusan.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.keputusan.*') }}">
        <x-heroicon-o-clipboard-document-check class="w-7 h-7" />
        <span class="text-[10px] mt-1">Keputusan</span>
    </a>

    {{-- Kegiatan --}}
    <a href="{{ route('kelembagaan.kegiatan.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.kegiatan.*') }}">
        <x-heroicon-o-calendar-days class="w-7 h-7" />
        <span class="text-[10px] mt-1">Kegiatan</span>
    </a>

    {{-- Agenda --}}
    <a href="{{ route('kelembagaan.agenda.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.agenda.*') }}">
        <x-heroicon-o-clock class="w-7 h-7" />
        <span class="text-[10px] mt-1">Agenda</span>
    </a>

    {{-- Anggaran --}}
    <a href="{{ route('kelembagaan.anggaran.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.anggaran.*') }}">
        <x-heroicon-o-banknotes class="w-7 h-7" />
        <span class="text-[10px] mt-1">Anggaran</span>
    </a>

    {{-- Pencairan --}}
    <a href="{{ route('kelembagaan.pencairan.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.pencairan.*') }}">
        <x-heroicon-o-arrow-down-tray class="w-7 h-7" />
        <span class="text-[10px] mt-1">Pencairan</span>
    </a>

    {{-- LPJ --}}
    <a href="{{ route('kelembagaan.lpj.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ aktifIcon('kelembagaan.lpj.*') }}">
        <x-heroicon-o-document-text class="w-7 h-7" />
        <span class="text-[10px] mt-1">LPJ</span>
    </a>

</div>
