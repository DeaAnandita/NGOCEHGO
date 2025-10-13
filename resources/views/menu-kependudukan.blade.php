<x-app-layout>
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            @php
                $categories = [
                    'Data Keluarga' => [
                        ['title' => 'Data Dasar Keluarga', 'color' => 'blue', 'desc' => 'Kelola data dasar keluarga', 'route' => 'dasar-keluarga.index'],
                        ['title' => 'Anggota Keluarga', 'color' => 'green', 'desc' => 'Kelola data anggota keluarga', 'route' => 'anggota-keluarga.index'],
                        ['title' => 'Bangun Keluarga', 'color' => 'blue', 'desc' => 'Kelola data bangun keluarga', 'route' => 'bangun-keluarga.index'],
                        ['title' => 'Kesejahteraan Keluarga', 'color' => 'green', 'desc' => 'Kelola data kesejahteraan keluarga', 'route' => 'kesejahteraan-keluarga.index'],
                    ],
                    'Aset & Ekonomi' => [
                        ['title' => 'Aset Keluarga', 'color' => 'blue', 'desc' => 'Kelola data aset keluarga', 'route' => 'aset-keluarga.index'],
                        ['title' => 'Aset Lahan & Tanah', 'color' => 'green', 'desc' => 'Kelola data aset lahan dan tanah', 'route' => 'aset-lahan.index'],
                        ['title' => 'Aset Ternak & Perikanan', 'color' => 'blue', 'desc' => 'Kelola data aset ternak & perikanan', 'route' => 'aset-ternak.index'],
                        ['title' => 'Penyewaan Lahan', 'color' => 'green', 'desc' => 'Kelola data penyewaan lahan ternak & perikanan', 'route' => 'penyewaan-lahan.index'],
                        ['title' => 'UMKM', 'color' => 'blue', 'desc' => 'Kelola data UMKM', 'route' => 'umkm.index'],
                        ['title' => 'Layanan Ekonomi', 'color' => 'green', 'desc' => 'Kelola data layanan ekonomi', 'route' => 'umkm.index'],
                        ['title' => 'Sarana & Prasarana Kerja', 'color' => 'blue', 'desc' => 'Kelola data sarpras kerja', 'route' => 'sarpras-kerja.index'],
                        ['title' => 'Usaha ART', 'color' => 'green', 'desc' => 'Kelola data usaha anggota rumah tangga', 'route' => 'usaha-art.index'],
                    ],
                    'Pelayanan & Sosial' => [
                        ['title' => 'Layanan Masyarakat', 'color' => 'blue', 'desc' => 'Kelola data layanan masyarakat', 'route' => 'layanan-masyarakat.index'],
                        ['title' => 'Bantuan Sosial', 'color' => 'green', 'desc' => 'Kelola data bantuan sosial', 'route' => 'bantuan-sosial.index'],
                        ['title' => 'Konflik Sosial', 'color' => 'blue', 'desc' => 'Kelola data konflik sosial', 'route' => 'konflik-sosial.index'],
                        ['title' => 'Sosial Ekonomi', 'color' => 'green', 'desc' => 'Kelola data sosial ekonomi', 'route' => 'sosial-ekonomi.index'],
                    ],
                    'Kesehatan' => [
                        ['title' => 'Kualitas Ibu Hamil', 'color' => 'blue', 'desc' => 'Kelola data ibu hamil', 'route' => 'ibu-hamil.index'],
                        ['title' => 'Kualitas Bayi', 'color' => 'green', 'desc' => 'Kelola data bayi', 'route' => 'bayi.index'],
                        ['title' => 'Kelahiran', 'color' => 'blue', 'desc' => 'Kelola data kelahiran', 'route' => 'kelahiran.index'],
                    ],
                ];
            @endphp

            <!-- Loop kategori -->
            @foreach ($categories as $category => $menus)
                <div class="mb-10">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">{{ $category }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                        @foreach ($menus as $menu)
                            <a href="{{ route($menu['route']) }}"
                               class="group bg-white shadow-md rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 p-6 hover:-translate-y-1 hover:border-{{ $menu['color'] }}-300">
                                <div class="flex flex-col items-start">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-{{ $menu['color'] }}-100 text-{{ $menu['color'] }}-600 mb-4 text-2xl">
                                        📂
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-{{ $menu['color'] }}-700 mb-2">
                                        {{ $menu['title'] }}
                                    </h3>
                                    <p class="text-gray-500 text-sm leading-snug">{{ $menu['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
