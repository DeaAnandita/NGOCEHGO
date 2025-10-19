<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Administrasi Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex min-h-screen text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-green-700 text-white flex flex-col">
        <!-- Logo / Header -->
        <div class="p-5 text-2xl font-bold border-b border-green-600">
            Desa Kaliwungu
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <p class="uppercase text-green-300 font-semibold mb-2">Modul Data</p>

            <a href="{{ route('administrasi.keluarga.index') }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/keluarga*') ? 'bg-green-600' : '' }}">
                👪 Data Keluarga
            </a>

            <a href="{{ route('administrasi.prasarana.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/prasarana*') ? 'bg-green-600' : '' }}">
                🏠 Prasarana Dasar
            </a>

            <a href="{{ route('administrasi.asetkeluarga.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/asetkeluarga*') ? 'bg-green-600' : '' }}">
                💼 Aset Keluarga
            </a>

            <a href="{{ route('administrasi.asetlahan.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/asetlahan*') ? 'bg-green-600' : '' }}">
                🌾 Aset Lahan & Tanah
            </a>

            <a href="{{ route('administrasi.ternak.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/ternak*') ? 'bg-green-600' : '' }}">
                🐄 Aset Ternak
            </a>

            <a href="{{ route('administrasi.perikanan.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/perikanan*') ? 'bg-green-600' : '' }}">
                🐟 Aset Perikanan
            </a>

            <a href="{{ route('administrasi.sejahtera.index') ?? '#' }}"
                class="block py-2 px-4 rounded-md hover:bg-green-600 transition-all duration-150 {{ request()->is('administrasi/sejahtera*') ? 'bg-green-600' : '' }}">
                🌿 Kesejahteraan Keluarga
            </a>
        </nav>

        <!-- Tombol kembali -->
        <div class="p-4 border-t border-green-600">
            <a href="{{ route('dashboard') ?? '#' }}"
               class="block w-full py-2 px-4 text-center bg-green-800 rounded hover:bg-green-900 transition">
               ← Kembali ke Dashboard
            </a>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-8 overflow-y-auto">
        <!-- Judul Halaman -->
        <header class="mb-6">
            <h1 class="text-2xl font-bold text-green-700">@yield('title')</h1>
            @hasSection('subtitle')
                <p class="text-gray-500 mt-1">@yield('subtitle')</p>
            @endif
        </header>

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Konten Dinamis -->
        @yield('content')
    </main>

</body>
</html>
