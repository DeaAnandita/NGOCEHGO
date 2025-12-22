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
            line-height: 1.6;
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
            margin-top: 4px;
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
        }

        th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .box {
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 6px;
            background-color: #f9fafb;
            margin-bottom: 12px;
        }

        h3 {
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 6px;
        }

        .rekomendasi {
            border: 1px solid #bbf7d0;
            background-color: #f0fdf4;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .rekomendasi h4 {
            margin: 0 0 6px;
            font-size: 13px;
            color: #166534;
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
        <p class="title">Laporan Analisis Konflik Sosial</p>
        <p class="subtitle">
            Periode {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </p>
    </div>

    {{-- RINGKASAN --}}
    <div class="box">
        <p><strong>Total Keluarga:</strong>{{ number_format($totalKeluarga) }} KK</p>
        <p><strong>Total Keluarga Terindikasi Konflik:</strong>{{ number_format($totalKasus) }} KK</p>
        <p><strong>Kategori Risiko Sosial:</strong>{{ $kategori }}</p>
    </div>


    {{-- TABEL DISTRIBUSI --}}
    <h3>Distribusi Konflik Sosial Berdasarkan Kategori</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Jenis Konflik Sosial</th>
                <th style="width:120px;">Jumlah Keluarga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td>Konflik Sosial Berlatar Belakang SARA</td>
                <td align="center">{{ $data['konflik_sara'] }}</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Kekerasan Fisik dalam Lingkup Keluarga</td>
                <td align="center">{{ $data['kekerasan_fisik'] }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>Kriminalitas dan Tindak Kejahatan</td>
                <td align="center">{{ $data['kriminalitas'] }}</td>
            </tr>
            <tr>
                <td align="center">4</td>
                <td>Penyimpangan Perilaku Sosial (Judi, Miras, Narkoba)</td>
                <td align="center">{{ $data['penyimpangan_perilaku'] }}</td>
            </tr>
            <tr>
                <td align="center">5</td>
                <td>Kejahatan Seksual</td>
                <td align="center">{{ $data['kejahatan_seksual'] }}</td>
            </tr>
            <tr>
                <td align="center">6</td>
                <td>Kehamilan Rentan & Tidak Sah</td>
                <td align="center">{{ $data['kehamilan_rentan'] }}</td>
            </tr>
            <tr>
                <td align="center">7</td>
                <td>Pertengkaran dalam Rumah Tangga</td>
                <td align="center">{{ $data['pertengkaran_keluarga'] }}</td>
            </tr>
            <tr>
                <td align="center">8</td>
                <td>KDRT dan Kekerasan Domestik</td>
                <td align="center">{{ $data['kdrt'] }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ANALISIS --}}
    <h3>Analisis Kondisi Sosial Masyarakat</h3>
    <div class="box">
        <p>
            Berdasarkan hasil pengolahan data, teridentifikasi sebanyak 
            <strong>{{ $totalKasus }}</strong> keluarga yang mengalami atau berpotensi mengalami
            konflik sosial. Sebagian besar konflik yang muncul bersifat
            <em>internal keluarga</em>, seperti pertengkaran rumah tangga dan
            penyimpangan perilaku.
        </p>

        <p>
            Tingkat risiko sosial secara umum berada pada kategori
            <strong>{{ $kategori }}</strong>. Kondisi ini menunjukkan bahwa
            konflik masih dapat dikendalikan melalui upaya preventif dan
            pembinaan berkelanjutan oleh pemerintah desa bersama masyarakat.
        </p>
    </div>

    {{-- REKOMENDASI --}}
    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan dan Tindak Lanjut</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>
            Laporan ini dihasilkan secara otomatis oleh
            <strong>Sistem Informasi Ketahanan Keluarga</strong>.
        </p>
        <p>
            Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </p>
    </div>

</body>
</html>
