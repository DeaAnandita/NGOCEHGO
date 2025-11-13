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

        .summary {
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 6px;
        }

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

        .box {
            border: 1px solid #d1d5db;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .analisis {
            background: #fff7ed;
        }

        .rekomendasi {
            background: #f0fdf4;
        }

        .rekomendasi h4,
        .analisis h4 {
            margin: 0 0 6px;
            font-size: 13px;
        }

        .analisis h4 { color: #92400e; }
        .rekomendasi h4 { color: #166534; }

        .analisis ul,
        .rekomendasi ul {
            margin: 0;
            padding-left: 18px;
        }

        .analisis li,
        .rekomendasi li {
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Ketepatan Program</p>
        <p class="subtitle">Periode: {{ $periode ?? now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($totalPenduduk ?? 0) }}</p>
        <p><strong>Rata-rata Ketepatan Sasaran Program:</strong> {{ number_format($rataKetepatan ?? 0, 1) }} / 100</p>
        <p><strong>Kategori Evaluasi Program:</strong> {{ ucfirst($kategori ?? '-') }}</p>
    </div>

    <h3>Ringkasan Output Sistem (Data Aktif)</h3>
    <table>
        <thead>
            <tr>
                <th>Indikator</th>
                <th style="width:35%;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Penduduk Terdata</td>
                <td align="center">{{ number_format($totalPenduduk ?? 0) }}</td>
            </tr>
            <tr>
                <td>Penduduk Menerima Minimal 1 Program Aktif</td>
                <td align="center">{{ number_format($minSatu ?? 0) }}</td>
            </tr>
            <tr>
                <td>Penduduk Tanpa Program Aktif (semua ≠ 2)</td>
                <td align="center">{{ number_format($tanpaProgram ?? 0) }}</td>
            </tr>
            <tr>
                <td>Penerima Lebih dari 3 Program Aktif</td>
                <td align="center">{{ number_format($lebihTiga ?? 0) }}</td>
            </tr>
            <tr>
                <td>Rata-rata Jumlah Program Aktif per Penduduk</td>
                <td align="center">{{ number_format($rataProgram ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:11px; color:#6b7280; margin-top:4px;">
        Catatan: Nilai dihitung otomatis oleh sistem berdasarkan data aktif program serta yang terhubung dengan data kependudukan dalam Sistem <strong>NGOCEH GO</strong>.
    </p>

    <h3>Rata-rata Ketepatan Tiap Program Sosial Nasional</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Program Sosial</th>
                <th style="width:120px;">Jumlah Penerima Aktif</th>
                <th style="width:150px;">Persentase dari Total Penduduk (%)</th>
                <th style="width:130px;">Ketepatan Sasaran (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs ?? [] as $i => $p)
                <tr>
                    <td align="center">{{ $i + 1 }}</td>
                    <td>{{ $p['nama'] }}</td>
                    <td align="center">{{ number_format($p['jumlah']) }}</td>
                    <td align="center">{{ number_format($p['persen'], 1) }}</td>
                    <td align="center">{{ number_format($p['ketepatan'] ?? 0, 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Analisis Interpretatif dalam box -->
    <div class="box analisis">
        <h4>Analisis Interpretatif</h4>
        <ul>
            <li><strong>Rata-rata Ketepatan Program:</strong> {{ number_format($rataKetepatan ?? 0,1) }}%</li>
            <li><strong>Program Paling Tepat Sasaran:</strong> 
                {{ $programs ? collect($programs)->sortByDesc('ketepatan')->first()['nama'] ?? '-' : '-' }}
                ({{ number_format(collect($programs)->max('ketepatan') ?? 0,1) }}%)
            </li>
            <li><strong>Program Kurang Tepat:</strong> 
                {{ $programs ? collect($programs)->sortBy('ketepatan')->first()['nama'] ?? '-' : '-' }}
                ({{ number_format(collect($programs)->min('ketepatan') ?? 0,1) }}%)
            </li>
        </ul>
        <p style="margin-top:6px;">
            <strong>Interpretasi Umum:</strong> Sebagian besar bantuan sosial telah menjangkau kelompok masyarakat yang berhak menerima,
            menunjukkan tingkat akurasi distribusi yang cukup baik. Namun masih terdapat ketidaktepatan pada program non-PBI dan mandiri, menandakan
            perlunya peningkatan verifikasi kelayakan penerima serta sinkronisasi lintas instansi agar penyaluran bantuan menjadi lebih efisien dan tepat sasaran.
        </p>
    </div>

    <!-- Rekomendasi Kebijakan -->
    <div class="box rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @foreach(($rekomendasi ?? []) as $r)
                <li>{{ $r }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Data Desa NGOCEH GO</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ $tanggal ?? now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
