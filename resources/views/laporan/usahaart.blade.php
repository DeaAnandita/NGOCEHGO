<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Usaha Rumah Tangga</title>
    <style>
        @page { margin: 20mm; size: A4; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            color: #000; 
            font-size: 11pt; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0;
        }
        .container { 
            width: 100%; 
            max-width: 780px; 
            margin: 0 auto; 
        }

        /* HEADER */
        h1 { 
            text-align: center; 
            font-size: 16pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            margin: 0 0 5px 0; 
        }
        .periode { 
            text-align: center; 
            font-size: 12pt; 
            color: #333; 
            margin-bottom: 20px; 
        }

        /* SUMMARY BOX */
        .summary-box {
            border: 1px solid #000;
            padding: 12px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            font-size: 11pt;
        }
        .summary-box p {
            margin: 6px 0;
        }

        /* SECTION TITLE */
        .section-title {
            font-weight: bold;
            font-size: 12pt;
            margin: 20px 0 8px 0;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* ANALYSIS BOX */
        .analysis-box {
            border: 1px solid #000;
            padding: 12px;
            margin: 15px 0;
            background-color: #f9f9f9;
        }
        .analysis-box ul {
            margin: 8px 0;
            padding-left: 20px;
        }
        .analysis-box li {
            margin-bottom: 4px;
        }

        /* RECOMMENDATION BOX */
        .recommendation-box {
            border: 2px solid #4CAF50;
            background-color: #e8f5e9;
            padding: 12px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .recommendation-box h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
            color: #1b5e20;
        }
        .recommendation-box ul {
            margin: 8px 0;
            padding-left: 20px;
        }
        .recommendation-box li {
            margin-bottom: 6px;
        }

        /* KESIMPULAN */
        .conclusion-box {
            border: 1px solid #000;
            background-color: #f9f9f9;
            padding: 12px;
            margin: 20px 0;
            font-size: 11pt;
        }

 /* FOOTER & TANGGAL CETAK - BERDEKATAN */
        .footer {
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 11px;
            color: #666;
            margin-top: 25px;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        .print-date {
            font-size: 11px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }
        
    </style>
</head>
<body>
<div class="container">
    

    <!-- PAGE 1 -->
    <h1>LAPORAN ANALISIS USAHA RUMAH TANGGA</h1>
    <div class="periode">Periode: {{ now()->translatedFormat('F Y') }}</div>

    <!-- RINGKASAN -->
    <div class="summary-box">
        <p><strong>Total Usaha Terdata:</strong> {{ $summary['total_usaha'] ?? 0 }}</p>
        <p><strong>Skor Produktivitas Rata-rata:</strong> {{ $summary['score'] ?? 0 }} / 100</p>
        <p><strong>Kategori Usaha:</strong> 
            @if(!empty($summary['dominant_sector'][0]['sector']))
                Mikro - Sektor {{ $summary['dominant_sector'][0]['sector'] }}
            @else
                Tidak Tersedia
            @endif
        </p>
    </div>

    <!-- TABEL INDIKATOR -->
    <div class="section-title">Rata-rata Indikator Usaha Rumah Tangga</div>
    <table>
        <tr>
            <th style="width: 8%;">No</th>
            <th style="width: 50%;">Nama Indikator</th>
            <th style="width: 42%;">Nilai Rata-rata / Dominan</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Lapangan Usaha Dominan</td>
            <td>
                @forelse($summary['dominant_sector'] ?? [] as $item)
                    {{ $item['sector'] }} ({{ $item['percentage'] }}%)<br>
                @empty
                    Tidak ada data.
                @endforelse
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Persentase Memiliki Tempat Usaha</td>
            <td>{{ $summary['place_percentage'] ?? 0 }}%</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Kategori Omzet Terbanyak</td>
            <td>{{ $summary['top_omzet'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Rata-rata Jumlah Pekerja per Usaha</td>
            <td>{{ $summary['avg_pekerja'] ?? 0 }}</td>
        </tr>
    </table>

    <!-- ANALISIS INTERPRETATIF -->
    <div class="section-title">Analisis Interpretatif</div>
    <div class="analysis-box">
        <ul>
            <li><strong>Sektor usaha dominan:</strong>
                @foreach($summary['dominant_sector'] ?? [] as $item)
                    {{ $item['sector'] }} ({{ $item['percentage'] }}%),
                @endforeach
            </li>
            <li>{{ $summary['place_percentage'] ?? 0 }}% usaha memiliki tempat produksi atau jualan tetap.</li>
            <li><strong>Omzet terbanyak:</strong> {{ $summary['top_omzet'] ?? '-' }}.</li>
            <li><strong>Rata-rata jumlah pekerja:</strong> {{ $summary['avg_pekerja'] ?? 0 }} orang.</li>
        </ul>
        <p style="margin-top:10px;">
           <p><strong>Interpretasi Umum:</strong> {{ $summary['general_interpretation'] }}</p>
        </p>
    </div>

    <!-- REKOMENDASI -->
    <div class="section-title">Rekomendasi Intervensi Pemerintah</div>
    <div class="recommendation-box">
        <h3>Program Bantuan Modal dan Peralatan</h3>
        <ul>
            <li>Penyaluran bantuan modal mikro melalui Dana Desa atau KUR Mikro.</li>
            <li>Bantuan alat produksi sederhana untuk usaha kuliner, pertanian, dan kerajinan.</li>
        </ul>

        <h3>Pelatihan Kewirausahaan dan Manajemen Usaha</h3>
        <ul>
            <li>Pelatihan pencatatan keuangan sederhana dan strategi pemasaran.</li>
            <li>Workshop inovasi produk lokal agar memiliki nilai jual lebih tinggi.</li>
        </ul>

        <h3>Digitalisasi dan Akses Pasar Daring</h3>
        <ul>
            <li>Pembentukan platform e-marketplace desa (Ngoceh Go).</li>
            <li>Pendampingan literasi digital agar usaha dapat memasarkan produk secara online.</li>
        </ul>

        <h3>Pendampingan Akses Keuangan dan Koperasi Desa</h3>
        <ul>
            <li>Fasilitasi pembentukan koperasi usaha rumah tangga.</li>
            <li>Pendampingan proposal pembiayaan ke lembaga mikro dan BUMDes.</li>
        </ul>

        <h3>Penguatan Klaster Usaha dan Zona Ekonomi Desa</h3>
        <ul>
            <li>Pengelompokan usaha berdasarkan sektor potensial (kuliner, pertanian, kerajinan).</li>
            <li>Pembangunan pusat promosi produk lokal (galeri desa).</li>
        </ul>

        <h3>Monitoring dan Evaluasi</h3>
        <ul>
            <li>Pemantauan kenaikan pendapatan usaha per 6 bulan.</li>
            <li>Penilaian efektivitas bantuan modal dan pelatihan.</li>
        </ul>
    </div>


<!-- FOOTER + TANGGAL CETAK (BERDEKATAN, SATU HALAMAN) -->
    <div class="footer">
        Laporan ini dihasilkan otomatis oleh Sistem Ngoceh Go (Modul: Usaha ART)
    </div>
    <div class="print-date">
        Tanggal Cetak: {{ $tanggal_cetak }}
    </div>

</div> <!-- end container -->
</body>
</html>