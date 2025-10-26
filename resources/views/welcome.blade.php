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
<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex flex-col font-[Poppins] overflow-hidden">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-12 py-6 border-b border-gray-200 bg-white/70 backdrop-blur-sm shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo-ngoceh.png') }}" alt="Logo" class="h-10">
        </div>
        <div class="flex gap-4">
            <a href="{{ route('login') }}"
               class="px-6 py-2.5 bg-transparent text-lime-700 border border-lime-500 rounded-lg font-medium hover:bg-lime-500 hover:text-white transition-all duration-300 shadow-sm">
               Masuk
            </a>
            <a href="{{ route('register') }}"
               class="px-6 py-2.5 bg-blue-800 text-white rounded-lg font-medium hover:bg-blue-900 transition-all duration-300 shadow-sm">
               Daftar
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-6">
        <div class="max-w-2xl">
            <h1 class="text-5xl font-bold text-blue-900 leading-tight mb-4">Ngoceh. Ndata. Nyimpen.</h1>
            <p class="text-gray-600 text-lg mb-10">
                Platform pendataan keluarga berbasis suara dan data untuk mempermudah pengumpulan informasi kemiskinan di Desa Kaliwungu Kudus.
            </p>
        </div>

        <!-- Report Images -->
        <div class="flex justify-center gap-8 mt-2">
            @foreach (['12.png', '23.png', '33.png'] as $index => $report)
                <div class="{{ $index == 1 ? 'w-64 h-64' : 'w-56 h-56' }} bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-2 transform transition-all duration-500">
                    <img src="{{ asset('images/'.$report) }}" alt="Report Data" class="w-full h-full object-cover">
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-gray-500 text-sm py-4 text-center">
        © {{ date('Y') }} NGOCEH GO. All rights reserved.
    </footer>

    <!-- Animations -->
    <style>
        html, body {
            height: 100%;
        }
    </style>
</body>
</html>
