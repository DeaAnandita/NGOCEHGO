<x-app-layout>
    <!-- Background image dengan scrollable content -->
    <div class="min-h-screen bg-blue-70 bg-cover bg-center bg-no-repeat">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            <!-- Header Welcome -->
                <div class="bg-gradient-to-r from-blue-500/95 to-blue-300/95 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden mb-8 text-white">
                    <div class="px-6 py-10 sm:px-8 sm:py-12 lg:px-10 lg:py-14">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight">
                            Selamat Datang, {{ Auth::user()->name }}
                        </h1>
                        <p class="mt-4 text-base sm:text-lg lg:text-lg opacity-90">
                            Pantau data dan aktivitas desa secara realtime.
                        </p>
                    </div>
                </div>

            <!-- Akses Cepat -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 drop-shadow-md">Akses Cepat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- ... (sama seperti sebelumnya) ... -->
                    <a href="{{ route('menu.kependudukan') }}" class="block bg-white/90 backdrop-blur-sm rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg hover:bg-white transition">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Data Kependudukan</h3>
                                <p class="text-sm text-gray-600">Kelola data Kependudukan.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('voice.menu') }}" class="block bg-white/90 backdrop-blur-sm rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg hover:bg-white transition">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Input Data (Voice)</h3>
                                <p class="text-sm text-gray-600">Input data dengan suara.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('master.list') }}" class="block bg-white/90 backdrop-blur-sm rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg hover:bg-white transition">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Soal dan Jawaban</h3>
                                <p class="text-sm text-gray-600">Panduan data desa.</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('menu.exportall') }}" class="block bg-white/90 backdrop-blur-sm rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg hover:bg-white transition">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Download Excel</h3>
                                <p class="text-sm text-gray-600">Unduh data lengkap.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Statistik Umum Desa (kiri) -->
                <div class="flex flex-col">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 drop-shadow-md">Statistik Umum Desa</h2>
                    <div class="space-y-6">
                        <!-- ... (4 card statistik sama seperti sebelumnya) ... -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Total Keluarga (KK)</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2" id="jumlahKeluarga">0</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Total Penduduk</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2" id="jumlahPenduduk">0</p>
                                </div>
                                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Laki-Laki</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2" id="jumlahLaki">0</p>
                                </div>
                                <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Perempuan</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2" id="jumlahPerempuan">0</p>
                                </div>
                                <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts (kanan): 4 chart 2x2, judul di luar -->
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 drop-shadow-md">Visualisasi Data Desa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Chart 1: Penduduk per Dusun -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Penduduk per Dusun</h3>
                            <div class="flex-1 min-h-0 relative">
                                <canvas id="dusunChart"></canvas>
                            </div>
                        </div>

                        <!-- Chart 2: Kelompok Usia -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Kelompok Usia</h3>
                            <div class="flex-1 min-h-0 relative">
                                <canvas id="usiaChart"></canvas>
                            </div>
                        </div>

                        <!-- Chart 4: Agama -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-md border border-gray-200 p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Agama</h3>
                            <div class="flex-1 min-h-0 relative">
                                <canvas id="agamaChart"></canvas>
                            </div>
                        </div>

                        <!-- 3. Status Perkawinan -->
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Perkawinan</h3>
                            <div class="flex-1 min-h-0">
                                <canvas id="statusKawinChart"></canvas>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>

            <!-- Update Time -->
            <div class="mt-8 text-center text-sm text-gray-700 drop-shadow-md" id="updateTime">Memuat...</div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jumlahKeluargaEl = document.getElementById('jumlahKeluarga');
            const jumlahPendudukEl = document.getElementById('jumlahPenduduk');
            const jumlahLakiEl = document.getElementById('jumlahLaki');
            const jumlahPerempuanEl = document.getElementById('jumlahPerempuan');
            const updateTimeEl = document.getElementById('updateTime');

            let dusunChart, usiaChart, statusKawinChart, agamaChart;

            function countUp(el, target) {
                let start = 0;
                const duration = 1800;
                const increment = target / (duration / 16);
                const timer = setInterval(() => {
                    start += increment;
                    if (start >= target) {
                        el.textContent = Math.floor(target).toLocaleString('id-ID');
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(start).toLocaleString('id-ID');
                    }
                }, 16);
            }

            async function fetchStats() {
                try {
                    const response = await axios.get('/api/statistik-desa');
                    const data = response.data;

                    // Update angka
                    countUp(jumlahKeluargaEl, data.keluarga || 0);
                    countUp(jumlahPendudukEl, data.penduduk || 0);
                    countUp(jumlahLakiEl, data.laki || 0);
                    countUp(jumlahPerempuanEl, data.perempuan || 0);

                    // Chart 1: Dusun
                    if (dusunChart) dusunChart.destroy();
                    const ctx1 = document.getElementById('dusunChart').getContext('2d');
                    dusunChart = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: data.dusun?.map(d => d.nama.length > 12 ? d.nama.substr(0,12)+'...' : d.nama) || [],
                            datasets: [{ data: data.dusun?.map(d => d.jumlah) || [], backgroundColor: '#3B82F6', borderRadius: 6 }]
                        },
                        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, title: { display: false } }, scales: { x: { beginAtZero: true } } }
                    });

                    // Chart 2: Usia
                    if (usiaChart) usiaChart.destroy();
                    const ctx2 = document.getElementById('usiaChart').getContext('2d');
                    usiaChart = new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: ['Anak (0-14)', 'Produktif (15-64)', 'Lansia (65+)'],
                            datasets: [{ data: [data.usia.anak || 0, data.usia.produktif || 0, data.usia.lansia || 0], backgroundColor: ['#10B981', '#3B82F6', '#F59E0B'], borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' }, title: { display: false } } }
                    });

                    // Chart 3: Status Perkawinan (baru)
                    
                    if (statusKawinChart) statusKawinChart.destroy();
                    const ctx3 = document.getElementById('statusKawinChart').getContext('2d');
                    statusKawinChart = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: data.status_kawin?.map(d => d.nama.length > 12 ? d.nama.substr(0,12)+'...' : d.nama) || [],
                            datasets: [{ data: data.status_kawin?.map(d => d.jumlah) || [], backgroundColor: '#de3a7f', borderRadius: 6 }]
                        },
                        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, title: { display: false } }, scales: { x: { beginAtZero: true } } }
                    });

                    // Chart 4: Agama
                    if (agamaChart) agamaChart.destroy();
                    const ctx4 = document.getElementById('agamaChart').getContext('2d');
                    agamaChart = new Chart(ctx4, {
                        type: 'doughnut',
                        data: {
                            labels: data.agama?.map(a => a.nama) || [],
                            datasets: [{ data: data.agama?.map(a => a.jumlah) || [], backgroundColor: ['#10B981','#3B82F6','#8B5CF6','#F59E0B','#EF4444','#6B7280'], borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' }, title: { display: false } } }
                    });

                    const now = new Date();
                    updateTimeEl.textContent = `Terakhir diperbarui: ${now.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}`;

                } catch (err) {
                    console.error(err);
                    updateTimeEl.textContent = 'Gagal memuat data';
                }
            }

            fetchStats();
            setInterval(fetchStats, 60000);
        });
    </script>
</x-app-layout>