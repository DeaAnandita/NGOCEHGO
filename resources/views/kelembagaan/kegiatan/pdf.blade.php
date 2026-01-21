<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kegiatan</title>
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

    <h3 style="text-align:center">LAPORAN KEGIATAN LEMBAGA</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Unit</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Sumber Dana</th>
                <th>Pagu</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Lokasi</th>
                <th>Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kegiatan as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->nama_kegiatan }}</td>
                    <td>{{ $k->jenis->jenis_kegiatan ?? '-' }}</td>
                    <td>{{ $k->unit->unit_keputusan ?? '-' }}</td>
                    <td>{{ $k->periode->tahun_awal ?? '-' }}</td>
                    <td>{{ $k->status->status_kegiatan ?? '-' }}</td>
                    <td>{{ $k->sumberDana->sumber_dana ?? '-' }}</td>
                    <td>{{ number_format($k->pagu_anggaran, 0, ',', '.') }}</td>
                    <td>{{ $k->tgl_mulai }}</td>
                    <td>{{ $k->tgl_selesai }}</td>
                    <td>{{ $k->lokasi }}</td>
                    <td>
                        {{ $k->keputusan ? $k->keputusan->nomor_sk : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
