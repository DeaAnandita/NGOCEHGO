<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOCEH. NDATA.</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/@tailwindcss/browser"></script>
    <script src="https://cdn.jsdelivr.net/npm/framer-motion@11.0.0/dist/framer-motion.umd.js"></script>
</head>
<body class="bg-[#F4F9FF] min-h-screen flex flex-col items-center justify-center overflow-hidden">

    <!-- Navbar -->
    <header class="w-full flex justify-between items-center px-10 py-5 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
            <h1 class="text-lg font-semibold text-blue-800">NGOCEH</h1>
        </div>
        <a href="{{ route('login') }}" 
           class="bg-white text-blue-700 border border-blue-200 px-4 py-2 rounded-full hover:bg-blue-700 hover:text-white transition duration-300">
            Sign in
        </a>
    </header>

    <!-- Title -->
    <div class="text-center mt-10">
        <h1 class="text-4xl sm:text-5xl font-bold text-blue-900 tracking-wide">NGOCEH. NDATA.</h1>
    </div>

    <!-- Cards -->
    <div class="flex flex-wrap justify-center gap-10 mt-16 px-4 animate-bounce">
        @for ($i = 1; $i <= 3; $i++)
        <a href="#" 
           class="w-72 h-64 bg-white rounded-[2rem] shadow-lg flex flex-col items-center justify-center text-center
                  text-lg font-semibold text-gray-900 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:scale-105">
            <span class="text-xl font-bold mb-2">REPORT DATA</span>
            <p class="text-sm text-gray-500">Klik untuk melihat laporan</p>
        </a>
        @endfor
    </div>

    <!-- Background Curves -->
    <div class="absolute top-0 left-0 w-1/2 h-full bg-[#EAF3F8] rounded-br-[50%] -z-10"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-[#EAF3F8] rounded-bl-[50%] -z-10"></div>

    <!-- Animation CSS -->
    <style>
        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up {
            animation: slideUp 1s ease-out;
        }
    </style>

</body>
</html>
