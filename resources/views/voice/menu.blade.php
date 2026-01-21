<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-4">

        {{-- 🔙 Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 
                      text-sm font-medium transition-colors duration-200 
                      border-b-2 border-transparent hover:border-blue-300 pb-1">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali ke Dashboard
            </a>
        </div>

        {{-- 🧊 Header Card – Gradient seperti contoh --}}
        <div
            class="relative bg-gradient-to-br from-blue-200 to-blue-100 
                    border border-blue-200 text-gray-800 rounded-3xl p-8 overflow-hidden">
            <div class="relative z-10 max-w-2xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl font-semibold">
                    Input Data dengan Suara
                </h2>
                <p class="opacity-90 mt-3 text-sm sm:text-base">
                    Pilih jenis data yang ingin kamu isi menggunakan suara.
                    Sistem akan membimbingmu langkah demi langkah —
                    <span class="font-medium text-blue-700">mudah, cepat, dan tanpa ribet</span>.
                </p>
            </div>
        </div>

        {{-- 📂 Pilih Menu --}}
        <div class="mt-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                Pilih Menu
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- 🎧 MENU KELUARGA --}}
                <a href="{{ route('voice.keluarga.index') }}"
                    class="group bg-gradient-to-br from-blue-100 to-blue-50 
                          hover:from-blue-200 hover:to-blue-100 
                          border border-blue-200 p-6 rounded-2xl shadow 
                          transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md 
                                      group-hover:scale-110 transition">
                            <x-heroicon-o-user-group class="w-7 h-7" />
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">
                            Input Data Keluarga
                        </h4>
                        <p class="text-gray-600 text-sm mt-2">
                            Isi data dasar, prasarana, dan aset keluarga dengan panduan suara interaktif.
                        </p>
                        <button
                            class="mt-4 w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg 
                                      hover:bg-blue-700 transition text-sm">
                            Mulai Sekarang
                        </button>
                    </div>
                </a>

                {{-- 🎙️ MENU PENDUDUK --}}
                <a href="{{ route('voice.penduduk.index') }}"
                    class="group bg-gradient-to-br from-green-100 to-green-50 
                          hover:from-green-200 hover:to-green-100 
                          border border-green-200 p-6 rounded-2xl shadow 
                          transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="bg-green-500 text-white p-3 rounded-full mb-3 shadow-md 
                                      group-hover:scale-110 transition">
                            <x-heroicon-o-user class="w-7 h-7" />
                        </div>
                        <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900">
                            Input Data Penduduk
                        </h4>
                        <p class="text-gray-600 text-sm mt-2">
                            Rekam data individu per anggota keluarga berdasarkan nomor KK dengan panduan suara.
                        </p>
                        <button
                            class="mt-4 w-full bg-green-600 text-white font-medium py-2.5 rounded-lg 
                                      hover:bg-green-700 transition text-sm">
                            Mulai Sekarang
                        </button>
                    </div>
                </a>
                {{-- 🎙️ MENU KELEMBAGAAN --}}
                <a href="{{ route('voice.kelembagaan.index') }}"
                    class="group bg-gradient-to-br from-emerald-100 to-emerald-50 
          hover:from-emerald-200 hover:to-emerald-100 
          border border-emerald-200 p-6 rounded-2xl shadow 
          transition-all duration-200 hover:-translate-y-1">

                    <div class="flex flex-col items-center text-center">

                        <div
                            class="bg-emerald-600 text-white p-3 rounded-full mb-3 shadow-md 
                    group-hover:scale-110 transition">
                            <x-heroicon-o-building-office-2 class="w-7 h-7" />
                        </div>

                        <h4 class="text-lg font-semibold text-emerald-800 group-hover:text-emerald-900">
                            Input Kelembagaan
                        </h4>

                        <p class="text-gray-600 text-sm mt-2">
                            Input data kegiatan, anggaran, pencairan, dan LPJ kelembagaan menggunakan suara.
                        </p>

                        <button
                            class="mt-4 w-full bg-emerald-600 text-white font-medium py-2.5 rounded-lg 
                       hover:bg-emerald-700 transition text-sm">
                            Mulai Sekarang
                        </button>

                    </div>
                </a>
                {{-- 📄 MENU PELAYANAN SURAT --}}
                <a href="{{ route('voice.pelayanan.index') }}"
                    class="group bg-gradient-to-br from-indigo-100 to-indigo-50 
          hover:from-indigo-200 hover:to-indigo-100 
          border border-indigo-200 p-6 rounded-2xl shadow 
          transition-all duration-200 hover:-translate-y-1">

                    <div class="flex flex-col items-center text-center">

                        <div
                            class="bg-indigo-600 text-white p-3 rounded-full mb-3 shadow-md 
                    group-hover:scale-110 transition">
                            <x-heroicon-o-document-text class="w-7 h-7" />
                        </div>

                        <h4 class="text-lg font-semibold text-indigo-800 group-hover:text-indigo-900">
                            Pelayanan Surat
                        </h4>

                        <p class="text-gray-600 text-sm mt-2">
                            Ajukan surat keterangan, domisili, usaha, dan lainnya menggunakan panduan suara.
                        </p>

                        <button
                            class="mt-4 w-full bg-indigo-600 text-white font-medium py-2.5 rounded-lg 
                   hover:bg-indigo-700 transition text-sm">
                            Mulai Sekarang
                        </button>

                    </div>
                </a>
                {{-- 🏗️ MENU ADMINISTRASI PEMBANGUNAN --}}
                <a href="{{ route('voice.pembangunan.index') }}"
                    class="group bg-gradient-to-br from-orange-100 to-orange-50 
          hover:from-orange-200 hover:to-orange-100 
          border border-orange-200 p-6 rounded-2xl shadow 
          transition-all duration-200 hover:-translate-y-1">

                    <div class="flex flex-col items-center text-center">

                        <div
                            class="bg-orange-600 text-white p-3 rounded-full mb-3 shadow-md 
                group-hover:scale-110 transition">
                            <x-heroicon-o-building-office-2 class="w-7 h-7" />
                        </div>

                        <h4 class="text-lg font-semibold text-orange-800 group-hover:text-orange-900">
                            Administrasi Pembangunan
                        </h4>

                        <p class="text-gray-600 text-sm mt-2">
                            Input data proyek, bantuan, dan kader pembangunan desa menggunakan panduan suara.
                        </p>

                        <button
                            class="mt-4 w-full bg-orange-600 text-white font-medium py-2.5 rounded-lg 
                hover:bg-orange-700 transition text-sm">
                            Mulai Sekarang
                        </button>

                    </div>
                </a>
                {{-- 🎤 MENU ADMINISTRASI UMUM --}}
                <a href="{{ route('voice.admin-umum.index') }}"
                    class="group bg-gradient-to-br from-sky-100 to-sky-50 
          hover:from-sky-200 hover:to-sky-100 
          border border-sky-200 p-6 rounded-2xl shadow 
          transition-all duration-200 hover:-translate-y-1">

                    <div class="flex flex-col items-center text-center">

                        <div
                            class="bg-sky-600 text-white p-3 rounded-full mb-3 shadow-md 
                group-hover:scale-110 transition">
                            <x-heroicon-o-microphone class="w-7 h-7" />
                        </div>

                        <h4 class="text-lg font-semibold text-sky-800 group-hover:text-sky-900">
                            Administrasi Umum
                        </h4>

                        <p class="text-gray-600 text-sm mt-2">
                            Input buku peraturan, keputusan, aparat, tanah, agenda, ekspedisi, dan inventaris via suara.
                        </p>

                        <button
                            class="mt-4 w-full bg-sky-600 text-white font-medium py-2.5 rounded-lg 
                hover:bg-sky-700 transition text-sm">
                            Mulai Sekarang
                        </button>

                    </div>
                </a>

            </div>
        </div>

    </div>
</x-app-layout>
