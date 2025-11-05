<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Analisis Aset Keluarga</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; padding: 40px; color: #333; }
        h1, h2, h3 { color: #1e40af; }
        h1 { text-align: center; font-size: 20pt; margin-bottom: 10px; }
        h2 { border-bottom: 2px solid #1e40af; padding-bottom: 4px; }
        p { font-size: 11pt; text-align: justify; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10pt; }
        th, td { border: 1px solid #999; padding: 8px; }
        th { background-color: #eef2ff; text-align: center; }
        .chart { text-align: center; margin: 20px 0; }
        .badge { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px; }
        .baik { background-color: #10b981; }
        .sedang { background-color: #f59e0b; }
        .buruk { background-color: #ef4444; }
        .footer { text-align: center; font-size: 10pt; color: #555; margin-top: 40px; border-top: 1px solid #ccc; padding-top: 10px; }
        
    </style>
</head>
<body>

    <h1>LAPORAN ANALISIS ASET KELUARGA</h1>
    <p style="text-align:center;">Desa Kaliwungu, Kabupaten Kudus — Periode {{ $periode }}</p>

    <h2>1. Profil Umum</h2>
    <p>
        Laporan ini menyajikan hasil analisis data aset keluarga di Desa Kaliwungu. 
        Analisis dilakukan berdasarkan 42 indikator kepemilikan aset, yang mencerminkan tingkat kesejahteraan keluarga.
    </p>
    <p><strong>Total Data Keluarga:</strong> {{ $totalKeluarga }}</p>

    <div class="chart">
        <img src="{{ $pieChartUrl }}" width="300"><br>
        <small><span class="badge baik"></span>Baik &nbsp;
        <span class="badge sedang"></span>Sedang &nbsp;
        <span class="badge buruk"></span>Buruk</small>
    </div>

    <h2>2. Klasifikasi Kesejahteraan</h2>
    <table>
        <thead>
            <tr><th>Kategori</th><th>Jumlah</th><th>Persentase</th></tr>
        </thead>
        <tbody>
            <tr><td><span class="badge baik"></span> Baik</td><td>{{ $baik }}</td><td>{{ $persenBaik }}%</td></tr>
            <tr><td><span class="badge sedang"></span> Sedang</td><td>{{ $sedang }}</td><td>{{ $persenSedang }}%</td></tr>
            <tr><td><span class="badge buruk"></span> Buruk</td><td>{{ $buruk }}</td><td>{{ $persenBuruk }}%</td></tr>
        </tbody>
    </table>

    <p>
        Berdasarkan hasil klasifikasi, sebagian besar keluarga tergolong dalam kategori <strong>{{ $dominan }}</strong>.
        Hal ini menunjukkan kondisi ekonomi masyarakat secara umum di desa.
    </p>

    <div>
    <h2>3. Aset Paling Banyak Dimiliki</h2>
    <div class="chart">
        <img src="{{ $barChartUrl }}" width="420"><br>
        <small>Visualisasi 5 aset yang paling sering dimiliki keluarga di Desa Kaliwungu.</small>
    </div>

    <table>
        <thead><tr><th>Nama Aset</th><th>Jumlah Dimiliki</th></tr></thead>
        <tbody>
            @foreach($topAset as $a)
                <tr><td>{{ $a['nama'] }}</td><td>{{ $a['jumlah'] }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <div>

    <h2>4. Kesimpulan dan Rekomendasi</h2>
    <p>
        Dari hasil analisis, diketahui bahwa kategori <strong>{{ $dominan }}</strong> mendominasi dengan persentase {{ $persenDominan }}%.
        Pemerintah desa disarankan untuk memprioritaskan program bantuan kepada kategori <strong>Buruk</strong> ({{ $persenBuruk }}%),
        serta program pengembangan ekonomi produktif bagi kategori <strong>Sedang</strong>.
    </p>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh Sistem Pendataan Aset Keluarga Desa Kaliwungu</p>
        <p>{{ $tanggal }}</p>
    </div>

</body>
</html>
