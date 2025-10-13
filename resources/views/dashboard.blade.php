<x-app-layout>
    <div x-data="{ sidebarOpen: false }" class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header Card --}}
            <div class="relative bg-gradient-to-br from-blue-200 to-blue-100 hover:from-blue-200 hover:to-blue-100 border border-blue-200 text-black-800 rounded-3xl p-8 overflow-hidden">
                <div class="relative z-10 max-w-xl">
                    <h2 class="text-2xl font-semibold">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
                    <p class="opacity-90 mt-2">Senang bertemu lagi! Mari pantau data dan aktivitas desa hari ini 🌿</p>
                    <a href="#" class="inline-block mt-4 bg-white text-blue-600 px-6 py-2.5 rounded-xl font-medium hover:bg-blue-50 transition">
                        Lihat Menu Utama
                    </a>
                </div>
                {{-- SVG Ilustrasi (hanya tampil di layar ≥ sm) --}}
                <img src="{{ asset('images/welcome.svg') }}"
                    alt="Welcome Illustration"
                    class="absolute -top-10 right-0 w-72 opacity-95 drop-shadow-lg pointer-events-none hidden sm:block">
            </div>

        {{-- Quick Access Menu --}}
        <div class="mt-10">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">📂 Akses Cepat</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Buat Soal -->
                <a href="#"
                   class="group bg-gradient-to-br from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 border border-blue-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            📘
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">Buat Soal</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Buat dan kelola soal dalam bentuk teks maupun suara.</p>
                    </div>
                </a>

                <!-- Menu Utama -->
                <a href="{{ route('menu.utama') }}"
                   class="group bg-gradient-to-br from-green-100 to-green-50 hover:from-green-200 hover:to-green-100 border border-green-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            📂
                        </div>
                        <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900">Menu Utama</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Kelola data aset keluarga serta aset lahan & tanah.</p>
                    </div>
                </a>

                <!-- Master Data -->
                <a href="{{ route('master.list') }}"
                class="group bg-gradient-to-br from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 border border-blue-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            📊
                        </div>
                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900">Master Data</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Kelola semua master data</p>
                    </div>
                </a>


                <!-- Pengaturan -->
                <a href="{{ route('profile.edit') }}"
                   class="group bg-gradient-to-br from-orange-100 to-orange-50 hover:from-orange-200 hover:to-orange-100 border border-orange-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="bg-orange-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
                            ⚙️
                        </div>
                        <h4 class="text-lg font-semibold text-orange-800 group-hover:text-orange-900">Pengaturan</h4>
                        <p class="text-gray-600 text-sm mt-1 text-center">Atur preferensi dan pengelolaan sistem Anda.</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Statistik Section --}}
        <div class="mt-12 bg-white rounded-2xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">📈 Statistik Data Desa</h3>
                <span class="text-sm text-gray-500" id="updateTime"></span>
            </div>
            <canvas id="desaChart" height="120"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('desaChart').getContext('2d');

                let chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Penduduk', 'Keluarga', 'Aset', 'Lahan'],
                        datasets: [{
                            label: 'Jumlah Data',
                            data: [0, 0, 0, 0],
                        backgroundColor: ['#60a5fa', '#34d399', '#fbbf24', '#f87171']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            async function fetchData() {
                try {
                    const response = await axios.get('/api/statistik-desa');
                    const data = response.data;

                    chart.data.datasets[0].data = [
                        data.penduduk,
                        data.keluarga,
                        data.aset,
                        data.lahan
                    ];
                    chart.update();

                    const now = new Date();
                    document.getElementById('updateTime').textContent =
                        `Terakhir diperbarui: ${now.toLocaleTimeString()}`;
                } catch (error) {
                    console.error('Gagal memuat data statistik:', error);
                }
            }

            // Muat pertama kali
            fetchData();

            // Refresh otomatis setiap 10 detik
            setInterval(fetchData, 10000);
        });
        </script>


    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>
