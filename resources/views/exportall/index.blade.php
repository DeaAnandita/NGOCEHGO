<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-4">

        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 
                      text-sm font-medium transition-colors duration-200 
                      border-b-2 border-transparent hover:border-blue-300 pb-1">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali ke Dashboard
            </a>
        </div>

        {{-- Header Card – Gradient Biru (Sama persis) --}}
        <div class="relative bg-gradient-to-br from-blue-200 to-blue-100 
                    border border-blue-200 text-gray-800 rounded-3xl p-8 overflow-hidden">
            <div class="relative z-10 max-w-2xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl font-semibold">
                    Ekspor Semua Data Keluarga & Penduduk
                </h2>
                <p class="opacity-90 mt-3 text-sm sm:text-base">
                    Unduh data lengkap keluarga dan penduduk dalam format Excel (.xlsx).
                </p>
            </div>
        </div>

        {{-- Pilih Tabel --}}
        <div class="mt-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                Pilih Menu
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- TABEL KELUARGA --}}
                <a href="{{ route('export.all.keluarga') }}"
                   class="group bg-gradient-to-br from-blue-100 to-blue-50 
                          hover:from-blue-200 hover:to-blue-100 
                          border border-blue-200 p-6 rounded-2xl shadow 
                          transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md 
                                      group-hover:scale-110 transition">
                            <x-heroicon-o-user-group class="w-7 h-7" />
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">
                            Data Keluarga
                        </h4>
                        <p class="text-gray-600 text-sm mt-2">
                            Tabel keluarga berdasarkan Nomor Kartu Keluarga (No. KK).
                        </p>
                        <button class="mt-4 w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg 
                                      hover:bg-blue-700 transition text-sm">
                            Download Excel
                        </button>
                    </div>
                </a>

                {{-- TABEL PENDUDUK --}}
                <a href="{{ route('dasar-penduduk.index') }}"
                   class="group bg-gradient-to-br from-green-100 to-green-50 
                          hover:from-green-200 hover:to-green-100 
                          border border-green-200 p-6 rounded-2xl shadow 
                          transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-green-500 text-white p-3 rounded-full mb-3 shadow-md 
                                      group-hover:scale-110 transition">
                            <x-heroicon-o-user class="w-7 h-7" />
                        </div>
                        <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900">
                            Data Penduduk
                        </h4>
                        <p class="text-gray-600 text-sm mt-2">
                            Tabel individu berdasarkan Nomor Induk Kependudukan (NIK).
                        </p>
                        <button class="mt-4 w-full bg-green-600 text-white font-medium py-2.5 rounded-lg 
                                      hover:bg-green-700 transition text-sm">
                            Download Excel
                        </button>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>