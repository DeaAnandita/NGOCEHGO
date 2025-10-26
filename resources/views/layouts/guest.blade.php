<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NGOCEH GO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-[Poppins] text-gray-900 antialiased min-h-screen flex items-center justify-center bg-blue-100">

    <!-- Wrapper kotak putih -->
    <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-xl overflow-hidden max-w-5xl w-full mx-6">
        
        <!-- Kiri: Ilustrasi -->
        <div class="hidden md:flex w-1/2 bg-blue-50 items-center justify-center p-8">
            <img src="{{ asset('images/logo-ngoceh.png') }}" 
                 alt="Ilustrasi NGOCEH GO" 
                 class="max-w-sm">
        </div>

        <!-- Kanan: Slot Form -->
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <img src="{{ asset('images/ijo.png') }}" alt="Logo NGOCEH GO" class="h-16 w-auto mb-3">
                </a>
            </div>

            {{ $slot }}
        </div>

    </div>

</body>
</html>
