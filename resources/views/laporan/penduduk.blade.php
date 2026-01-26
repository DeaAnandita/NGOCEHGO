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

    <h2>5A. Rekapitulasi Penduduk & KK Keseluruhan</h2>
    <table>
        <tr><th>Total KK</th><th>Total Penduduk</th></tr>
        <tr>
            <td class="right">{{ number_format($rekapPenduduk->total_keluarga) }}</td>
            <td class="right">{{ number_format($rekapPenduduk->total_penduduk) }}</td>
        </tr>
    </table>
    <div class="catatan">
    <b>Interpretasi 5A: Rekapitulasi Penduduk & KK Keseluruhan</b><br>
    Laporan menunjukkan bahwa total jumlah keluarga (KK) sebanyak <strong>{{ number_format($rekapPenduduk->total_keluarga) }}</strong> KK dengan total penduduk sebesar <strong>{{ number_format($rekapPenduduk->total_penduduk) }}</strong> jiwa. Informasi ini menjadi dasar penting dalam merencanakan layanan publik seperti kesehatan, pendidikan dan fasilitas umum lainnya.
    Jumlah penduduk ini mencerminkan potensi SDM yang tersedia dan dapat digunakan sebagai acuan dalam pengambilan keputusan pembangunan desa yang lebih tepat sasaran.
    </div>
    <h2>5B. Rekap Berdasarkan Dusun / Lingkungan</h2>
    <table>
        <thead><tr><th>Dusun</th><th>Total KK</th><th>Total Penduduk</th></tr></thead>
        <tbody>
            @foreach($perDusunPenduduk as $row)
            <tr>
                <td>{{ $row->dusun }}</td>
                <td class="right">{{ number_format($row->total_keluarga) }}</td>
                <td class="right">{{ number_format($row->total_penduduk) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="catatan">
    <b>Interpretasi 5B: Rekap Berdasarkan Dusun / Lingkungan</b><br>
    Dari rekap per dusun/lingkungan, tampak bahwa jumlah keluarga dan penduduk berbeda-beda antar dusun. Dusun dengan jumlah penduduk besar menunjukkan area yang padat aktivitas sosial dan ekonomi, sementara dusun dengan jumlah penduduk lebih sedikit bisa menandakan area yang belum berkembang atau lebih terpencil.
    Perbedaan ini penting untuk mengidentifikasi kebutuhan layanan dasar seperti fasilitas kesehatan dan pendidikan, serta untuk memprioritaskan alokasi anggaran pembangunan berdasarkan kebutuhan penduduk di setiap dusun.
    </div>
    <div class="page-break"></div>

    <h2>5C. Rekap Berdasarkan RW / RT</h2>
    @foreach($rekapPendudukByRw as $rw => $rts)
        <strong>RW {{ $rw }}</strong>
        <table>
            <thead>
                <tr><th>RT</th><th>Total KK</th><th>Total Penduduk</th></tr>
            </thead>
            <tbody>
                @foreach($rts as $row)
                <tr>
                    <td>{{ $row->rt }}</td>
                    <td class="right">{{ number_format($row->total_keluarga) }}</td>
                    <td class="right">{{ number_format($row->total_penduduk) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Interpretasi 5C hanya sekali di sini — setelah semua RW/RT ditampilkan -->
    <div class="catatan">
        <b>Interpretasi 5C: Rekap Berdasarkan RW / RT</b><br>
        Rekap RW/RT memberikan gambaran lengkap mengenai sebaran jumlah keluarga dan penduduk di tingkat lingkungan terkecil (RT) berdasarkan kelompok RW. Struktur ini membantu mengidentifikasi RT atau RW dengan jumlah penduduk yang relatif tinggi atau rendah.  
        RT/RW dengan angka penduduk dan keluarga yang tinggi bisa menjadi fokus perencanaan program sosial & ekonomi, misalnya fasilitasi layanan kesehatan bergerak, prioritas dalam bantuan sosial (PKH, BPNT, BLT), atau kelompok pemberdayaan ekonomi lokal.  
        Data ini juga relevan untuk pembagian sasaran layanan berbasis komunitas, pengembangan jalur darurat kesehatan, serta perencanaan distribusi sumber daya desa yang lebih efisien.
    </div>

    <div class="page-break"></div>

    <h2>6. Rekomendasi Intervensi Pemerintah</h2>
    <table>
        <thead>
            <tr>
                <th>Indikator</th>
                <th>Analisis & Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pengangguran</td>
                <td>
                    {{ $persenTidakBekerja }}% penduduk belum bekerja. Ini berarti banyak warga belum mendapat pekerjaan layak.  
                    <b>Rekomendasi:</b> Perlu program pelatihan kerja sesuai kebutuhan pasar (mis. digital, keterampilan usaha), peluang kerja lokal, dan dukungan modal usaha kecil untuk pemuda dan pencari kerja.
                </td>
            </tr>
            <tr>
                <td>Mutasi Penduduk</td>
                <td>
                    Mobilitas penduduk yang tinggi dapat menciptakan ketidakseimbangan sosial-ekonomi di desa.  
                    <b>Rekomendasi:</b> Perkuat sistem pendaftaran penduduk dan layanan adaptasi sosial bagi warga pendatang agar mereka cepat terintegrasi dan dapat berkontribusi pada ekonomi lokal.
                </td>
            </tr>
            <tr>
                <td>Struktur Keluarga</td>
                <td>
                    Banyak keluarga memiliki tanggungan (anak, lansia, orang yang tidak bekerja), yang bisa menambah beban ekonomi rumah tangga.  
                    <b>Rekomendasi:</b> Fasilitasi program bantuan pangan, beasiswa pendidikan anak miskin, serta penyuluhan keluarga berencana untuk meningkatkan kesejahteraan keluarga.
                </td>
            </tr>
            <tr>
                <td>Kepadatan Dusun</td>
                <td>
                    Ada variasi jumlah penduduk antar dusun; dusun yang lebih padat memerlukan lebih banyak layanan dasar.  
                    <b>Rekomendasi:</b> Prioritaskan pembangunan fasilitas kesehatan (posyandu/klinik desa), pendidikan (PAUD/TK), dan ruang publik untuk warga di dusun dengan kepadatan tinggi.
                </td>
            </tr>
            <tr>
                <td>RW / RT Padat</td>
                <td>
                    Beberapa RW/RT memiliki jumlah keluarga dan penduduk lebih tinggi daripada rata-rata.  
                    <b>Rekomendasi:</b> Gunakan RW/RT sebagai basis program pemberdayaan mikro (mis. pelatihan usaha komunitas, bank sampah, kelompok ibu produktif) untuk membantu penduduk bangkit secara ekonomi.
                </td>
            </tr>
            <tr>
                <td>Komunitas & Sosial</td>
                <td>
                    Data menunjukkan potensi kolaborasi antar kelompok sosial dan agama.  
                    <b>Rekomendasi:</b> Bangun kerja sama dengan lembaga keagamaan, kelompok pemuda, dan komunitas lokal untuk memperluas jangkauan program sosial dan ekonomi desa.
                </td>
            </tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Kesimpulan:</b>  
        Berdasarkan data kependudukan terbaru, desa memiliki potensi SDM yang besar tetapi masih menghadapi tantangan pengangguran dan struktur keluarga dengan banyak tanggungan.  
        Rekomendasi di atas dirancang untuk meningkatkan kesejahteraan masyarakat secara nyata dengan pendekatan yang mudah dipahami dan terukur. Evaluasi secara berkala diperlukan untuk menyesuaikan program dengan dinamika kondisi desa.
    </div>


</div>
</body>
</html>