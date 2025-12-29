<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Data Prasarana Dasar - Analisis Kebutuhan</title>
    <style>
        @page { margin: 15mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11pt; line-height: 1.5; margin: 0; }
        .container { max-width: 820px; margin: 0 auto; padding: 0 10px; }
        h1 { text-align:center; color:#1e40af; font-size:20pt; margin-bottom:4px; }
        h2 { color:#1e40af; font-size:14pt; border-bottom:2px solid #6366f1; padding-bottom:6px; margin-top:18px; }
        p { text-align:justify; margin:6px 0; }
        table { width:100%; border-collapse:collapse; margin:10px 0; font-size:10pt; }
        th, td { border:1px solid #e5e7eb; padding:6px; vertical-align:top; }
        th { background:#eef2ff; text-align:center; }
        .chart { display:block; margin:12px auto; max-width:100%; }
        .page-break { page-break-before: always; }
        .card { background:#f8fafc; border-left:5px solid #6366f1; padding:10px; border-radius:6px; margin-top:10px; }
        .small { font-size:9pt; color:#6b7280; }
        .badge { display:inline-block; padding:4px 8px; border-radius:6px; background:#eef2ff; color:#1e40af; font-weight:600; font-size:9pt; }
        .need { color:#ef4444; font-weight:bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>LAPORAN ANALISIS PRASARANA DASAR & IDENTIFIKASI KELUARGA PRIORITAS</h1>
    <p style="text-align:center; font-weight:600;">Sistem Pendataan Prasarana Desa Kaliwungu — Periode: {{ $periode }}</p>

    <div class="page-break"></div>
    <h2>Ringkasan & Tujuan</h2>
    <p>Total entri prasarana dasar: <strong>{{ $totalData }}</strong>.</p>
    <p>Tujuan laporan ini: menilai kondisi fisik tempat tinggal keluarga dan mengidentifikasi keluarga yang kekurangan (sasaran prioritas bantuan).</p>

    <h2>Ringkasan Kategori Kebutuhan</h2>
    <p>Distribusi kategori (Baik — Sedang — Prioritas) berdasarkan skor kekurangan:</p>
    <img src="{{ $charts['kategori'] }}" class="chart" alt="Chart Kategori">
    <table>
        <thead><tr><th>Kategori</th><th>Jumlah Keluarga</th></tr></thead>
        <tbody>
            <tr><td class="badge">Baik</td><td style="text-align:center;">{{ $kategoriCounts['Baik'] }}</td></tr>
            <tr><td class="badge">Sedang</td><td style="text-align:center;">{{ $kategoriCounts['Sedang'] }}</td></tr>
            <tr><td class="badge need">Prioritas (Butuh Bantuan)</td><td style="text-align:center;">{{ $kategoriCounts['Prioritas'] }}</td></tr>
        </tbody>
    </table>

    <h2 class="page-break">Tipe Kekurangan Terbanyak</h2>
    <p>Jenis kekurangan yang paling sering muncul (digunakan untuk perencanaan intervensi):</p>
    <img src="{{ $charts['deficits'] }}" class="chart" alt="Chart Kekurangan">
    <table>
        <thead><tr><th>Jenis Kekurangan</th><th>Jumlah Kasus</th></tr></thead>
        <tbody>
            @foreach($deficitTypes as $type => $count)
                <tr><td>{{ ucwords(str_replace(['_'], [' '], $type)) }}</td><td style="text-align:center;">{{ $count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <h2>Daftar Keluarga Prioritas (Top {{ count($topNeedy) }})</h2>
    <p>Berikut daftar keluarga dengan skor kekurangan tertinggi — segera verifikasi lapangan untuk masing-masing keluarga ini:</p>
    <table>
        <thead>
            <tr>
                <th>No KK</th>
                <th>Kepala Keluarga</th>
                <th>Dusun</th>
                <th>Skor Kekurangan</th>
                <th>Detail Kekurangan</th>
                <th>Data Ringkas Prasarana</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topNeedy as $n)
                <tr>
                    <td>{{ $n['no_kk'] }}</td>
                    <td>{{ $n['kepala'] ?? '-' }}</td>
                    <td>{{ $n['namadusun'] ?? '-' }}</td>
                    <td style="text-align:center;"><span class="{{ $n['score'] >= 4 ? 'need' : '' }}">{{ $n['score'] }}</span></td>
                    <td>
                        @if(count($n['deficits'])>0)
                            <ul style="margin:0 0 0 14px; padding:0;">
                                @foreach($n['deficits'] as $d)
                                    <li>{{ $d }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <small>
                            Lantai: {{ $n['jenislantai'] ?? '-' }}<br>
                            Dinding: {{ $n['jenisdinding'] ?? '-' }}<br>
                            Atap: {{ $n['jenisatap'] ?? '-' }}<br>
                            Air: {{ $n['sumberair'] ?? '-' }}<br>
                            Penerangan: {{ $n['sumberpenerangan'] ?? '-' }}<br>
                            Luas lantai: {{ $n['luaslantai'] ?? '-' }} m²<br>
                            Jumlah kamar: {{ $n['jumlahkamar'] ?? '-' }}
                        </small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <h2>Interpretasi & Rekomendasi</h2>
    <div class="card">
        <h3>Interpretasi Otomatis</h3>
        <p>
            Sistem ini mendeteksi atribut yang tidak diisi atau berada pada ambang kurang (mis: luas lantai &le; 9 m², jumlah kamar &le; 1).
            Keluarga dengan skor tinggi (≥4) perlu prioritas verifikasi lapangan untuk memastikan kondisi riil — kemudian dapat menjadi prioritas bantuan perbaikan rumah atau fasilitas dasar (air, sanitasi, listrik).
        </p>
    </div>

    <div class="card">
        <h3>Rekomendasi Awal</h3>
        <ol>
            <li>Segera lakukan verifikasi lapangan terhadap daftar keluarga prioritas di atas (cek fisik & dokumentasi foto).</li>
            <li>Fokus program bantuan pada kekurangan yang paling banyak terjadi (lihat Chart Kekurangan).</li>
            <li>Lengkapi data prasarana yang kosong di database.</li>
            <li>Buat rencana anggaran prioritas berdasarkan jumlah kasus per jenis kekurangan.</li>
        </ol>
    </div>

    <p class="small" style="margin-top:18px;">
        Laporan dibuat otomatis oleh sistem pada: {{ $tanggal }}.  
        Catatan: aturan penilaian bersifat konservatif (kosong = kekurangan).
    </p>
</div>
</body>
</html>
