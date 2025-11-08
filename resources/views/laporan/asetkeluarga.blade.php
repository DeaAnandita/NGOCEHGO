<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Aset Keluarga</title>
    <style>
        @page { margin: 15mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11pt; line-height: 1.6; margin: 0; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { font-size: 24pt; color: #1e40af; margin: 0 0 15px; font-weight: bold; text-align: center; }
        h2 { font-size: 18pt; color: #1e40af; margin: 35px 0 12px; border-bottom: 3px solid #6366f1; padding-bottom: 6px; }
        h3 { font-size: 14pt; color: #1e40af; margin: 20px 0 10px; }
        .flex { display: flex; gap: 8px; justify-content: space-between; }
        .flex > div { flex: 1; min-width: 300px; }
        .card { background: #f8fafc; padding: 8px 10px; border-radius: 6px; border-left: 5px solid #6366f1; line-height: 1.4; }
        .highlight { font-size: 48pt; font-weight: bold; color: #1e40af; margin: 10px 0 5px; line-height: 1; }
        .badge { display: inline-block; width: 14px; height: 14px; border-radius: 50%; margin-right: 8px; vertical-align: middle; }
        .baik { background: #10b981; }
        .sedang { background: #f59e0b; }
        .buruk { background: #ef4444; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10pt; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #eef2ff; font-weight: bold; text-align: center; }
        .catatan { background: #e0e7ff; padding: 18px; border-radius: 10px; font-size: 10pt; margin: 25px 0; border-left: 5px solid #6366f1; }
        .legend { text-align: center; margin: 12px 0; font-size: 10pt; }
        .legend span { margin: 0 15px; }
        .footer { text-align: center; color: #6b7280; font-size: 9pt; margin-top: 60px; padding-top: 15px; border-top: 1px solid #e5e7eb; }
        img.chart { max-width: 100%; height: auto; display: block; margin: 15px auto; }
        .img-rapat { width: 100%; max-width: 380px; margin: 20px auto; display: block; border-radius: 12px; }
        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .page-break { page-break-before: always; }
        .mb-0 { margin-bottom: 0; }
        .mt-0 { margin-top: 0; }
        .small { font-size: 10pt; color: #6b7280; }
        .underline { text-decoration: underline; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }
        .no-border { border: none; }
        .pie-container { text-align: center; margin: 30px 0; }
        .bar-container { margin: 30px 0; }
        
    </style>

</head>
<body>

<div class="container">

    <!-- HALAMAN 1 -->
    <h1>Laporan Analisis Aset Keluarga Desa Kaliwungu</h1>
    <p class="text-justify" style="margin-bottom:20px;">
        Dokumen ini menyajikan struktur dan komponen kunci dari “Laporan Analisis Aset Keluarga” yang dirancang untuk membantu perangkat Desa Kaliwungu, Kabupaten Kudus, dalam pengambilan keputusan berbasis data. Laporan ini memberikan gambaran komprehensif mengenai profil aset dan tingkat kesejahteraan keluarga di desa, diolah secara otomatis oleh sistem.
    </p>
    <p class="text-justify" style="margin-bottom:25px;">
        Laporan ini terdiri dari tujuh bagian utama, mulai dari Header Laporan hingga Kesimpulan Otomatis dan Footer Laporan, memastikan kelengkapan dan kemudahan interpretasi data kuantitatif dan visual.
    </p>

    <div class="flex">
        <div class="card">
            <h3 class="mb-0" style="color:#1e40af;">1 Struktur Laporan yang Jelas</h3>
            <p class="mt-0 small">Memastikan setiap bagian informasi disajikan secara sistematis untuk navigasi dan pemahaman yang cepat.</p>
        </div>
        <div class="card">
            <h3 class="mb-0" style="color:#1e40af;">2 Visualisasi Data yang Efektif</h3>
            <p class="mt-0 small">Penggunaan grafik Pie dan Bar untuk memudahkan interpretasi proporsi kesejahteraan dan kepemilikan aset dominan.</p>
        </div>
        <div class="card">
            <h3 class="mb-0" style="color:#1e40af;">3 Rekomendasi Berbasis Sistem</h3>
            <p class="mt-0 small">Menyediakan kesimpulan otomatis dan rekomendasi awal untuk tindak lanjut kebijakan desa.</p>
        </div>
    </div>

    <!-- HALAMAN 2 & 3 GABUNG -->
    <div class="page-break"></div>
    <h2>Informasi Umum dan Profil Kesejahteraan Keluarga</h2>
    <p class="text-justify" style="color:#4b5563; margin-bottom:25px;">
        Bagian awal laporan ini memuat informasi umum yang sangat penting sebagai konteks data, 
        diikuti dengan visualisasi utama mengenai proporsi kesejahteraan keluarga di Desa Kaliwungu.
    </p>

    <!-- TABEL LUAR UNTUK DUA KOLOM -->
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <!-- KOLOM KIRI: HEADER & RINGKASAN DATA -->
            <td style="width:50%; vertical-align:top; padding-right:10px;">
                <h3 style="color:#1e40af; margin-bottom:10px;">Header dan Ringkasan Data</h3>

                <p style="margin:0 0 5px; font-weight:bold; font-size:12pt;">
                    LAPORAN ANALISIS ASET KELUARGA
                </p>
                <p style="margin:0 0 5px;">Lokasi: Desa Kaliwungu, Kabupaten Kudus</p>
                <p style="margin:0 0 15px;">Periode Laporan: <p style="color: #1e40af">{{ $periode }}</p></p>

                <p style="margin:0 0 5px; font-weight:bold;">Total Data Keluarga</p>
                <div class="highlight" style="border:1px solid #ccc; background:#f9fafb; padding:8px; width:90px; text-align:center; font-size:35pt; font-weight:bold; border-radius:5px;">
                    {{ number_format($totalKeluarga) }}
                </div>
                <p class="small" style="margin-top:8px; font-size:12px;">
                    Jumlah keseluruhan keluarga yang tercatat dan dianalisis dalam sistem pendataan aset desa.
                </p>
            </td>

            <!-- KOLOM KANAN: VISUALISASI PIE CHART -->
            <td style="width:50%; vertical-align:top; padding-left:10px;">
                <h3 style="color:#1e40af; margin-bottom:10px;">Visualisasi Proporsi Kesejahteraan</h3>
                <p class="small" style="margin-bottom:10px; font-size:13px;">
                    Diagram Lingkaran (Pie Chart) memberikan pandangan instan mengenai distribusi status ekonomi keluarga, 
                    yang dikategorikan menjadi Baik, Sedang, dan Buruk berdasarkan skor kepemilikan aset mereka.
                </p>

                <div style="text-align:center; margin-bottom:10px;">
                    <img src="{{ $pieChartUrl }}" width="400" alt="Pie Chart">
                </div>

                {{-- <div style="font-size:11px; text-align:center; margin-bottom:5px;">
                    <span><span class="badge baik"></span> Baik (Hijau)</span>&nbsp;&nbsp;
                    <span><span class="badge sedang"></span> Sedang (Kuning)</span>&nbsp;&nbsp;
                    <span><span class="badge buruk"></span> Buruk (Merah)</span>
                </div> --}}

                <p class="small italic" style="margin-top:8px; font-size:12px; text-align:justify;">
                    Interpretasi visual ini membantu pemerintah desa mengidentifikasi kelompok prioritas 
                    yang membutuhkan intervensi atau program bantuan sosial.
                </p>
            </td>
        </tr>
    </table>


    <!-- HALAMAN 4 -->
    <div class="page-break"></div>
    <h2>Analisis Mendalam: Aset Paling Dominan</h2>
    <p class="text-justify" style="color:#4b5563; margin-bottom:25px;">
        Bagian ini berfokus pada data kepemilikan aset spesifik, menggunakan Diagram Batang untuk menyoroti 5 aset yang paling sering dimiliki oleh keluarga di Desa Kaliwungu. Informasi ini relevan untuk memahami pola konsumsi dan infrastruktur dasar yang telah dimiliki masyarakat.
    </p>

    <h3 style="color:#1e40af; margin:20px 0 12px;">Top 5 Aset yang Paling Banyak Dimiliki</h3>
    <div class="bar-container">
        <img src="{{ $barChartUrl }}" width="90%" alt="Bar Chart Aset">
    </div>
    <p class="text-justify" style="margin-top:20px; color:#4b5563;">
        Tingginya kepemilikan Sepeda Motor dan Handphone menunjukkan mobilitas dan akses komunikasi yang baik di desa. Data ini dapat digunakan untuk perencanaan program yang memanfaatkan sarana komunikasi digital.
    </p>

    <!-- HALAMAN 5 -->
<div class="page-break"></div>
<h2>Detail Kuantitatif: Tabel Statistik Kesejahteraan & Aset Populer</h2>
<p class="text-justify" style="color:#4b5563; margin-bottom:25px;">
    Untuk mendukung visualisasi pada Card 2 dan 3, bagian ini menyajikan data dalam format tabel yang presisi. 
    Tabel memberikan angka mentah (Jumlah) dan perbandingan relatif (Persentase) untuk analisis yang lebih akurat.
</p>

<!-- Gunakan tabel luar untuk membagi menjadi dua kolom -->
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <!-- Kolom kiri: Statistik Kesejahteraan -->
        <td style="vertical-align:top; width:50%; padding-right:10px;">
            <h3 style="color:#1e40af; margin-bottom:8px;">Tabel Statistik Kesejahteraan Keluarga</h3>
            <table style="width:100%; border-collapse:collapse; font-size:12px;">
                <thead>
                    <tr>
                        <th style="border:1px solid #999; padding:6px;">Kategori</th>
                        <th style="border:1px solid #999; padding:6px;">Jumlah</th>
                        <th style="border:1px solid #999; padding:6px;">Persentase (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:1px solid #999; padding:6px;">Baik</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $baik }}</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $persenBaik }}%</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #999; padding:6px;">Sedang</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $sedang }}</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $persenSedang }}%</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #999; padding:6px;">Buruk</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $buruk }}</td>
                        <td style="border:1px solid #999; padding:6px;">{{ $persenBuruk }}%</td>
                    </tr>
                </tbody>
            </table>
            <p class="small" style="margin-top:8px; font-size:11px;">
                Mayoritas keluarga berada di kategori <strong>Sedang</strong>, menandakan perlunya program ekonomi menengah untuk mendorong ke kategori Baik.
            </p>
        </td>

        <!-- Kolom kanan: 5 Aset Terpopuler -->
        <td style="vertical-align:top; width:50%; padding-left:10px;">
            <h3 style="color:#1e40af; margin-bottom:8px;">Tabel "5 Aset Yang Paling Banyak Dimiliki"</h3>
            <table style="width:100%; border-collapse:collapse; font-size:12px;">
                <thead>
                    <tr>
                        <th style="border:1px solid #999; padding:6px;">Nama Aset</th>
                        <th style="border:1px solid #999; padding:6px;">Jumlah Dimiliki</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topAset as $a)
                        <tr>
                            <td style="border:1px solid #999; padding:6px;">{{ $a['nama'] }}</td>
                            <td style="border:1px solid #999; padding:6px; text-align:center;">{{ $a['jumlah'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="small" style="margin-top:8px; font-size:11px;">
                Lima aset non-lahan paling banyak dimiliki, mencerminkan pola konsumsi masyarakat.
            </p>
        </td>
    </tr>
</table>

<div class="catatan" style="margin-top:15px; font-size:11px;">
    <strong>CATATAN PENTING:</strong> Angka-angka ini adalah hasil perhitungan otomatis dari sistem pendataan desa dan mencerminkan prioritas pengeluaran rumah tangga.
</div>

    {{-- <img src="{{ public_path('images/rapat.png') }}" class="img-rapat" alt="Rapat Desa"> --}}

    <!-- HALAMAN 7 (Kesimpulan + Footer di satu halaman) -->
    <div class="page-break"></div>
    <h2>Kesimpulan Otomatis dan Rekomendasi Tindak Lanjut</h2>
    <p class="text-justify" style="color:#4b5563; margin-bottom:25px;">
        Bagian akhir laporan ini adalah kesimpulan deskriptif yang dihasilkan secara otomatis oleh sistem, memberikan interpretasi cepat dan rekomendasi awal bagi Pemerintah Desa Kaliwungu.
    </p>

    <div class="flex">
        <div class="card">
            <h3>Kategori Kesejahteraan Dominan</h3>
            <p class="small">
                Kategori kesejahteraan yang paling dominan adalah <strong>{{ $dominan }}</strong> ({{ $persenDominan }}%).
                Hal ini mengindikasikan bahwa sebagian besar keluarga memiliki akses ke aset dasar namun rentan terhadap goncangan ekonomi.
                Hanya {{ $persenBuruk }}% yang berada di kategori Buruk, namun kelompok ini memerlukan perhatian mendesak.
            </p>
        </div>
        <div class="card">
            <h3>Makna Kepemilikan Aset</h3>
            <p class="small">
                Kepemilikan aset tertinggi adalah <strong>{{ $topAset[0]['nama'] ?? '-' }}</strong> dan HP.
                Ini menunjukkan tingginya mobilitas dan konektivitas masyarakat.
            </p>
        </div>
        <div class="card">
            <h3>Rekomendasi Kebijakan</h3>
            <p class="small">
                Fokuskan program bantuan sosial kepada {{ $persenBuruk }}% keluarga kategori Buruk, dan tingkatkan kapasitas ekonomi untuk kategori Sedang.
            </p>
        </div>
    </div>

    <p style="color:#4f46e5; margin-top:30px; font-size:10pt; border-top:1px solid #e5e7eb; padding-top:12px;">
        Laporan ini dihasilkan secara otomatis oleh Sistem Manajemen Data Desa pada tanggal {{ $tanggal }}.
        Data disajikan untuk pertimbangan strategis dan perencanaan pembangunan Desa Kaliwungu.
    </p>

</body>
</html>