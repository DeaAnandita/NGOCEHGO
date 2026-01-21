<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
        }

        .header {
            text-align: center;
            font-weight: bold;
        }

        .signature {
            text-align: center;
            margin-top: 40px;
        }

        .barcode img {
            width: 90px;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>

    @php
        $tanggalSurat = $surat->tanggal_surat ?? ($surat->approved_at ?? $surat->created_at);
    @endphp

    <div class="header">
        PEMERINTAH DESA KALIWUNGU<br>
        KECAMATAN KALIWUNGU<br>
        KABUPATEN KUDUS
        <hr>
    </div>

    <p style="text-align:center;font-weight:bold;text-decoration:underline">
        SURAT KETERANGAN / PENGANTAR
    </p>

    <p style="text-align:center">
        Nomor : {{ $surat->nomor_surat }}
    </p>

    <p>
        Yang bertanda tangan di bawah ini Kepala Desa Kaliwungu Kecamatan Kaliwungu Kabupaten Kudus menerangkan bahwa:
    </p>

    <table>
        <tr>
            <td width="30%">Nama</td>
            <td>{{ strtoupper($surat->nama) }}</td>
        </tr>
        <tr>
            <td>Tempat, Tgl Lahir</td>
            <td>{{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir?->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>{{ $surat->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>{{ $surat->nik }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>{{ $surat->alamat }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>{{ $surat->pekerjaan }}</td>
        </tr>
        <tr>
            <td>Keperluan</td>
            <td>{{ $surat->keperluan }}</td>
        </tr>
    </table>

    <p>Demikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>

    <div class="signature">
        Kaliwungu, {{ \Carbon\Carbon::parse($tanggalSurat)->translatedFormat('d F Y') }}<br>
        Kepala Desa Kaliwungu<br><br>

        @if ($surat->barcode_verifikasi_path)
            <div class="barcode">
                <img src="{{ public_path($surat->barcode_verifikasi_path) }}">
            </div>
        @endif

        ( _______________________ )
    </div>

    @if ($surat->kode_verifikasi)
        <p style="text-align:center;font-size:10px">
            Kode Verifikasi: {{ $surat->kode_verifikasi }}
        </p>
    @endif

</body>

</html>
