<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOCEH. NDATA.</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/@tailwindcss/browser"></script>

    <!-- Font modern -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex flex-col font-[Poppins] overflow-x-hidden">

    <!-- Navbar -->
    <header class="flex flex-wrap justify-between items-center px-6 sm:px-10 lg:px-12 py-5 border-b border-gray-200 bg-white/70 backdrop-blur-sm shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo-ngoceh.png') }}" alt="Logo" class="h-10 sm:h-12">
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4 mt-4 sm:mt-0">
            <a href="{{ route('login') }}"
               class="px-5 sm:px-6 py-2 sm:py-2.5 bg-transparent text-lime-700 border border-lime-500 rounded-md font-medium hover:bg-lime-500 hover:text-white transition-all duration-300 shadow-sm text-sm sm:text-base">
               Masuk
            </a>
            <a href="{{ route('register') }}"
               class="px-5 sm:px-6 py-2 sm:py-2.5 bg-blue-800 text-white rounded-md font-medium hover:bg-blue-900 transition-all duration-300 shadow-sm text-sm sm:text-base">
               Daftar
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-6 py-10">
        <div class="max-w-2xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-blue-900 leading-tight mb-4">
                Ngoceh. Ndata. Nyimpen.
            </h1>
            <p class="text-gray-600 text-base sm:text-lg mb-10">
                Platform pendataan keluarga berbasis suara dan data untuk mempermudah pengumpulan informasi kemiskinan di Desa Kaliwungu Kudus.
            </p>
        </div>

        <!-- Report Images -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 mt-4 sm:mt-8 w-full max-w-5xl px-4 sm:px-0">
            @foreach (['12.png', '23.png', '33.png'] as $index => $report)
                <div class="mx-auto {{ $index == 1 ? 'w-64 h-64 sm:w-72 sm:h-72' : 'w-56 h-56 sm:w-64 sm:h-64' }} bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-2 transform transition-all duration-500">
                    <img src="{{ asset('images/'.$report) }}" alt="Report Data" class="w-full h-full object-cover">
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-gray-500 text-xs sm:text-sm py-6 text-center border-t border-gray-100">
        © {{ date('Y') }} NGOCEH GO. All rights reserved.
    </footer>

</body>
</html>
