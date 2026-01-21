<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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
    </style>
</head>

<body>

    <h3 style="text-align:center">LAPORAN ANGGARAN KELEMBAGAAN</h3>

    @foreach ($anggaran as $a)
        <p>
            <b>{{ $a->unit->unit_keputusan }}</b> —
            Periode {{ $a->periode->tahun_awal }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Sumber</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $a->total_anggaran;
                    $terpakai = $a->kegiatanAnggaran->sum('nilai_anggaran');
                @endphp

                @foreach ($a->kegiatanAnggaran as $k)
                    <tr>
                        <td>{{ $k->kegiatan->nama_kegiatan }}</td>
                        <td>{{ $k->sumber->sumber_dana }}</td>
                        <td style="text-align:right">
                            Rp {{ number_format($k->nilai_anggaran, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2"><b>Total Anggaran</b></td>
                    <td><b>Rp {{ number_format($total, 0, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td colspan="2">Terpakai</td>
                    <td>Rp {{ number_format($terpakai, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2">Sisa</td>
                    <td>Rp {{ number_format($total - $terpakai, 0, ',', '.') }}</td>
                </tr>

            </tbody>
        </table>
        <br>
    @endforeach

</body>

</html>
