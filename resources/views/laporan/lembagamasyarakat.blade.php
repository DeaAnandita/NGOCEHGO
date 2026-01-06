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

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <p class="title">Laporan Analisis Lembaga Masyarakat</p>
        <p class="subtitle">Periode: {{ $periode }}</p>
    </div>

    <!-- RINGKASAN -->
    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($totalPenduduk) }}</p>
        <p><strong>Rata-rata Tingkat Partisipasi:</strong> {{ $skorPartisipasi }} / 100</p>

        <div class="category-box">
            Kategori Partisipasi Dominan: {{ strtoupper($kategoriDominan) }}
        </div>
    </div>

    <!-- TABEL PARTISIPASI LEMBAGA -->
    <h3>Partisipasi Penduduk pada Lembaga Masyarakat</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Lembaga</th>
                <th style="width:120px;">Jumlah Terlibat</th>
                <th style="width:120px;">Jumlah Tidak Terlibat</th>
                <th style="width:200px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lembaga as $i => $item)
                <tr>
                    <td align="center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama_lembaga }}</td>
                    <td align="center">{{ $item->jumlah_ya }}</td>
                    <td align="center">{{ $item->jumlah_tidak }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PAGE 2 -->
    <div class="page-break"></div>

    <!-- RINGKASAN KATEGORI -->
    <h3>Ringkasan Tingkat Keterlibatan</h3>
    <div class="summary">
        <p>• <strong>Keterlibatan Tinggi:</strong> {{ $tinggi }} penduduk ({{ $persenTinggi }}%)</p>
        <p>• <strong>Keterlibatan Sedang:</strong> {{ $sedang }} penduduk ({{ $persenSedang }}%)</p>
        <p>• <strong>Keterlibatan Rendah:</strong> {{ $rendah }} penduduk ({{ $persenRendah }}%)</p>
    </div>

    <!-- ANALISIS -->
    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>
            {{ $analisis }}
        </p>
    </div>

    <!-- REKOMENDASI -->
    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Desa Cerdas</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ $tanggal }}</p>
    </div>

</body>
</html>
