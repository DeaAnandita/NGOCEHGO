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
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Sejahtera Keluarga</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($data->count()) }}</p>
        <p><strong>Skor Kesejahteraan Rata-rata:</strong> {{ $skor }} / 100</p>
        <p><strong>Kategori Keluarga:</strong> {{ $kategori }}</p>
    </div>

    <h3>Rata-rata Indikator Sejahtera Keluarga (61–68)</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Indikator</th>
                <th style="width:140px;">Nilai Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            <tr><td align="center">61</td><td>Rata-rata uang saku anak per hari</td><td align="center">{{ number_format($indikator['uang_saku'], 2) }}</td></tr>
            <tr><td align="center">62</td><td>Keluarga memiliki kebiasaan merokok (bungkus per hari)</td><td align="center">{{ number_format($indikator['rokok'], 2) }}</td></tr>
            <tr><td align="center">63</td><td>Kepala keluarga minum kopi di kedai (kali per hari)</td><td align="center">{{ number_format($indikator['kopi_kali'], 2) }}</td></tr>
            <tr><td align="center">64</td><td>Kepala keluarga minum kopi di kedai (jam per hari)</td><td align="center">{{ number_format($indikator['kopi_jam'], 2) }}</td></tr>
            <tr><td align="center">65</td><td>Rata-rata pulsa yang digunakan per minggu</td><td align="center">{{ number_format($indikator['pulsa'], 2) }}</td></tr>
            <tr><td align="center">66</td><td>Rata-rata pendapatan keluarga per bulan</td><td align="center">{{ number_format($indikator['pendapatan'], 2) }}</td></tr>
            <tr><td align="center">67</td><td>Rata-rata pengeluaran keluarga per bulan</td><td align="center">{{ number_format($indikator['pengeluaran'], 2) }}</td></tr>
            <tr><td align="center">68</td><td>Rata-rata uang belanja keluarga per bulan</td><td align="center">{{ number_format($indikator['belanja'], 2) }}</td></tr>
        </tbody>
    </table>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>• <strong>Rasio Pengeluaran terhadap Pendapatan:</strong> 
            {{ $indikator['pendapatan'] > 0 ? round($indikator['pengeluaran'] / $indikator['pendapatan'] * 100, 2) : 0 }}%
        </p>
        <p>• <strong>Rasio Belanja terhadap Pendapatan:</strong> 
            {{ $indikator['pendapatan'] > 0 ? round($indikator['belanja'] / $indikator['pendapatan'] * 100, 2) : 0 }}%
        </p>
        <p>• <strong>Konsumsi Rokok & Kopi terhadap Pendapatan:</strong> 
            {{ round((($indikator['rokok'] + $indikator['kopi_kali']) / max($indikator['pendapatan'],1)) * 100, 2) }}%
        </p>
        <p>• <strong>Interpretasi Umum:</strong> 
            {{ $kategori === 'Sejahtera Stabil' ? 'Keluarga memiliki keseimbangan pendapatan dan pengeluaran.' : 
               ($kategori === 'Konsumtif Tidak Efisien' ? 'Pola pengeluaran konsumtif tinggi, perlu intervensi edukasi finansial.' :
               ($kategori === 'Miskin / Rentan Kemiskinan' ? 'Pendapatan keluarga rendah dan pengeluaran tinggi, membutuhkan dukungan sosial ekonomi.' :
               ($kategori === 'Kurang Dukungan Pendidikan Anak' ? 'Alokasi ke pendidikan anak masih rendah, perlu program pendidikan dan gizi.' :
               'Perlu intervensi ketahanan pangan.'))) }}
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Keluarga</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
