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
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Lembaga Ekonomi</p>
        <p class="subtitle">Periode: {{ $periode }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($totalPenduduk) }}</p>
        <p><strong>Keterlibatan Ekonomi Rendah:</strong> {{ $rendah }} ({{ $persenRendah }}%)</p>
        <p><strong>Keterlibatan Ekonomi Sedang:</strong> {{ $sedang }} ({{ $persenSedang }}%)</p>
        <p><strong>Keterlibatan Ekonomi Tinggi:</strong> {{ $tinggi }} ({{ $persenTinggi }}%)</p>
        <p><strong>Kategori Dominan:</strong> {{ strtoupper($dominan) }} ({{ $persenDominan }}%)</p>
    </div>

    <h3>Top 10 Jenis Usaha dengan Partisipasi Tertinggi</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Lembaga / Jenis Usaha Ekonomi</th>
                <th style="width:150px;">Jumlah Penduduk Terlibat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSoal as $i => $item)
                <tr>
                    <td align="center">{{ $i + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td align="center">{{ $item['jumlah'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Analisis Ekonomi Desa</h3>
    <div class="summary">
        <p>
            • <strong>Tingkat Aktivitas Ekonomi:</strong>
            @if($dominan === 'Tinggi')
                Aktivitas ekonomi masyarakat sangat tinggi, menandakan produktivitas dan kemandirian warga desa dalam sektor usaha.
            @elseif($dominan === 'Sedang')
                Aktivitas ekonomi masyarakat tergolong cukup baik, namun perlu peningkatan kapasitas usaha dan akses permodalan.
            @else
                Partisipasi ekonomi masyarakat masih rendah, dibutuhkan pendampingan usaha dan program pemberdayaan ekonomi.
            @endif
        </p>

        <p>
            • <strong>Interpretasi Umum:</strong>
            Data ini menunjukkan tingkat aktivitas usaha masyarakat dalam berbagai sektor seperti koperasi, perdagangan, industri rumah tangga, dan jasa.
            Hasil ini menjadi dasar kebijakan desa untuk memperkuat ekonomi produktif melalui BUMDes dan UMKM.
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Ekonomi Desa</h4>
        <ul>
            @if($dominan === 'Tinggi')
                <li>Perkuat kolaborasi antar pelaku usaha melalui BUMDes dan koperasi.</li>
                <li>Fasilitasi pengembangan usaha melalui akses permodalan dan pelatihan manajemen.</li>
                <li>Dorong digitalisasi pemasaran untuk produk UMKM desa.</li>
            @elseif($dominan === 'Sedang')
                <li>Adakan pelatihan wirausaha dan inovasi produk bagi masyarakat produktif.</li>
                <li>Kembangkan jejaring kerja sama dengan lembaga keuangan rakyat dan koperasi.</li>
                <li>Optimalkan potensi sektor industri kecil dan perdagangan lokal.</li>
            @else
                <li>Identifikasi hambatan ekonomi masyarakat (permodalan, keterampilan, atau pasar).</li>
                <li>Bangun program inkubasi usaha mikro dan dukungan BUMDes untuk sektor strategis.</li>
                <li>Integrasikan program pemberdayaan ekonomi dengan kegiatan sosial masyarakat.</li>
            @endif
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Desa Cerdas</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ $tanggal }}</p>
    </div>
</body>
</html>
