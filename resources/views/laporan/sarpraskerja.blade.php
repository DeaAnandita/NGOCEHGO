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
            background: #f9fafb;
            padding: 10px;
            border-radius: 6px;
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
        .analysis {
            border: 1px solid #d1d5db;
            background: #f0f9ff;
            padding: 10px;
            border-radius: 6px;
        }
        .rekomendasi {
            border: 1px solid #d1d5db;
            background: #f0fdf4;
            padding: 10px;
            border-radius: 6px;
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
        <p class="title">Laporan Analisis Sarana dan Prasarana Kerja</p>
        <p class="subtitle">Periode: {{ $periode }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($totalKeluarga) }}</p>
        <p><strong>Skor Ketersediaan Sarpras Rata-rata:</strong> {{ number_format($skorRataRata, 2) }} / 100</p>
        <p><strong>Kategori Keluarga Dominan:</strong> {{ $dominan }}</p>
        <p><strong>Distribusi Kategori:</strong> 
            Tinggi {{ $persenTinggi }}%, Sedang {{ $persenSedang }}%, Rendah {{ $persenRendah }}%
        </p>
    </div>

    <h3>Rata-rata Ketersediaan Sarana dan Prasarana Kerja</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Jenis Sarana/Prasarana</th>
                <th style="width: 140px;">Nilai Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indikator as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td align="center">{{ number_format($item['nilai'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Analisis Interpretatif</h3>
    <div class="analysis">
        <p>• <strong>Kondisi Umum:</strong> {{ $interpretasi }}</p>

        <p>• <strong>Distribusi Ketersediaan:</strong>
            @if($persenTinggi > 60)
                Sarana dan prasarana kerja sudah merata di sebagian besar keluarga.
            @elseif($persenSedang > 50)
                Ketersediaan sarpras cukup seimbang antar keluarga, meski masih ada ketimpangan kecil.
            @else
                Sebagian besar keluarga masih memiliki keterbatasan akses terhadap sarana dan prasarana kerja.
            @endif
        </p>

        <p>• <strong>Potensi Ekonomi dan Produktivitas:</strong>
            @if($dominan === 'Tinggi')
                Desa memiliki potensi ekonomi kerja tinggi berkat kelengkapan sarpras dan fasilitas usaha.
            @elseif($dominan === 'Sedang')
                Potensi ekonomi dapat meningkat dengan optimalisasi pemanfaatan sarpras yang sudah ada.
            @else
                Diperlukan dukungan pemerintah dalam bentuk bantuan sarana kerja dan infrastruktur dasar.
            @endif
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
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Informasi Desa Kaliwungu</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ $tanggal }}</p>
    </div>
</body>
</html>