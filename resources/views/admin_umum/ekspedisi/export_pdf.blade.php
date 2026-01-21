<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Buku Ekspedisi</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .center {
            text-align: center;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h3>BUKU EKSPEDISI SURAT</h3>

    @if ($search)
        <p>Filter: <b>{{ $search }}</b></p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No Surat</th>
                <th>Tanggal Surat</th>
                <th>Identitas</th>
                <th>Isi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $d)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="center">{{ $d->ekspedisi_tanggal }}</td>
                    <td>{{ $d->ekspedisi_nomorsurat }}</td>
                    <td class="center">{{ $d->ekspedisi_tanggalsurat }}</td>
                    <td>{{ $d->ekspedisi_identitassurat }}</td>
                    <td>{{ $d->ekspedisi_isisurat }}</td>
                    <td>{{ $d->ekspedisi_keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
