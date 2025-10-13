<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                    Menu Utama
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Menu Kependudukan -->
                    <a href="{{ route('menu.kependudukan') }}"
                       class="group bg-gradient-to-br from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1 border border-blue-200">
                        <div class="flex flex-col items-center">
                            <div class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.29.534 6.121 1.482M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">Kependudukan</h4>
                            <p class="text-gray-600 text-sm mt-1">Kelola Data Kependudukan</p>
                        </div>
                    </a>

                    <!-- Menu Layanan Umum -->
                    {{-- <a href="{{ route('menu-LayananUmum') }}"
                       class="group bg-gradient-to-br from-green-100 to-green-50 hover:from-green-200 hover:to-green-100 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1 border border-green-200">
                        <div class="flex flex-col items-center">
                            <div class="bg-green-500 text-white p-3 rounded-full mb-3 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2h6v2m-7-4h8V7H6v6z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900">Layanan Umum</h4>
                            <p class="text-gray-600 text-sm mt-1">Kelola Layanan dan Administrasi</p>
                        </div>
                    </a> --}}

                    <!-- Menu Master Data -->
                    <a href="{{ route('menu-master-data') }}"
                       class="group bg-gradient-to-br from-purple-100 to-purple-50 hover:from-purple-200 hover:to-purple-100 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1 border border-purple-200">
                        <div class="flex flex-col items-center">
                            <div class="bg-purple-500 text-white p-3 rounded-full mb-3 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-purple-800 group-hover:text-purple-900">Master Data</h4>
                            <p class="text-gray-600 text-sm mt-1">Kelola Data Master</p>
                        </div>
                    </a>

                    <!-- Menu Daftar Keluarga -->
                    {{-- <a href="{{ route('menu-daftarkeluarga') }}"
                       class="group bg-gradient-to-br from-orange-100 to-orange-50 hover:from-orange-200 hover:to-orange-100 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1 border border-orange-200">
                        <div class="flex flex-col items-center">
                            <div class="bg-orange-500 text-white p-3 rounded-full mb-3 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H7v-2a4 4 0 014-4h1m4-4a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-orange-800 group-hover:text-orange-900">Daftar Keluarga</h4>
                            <p class="text-gray-600 text-sm mt-1">Kelola Data Keluarga</p>
                        </div>
                    </a> --}}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
