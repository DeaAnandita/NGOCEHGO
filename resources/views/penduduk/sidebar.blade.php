<div class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4 my-6">
    <a href="{{ route('dasar-penduduk.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('dasar-penduduk.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-user-group class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Penduduk</span>
    </a>

    <a href="{{ route('penduduk.kelahiran.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.kelahiran.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-user-plus class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Kelahiran</span>
    </a>

    <a href="{{ route('penduduk.sosialekonomi.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.sosialekonomi.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-banknotes class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Sosial Ekonomi</span>
    </a>

    <a href="{{ route('penduduk.usahaart.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.usahaart.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-building-storefront class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Usaha ART</span>
    </a>

    <a href="{{ route('penduduk.programserta.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.programserta.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-identification class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Program Serta</span>
    </a>

    <a href="{{ route('penduduk.lemdes.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.lemdes.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-building-library class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Lembaga Desa</span>
    </a>

    <a href="{{ route('penduduk.lemmas.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.lemmas.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-swatch class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Lembaga Masyarakat</span>
    </a>

    <a href="{{ route('penduduk.lembagaekonomi.index') }}"
       class="flex flex-col items-center {{ request()->routeIs('penduduk.lembagaekonomi.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-currency-dollar class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Lembaga Ekonomi</span>
    </a>
</div>
