<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOCEH GO - Pendataan Keluarga Desa Kaliwungu</title>
    @vite('resources/css/app.css')

    <!-- Font resmi: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <meta name="description" content="Platform resmi pendataan keluarga berbasis suara dan data untuk program penanggulangan kemiskinan Desa Kaliwungu, Kecamatan Kaliwungu, Kabupaten Kudus.">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col font-[Poppins] text-gray-800">

    <!-- Navbar responsive dengan hamburger menu (menggunakan Alpine.js sederhana atau checkbox hack – di sini saya gunakan pure Tailwind + checkbox untuk no JS) -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-4 sm:py-5">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo-ngoceh.png') }}" alt="Logo Resmi NGOCEH GO Desa Kaliwungu" class="h-10 sm:h-12">
                </div>

                <!-- Desktop Buttons -->
                <nav class="hidden md:flex items-center gap-3 lg:gap-4">
                    <a href="{{ route('login') }}"
                       class="px-5 py-2.5 bg-transparent text-lime-700 border border-lime-500 rounded-md font-medium hover:bg-lime-500 hover:text-white transition-all duration-300 shadow-sm text-sm lg:text-base">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 bg-blue-800 text-white rounded-md font-medium hover:bg-blue-900 transition-all duration-300 shadow-sm text-sm lg:text-base">
                        Daftar
                    </a>
                </nav>

                <!-- Mobile Hamburger -->
                <div class="md:hidden">
                    <label for="menu-toggle" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (checkbox hack) -->
        <input type="checkbox" id="menu-toggle" class="hidden peer">
        <div class="md:hidden hidden peer-checked:block bg-white border-t border-gray-200">
            <nav class="flex flex-col gap-4 px-6 py-6">
                <a href="{{ route('login') }}"
                   class="px-5 py-2.5 bg-transparent text-lime-700 border border-lime-500 rounded-md font-medium text-center hover:bg-lime-500 hover:text-white transition-all duration-300">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2.5 bg-blue-800 text-white rounded-md font-medium text-center hover:bg-blue-900 transition-all duration-300">
                    Daftar
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section – lebih responsif -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-12 pt-28 pb-16 md:pt-32 md:pb-20">
        <div class="max-w-4xl w-full">
            <h1 class="text-2xl sm:text-2xl md:text-4xl lg:text-5xl font-bold text-blue-900 leading-tight mb-6">
                Ngoceh. Ndata. Nyimpen.
            </h1>
            <p class="text-lg sm:text-xl md:text-xl text-gray-700 mb-4 font-medium">
                Sistem Pendataan Kemiskinan Berbasis Suara dan Data
            </p>
            <p class="text-base sm:text-lg md:text-lg text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed">
                Platform resmi untuk pengumpulan data kependudukan dan pemetaan kemiskinan ekstrem di Desa Kaliwungu 
                berbasis input suara dan formulir terstruktur.
            </p>

            <!-- Contoh Laporan – grid responsif -->
            <div class="w-full max-w-6xl mt-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach (['12.png', '23.png', '33.png'] as $report)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="aspect-[4/3] bg-gray-100">
                                <img 
                                    src="{{ asset('images/'.$report) }}" 
                                    alt="Contoh laporan data pendataan keluarga" 
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-500 text-white py-8 sm:py-10 text-center mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            <p class="text-sm">
                © {{ date('Y') }} NGOCEH GO - Pendataan Kemiskinan Berbasis Suara dan Data. All rights reserved.
            </p>
            <p class="text-xs mt-2 opacity-90">
                Kontak: Kantor Balai Desa Kaliwungu | Email: info@desa-kaliwungu.go.id
            </p>
        </div>
    </footer>

</body>
</html>