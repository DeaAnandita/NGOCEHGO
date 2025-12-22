<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 25px 30px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }
        .header { text-align: center; margin-bottom: 12px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .subtitle { font-size: 13px; margin-top: 4px; }
        .summary {
            border: 1px solid #ccc;
            background: #f9fafb;
            padding: 10px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 12px;
        }
        th {
            background: #f3f4f6;
            text-align: center;
        }
        h3 {
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 6px;
        }
        .analysis {
            border: 1px solid #ccc;
            background: #f0f9ff;
            padding: 10px;
        }
        .rekomendasi {
            border: 1px solid #ccc;
            background: #f0fdf4;
            padding: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">Laporan Analisis Aset Ternak</div>
    <div class="subtitle">Periode {{ $periode }}</div>
</div>

<div class="summary">
    <p><strong>Total Keluarga Terdata:</strong> {{ $totalKeluarga }}</p>
    <p><strong>Keluarga Memiliki Ternak:</strong> {{ $keluargaPunyaTernak }}</p>
    <p><strong>Keluarga Tanpa Ternak:</strong> {{ $keluargaTanpaTernak }}</p>
</div>

<h3>Distribusi Kepemilikan Aset Ternak</h3>
<table>
    <thead>
        <tr>
            <th style="width:40px">No</th>
            <th>Jenis Aset Ternak</th>
            <th style="width:140px">Jumlah Keluarga Pemilik</th>
            <th style="width:140px">Jumlah Aset Ternak</th>
        </tr>
    </thead>
    <tbody>
        @foreach($indikator as $index => $item)
        <tr>
            <td align="center">{{ $index + 1 }}</td>
            <td>{{ $item['nama'] }}</td>
            <td align="center">{{ $item['jumlah_keluarga'] }}</td>
            <td align="center">{{ $item['jumlah_aset'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Analisis Interpretatif</h3>
<div class="analysis">
    <p>{{ $interpretasi }}</p>
</div>

<div class="rekomendasi">
    <h3>Arah Kebijakan dan Intervensi Pemerintah</h3>
    <ul>
        @foreach($rekomendasi as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</div>

<div class="footer">
    <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Informasi Desa Kaliwungu</strong>.</p>
    <p><em>Tanggal Cetak:</em> {{ $tanggal }}</p>
</div>

</body>
</html>
