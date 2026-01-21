<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pencairan Anggaran</title>

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
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .group {
            background: #fafafa;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center">LAPORAN PENCAIRAN & REALISASI ANGGARAN</h3>

    <table>
        <thead>
            <tr>
                <th>Kegiatan</th>
                <th>Tgl Cair</th>
                <th>Jumlah Cair</th>
                <th>Terpakai</th>
                <th>Sisa</th>
                <th>Uraian</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pencairan as $p)
                @php
                    $terpakai = $p->realisasi->sum('jumlah');
                    $sisa = $p->jumlah - $terpakai;
                    $rows = max(1, $p->realisasi->count());
                @endphp

                @if ($p->realisasi->count() == 0)
                    <tr>
                        <td>{{ $p->kegiatan->nama_kegiatan }}</td>
                        <td class="center">{{ \Carbon\Carbon::parse($p->tanggal_cair)->format('d-m-Y') }}</td>
                        <td class="right">{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($terpakai, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($sisa, 0, ',', '.') }}</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="right">-</td>
                    </tr>
                @else
                    @foreach ($p->realisasi as $i => $r)
                        <tr>
                            @if ($i == 0)
                                <td rowspan="{{ $rows }}" class="group">{{ $p->kegiatan->nama_kegiatan }}</td>
                                <td rowspan="{{ $rows }}" class="center group">
                                    {{ \Carbon\Carbon::parse($p->tanggal_cair)->format('d-m-Y') }}</td>
                                <td rowspan="{{ $rows }}" class="right group">
                                    {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                <td rowspan="{{ $rows }}" class="right group">
                                    {{ number_format($terpakai, 0, ',', '.') }}</td>
                                <td rowspan="{{ $rows }}" class="right group">
                                    {{ number_format($sisa, 0, ',', '.') }}</td>
                            @endif

                            <td>{{ $r->uraian }}</td>
                            <td class="center">{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                            <td class="right">{{ number_format($r->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

</body>

</html>
