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
<body class="font-[Poppins] text-gray-900 antialiased min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
    style="background-color: rgb(148, 194, 255)">

    <div class="w-full sm:max-w-md mx-auto px-6 py-6 bg-white/95 backdrop-blur-sm shadow-xl rounded-2xl">
        <div class="flex flex-col items-center mb-6">
            <a href="{{ route('login') }}">
                <img src="{{ asset('images/ijo.png') }}" alt="Logo" class="h-20 w-auto">
            </a>
        </div>

        {{ $slot }}
    </div>

</body>
</html>
