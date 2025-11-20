<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NGOCEH GO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('styles')

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
    <!-- Sidebar Utama -->
    <aside id="sidebar"
           x-bind:class="sidebarOpen ? 'ml-0' : '-ml-64'"
           class="w-64 bg-white shadow-md h-screen fixed transition-all duration-300 z-40">
        <div class="flex flex-col h-full">
            <!-- Header Sidebar -->
            <div class="px-6 py-4 bg-white-500 text-white font-bold text-xl flex items-center justify-center">
                 <img src="{{ asset('images/logo-ngoceh.png') }}" class="w-30 h-17">
            </div>

            <!-- Menu List -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <x-heroicon-o-home class="w-5 h-5" /> 
                    <span>Dashboard</span>
                </a>
                <!-- Menu Kependudukan dengan Submenu -->
                <div x-data="{ open: {{ request()->routeIs('dasar-keluarga.*') || request()->routeIs('dasar-penduduk.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="flex items-center justify-between w-full gap-3 px-4 py-2 rounded-lg  text-sm 
                            {{ request()->routeIs('dasar-keluarga.*') || request()->routeIs('dasar-penduduk.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-user-group class="w-5 h-5" />
                            <span>Kependudukan</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <!-- Submenu -->
                    <div x-show="open" x-collapse class="mt-1 space-y-1 pl-10">
                        <a href="{{ route('dasar-keluarga.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm
                        {{ request()->routeIs('dasar-keluarga.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <x-heroicon-o-user class="w-5 h-5" />
                            <span>Data Keluarga</span>
                        </a>
                        <a href="{{ route('dasar-penduduk.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm
                        {{ request()->routeIs('dasar-penduduk.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <x-heroicon-o-user class="w-5 h-5" />
                            <span>Data Penduduk</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('master.list') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                {{ request()->routeIs('master.list') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <x-heroicon-o-inbox-stack class="w-5 h-5" /> 
                <span>Master Data</span>
                </a>
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
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <img src="{{ asset('images/ijo.png') }}" alt="Logo" class="h-10 w-auto object-contain" />
                            <span class="font-semibold text-gray-700 text-left">"Ngoceh GO" Desa Kaliwungu Kudus</span>
                        </div>
                    </button>
                </div>

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

            <!-- PROGRESS BAR (HANYA DI VOICE) -->
            {{ $progresskeluarga ?? '' }}
            {{ $progresspenduduk ?? '' }}

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>

        <footer class="text-center py-4 text-sm text-gray-500 bg-white/50 mt-auto">
            Sistem Informasi Desa Kaliwungu © {{ date('Y') }} | Dibuat dengan 💚 oleh Tim Kandang Macan
        </footer>
    </div>
    @stack('scripts')

</body>
</html>