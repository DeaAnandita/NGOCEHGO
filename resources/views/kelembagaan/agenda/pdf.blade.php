<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Agenda</title>
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
            border: 1px solid black;
            padding: 4px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center">LAPORAN AGENDA KELEMBAGAAN</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Unit</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Tempat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agenda as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->judul_agenda }}</td>
                    <td>{{ $a->jenis->jenis_agenda ?? '-' }}</td>
                    <td>{{ $a->unit->unit_keputusan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $a->jam_mulai }} - {{ $a->jam_selesai ?? '-' }}</td>
                    <td>{{ $a->tempat->tempat_agenda ?? '-' }}</td>
                    <td>{{ $a->status->status_agenda ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
