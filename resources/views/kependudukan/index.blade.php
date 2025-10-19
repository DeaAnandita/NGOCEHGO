<x-app-layout>
    <div x-data="{ sidebarOpen: false }" class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-br from-blue-200 to-blue-100 border border-blue-200 text-black-800 rounded-3xl p-8 overflow-hidden">
            <div class="relative z-10 max-w-xl">
                <h2 class="text-2xl font-semibold">Administrasi Kependudukan</h2>
                <p class="opacity-90 mt-2">Pilih salah satu menu untuk mengelola data kependudukan desa.</p>
            </div>
        </div>

        <div class="mt-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">📂 Pilih Menu</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <a href="{{ route('dasar-keluarga.index') }}"
                   class="group bg-gradient-to-br from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 border border-blue-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            👨‍👩‍👧
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">Menu Keluarga</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Kelola data keluarga berdasarkan Nomor Kartu Keluarga (NO_KK).</p>
                    </div>
                </a>
                <a href="{{ route('penduduk.index') }}"
                   class="group bg-gradient-to-br from-green-100 to-green-50 hover:from-green-200 hover:to-green-100 border border-green-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            👤
                        </div>
                        <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900">Menu Penduduk</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Kelola data penduduk berdasarkan Nomor Induk Kependudukan (NIK).</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>