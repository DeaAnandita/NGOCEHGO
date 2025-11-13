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

        .analisis h4,
        .rekomendasi h4 {
            margin: 0 0 6px;
            font-size: 13px;
        }

        .analisis h4 { color: #92400e; }
        .rekomendasi h4 { color: #166534; }

        ul {
            margin: 0;
            padding-left: 18px;
        }
        li {
            margin-bottom: 4px;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
            text-align: left;
        }

        /* biar tabel gak kepotong di tengah halaman PDF */
        table, th, td { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Lembaga Desa</p>
        <p class="subtitle">Periode: {{ $periode ?? now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($totalPenduduk ?? 0) }}</p>
        <p><strong>Rata-rata Tingkat Keterlibatan Aparatur Desa:</strong> {{ number_format($rataKeterlibatan ?? 0, 1) }} / 100</p>
        <p><strong>Kategori Evaluasi Kelembagaan:</strong> {{ ucfirst($kategori ?? '-') }}</p>
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
                <td>Penduduk dengan Minimal 1 Jabatan di Lembaga Desa</td>
                <td align="center">{{ number_format($minSatu ?? 0) }}</td>
            </tr>
            <tr>
                <td>Penduduk Tanpa Jabatan (semua ≠ 2)</td>
                <td align="center">{{ number_format($tanpaJabatan ?? 0) }}</td>
            </tr>
            <tr>
                <td>Penduduk dengan Lebih dari 2 Jabatan Aktif</td>
                <td align="center">{{ number_format($lebihDua ?? 0) }}</td>
            </tr>
            <tr>
                <td>Rata-rata Jumlah Jabatan per Penduduk</td>
                <td align="center">{{ number_format($rataJabatan ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:11px; color:#6b7280; margin-top:4px;">
        Catatan: Nilai dihitung otomatis oleh sistem berdasarkan data aktif lembaga desa dan keterlibatan aparatur,
        terhubung langsung dengan data kependudukan dalam Sistem <strong>NGOCEH GO</strong>.
    </p>

    <h3>Distribusi Jabatan dalam Struktur Pemerintahan Desa</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Jabatan Pemerintahan Desa</th>
                <th style="width:120px;">Jumlah Aparatur Aktif</th>
                <th style="width:150px;">Persentase dari Total Penduduk (%)</th>
                <th style="width:130px;">Tingkat Keterisian (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jabatanList ?? [] as $i => $j)
                <tr>
                    <td align="center">{{ $i + 1 }}</td>
                    <td>{{ $j['nama'] ?? '-' }}</td>
                    <td align="center">{{ number_format($j['jumlah'] ?? 0) }}</td>
                    <td align="center">{{ number_format($j['persen'] ?? 0, 1) }}</td>
                    <td align="center">{{ number_format($j['keterisian'] ?? 0, 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="box analisis">
        <h4>Analisis Interpretatif</h4>
        <ul>
            <li><strong>Rata-rata Tingkat Keterlibatan Aparatur:</strong> {{ number_format($rataKeterlibatan ?? 0,1) }}%</li>
            <li><strong>Jabatan dengan Keterisian Tertinggi:</strong>
                {{ $jabatanList ? collect($jabatanList)->sortByDesc('keterisian')->first()['nama'] ?? '-' : '-' }}
                ({{ number_format(collect($jabatanList)->max('keterisian') ?? 0,1) }}%)
            </li>
            <li><strong>Jabatan dengan Keterisian Terendah:</strong>
                {{ $jabatanList ? collect($jabatanList)->sortBy('keterisian')->first()['nama'] ?? '-' : '-' }}
                ({{ number_format(collect($jabatanList)->min('keterisian') ?? 0,1) }}%)
            </li>
        </ul>
        <p style="margin-top:6px;">
            <strong>Interpretasi Umum:</strong> Struktur kelembagaan desa menunjukkan kondisi yang 
            {{ strtolower($kategori ?? 'cukup aktif dan fungsional') }}. 
            Mayoritas jabatan pemerintahan telah terisi, namun beberapa posisi seperti 
            {{ $jabatanList ? collect($jabatanList)->sortBy('keterisian')->first()['nama'] ?? 'beberapa jabatan administratif' : 'anggota BPD' }} 
            masih memerlukan peningkatan partisipasi aparatur untuk memastikan fungsi pemerintahan berjalan optimal.
        </p>
    </div>

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
