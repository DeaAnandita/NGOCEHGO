<div class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4 my-6">
    <a href="{{ route('dasar-keluarga.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('dasar-keluarga.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-user-group class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Keluarga</span>
    </a>

    <a href="{{ route('keluarga.prasarana.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.prasarana.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-home-modern class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Prasarana</span>
    </a>

    <a href="{{ route('keluarga.asetkeluarga.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.asetkeluarga.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-tv class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Aset Keluarga</span>
    </a>

    <a href="{{ route('keluarga.asetlahan.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.asetlahan.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-photo class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Aset Lahan</span>
    </a>

    <a href="{{ route('keluarga.asetternak.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.asetternak.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-bug-ant class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Aset Ternak</span>
    </a>

    <a href="{{ route('keluarga.asetperikanan.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.asetperikanan.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-bug-ant class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Aset Perikanan</span>
    </a>

    <a href="{{ route('keluarga.sarpraskerja.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.sarpraskerja.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-truck class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Sarpras Kerja</span>
    </a>

    <a href="{{ route('keluarga.bangunkeluarga.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.bangunkeluarga.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-users class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Bangun Keluarga</span>
    </a>

    <a href="{{ route('keluarga.sejahterakeluarga.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.sejahterakeluarga.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-heart class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Sejahtera Keluarga</span>
    </a>

    <a href="{{ route('keluarga.konfliksosial.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.konfliksosial.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-fire class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Konflik Sosial</span>
    </a>

    <a href="{{ route('keluarga.kualitasibuhamil.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.kualitasibuhamil.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-star class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Kualitas Ibu Hamil</span>
    </a>

    <a href="{{ route('keluarga.kualitasbayi.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('keluarga.kualitasbayi.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-sun class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Kualitas Bayi</span>
    </a>
</div>
