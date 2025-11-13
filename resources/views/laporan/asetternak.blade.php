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
        <p class="title">Laporan Analisis Aset Ternak</p>
        <p class="subtitle">Periode: {{ $periode }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($totalKeluarga) }}</p>
        <p><strong>Skor Kepemilikan Aset Rata-rata:</strong> {{ number_format($skorRataRata, 2) }} / 100</p>
        <p><strong>Kategori Keluarga Dominan:</strong> {{ $dominan }}</p>
        <p><strong>Distribusi Kategori:</strong> 
            Tinggi {{ $persenTinggi }}%, Sedang {{ $persenSedang }}%, Rendah {{ $persenRendah }}%
        </p>
    </div>

    <h3>Rata-rata Kepemilikan Aset Ternak</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Jenis Aset Ternak</th>
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

        <p>• <strong>Distribusi Aset:</strong>
            @if($persenTinggi > 60)
                Kepemilikan aset ternak tergolong sangat merata di antara keluarga peternak.
            @elseif($persenSedang > 50)
                Kepemilikan aset ternak cukup seimbang antar keluarga.
            @else
                Sebagian besar keluarga masih memiliki aset ternak dalam jumlah terbatas.
            @endif
        </p>

        <p>• <strong>Potensi Ekonomi:</strong>
            @if($dominan === 'Tinggi')
                Sektor peternakan berpotensi menjadi penggerak utama ekonomi desa dengan populasi ternak produktif.
            @elseif($dominan === 'Sedang')
                Terdapat potensi ekonomi yang kuat jika diimbangi dengan pelatihan pengelolaan pakan dan kesehatan ternak.
            @else
                Perlu dukungan pemerintah melalui bantuan modal, bibit unggul, dan pendampingan teknis peternak.
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
