<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penduduk & Intervensi Kemiskinan</title>
    <style>
        @page { margin: 15mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; color:#1f2937; font-size:11pt; line-height:1.6; margin:0; }
        h1,h2 { color:#1e40af; text-align:center; }
        h2 { border-bottom:2px solid #6366f1; padding-bottom:4px; margin-top:20px; }
        table { width:100%; border-collapse:collapse; margin:10px 0; }
        th,td { border:1px solid #e5e7eb; padding:6px; }
        th { background:#eef2ff; text-align:center; }
        .right { text-align:right; }
        .page-break { page-break-before: always; }
        .small { font-size:9pt; color:#6b7280; }
        .catatan { background:#e0e7ff; border-left:5px solid #6366f1; padding:12px; border-radius:8px; margin:15px 0; line-height:1.7; }
    </style>
</head>
<body>

<h1>Laporan Analisis Data Penduduk Desa</h1>
    <p class="small">Periode: <strong>{{ $periode }}</strong> | Dicetak: <strong>{{ $tanggal }}</strong></p>

    <h2>1. Statistik Umum Penduduk</h2>
    <table>
        <thead><tr><th>Kategori</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
        <tbody>
            <tr><td>Total Penduduk</td><td class="right">{{ number_format($totalPenduduk) }}</td><td class="right">100</td></tr>
            <tr><td>Laki-laki</td><td class="right">{{ number_format($laki) }}</td><td class="right">{{ $persenLaki }}</td></tr>
            <tr><td>Perempuan</td><td class="right">{{ number_format($perempuan) }}</td><td class="right">{{ $persenPerempuan }}</td></tr>

            <tr><td colspan="3"><strong>Alasan Tidak Bekerja</strong></td></tr>
            <tr><td>&nbsp;&nbsp;• Masih Sekolah/Pelajar</td><td class="right">{{ number_format($totalSekolah) }}</td><td class="right">{{ $totalPenduduk > 0 ? round($totalSekolah / $totalPenduduk * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Laki-laki</td><td class="right">{{ number_format($sekolahLaki) }}</td><td class="right">{{ $laki > 0 ? round($sekolahLaki / $laki * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Perempuan</td><td class="right">{{ number_format($sekolahPerempuan) }}</td><td class="right">{{ $perempuan > 0 ? round($sekolahPerempuan / $perempuan * 100, 1) : 0 }}</td></tr>

            <tr><td>&nbsp;&nbsp;• Pensiunan</td><td class="right">{{ number_format($totalPensiunan) }}</td><td class="right">{{ $totalPenduduk > 0 ? round($totalPensiunan / $totalPenduduk * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Laki-laki</td><td class="right">{{ number_format($pensiunanLaki) }}</td><td class="right">{{ $laki > 0 ? round($pensiunanLaki / $laki * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Perempuan</td><td class="right">{{ number_format($pensiunanPerempuan) }}</td><td class="right">{{ $perempuan > 0 ? round($pensiunanPerempuan / $perempuan * 100, 1) : 0 }}</td></tr>

            <tr><td>&nbsp;&nbsp;• Mengurus Rumah Tangga</td><td class="right">{{ number_format($totalRumahTangga) }}</td><td class="right">{{ $totalPenduduk > 0 ? round($totalRumahTangga / $totalPenduduk * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Laki-laki</td><td class="right">{{ number_format($rumahTanggaLaki) }}</td><td class="right">{{ $laki > 0 ? round($rumahTanggaLaki / $laki * 100, 1) : 0 }}</td></tr>
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;↳ Perempuan</td><td class="right">{{ number_format($rumahTanggaPerempuan) }}</td><td class="right">{{ $perempuan > 0 ? round($rumahTanggaPerempuan / $perempuan * 100, 1) : 0 }}</td></tr>

            <tr><td>Total Tidak Bekerja (termasuk lainnya)</td><td class="right">{{ number_format($totalPenduduk * $persenTidakBekerja / 100) }}</td><td class="right">{{ $persenTidakBekerja }}</td></tr>
        </tbody>
    </table>
    <div class="page-break"></div>

    <div class="catatan">
        <b>Interpretasi:</b> Tingkat pengangguran sebesar {{ $persenTidakBekerja }}% menunjukkan adanya tekanan ekonomi yang signifikan pada penduduk usia produktif. Apabila persentase ini meningkat dibandingkan tahun sebelumnya, hal ini dapat mengindikasikan kurangnya lapangan kerja lokal atau dampak faktor eksternal seperti musim paceklik maupun krisis ekonomi. Sebaliknya, penurunan persentase dapat mencerminkan keberhasilan program pelatihan kerja atau musim panen yang baik. Intervensi ekonomi seperti padat karya dan bantuan modal usaha perlu diprioritaskan, dengan intensitas disesuaikan berdasarkan tren tahunan.
    </div>

    <div class="page-break"></div>

    <h2>2. Distribusi Jenis Kelamin & Agama</h2>
    <table>
        <thead><tr><th>Agama</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
        <tbody>
            @foreach($agamaStat as $row)
                <tr><td>{{ $row['nama'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td><td class="right">{{ $row['persen'] }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Distribusi agama menunjukkan komposisi masyarakat desa yang relatif campuran atau beragam tergantung dominasi satu kelompok. Perubahan persentase dari tahun ke tahun (biasanya 1–5%) dapat disebabkan oleh migrasi penduduk atau konversi. Dominasi agama mayoritas memberikan peluang kolaborasi dengan lembaga keagamaan dalam penyaluran bantuan sosial, namun perlu diperhatikan untuk menghargai agar kelompok minoritas tidak terpinggirkan dalam akses layanan publik.
    </div>

    <div class="page-break"></div>

    <h2>3. Mutasi Penduduk</h2>
    <table>
        <thead><tr><th>Kategori Mutasi</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
        <tbody>
            @foreach($mutasiStat as $row)
                <tr><td>{{ $row['nama'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td><td class="right">{{ $row['persen'] }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Tingginya mutasi keluar (pindah) dibandingkan mutasi masuk dapat mengindikasikan kurangnya peluang ekonomi di desa, yang berpotensi menyebabkan penuaan populasi dan kehilangan tenaga kerja muda. Ketidaksetabilan tahunan sering dipengaruhi oleh faktor musiman (misalnya migrasi kerja pasca-Lebaran) atau kebijakan pemerintah. Peningkatan mutasi keluar perlu direspons dengan program reintegrasi dan pengembangan ekonomi lokal untuk menjaga stabilitas demografi desa.
    </div>

    <div class="page-break"></div>

    <h2>4. Struktur Keluarga dan Hubungan Anggota</h2>
    <table>
        <thead><tr><th>Hubungan</th><th>Jumlah Penduduk</th></tr></thead>
        <tbody>
            @foreach($hubunganStat as $row)
                <tr><td>{{ $row['hubungan'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Jumlah tanggungan (anak, cucu, atau anggota keluarga lain yang tidak bekerja) yang tinggi menunjukkan beban ekonomi besar bagi kepala keluarga. Perubahan komposisi ini dari tahun ke tahun dapat dipengaruhi oleh keberhasilan program keluarga berencana atau dampak pandemi/bencana. Jika proporsi tanggungan meningkat, risiko kemiskinan multigenerasi akan bertambah, sehingga diperlukan intervensi berupa bantuan pendidikan, kesehatan reproduksi, dan nutrisi anak.
    </div>

    <div class="page-break"></div>

    <h2>5. Sebaran Keluarga dan Anggota per Desa</h2>
    <table>
        <thead><tr><th>Wilayah (Desa, Kecamatan)</th><th>Jumlah Keluarga</th><th>Jumlah Penduduk</th><th>Rata-rata Anggota/KK</th></tr></thead>
        <tbody>
            @foreach($dataKK as $row)
                <tr>
                    <td>{{ $row['wilayah'] }}</td>
                    <td class="right">{{ number_format($row['keluarga']) }}</td>
                    <td class="right">{{ number_format($row['penduduk']) }}</td>
                    <td class="right">{{ $row['rata'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Rata-rata anggota per kartu keluarga yang tinggi (>5 orang) mengindikasikan keluarga besar dengan kemungkinan memiliki potensi kesulitan memenuhi kebutuhan dasar. Ketimpangan antarwilayah (misalnya satu dusun padat penduduk) dapat memperburuk akses terhadap layanan publik. Perubahan tahunan dipengaruhi oleh migrasi internal atau pembangunan infrastruktur. Data ini penting untuk pemetaan prioritas bantuan agar lebih tepat sasaran pada wilayah dengan beban tertinggi.
    </div>

    <div class="page-break"></div>

    <h2>6. Rekomendasi Intervensi Pemerintah</h2>
    <table>
        <thead><tr><th>Indikator</th><th>Analisis</th><th>Rekomendasi</th></tr></thead>
        <tbody>
            <tr><td>Pengangguran</td><td>{{ $persenTidakBekerja }}% penduduk belum bekerja.</td><td>Program padat karya, pelatihan kerja, bantuan modal usaha kecil.</td></tr>
            <tr><td>Mutasi Tinggi</td><td>Mobilitas tinggi berpotensi menyebabkan ketimpangan ekonomi.</td><td>Program reintegrasi keluarga migran dan bantuan adaptasi ekonomi.</td></tr>
            <tr><td>Struktur Keluarga</td><td>Banyak tanggungan dalam satu keluarga.</td><td>Bantuan pangan, pendidikan, dan program keluarga berencana.</td></tr>
            <tr><td>Agama & Sosial</td><td>Potensi kolaborasi lembaga sosial dan keagamaan.</td><td>Pemberdayaan komunitas berbasis keagamaan.</td></tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Kesimpulan:</b> Berdasarkan data sosial ekonomi dan kependudukan, pemerintah desa dapat memetakan keluarga rentan dan merancang program pengentasan kemiskinan berbasis data yang lebih tepat sasaran. Evaluasi tahunan terhadap perubahan persentase pada setiap indikator diperlukan untuk menyesuaikan prioritas dan intensitas intervensi agar tetap relevan dengan kondisi terkini.
    </div>

</div>
</body>
</html>