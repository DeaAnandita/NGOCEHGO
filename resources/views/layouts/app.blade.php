<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Informasi Desa Kaliwungu') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 50%, #ede9fe 100%);
            min-height: 100vh;
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        main { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from {opacity:0; transform:translateY(8px);} to {opacity:1; transform:translateY(0);} }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased text-gray-800" x-data="{ sidebarOpen: false, settingsOpen: false }">

    <!-- Sidebar -->
    <aside id="sidebar"
        x-bind:class="sidebarOpen ? 'ml-0' : '-ml-64'"
        class="w-64 bg-white shadow-md h-screen fixed transition-all duration-300 z-40">
        <div class="flex flex-col h-full">
            <!-- Header Sidebar -->
            <div class="px-6 py-4 bg-blue-600 text-white font-bold text-xl flex items-center justify-center">
                <span>Sistem Desa</span>
            </div>

            <!-- Menu List -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2 rounded-lg font-semibold
                   {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    🏠 Dashboard
                </a>

                <div>
                    <a href="{{ route('menu.utama') }}"
                       class="block px-4 py-2 rounded-lg font-semibold
                       {{ request()->routeIs('menu.utama*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        📋 Menu Utama
                    </a>

                    <div class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('menu.kependudukan') }}"
                           class="block px-4 py-2 rounded-lg font-semibold
                           {{ request()->routeIs('menu.kependudukan*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            👨‍👩‍👧 Menu Kependudukan
                        </a>

                        {{-- <div class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('dasar-keluarga.index') }}"
                                class="block px-3 py-1 text-sm rounded-md
                                {{ request()->routeIs('dasar-keluarga*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                 Data Keluarga</a>
                            <a href="{{ route('aset-keluarga.index') }}"
                                class="block px-3 py-1 text-sm rounded-md
                                {{ request()->routeIs('aset-keluarga*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                 Aset Keluarga</a>
                            <a href="{{ route('aset-lahan.index') }}"
                                class="block px-3 py-1 text-sm rounded-md
                                {{ request()->routeIs('aset-lahan*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                 Aset Lahan & Tanah</a>
                            <a href="{{ route('aset-keluarga.index') }}"
                                class="block px-3 py-1 rounded-md text-sm
                                {{ request()->routeIs('aset-keluarga*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Aset Keluarga
                            </a>
                            <a href="{{ route('aset-lahan.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('aset-lahan*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Aset Lahan & Tanah
                            </a>
                            <a href="{{ route('aset-ternak.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('aset-ternak*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Aset Ternak & Perikanan
                            </a>
                            <a href="{{ route('penyewaan-lahan.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('penyewaan-lahan*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Penyewaan Lahan
                            </a>
                            <a href="{{ route('umkm.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('umkm*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                UMKM
                            </a>
                            <a href="{{ route('umkm.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('layanan-ekonomi*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Layanan Ekonomi
                            </a>
                            <a href="{{ route('sarpras-kerja.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('sarpras-kerja*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Sarana & Prasarana Kerja
                            </a>
                            <a href="{{ route('usaha-art.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('usaha-art*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Usaha ART
                            </a>
                            <a href="{{ route('layanan-masyarakat.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('layanan-masyarakat*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Layanan Masyarakat
                            </a>
                            <a href="{{ route('bantuan-sosial.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('bantuan-sosial*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Bantuan Sosial
                            </a>
                            <a href="{{ route('konflik-sosial.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('konflik-sosial*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Konflik Sosial
                            </a>
                            <a href="{{ route('sosial-ekonomi.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('sosial-ekonomi*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Sosial Ekonomi
                            </a>
                            <a href="{{ route('ibu-hamil.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('ibu-hamil*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Kualitas Ibu Hamil
                            </a>
                            <a href="{{ route('bayi.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('bayi*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Kualitas Bayi
                            </a>
                            <a href="{{ route('kelahiran.index') }}"
                            class="block px-3 py-1 rounded-md text-sm
                            {{ request()->routeIs('kelahiran*') ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                                Kelahiran
                            </a>
                        </div> --}}
                    </div>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Overlay (mobile) -->
    <div class="fixed inset-0 bg-black bg-opacity-30 z-30 lg:hidden"
         x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak></div>

    <!-- Konten utama -->
    <div class="flex flex-col min-h-screen transition-all duration-300"
         :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">

        <!-- Navbar -->
        <header class="sticky top-0 z-20 bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <!-- Tombol Toggle Sidebar -->
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                    </button>
                    {{-- <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                    </a> --}}
                    <span class="font-semibold text-gray-700">Desa Kaliwungu Kudus</span>
                </div>

                <!-- Profil -->
                <div class="relative">
                    <button @click="settingsOpen = !settingsOpen" class="flex items-center gap-2 focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                             d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div x-show="settingsOpen" @click.outside="settingsOpen = false"
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50" x-cloak>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @isset($header)
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <main class="flex-1 p-6 overflow-y-auto">
            {{ $slot }}
        </main>

        <footer class="text-center py-4 text-sm text-gray-500 bg-white/50 mt-auto">
            Sistem Informasi Desa Kaliwungu © {{ date('Y') }} | Dibuat dengan 💚 oleh Tim Kandang Macan
        </footer>
    </div>

</body>
</html>
