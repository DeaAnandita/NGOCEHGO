<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap Buku Agenda Lembaga</title>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f0f0f0;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        h3 {
            margin-bottom: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h3>REKAP BUKU AGENDA LEMBAGA</h3>

    @if ($search)
        <p style="font-size:10px">
            Filter pencarian: <b>{{ $search }}</b>
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Jenis Agenda</th>
                <th>Kode</th>
                <th>No Surat</th>
                <th>Tanggal Surat</th>
                <th>Identitas Surat</th>
                <th>Isi Surat</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $i => $d)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="center">{{ $d->agendalembaga_tanggal }}</td>
                    <td>{{ $d->jenisAgenda->jenisagenda_umum ?? '-' }}</td>
                    <td>{{ $d->kdagendalembaga }}</td>
                    <td>{{ $d->agendalembaga_nomorsurat }}</td>
                    <td class="center">{{ $d->agendalembaga_tanggalsurat }}</td>
                    <td>{{ $d->agendalembaga_identitassurat }}</td>
                    <td>{{ $d->agendalembaga_isisurat }}</td>
                    <td>{{ $d->agendalembaga_keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:10px;font-size:10px">
        Data ini diambil dari sistem administrasi desa dan digunakan sebagai arsip resmi.
    </p>

</body>

</html>
