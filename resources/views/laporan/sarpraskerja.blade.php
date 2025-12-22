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
        .header {
            text-align: center;
            margin-bottom: 14px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            margin-top: 6px;
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
        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .summary {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        h3 {
            font-size: 13px;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        .analysis {
            border: 1px solid #d1d5db;
            background-color: #f0f9ff;
            padding: 10px;
            border-radius: 6px;
        }

        .rekomendasi {
            border: 1px solid #d1d5db;
            background-color: #f0fdf4;
            padding: 10px;
            border-radius: 6px;
            margin-top: 12px;
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
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <p class="title">Laporan Analisis Sarana dan Prasarana Kerja</p>
        <p class="subtitle">
            Periode {{ $periode }}
        </p>
    </div>

    {{-- ================= RINGKASAN ================= --}}
    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($totalKeluarga) }} KK</p>
        <p><strong>Skor Kepemilikan Sarpras Rata-rata:</strong> {{ number_format($skorRataRata, 2) }}</p>
        <p><strong>Kategori Keluarga Dominan:</strong> {{ $dominan }}</p>
        <p>
            <strong>Distribusi Kategori:</strong><br>
            Tinggi {{ $persenTinggi }}% &nbsp;|&nbsp;
            Sedang {{ $persenSedang }}% &nbsp;|&nbsp;
            Rendah {{ $persenRendah }}%
        </p>
    </div>

    {{-- ================= TABEL SARPRAS ================= --}}
    <h3>Jumlah Keluarga yang Memiliki Sarana dan Prasarana Kerja</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Jenis Sarana / Prasarana Kerja</th>
                <th style="width: 160px;">Jumlah Keluarga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indikator as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td align="center">{{ $item['nilai'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= ANALISIS ================= --}}
    <h3>Analisis Interpretatif</h3>
    <div class="analysis">
        <p>
            <strong>Kondisi Umum:</strong><br>
            {{ $interpretasi }}
        </p>

        <p>
            <strong>Distribusi Kepemilikan Sarpras:</strong><br>
            @if($persenTinggi > 60)
                Kepemilikan sarana dan prasarana kerja sudah cukup merata pada sebagian besar keluarga.
            @elseif($persenSedang > 50)
                Kepemilikan sarpras berada pada tingkat menengah dan belum sepenuhnya merata.
            @else
                Sebagian besar keluarga masih mengalami keterbatasan dalam kepemilikan sarana kerja.
            @endif
        </p>

        <p>
            <strong>Implikasi terhadap Produktivitas Ekonomi:</strong><br>
            @if($dominan === 'Tinggi')
                Kondisi ini menunjukkan potensi ekonomi desa yang baik dan dapat dikembangkan lebih lanjut.
            @elseif($dominan === 'Sedang')
                Produktivitas ekonomi masih dapat ditingkatkan melalui intervensi sarana kerja yang tepat.
            @else
                Rendahnya kepemilikan sarana kerja berpotensi menghambat produktivitas dan pendapatan keluarga.
            @endif
        </p>
    </div>

    {{-- ================= REKOMENDASI ================= --}}
    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah Desa</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        <p>
            Laporan ini dihasilkan secara otomatis oleh
            <strong>Sistem Informasi Desa Kaliwungu</strong>
        </p>
        <p>
            <em>Tanggal Cetak:</em> {{ $tanggal }}
        </p>
    </div>

</body>
</html>
