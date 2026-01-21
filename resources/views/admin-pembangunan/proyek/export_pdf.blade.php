<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Buku Proyek</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background: #eee;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="title">LAPORAN BUKU PROYEK</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Pelaksana</th>
                <th>Lokasi</th>
                <th>Sumber Dana</th>
                <th>Nominal</th>
                <th>Manfaat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->kdproyek }}</td>
                    <td>{{ $p->proyek_tanggal }}</td>
                    <td>{{ $p->kegiatan->kegiatan ?? '-' }}</td>
                    <td>{{ $p->pelaksana->pelaksana ?? '-' }}</td>
                    <td>{{ $p->lokasi->lokasi ?? '-' }}</td>
                    <td>{{ $p->sumber->sumber_dana ?? '-' }}</td>
                    <td>{{ number_format($p->proyek_nominal, 0, ',', '.') }}</td>
                    <td>{{ $p->proyek_manfaat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
