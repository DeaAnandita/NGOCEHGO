<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Buku Kader</title>
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

    <div class="title">LAPORAN BUKU KADER</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Nama Penduduk</th>
                <th>Pendidikan</th>
                <th>Bidang</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->kdkader }}</td>
                    <td>{{ $k->kader_tanggal }}</td>
                    <td>{{ $k->penduduk->nama ?? '-' }}</td>
                    <td>{{ $k->pendidikan->pendidikan ?? '-' }}</td>
                    <td>{{ $k->bidang->bidang ?? '-' }}</td>
                    <td>{{ $k->status->statuskader ?? '-' }}</td>
                    <td>{{ $k->kader_keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
