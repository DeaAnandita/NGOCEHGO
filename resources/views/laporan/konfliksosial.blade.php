<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Konflik Sosial</title>
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
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10pt; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #eef2ff; font-weight: bold; text-align: center; }
        .catatan { background: #e0e7ff; padding: 18px; border-radius: 10px; font-size: 10pt; margin: 25px 0; border-left: 5px solid #6366f1; }
        .legend { text-align: center; margin: 12px 0; font-size: 10pt; }
        .footer { text-align: center; color: #6b7280; font-size: 9pt; margin-top: 60px; padding-top: 15px; border-top: 1px solid #e5e7eb; }
        img.chart { max-width: 100%; height: auto; display: block; margin: 15px auto; }
        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .page-break { page-break-before: always; }
        .small { font-size: 10pt; color: #6b7280; }
    </style>
</head>
<body>

<div class="container">

    <!-- HALAMAN 1 -->
    <h1>Laporan Analisis Konflik Sosial Desa Kaliwungu</h1>
    <p class="text-justify" style="margin-bottom:20px;">
        Dokumen ini berisi hasil analisis data konflik sosial yang terjadi di Desa Kaliwungu.
        Tujuannya untuk membantu pemerintah desa dalam mengidentifikasi faktor penyebab konflik,
        memetakan tingkat keparahan, serta menentukan langkah-langkah pencegahan dan penanganan yang tepat.
    </p>

    <div class="flex">
        <div class="card">
            <h3>Struktur Analisis</h3>
            <p class="small">Laporan disusun berdasarkan data survei dan input masyarakat tentang konflik sosial, meliputi pertengkaran, kekerasan, dan faktor penyebab lainnya.</p>
        </div>
        <div class="card">
            <h3>Visualisasi Data</h3>
            <p class="small">Menggunakan grafik Pie dan Bar untuk memperlihatkan distribusi kategori konflik sosial berdasarkan tingkat keparahan.</p>
        </div>
        <div class="card">
            <h3>Rekomendasi Otomatis</h3>
            <p class="small">Memberikan kesimpulan otomatis untuk membantu perumusan kebijakan sosial dan keamanan desa.</p>
        </div>
    </div>

    <!-- HALAMAN 2 -->
    <div class="page-break"></div>
    <h2>Profil Umum Konflik Sosial</h2>
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:50%; vertical-align:top; padding-right:10px;">
                <h3 style="color:#1e40af;">Ringkasan Data</h3>
                <p><strong>Total Keluarga Terdata:</strong></p>
                <div class="highlight" style="border:1px solid #ccc; background:#f9fafb; padding:8px; width:90px; text-align:center; font-size:35pt; border-radius:5px;">
                    {{ number_format($totalKeluarga) }}
                </div>
                <p class="small">Jumlah keluarga yang tercatat memiliki data konflik sosial.</p>
                <p><strong>Kategori Dominan:</strong> {{ $dominan ?? '-' }}</p>
                <p><strong>Persentase Tertinggi:</strong> {{ $persenDominan ?? 0 }}%</p>
            </td>
            <td style="width:50%; vertical-align:top; padding-left:10px;">
                <h3 style="color:#1e40af;">Visualisasi Konflik Sosial</h3>
                <img src="{{ $pieChartUrl }}" class="chart" width="360" alt="Grafik Pie Konflik Sosial">
                <p class="small italic">Grafik menunjukkan distribusi tingkat konflik (Ringan, Sedang, Berat).</p>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>
    <h2>Analisis Indikator Konflik Sosial</h2>
    <p class="text-justify" style="color:#4b5563;">
        Bagian ini menampilkan daftar indikator penyebab konflik sosial di masyarakat serta jumlah keluarga yang teridentifikasi pada setiap kategori.
    </p>

    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Jenis Konflik Sosial</th>
                <th style="width:140px;">Jumlah Keluarga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSoal as $i => $item)
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td style="text-align:center;">{{ $item['jumlah'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <div class="bar-container">
        <h2 style="color:#1e40af;">Grafik Top Konflik Sosial</h2>
        <img src="{{ $barChartUrl }}" class="chart" width="90%" alt="Grafik Batang Konflik Sosial">
    </div>

    <div class="catatan">
        <strong>Catatan:</strong> Data diambil dari rekapitulasi entri lapangan. Kategori dominan dihitung berdasarkan proporsi terbesar keluarga yang mengalami konflik.
    </div>

    <!-- HALAMAN 3 -->
    <div class="page-break"></div>
    <h2>Kesimpulan dan Rekomendasi Otomatis</h2>

    <div class="flex">
        <div class="card">
            <h3>Kategori Dominan</h3>
            <p class="small">Tingkat konflik sosial yang paling sering muncul adalah kategori <strong>{{ $dominan ?? '-' }}</strong> dengan persentase <strong>{{ $persenDominan ?? 0 }}%</strong>.</p>
        </div>
        <div class="card">
            <h3>Analisis Umum</h3>
            <p class="small">Sebagian besar konflik sosial dipicu oleh permasalahan rumah tangga, penyalahgunaan alkohol, dan kesenjangan ekonomi antar warga.</p>
        </div>
        <div class="card">
            <h3>Rekomendasi</h3>
            <p class="small">Fokuskan kebijakan pada edukasi keluarga, peningkatan lapangan kerja, dan sosialisasi penyelesaian konflik secara damai di tingkat RT/RW.</p>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh Sistem Pembangunan Keluarga Desa Kaliwungu.</p>
        <p><em>Tanggal cetak:</em> {{ $tanggal }}</p>
    </div>

</div>
</body>
</html>
