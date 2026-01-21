<div class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4 my-6">

    @php
        $aktif = fn($r) => request()->routeIs($r) ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-blue-600';
    @endphp

    {{-- Bantuan --}}
    <a href="{{ route('admin-pembangunan.bantuan.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ $aktif('admin-pembangunan.bantuan.*') }}">
        <x-heroicon-o-gift class="w-7 h-7" />
        <span class="text-[10px] mt-1">Bantuan</span>
    </a>

    {{-- Kader --}}
    <a href="{{ route('admin-pembangunan.kader.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ $aktif('admin-pembangunan.kader.*') }}">
        <x-heroicon-o-users class="w-7 h-7" />
        <span class="text-[10px] mt-1">Kader</span>
    </a>

    {{-- Proyek --}}
    <a href="{{ route('admin-pembangunan.proyek.index') }}"
        class="flex flex-col items-center rounded-lg px-2 py-1 transition {{ $aktif('admin-pembangunan.proyek.*') }}">
        <x-heroicon-o-building-office-2 class="w-7 h-7" />
        <span class="text-[10px] mt-1">Proyek</span>
    </a>

</div>
