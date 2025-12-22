<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Data Kelahiran Desa</title>
    <style>
        @page { margin: 15mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; color:#1f2937; font-size:11pt; line-height:1.6; margin:0; }
        h1 { color:#1e40af; text-align:center; margin-bottom:10px; }
        h2 { color:#1e40af; text-align:center; border-bottom:2px solid #6366f1; padding-bottom:6px; margin:30px 0 20px 0; }
        table { width:100%; border-collapse:collapse; margin:15px 0; }
        th, td { border:1px solid #e5e7eb; padding:8px; vertical-align:middle; }
        th { background:#eef2ff; text-align:center; font-weight:bold; }
        .right { text-align:right; }
        .center { text-align:center; }
        .page-break { page-break-before: always; }
        .small { font-size:9pt; color:#6b7280; text-align:center; margin-bottom:20px; }
        .catatan { background:#e0e7ff; border-left:5px solid #6366f1; padding:14px; border-radius:8px; margin:20px 0; line-height:1.7; font-size:10.5pt; }
        .footer-note { font-size:9pt; color:#6b7280; text-align:center; margin-top:30px; }
    </style>
</head>
<body>

<div class="container">

    <!-- Halaman Judul -->
    <h1>Laporan Analisis Data Kelahiran Desa</h1>
    <p class="small">Periode: <strong>{{ $periode }}</strong> | Dicetak: <strong>{{ $tanggal }}</strong></p>

    <!-- 1. Statistik Umum Kelahiran -->
    <h2>1. Statistik Umum Kelahiran</h2>
    <table>
        <thead>
            <tr><th>Kategori</th><th>Jumlah</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            <tr><td>Total Kelahiran Tercatat</td><td class="right">{{ number_format($totalKelahiran) }}</td><td class="right">100</td></tr>
            <tr><td>Laki-laki</td><td class="right">{{ number_format($laki) }}</td><td class="right">{{ $persenLaki }}</td></tr>
            <tr><td>Perempuan</td><td class="right">{{ number_format($perempuan) }}</td><td class="right">{{ $persenPerempuan }}</td></tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Rasio jenis kelamin kelahiran sebesar {{ $persenLaki }}% laki-laki dan {{ $persenPerempuan }}% perempuan menunjukkan distribusi yang {{ abs($persenLaki - 50) <= 5 ? 'seimbang dan normal' : 'tidak seimbang' }}. Jika dibandingkan tahun sebelumnya, perubahan signifikan dapat mengindikasikan faktor migrasi atau preferensi gender. Rasio ideal secara alami berkisar 105–107 laki-laki per 100 perempuan.
    </div>

    <div class="page-break"></div>

    <!-- 2. Tempat Persalinan -->
    <h2>2. Tempat Persalinan</h2>
    <table>
        <thead>
            <tr><th>Tempat Persalinan</th><th>Jumlah</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            @foreach($tempatStat as $row)
                <tr>
                    <td>{{ $row['nama'] }}</td>
                    <td class="right">{{ number_format($row['jumlah']) }}</td>
                    <td class="right">{{ $row['persen'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Persentase persalinan di fasilitas kesehatan (RS, Puskesmas, Polindes, Bidan) mencerminkan akses dan kepercayaan masyarakat terhadap layanan formal. Jika persentase di luar faskes masih tinggi (>20%), perlu ditingkatkan sosialisasi dan akses transportasi darurat. Tren peningkatan persalinan institutional dari tahun ke tahun menunjukkan keberhasilan program kesehatan ibu.
    </div>

    <div class="page-break"></div>

    <!-- 3. Jenis Kelahiran -->
    <h2>3. Jenis Kelahiran</h2>
    <table>
        <thead>
            <tr><th>Jenis Kelahiran</th><th>Jumlah Anak</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            @foreach($jenisStat as $row)
                <tr>
                    <td>{{ $row['nama'] }}</td>
                    <td class="right">{{ number_format($row['jumlah']) }}</td>
                    <td class="right">{{ $row['persen'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Mayoritas kelahiran tunggal adalah kondisi normal. Jika ada peningkatan persentase kelahiran kembar atau lebih, perlu diwaspadai faktor genetik, usia ibu lanjut, atau penggunaan teknologi reproduksi. Kelahiran multipel memerlukan perhatian ekstra karena risiko komplikasi lebih tinggi.
    </div>

    <div class="page-break"></div>

    <!-- 4. Pertolongan Persalinan -->
    <h2>4. Pertolongan Persalinan</h2>
    <table>
        <thead>
            <tr><th>Penolong Persalinan</th><th>Jumlah</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            @foreach($tolongStat as $row)
                <tr>
                    <td>{{ $row['nama'] }}</td>
                    <td class="right">{{ number_format($row['jumlah']) }}</td>
                    <td class="right">{{ $row['persen'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Jika masih ada pertolongan oleh dukun atau keluarga, menandakan keterbatasan akses atau kepercayaan tradisional yang kuat. Penurunan persentase non-nakes dari tahun ke tahun mencerminkan keberhasilan program pendampingan dan pelatihan bidan desa.
    </div>

    <div class="page-break"></div>

    <!-- 5. Kondisi Bayi Saat Lahir -->
    <h2>5. Kondisi Bayi Saat Lahir (Berat & Panjang Badan)</h2>
    <table>
        <thead>
            <tr><th>Indikator</th><th>Jumlah</th><th>Persentase (%)</th><th>Keterangan</th></tr>
        </thead>
        <tbody>
            <tr><td>Berat Lahir Rendah (BBLR < 2500 g)</td>
                <td class="right">{{ number_format($jumlahBBLR) }}</td>
                <td class="right">{{ $persenBBLR }}%</td>
                <td>Target nasional: < 7%</td></tr>
            <tr><td>Berat Lahir Normal (≥ 2500 g)</td>
                <td class="right">{{ number_format($jumlahNormalBerat) }}</td>
                <td class="right">{{ $persenNormalBerat }}%</td>
                <td>Normal</td></tr>
            <tr><td>Panjang Lahir Pendek (< 48 cm)</td>
                <td class="right">{{ number_format($jumlahPendek) }}</td>
                <td class="right">{{ $persenPendek }}%</td>
                <td>Risiko stunting dini</td></tr>
            <tr><td>Panjang Lahir Normal (≥ 48 cm)</td>
                <td class="right">{{ number_format($jumlahNormalPanjang) }}</td>
                <td class="right">{{ $persenNormalPanjang }}%</td>
                <td>Normal</td></tr>
            <tr><td>Rata-rata Berat Lahir</td><td colspan="2" class="right">{{ $rataBerat }} gram</td><td>Normal: ≥ 2500 gram</td></tr>
            <tr><td>Rata-rata Panjang Lahir</td><td colspan="2" class="right">{{ $rataPanjang }} cm</td><td>Normal: ≥ 48 cm</td></tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> 
        Persentase BBLR sebesar {{ $persenBBLR }}% {{ $persenBBLR >= 7 ? 'melebihi target nasional dan berisiko tinggi terhadap mortalitas neonatal serta stunting' : 'masih dalam batas aman, namun tetap perlu pemantauan' }}. 
        {{ $persenPendek > 10 ? 'Angka panjang lahir pendek yang tinggi (' . $persenPendek . '%) merupakan indikator awal risiko stunting di masa balita.' : 'Panjang lahir mayoritas normal, menunjukkan pertumbuhan janin yang baik.' }}
        Kelengkapan pengisian data berat lahir {{ $persenDataBeratTerisi }}% dan panjang lahir {{ $persenDataPanjangTerisi }}% — jika rendah, perlu peningkatan pencatatan di posyandu/bidan.
    </div>

    <div class="page-break"></div>

    <!-- 6. Distribusi Kelahiran per Wilayah -->
    <!-- <h2>6. Distribusi Kelahiran per Wilayah</h2>
    <table>
        <thead>
            <tr><th>Wilayah (Desa/Kecamatan)</th><th>Jumlah Kelahiran</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            @foreach($kelahiranPerWilayah as $row)
                <tr>
                    <td>{{ $row['wilayah'] }}</td>
                    <td class="right">{{ number_format($row['jumlah']) }}</td>
                    <td class="right">{{ $row['persen'] }}</td>
                </tr>
            @endforeach
            <tr style="background:#eef2ff; font-weight:bold;">
                <td>Total</td>
                <td class="right">{{ number_format($totalKelahiran) }}</td>
                <td class="right">100</td>
            </tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Distribusi kelahiran antarwilayah menunjukkan konsentrasi populasi dan kebutuhan layanan kesehatan ibu-anak. Wilayah dengan jumlah kelahiran tertinggi perlu diprioritaskan dalam penempatan bidan desa, stok obat esensial, dan kegiatan posyandu. Ketidakmerataan yang tinggi dapat menjadi dasar relokasi sumber daya kesehatan.
    </div>

    <div class="page-break"></div>

    <!-- 7. Urutan Kelahiran -->
    <h2>6. Urutan Kelahiran (Kelahiran ke-)</h2>
    <table>
        <thead>
            <tr><th>Kelahiran ke-</th><th>Jumlah</th><th>Persentase (%)</th></tr>
        </thead>
        <tbody>
            @foreach($kelahiranKeStat as $row)
                <tr>
                    <td class="center">{{ $row['ke'] }}</td>
                    <td class="right">{{ number_format($row['jumlah']) }}</td>
                    <td class="right">{{ $row['persen'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        <b>Interpretasi:</b> Jika kelahiran ke-4 atau lebih masih signifikan (>10%), menandakan program Keluarga Berencana (KB) pasca persalinan belum optimal. Kelahiran dengan urutan tinggi berisiko terhadap kesehatan ibu dan kualitas pengasuhan anak. Peningkatan akses kontrasepsi jangka panjang diperlukan.
    </div>

    <div class="page-break"></div>

    <!-- 8. Rekomendasi Program -->
    <h2>7. Rekomendasi Program Kesehatan Ibu & Anak</h2>
    <table>
        <thead>
            <tr><th>Indikator</th><th>Analisis</th><th>Rekomendasi</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Persalinan di Faskes</td>
                <td>Belum semua ibu melahirkan di fasilitas kesehatan.</td>
                <td>Intensifikasi sosialisasi, pendampingan ibu hamil risiko tinggi, dan transportasi rujukan gratis.</td>
            </tr>
            <tr>
                <td>Penolong oleh Nakes</td>
                <td>Masih ada pertolongan oleh non-tenaga kesehatan.</td>
                <td>Pelatihan berkala bidan desa, program "bidan pendamping", dan penguatan peran kader kesehatan.</td>
            </tr>
            <tr>
                <td>BBLR & Stunting Dini</td>
                <td>Persentase BBLR {{ $persenBBLR }}% dan panjang pendek {{ $persenPendek }}%.</td>
                <td>Program 1000 HPK, pemberian tablet tambah darah rutin, pemeriksaan antenatal lengkap, dan edukasi gizi ibu hamil.</td>
            </tr>
            <tr>
                <td>Urutan Kelahiran Tinggi</td>
                <td>Ada kelahiran anak ke-4 atau lebih.</td>
                <td>Penguatan konseling KB pasca persalinan, penyediaan kontrasepsi gratis, dan kampanye keluarga kecil berkualitas.</td>
            </tr>
        </tbody>
    </table>

    <div class="catatan">
        <b>Kesimpulan:</b> Laporan ini memberikan gambaran komprehensif tentang kondisi kelahiran di desa. Evaluasi rutin setiap tahun terhadap indikator utama (BBLR, tempat persalinan, penolong, dan distribusi wilayah) sangat diperlukan untuk mengukur dampak program dan menyesuaikan intervensi agar lebih tepat sasaran, efektif, dan berbasis data.
    </div>

    <p class="footer-note">Laporan ini dihasilkan secara otomatis dari data Sistem Informasi Desa. Pastikan data kelahiran dan penduduk selalu diperbarui untuk akurasi analisis.</p>

</div>

</body>
</html>