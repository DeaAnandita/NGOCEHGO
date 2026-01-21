@php
    $tanggalSurat = $surat->tanggal_surat ?? ($surat->approved_at ?? $surat->created_at);
    $tanggalSurat = $tanggalSurat ? \Carbon\Carbon::parse($tanggalSurat) : null;
@endphp

<div style="width:800px;margin:auto;padding:30px;font-family:'Times New Roman';font-size:12pt">

    {{-- KOP SURAT --}}
    <table width="100%" style="border-bottom:2px solid black;padding-bottom:10px;margin-bottom:20px;">
        <tr>
            <td width="15%" align="center">
                <img src="{{ asset('images/logo-kudus.png') }}" width="80">
            </td>
            <td width="85%" align="center">
                <div style="font-weight:bold;font-size:16pt;">
                    PEMERINTAH DESA KALIWUNGU
                </div>
                <div style="font-weight:bold;font-size:14pt;">
                    KECAMATAN KALIWUNGU
                </div>
                <div style="font-weight:bold;font-size:14pt;">
                    KABUPATEN KUDUS
                </div>
            </td>
        </tr>
    </table>

    {{-- JUDUL --}}
    <p style="text-align:center;font-weight:bold;text-decoration:underline;margin-top:10px;">
        SURAT KETERANGAN / PENGANTAR
    </p>

    <p style="text-align:center;margin-bottom:20px;">
        Nomor : {{ $surat->nomor_surat }}
    </p>

    <p>
        Yang bertanda tangan di bawah ini Kepala Desa Kaliwungu Kecamatan Kaliwungu Kabupaten Kudus
        menerangkan bahwa:
    </p>

    {{-- DATA --}}
    <table width="100%" cellpadding="4" cellspacing="0" style="margin-top:10px;">
        <tr>
            <td width="30%">Nama</td>
            <td width="3%">:</td>
            <td>{{ strtoupper($surat->nama) }}</td>
        </tr>
        <tr>
            <td>Tempat, Tgl Lahir</td>
            <td>:</td>
            <td>{{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir?->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $surat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Kewarganegaraan</td>
            <td>:</td>
            <td>{{ $surat->kewarganegaraan }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $surat->agama }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ $surat->pekerjaan }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $surat->nik }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $surat->alamat }}</td>
        </tr>
        <tr>
            <td>Keperluan</td>
            <td>:</td>
            <td>{{ $surat->keperluan }}</td>
        </tr>
        <tr>
            <td>Tanggal Surat</td>
            <td>:</td>
            <td>{{ $tanggalSurat?->translatedFormat('d F Y') }}</td>
        </tr>

        @if ($surat->keterangan_lain)
            <tr>
                <td>Keterangan Lain</td>
                <td>:</td>
                <td>{{ $surat->keterangan_lain }}</td>
            </tr>
        @endif
    </table>



    <p style="margin-top:15px;">
        Demikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya.
    </p>

    {{-- TANDA TANGAN --}}
    <table width="100%" style="margin-top:50px;text-align:center;">
        <tr>
            {{-- KIRI — PEMEGANG --}}
            <td width="50%">
                Tanda Tangan<br>
                Pemegang<br><br><br><br><br><br><br><br>

                <u><b>{{ strtoupper($surat->nama) }}</b></u>
            </td>

            {{-- KANAN — KEPALA DESA --}}
            <td width="50%">
                Kaliwungu, {{ $tanggalSurat?->translatedFormat('d F Y') }}<br>
                Kepala Desa Kaliwungu<br><br>

                @if ($surat->barcode_verifikasi_path)
                    <img src="{{ asset($surat->barcode_verifikasi_path) }}" width="90">
                @endif

                <br><br>
                @if ($surat->kode_verifikasi)
                    <p style="text-align:center;font-size:10px;margin-top:15px">
                        Kode Verifikasi: {{ $surat->kode_verifikasi }}
                    </p>
                @endif
            </td>
        </tr>
    </table>



</div>
