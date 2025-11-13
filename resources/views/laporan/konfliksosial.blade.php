
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
        <p class="title">Laporan Analisis Konflik Sosial</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Kasus Konflik Sosial Terdata:</strong> {{ number_format($totalKasus ?? 0) }}</p>
        <p><strong>Desa dengan Kasus Tertinggi:</strong> 
            {{ !empty($desaTertinggi) && $desaTertinggi !== '-' ? $desaTertinggi : 'Tidak ada data' }}
        </p>
        <p><strong>Kategori Risiko:</strong> {{ $kategori ?? 'Tidak Diketahui' }}</p>
    </div>

    <h3>Distribusi Kasus Berdasarkan Jenis Konflik</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Jenis Konflik</th>
                <th style="width:100px;">Jumlah Kasus</th>
            </tr>
        </thead>
        <tbody>
            <tr><td align="center">1</td><td>Konflik SARA (1–4)</td><td align="center">{{ $data['konflik_sara'] ?? 0 }}</td></tr>
            <tr><td align="center">2</td><td>Kekerasan Fisik (5–6,13–14)</td><td align="center">{{ $data['kekerasan_fisik'] ?? 0 }}</td></tr>
            <tr><td align="center">3</td><td>Kriminalitas (7–9)</td><td align="center">{{ $data['kriminalitas'] ?? 0 }}</td></tr>
            <tr><td align="center">4</td><td>Penyimpangan Perilaku (10–12)</td><td align="center">{{ $data['penyimpangan_perilaku'] ?? 0 }}</td></tr>
            <tr><td align="center">5</td><td>Kejahatan Seksual (15–18)</td><td align="center">{{ $data['kejahatan_seksual'] ?? 0 }}</td></tr>
            <tr><td align="center">6</td><td>Kehamilan Rentan (19–21)</td><td align="center">{{ $data['kehamilan_rentan'] ?? 0 }}</td></tr>
            <tr><td align="center">7</td><td>Pertengkaran Keluarga (22–26)</td><td align="center">{{ $data['pertengkaran_keluarga'] ?? 0 }}</td></tr>
            <tr><td align="center">8</td><td>KDRT dan Kekerasan Dalam Rumah Tangga (27–32)</td><td align="center">{{ $data['kdrt'] ?? 0 }}</td></tr>
        </tbody>
    </table>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>• <strong>Persentase Kekerasan Fisik:</strong> 
            {{ round((($data['kekerasan_fisik'] ?? 0) / max($totalKasus ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Kriminalitas:</strong> 
            {{ round((($data['kriminalitas'] ?? 0) / max($totalKasus ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Pertengkaran Keluarga & KDRT:</strong> 
            {{ round(((($data['pertengkaran_keluarga'] ?? 0) + ($data['kdrt'] ?? 0)) / max($totalKasus ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Interpretasi Umum:</strong> 
            @if(($kategori ?? '') === 'Risiko Tinggi')
                Kondisi sosial desa sangat rawan konflik dan membutuhkan intervensi lintas sektor.
            @elseif(($kategori ?? '') === 'Risiko Sedang')
                Diperlukan peningkatan program pembinaan sosial dan patroli keamanan.
            @else
                Kondisi relatif aman, tetap lakukan monitoring berkala.
            @endif
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @forelse($rekomendasi ?? [] as $item)
                <li>{{ $item }}</li>
            @empty
                <li>Tidak ada rekomendasi khusus.</li>
            @endforelse
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Keluarga</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
