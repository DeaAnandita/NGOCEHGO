<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px 25px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            line-height: 1.5;
        }
        .header { text-align: center; margin-bottom: 12px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .subtitle { font-size: 13px; margin: 4px 0 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #fafafa; }
        .summary {
            border: 1px solid #d1d5db;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }
        h3 {
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 6px;
        }
        .rekomendasi {
            border: 1px solid #d1d5db;
            background: #f0fdf4;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .rekomendasi h4 {
            margin: 0 0 6px;
            font-size: 13px;
            color: #166534;
        }
        .rekomendasi ul {
            margin: 0;
            padding-left: 18px;
        }
        .rekomendasi li {
            margin-bottom: 4px;
        }
        .category-box {
            text-align: center;
            padding: 12px;
            background: #e0f2fe;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Prasarana Dasar Keluarga</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($total) }}</p>
        <p><strong>Skor Akses Prasarana Rata-rata:</strong> {{ $skor }} / 100</p>
        <p><strong>Rata-rata Luas Lantai:</strong> {{ $avg_luas_lantai }} m²</p>
        <p><strong>Rata-rata Jumlah Kamar:</strong> {{ round($avg_jumlah_kamar) }} kamar</p>
        <div class="category-box">
            Kategori Keluarga: {{ $kategori }}
        </div>
    </div>

    <h3>Persentase Akses Layak per Indikator</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Indikator Prasarana</th>
                <th>% Layak</th>
                <th>% Tidak Layak</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>Status Pemilik Bangunan (Milik Sendiri)</td><td align="center">{{ $indikator['status_bangunan'] }}%</td><td align="center">{{ round(100 - $indikator['status_bangunan'], 2) }}%</td></tr>
            <tr><td>2</td><td>Status Pemilik Lahan (Milik Sendiri)</td><td align="center">{{ $indikator['status_lahan'] }}%</td><td align="center">{{ round(100 - $indikator['status_lahan'], 2) }}%</td></tr>
            <tr><td>3</td><td>Jenis Lantai (Berkualitas Tinggi-Menengah)</td><td align="center">{{ $indikator['lantai_jenis'] }}%</td><td align="center">{{ round(100 - $indikator['lantai_jenis'], 2) }}%</td></tr>
            <tr><td>4</td><td>Kondisi Lantai (Bagus)</td><td align="center">{{ $indikator['lantai_kondisi'] }}%</td><td align="center">{{ round(100 - $indikator['lantai_kondisi'], 2) }}%</td></tr>
            <tr><td>5</td><td>Jenis Dinding (Berkualitas)</td><td align="center">{{ $indikator['dinding_jenis'] }}%</td><td align="center">{{ round(100 - $indikator['dinding_jenis'], 2) }}%</td></tr>
            <tr><td>6</td><td>Kondisi Dinding (Bagus)</td><td align="center">{{ $indikator['dinding_kondisi'] }}%</td><td align="center">{{ round(100 - $indikator['dinding_kondisi'], 2) }}%</td></tr>
            <tr><td>7</td><td>Jenis Atap (Berkualitas)</td><td align="center">{{ $indikator['atap_jenis'] }}%</td><td align="center">{{ round(100 - $indikator['atap_jenis'], 2) }}%</td></tr>
            <tr><td>8</td><td>Kondisi Atap (Bagus)</td><td align="center">{{ $indikator['atap_kondisi'] }}%</td><td align="center">{{ round(100 - $indikator['atap_kondisi'], 2) }}%</td></tr>
            <tr><td>9</td><td>Sumber Air Minum Aman</td><td align="center">{{ $indikator['air_minum'] }}%</td><td align="center">{{ round(100 - $indikator['air_minum'], 2) }}%</td></tr>
            <tr><td>10</td><td>Kondisi Air Baik</td><td align="center">{{ $indikator['kondisi_air'] }}%</td><td align="center">{{ round(100 - $indikator['kondisi_air'], 2) }}%</td></tr>
            <tr><td>11</td><td>Cara Perolehan Air (Tidak Membeli)</td><td align="center">{{ $indikator['perolehan_air'] }}%</td><td align="center">{{ round(100 - $indikator['perolehan_air'], 2) }}%</td></tr>
            <tr><td>12</td><td>Penerangan Listrik PLN</td><td align="center">{{ $indikator['penerangan'] }}%</td><td align="center">{{ round(100 - $indikator['penerangan'], 2) }}%</td></tr>
            <tr><td>13</td><td>Daya Listrik ≥1300 Watt</td><td align="center">{{ $indikator['daya_listrik'] }}%</td><td align="center">{{ round(100 - $indikator['daya_listrik'], 2) }}%</td></tr>
            <tr><td>14</td><td>Bahan Bakar Memasak Modern</td><td align="center">{{ $indikator['bahan_bakar'] }}%</td><td align="center">{{ round(100 - $indikator['bahan_bakar'], 2) }}%</td></tr>
            <tr><td>15</td><td>Fasilitas BAB Sendiri</td><td align="center">{{ $indikator['fasilitas_bab'] }}%</td><td align="center">{{ round(100 - $indikator['fasilitas_bab'], 2) }}%</td></tr>
            <tr><td>16</td><td>Pembuangan Tinja Aman</td><td align="center">{{ $indikator['pembuangan_tinja'] }}%</td><td align="center">{{ round(100 - $indikator['pembuangan_tinja'], 2) }}%</td></tr>
            <tr><td>17</td><td>Pembuangan Sampah Resmi</td><td align="center">{{ $indikator['pembuangan_sampah'] }}%</td><td align="center">{{ round(100 - $indikator['pembuangan_sampah'], 2) }}%</td></tr>
        </tbody>
    </table>

    <h3>Ringkasan Kategori Prasarana</h3>
    <div class="summary">
        <p>• <strong>Prasarana Bangunan & Kepemilikan:</strong> {{ $persen_bangunan }}%</p>
        <p>• <strong>Prasarana Air & Sanitasi:</strong> {{ $persen_air_sanitasi }}%</p>
        <p>• <strong>Prasarana Energi:</strong> {{ $persen_energi }}%</p>
    </div>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>Berdasarkan data prasarana dasar, mayoritas keluarga tergolong <strong>{{ $kategori }}</strong>.
            Akses terhadap hunian layak, air bersih, sanitasi, dan energi menjadi penentu utama tingkat kesejahteraan infrastruktur rumah tangga.</p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Penanggulangan Kemiskinan</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Ngoceh Go</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>