<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Lembaga Ekonomi</title>
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
        <p class="title">Laporan Analisis Lembaga Ekonomi</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($total) }}</p>
        <p><strong>Skor Partisipasi Ekonomi:</strong> {{ $skor }} / 100</p>
        <div class="category-box">
            Kategori Partisipasi: {{ $kategori }}
        </div>
    </div>

    <h3>Partisipasi Penduduk pada Lembaga Ekonomi</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Lembaga Ekonomi</th>
                <th style="width:120px;">Jumlah Terlibat (YA)</th>
                <th style="width:120px;">Jumlah Tidak Terlibat</th>
                <th style="width:200px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($master as $index => $item)
                @php
                    $kode = 'lemek_' . ($index + 1);
                    $info = $indikator[$kode] ?? ['count_ya' => 0, 'count_tidak' => 0, 'keterangan' => 'Data tidak tersedia.'];
                @endphp
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->lembaga }}</td>
                    <td align="center">{{ number_format($info['count_ya']) }}</td>
                    <td align="center">{{ number_format($info['count_tidak']) }}</td>
                    <td>{{ $info['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <h3>Ringkasan Partisipasi Lembaga Ekonomi</h3>
    <div class="summary">
        <p>• <strong>Rata-rata lembaga yang diikuti per penduduk:</strong> {{ $avg_partisipasi }} dari {{ $max_lembaga }} jenis lembaga</p>
        <p>• <strong>Total partisipasi keseluruhan:</strong> {{ number_format($total_partisipasi) }} partisipasi individu</p>
    </div>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>{{ $analisis }}</p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Penguatan Lembaga Ekonomi</h4>
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