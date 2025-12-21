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
        <p class="title">Laporan Kualitas Ibu Hamil</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Data Ibu Hamil Terdata:</strong> {{ number_format($totalData ?? 0) }}</p>
        <p><strong>Desa dengan Kasus Tertinggi:</strong> 
            {{ !empty($desaTertinggi) && $desaTertinggi !== '-' ? $desaTertinggi : 'Tidak ada data' }}
        </p>
        <p><strong>Kategori Risiko Kesehatan Ibu Hamil:</strong> {{ $kategori ?? 'Tidak Diketahui' }}</p>
    </div>

    <h3>Distribusi Data Kualitas Ibu Hamil</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Pertanyaan</th>
                <th style="width:80px;">ADA</th>
                <th style="width:100px;">PERNAH ADA</th>
                <th style="width:80px;">TIDAK ADA</th>
                <th style="width:100px;">TIDAK DIISI</th>
            </tr>
        </thead>
        <tbody>
            <tr><td align="center">1</td><td>Ibu hamil periksa di POSYANDU</td><td align="center">{{ $data['posyandu_ada'] ?? 0 }}</td><td align="center">{{ $data['posyandu_pernah'] ?? 0 }}</td><td align="center">{{ $data['posyandu_tidak'] ?? 0 }}</td><td align="center">{{ $data['posyandu_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">2</td><td>Ibu hamil periksa di PUSKESMAS</td><td align="center">{{ $data['puskesmas_ada'] ?? 0 }}</td><td align="center">{{ $data['puskesmas_pernah'] ?? 0 }}</td><td align="center">{{ $data['puskesmas_tidak'] ?? 0 }}</td><td align="center">{{ $data['puskesmas_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">3</td><td>Ibu hamil periksa di rumah sakit</td><td align="center">{{ $data['rumahsakit_ada'] ?? 0 }}</td><td align="center">{{ $data['rumahsakit_pernah'] ?? 0 }}</td><td align="center">{{ $data['rumahsakit_tidak'] ?? 0 }}</td><td align="center">{{ $data['rumahsakit_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">4</td><td>Ibu hamil periksa di dokter praktek</td><td align="center">{{ $data['dokter_ada'] ?? 0 }}</td><td align="center">{{ $data['dokter_pernah'] ?? 0 }}</td><td align="center">{{ $data['dokter_tidak'] ?? 0 }}</td><td align="center">{{ $data['dokter_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">5</td><td>Ibu hamil periksa di bidan praktek</td><td align="center">{{ $data['bidan_ada'] ?? 0 }}</td><td align="center">{{ $data['bidan_pernah'] ?? 0 }}</td><td align="center">{{ $data['bidan_tidak'] ?? 0 }}</td><td align="center">{{ $data['bidan_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">6</td><td>Ibu hamil periksa di dukun terlatih</td><td align="center">{{ $data['dukun_ada'] ?? 0 }}</td><td align="center">{{ $data['dukun_pernah'] ?? 0 }}</td><td align="center">{{ $data['dukun_tidak'] ?? 0 }}</td><td align="center">{{ $data['dukun_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">7</td><td>Ibu hamil tidak periksa kesehatan</td><td align="center">{{ $data['tidak_periksa_ada'] ?? 0 }}</td><td align="center">{{ $data['tidak_periksa_pernah'] ?? 0 }}</td><td align="center">{{ $data['tidak_periksa_tidak'] ?? 0 }}</td><td align="center">{{ $data['tidak_periksa_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">8</td><td>Ibu hamil yang meninggal</td><td align="center">{{ $data['meninggal_ada'] ?? 0 }}</td><td align="center">{{ $data['meninggal_pernah'] ?? 0 }}</td><td align="center">{{ $data['meninggal_tidak'] ?? 0 }}</td><td align="center">{{ $data['meninggal_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">9</td><td>Ibu hamil melahirkan</td><td align="center">{{ $data['melahirkan_ada'] ?? 0 }}</td><td align="center">{{ $data['melahirkan_pernah'] ?? 0 }}</td><td align="center">{{ $data['melahirkan_tidak'] ?? 0 }}</td><td align="center">{{ $data['melahirkan_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">10</td><td>Ibu nifas sakit</td><td align="center">{{ $data['nifas_sakit_ada'] ?? 0 }}</td><td align="center">{{ $data['nifas_sakit_pernah'] ?? 0 }}</td><td align="center">{{ $data['nifas_sakit_tidak'] ?? 0 }}</td><td align="center">{{ $data['nifas_sakit_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">11</td><td>Kematian ibu nifas</td><td align="center">{{ $data['kematian_nifas_ada'] ?? 0 }}</td><td align="center">{{ $data['kematian_nifas_pernah'] ?? 0 }}</td><td align="center">{{ $data['kematian_nifas_tidak'] ?? 0 }}</td><td align="center">{{ $data['kematian_nifas_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">12</td><td>Ibu nifas sehat</td><td align="center">{{ $data['nifas_sehat_ada'] ?? 0 }}</td><td align="center">{{ $data['nifas_sehat_pernah'] ?? 0 }}</td><td align="center">{{ $data['nifas_sehat_tidak'] ?? 0 }}</td><td align="center">{{ $data['nifas_sehat_kosong'] ?? 0 }}</td></tr>
            
            <tr><td align="center">13</td><td>Kematian ibu saat melahirkan</td><td align="center">{{ $data['kematian_melahirkan_ada'] ?? 0 }}</td><td align="center">{{ $data['kematian_melahirkan_pernah'] ?? 0 }}</td><td align="center">{{ $data['kematian_melahirkan_tidak'] ?? 0 }}</td><td align="center">{{ $data['kematian_melahirkan_kosong'] ?? 0 }}</td></tr>
        </tbody>
    </table>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>• <strong>Persentase Ibu Hamil Tidak Periksa Kesehatan (ADA):</strong> 
            {{ round((($data['tidak_periksa_ada'] ?? 0) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Kematian Ibu Hamil/Nifas/Melahirkan (ADA + PERNAH ADA):</strong> 
            {{ round(((($data['meninggal_ada'] ?? 0) + ($data['meninggal_pernah'] ?? 0) + 
                      ($data['kematian_nifas_ada'] ?? 0) + ($data['kematian_nifas_pernah'] ?? 0) + 
                      ($data['kematian_melahirkan_ada'] ?? 0) + ($data['kematian_melahirkan_pernah'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Pemeriksaan di Fasilitas Resmi (POSYANDU/PUSKESMAS/RS/Dokter/Bidan - ADA):</strong> 
            {{ round(((($data['posyandu_ada'] ?? 0) + ($data['puskesmas_ada'] ?? 0) + ($data['rumahsakit_ada'] ?? 0) + 
                      ($data['dokter_ada'] ?? 0) + ($data['bidan_ada'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Interpretasi Umum:</strong> 
            @if(($kategori ?? '') === 'Risiko Tinggi')
                Kondisi kesehatan ibu hamil sangat memprihatinkan, terdapat indikasi angka kematian ibu tinggi dan rendahnya akses pemeriksaan resmi. Diperlukan intervensi darurat lintas sektor.
            @elseif(($kategori ?? '') === 'Risiko Sedang')
                Terdapat risiko sedang pada kesehatan ibu hamil. Diperlukan penguatan program antenatal care, edukasi, dan peningkatan akses layanan kesehatan.
            @else
                Kondisi kesehatan ibu hamil relatif baik, tetap lakukan pemantauan rutin dan edukasi berkala.
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