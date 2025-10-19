<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sistem Informasi Aset Desa') }}</title>

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="antialiased relative min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
      style="background-image: url('{{ asset('images/bgdb.png') }}');">

    {{-- Overlay lembut agar teks tetap jelas --}}
    <div class="absolute inset-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6 md:px-12 py-16 gap-10">
        {{-- Bagian kiri: teks & tombol --}}
        <div class="md:w-1/2 text-center md:text-left">
            <div class="flex justify-center md:justify-start">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 w-auto mx-auto md:mx-0 mb-6">
                <h2 class="sr-only">Desa Kaliwungu Kudus</h2>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4 drop-shadow-sm" style="color: #4890CF;">
                Selamat Datang di <span style="color: #064175;">Sistem Pendataan Survey Kemiskinan</span>
            </h1>
            <p class="text-gray-700 text-lg mb-8 leading-relaxed">
                Platform untuk memantau dan mengelola data kemiskinan di Desa Kaliwungu Kudus secara efisien dan transparan.
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center md:justify-start">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-white font-semibold rounded shadow-md hover:bg-indigo-700 transition"
                   style="background-color: #064175;">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 font-semibold rounded hover:bg-indigo-600 hover:text-white transition"
                   style="border-color: #064175; text-color: #064175; border-width: 2px;">
                    Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- Footer kecil --}}
    {{-- <footer class="absolute bottom-4 w-full text-center text-gray-600 text-sm z-10 font-medium">
        &copy; {{ date('Y') }} Desa Kaliwungu Kudus — All rights reserved.
    </footer> --}}

</body>
</html>
