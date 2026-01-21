<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Buku Bantuan</title>
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

    <div class="title">LAPORAN BUKU BANTUAN</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bantuan</th>
                <th>Sasaran</th>
                <th>Jenis</th>
                <th>Sumber Dana</th>
                <th>Tgl Mulai</th>
                <th>Tgl Akhir</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->bantuan_nama }}</td>
                    <td>{{ $b->sasaran->sasaran ?? '-' }}</td>
                    <td>{{ $b->bantuan->bantuan ?? '-' }}</td>
                    <td>{{ $b->sumber->sumber_dana ?? '-' }}</td>
                    <td>{{ $b->bantuan_awal }}</td>
                    <td>{{ $b->bantuan_akhir }}</td>
                    <td>{{ number_format($b->bantuan_jumlah, 0, ',', '.') }}</td>
                    <td>{{ $b->bantuan_keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
