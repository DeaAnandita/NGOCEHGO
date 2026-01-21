<div class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4 my-6">

    <a href="{{ route('admin-umum.peraturan.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.peraturan.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-book-open class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Buku Peraturan</span>
    </a>

    <a href="{{ route('admin-umum.keputusan.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.keputusan.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-scale class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Buku Keputusan</span>
    </a>

    <a href="{{ route('admin-umum.aparat.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.aparat.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-users class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Buku Aparat</span>
    </a>

    <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.tanahkasdesa.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-banknotes class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Kas Tanah Desa</span>
    </a>

    <a href="{{ route('admin-umum.tanahdesa.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.tanahdesa.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-map class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Tanah Desa</span>
    </a>

    <a href="{{ route('admin-umum.agenda.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.agenda.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-calendar-days class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Agenda Lembaga</span>
    </a>

    <a href="{{ route('admin-umum.ekspedisi.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.ekspedisi.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-paper-airplane class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Ekspedisi</span>
    </a>

    <a href="{{ route('admin-umum.inventaris.index') }}"
        class="flex flex-col items-center {{ request()->routeIs('admin-umum.inventaris.*') ? 'bg-blue-100 text-blue-800' : 'text-gray-500 hover:text-blue-600' }} rounded-lg px-2 py-1 transition">
        <x-heroicon-o-archive-box class="w-7 h-7" />
        <span class="text-[10px] mt-1 text-center">Inventaris</span>
    </a>

</div>
