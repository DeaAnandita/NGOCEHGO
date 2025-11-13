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
        <p class="title">Laporan Analisis Lembaga Masyarakat</p>
        <p class="subtitle">Periode: {{ $periode }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Penduduk Terdata:</strong> {{ number_format($totalPenduduk) }}</p>
        <p><strong>Keterlibatan Rendah:</strong> {{ $rendah }} ({{ $persenRendah }}%)</p>
        <p><strong>Keterlibatan Sedang:</strong> {{ $sedang }} ({{ $persenSedang }}%)</p>
        <p><strong>Keterlibatan Tinggi:</strong> {{ $tinggi }} ({{ $persenTinggi }}%)</p>
        <p><strong>Kategori Dominan:</strong> {{ strtoupper($dominan) }} ({{ $persenDominan }}%)</p>
    </div>

    <h3>Top 8 Lembaga dengan Partisipasi Tertinggi</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Lembaga</th>
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

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>
            • <strong>Makna Tingkat Keterlibatan:</strong>
            @if($dominan === 'Tinggi')
                Partisipasi masyarakat dalam lembaga sangat aktif. Ini menunjukkan kesadaran sosial dan solidaritas tinggi antarwarga.
            @elseif($dominan === 'Sedang')
                Keterlibatan masyarakat cukup baik namun belum merata, perlu dorongan agar partisipasi lebih menyeluruh.
            @else
                Partisipasi lembaga masih rendah, menandakan perlunya pembinaan kelembagaan masyarakat tingkat desa/kelurahan.
            @endif
        </p>

        <p>
            • <strong>Interpretasi Umum:</strong>
            Keterlibatan dalam lembaga sosial merupakan indikator penting dari modal sosial dan ketahanan masyarakat. 
            Data ini dapat menjadi dasar intervensi sosial untuk memperkuat jejaring kelembagaan di tingkat desa.
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @if($dominan === 'Tinggi')
                <li>Perkuat dukungan dan pendanaan bagi lembaga masyarakat yang aktif.</li>
                <li>Kembangkan kegiatan lintas lembaga untuk memperkuat kolaborasi.</li>
                <li>Berikan penghargaan bagi masyarakat yang berkontribusi dalam lembaga sosial.</li>
            @elseif($dominan === 'Sedang')
                <li>Lakukan sosialisasi pentingnya peran lembaga masyarakat di tingkat RT/RW.</li>
                <li>Adakan pelatihan manajemen organisasi bagi pengurus lembaga lokal.</li>
                <li>Fasilitasi kolaborasi antar lembaga melalui forum koordinasi desa.</li>
            @else
                <li>Identifikasi faktor penyebab rendahnya partisipasi masyarakat.</li>
                <li>Berikan pendampingan dan pembinaan kepada pengurus lembaga pasif.</li>
                <li>Integrasikan program pemerintah dengan kegiatan lembaga masyarakat.</li>
            @endif
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Desa Cerdas</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ $tanggal }}</p>
    </div>
</body>
</html>
