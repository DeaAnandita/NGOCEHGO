<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-3">
            <h2 class="text-2xl font-semibold mb-6">📊 Master Data</h2>

            <!-- Search bar -->
            <div class="relative w-full sm:w-72">
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Cari master data..."
                    class="w-full rounded-xl border-blue-300 focus:ring-2 focus:ring-blue-400 pl-10 pr-4 py-2 text-sm"
                >
                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
            </div>
        </div>

        <!-- Tambahkan ID di sini -->
        <div id="masterGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card -->
            <x-master-card route="agama" color="blue" icon="🏠" title="Agama" desc="Kelola data agama" />
            <x-master-card route="aktanikah" color="blue" icon="🏠" title="Akta Nikah" desc="Kelola data akta nikah" />
            <x-master-card route="asetkeluarga" color="blue" icon="🏠" title="Aset Keluarga" desc="Kelola data aset keluarga" />
            <x-master-card route="asetlahan" color="blue" icon="🌾" title="Aset Lahan" desc="Kelola data aset lahan & tanah" />
            <x-master-card route="asetternak" color="blue" icon="🐄" title="Aset Ternak" desc="Kelola data aset ternak" />
            <x-master-card route="asetperikanan" color="blue" icon="🏠" title="Aset Perikanan" desc="Kelola data aset perikanan" />
            <x-master-card route="bahanbakarmemasak" color="blue" icon="🏠" title="Bahan Bakar Memasak" desc="Kelola data bahan bakar memasak" />
            <x-master-card route="carapembuangansampah" color="blue" icon="🏠" title="Cara Buang Sampah" desc="Kelola data cara buang sampah" />
            <x-master-card route="caraperolehanair" color="blue" icon="🏠" title="Cara Perolehan Air" desc="Kelola data cara perolehan air" />
            <x-master-card route="desa" color="blue" icon="🏠" title="Desa" desc="Kelola data desa" />
            <x-master-card route="dusun" color="blue" icon="🏠" title="Dusun" desc="Kelola data dusun" />
            <x-master-card route="fasilitastempatbab" color="blue" icon="🏠" title="Fasilitas Tempat Bab" desc="Kelola data fasilitas tempat bab" />
            <x-master-card route="hubungankeluarga" color="blue" icon="🏠" title="Hubungan Keluarga" desc="Kelola data hubungan keluarga" />
            <x-master-card route="hubungankepalakeluarga" color="blue" icon="🏠" title="Hubungan Kep Keluarga" desc="Kelola data hubungan kep keluarga" />
            <x-master-card route="ijasahterakhir" color="blue" icon="🏠" title="Ijasah Terakhir" desc="Kelola data ijasah terakhir" />
            <x-master-card route="imunisasi" color="blue" icon="🏠" title="Imunisasi" desc="Kelola data imunisasi" />
            <x-master-card route="inventaris" color="blue" icon="🏠" title="Inventaris" desc="Kelola data inventaris" />
            <x-master-card route="jabatan" color="blue" icon="🏠" title="Jabatan" desc="Kelola data jabatan" />
            <x-master-card route="jawab" color="blue" icon="🏠" title="Jawab" desc="Kelola data jawab" />
            <x-master-card route="jawabbangun" color="blue" icon="🏠" title="Jawab Bangun" desc="Kelola data jawab bangun" />
            <x-master-card route="jawabkonflik" color="blue" icon="🏠" title="Jawab Konflik" desc="Kelola data jawab konflik" />
            <x-master-card route="jawabkualitasbayi" color="blue" icon="🏠" title="Jawab Kualitas Bayi" desc="Kelola data jawab kualitas bayi" />
            <x-master-card route="jawabkualitasibuhamil" color="blue" icon="🏠" title="Jawab Kualitas Ibu Hamil" desc="Kelola data jawab kualitas ibu hamil" />
            <x-master-card route="jawablemdes" color="blue" icon="🏠" title="Jawab Lemdes" desc="Kelola data jawab lemdes" />
            <x-master-card route="jawablemek" color="blue" icon="🏠" title="Jawab Lemek" desc="Kelola data jawab lemek" />
            <x-master-card route="jawablemmas" color="blue" icon="🏠" title="Jawab Lemmas" desc="Kelola data jawab lemmas" />
            <x-master-card route="jawabsarpras" color="blue" icon="🏠" title="Jawab Sarpras" desc="Kelola data jawab sarpras" />
            <x-master-card route="jawabprogramserta" color="blue" icon="🏠" title="Jawab Program Serta" desc="Kelola data jawab program serta" />
            <x-master-card route="jawabtempatpersalinan" color="blue" icon="🏠" title="Jawab Temp Persalinan" desc="Kelola data jawab temp persalinan" />
            <x-master-card route="jenisatapbangunan" color="blue" icon="🏠" title="Jenis Atap Bangunan" desc="Kelola data jenis atap bangunan" />
            <x-master-card route="jenisbahangalian" color="blue" icon="🏠" title="Jenis Bahan Galian" desc="Kelola data jenis bahan galian" />
            <x-master-card route="jenisdindingbangunan" color="blue" icon="🏠" title="Jenis Dinding Bangunan" desc="Kelola data jenis dinding bangunan" />
            <x-master-card route="jenisdisabilitas" color="blue" icon="🏠" title="Jenis Disabilitas" desc="Kelola data jenis disabilitas" />
            <x-master-card route="jenisfisikbangunan" color="blue" icon="🏠" title="Jenis Fisik Bangunan" desc="Kelola data jenis fisik bangunan" />
            <x-master-card route="jeniskelahiran" color="blue" icon="🏠" title="Jenis Kelahiran" desc="Kelola data jenis kelahiran" />
            <x-master-card route="jeniskelamin" color="blue" icon="🏠" title="Jenis Kelamin" desc="Kelola data jenis kelamin" />
            <x-master-card route="jenislantaibangunan" color="blue" icon="🏠" title="Jenis Lantai Bangunan" desc="Kelola data jenis lantai bangunan" />
            <x-master-card route="jenislembaga" color="blue" icon="🏠" title="Jenis Lembaga" desc="Kelola data jenis lembaga" />
            <x-master-card route="kartuidentitas" color="blue" icon="🏠" title="Kartu Identitas" desc="Kelola data kartu identitas" />
            <x-master-card route="kabupaten" color="blue" icon="🏠" title="Kabupaten" desc="Kelola data kabupaten" />
            <x-master-card route="kecamatan" color="blue" icon="🏠" title="Kecamatan" desc="Kelola data kecamatan" />
            <x-master-card route="kondisiatapbangunan" color="blue" icon="🏠" title="Kondisi Atap Bangunan" desc="Kelola data kondisi atap bangunan" />
            <x-master-card route="kondisidindingbangunan" color="blue" icon="🏠" title="Kondisi Dinding Bangunan" desc="Kelola data kondisi dinding bangunan" />
            <x-master-card route="kondisilantaibangunan" color="blue" icon="🏠" title="Kondisi Lantai Bangunan" desc="Kelola data kondisi lantai bangunan" />
            <x-master-card route="kondisilapanganusaha" color="blue" icon="🏠" title="Kondisi Lapangan Usaha" desc="Kelola data kondisi lapangan usaha" />
            <x-master-card route="lapanganusaha" color="blue" icon="🏠" title="Lapangan Usaha" desc="Kelola data lapangan usaha" />
            <x-master-card route="kondisisumberair" color="blue" icon="🏠" title="Kondisi Sumber Air" desc="Kelola data kondisi sumber air" />
            <x-master-card route="konfliksosial" color="blue" icon="🏠" title="Konflik Sosial" desc="Kelola data konflik sosial" />
            <x-master-card route="kualitasbayi" color="blue" icon="🏠" title="Kualitas Bayi" desc="Kelola data kualitas bayi" />
            <x-master-card route="kualitasibuhamil" color="blue" icon="🏠" title="Kualitas Ibu Hamil" desc="Kelola data kualitas ibu hamil" />
            <x-master-card route="lembaga" color="blue" icon="🏠" title="Lembaga" desc="Kelola data lembaga" />
            <x-master-card route="mutasikeluar" color="blue" icon="🏠" title="Mutasi Keluar" desc="Kelola data mutasi keluar" />
            <x-master-card route="mutasimasuk" color="blue" icon="🏠" title="Mutasi Masuk" desc="Kelola data mutasi masuk" />
            <x-master-card route="manfaatmataair" color="blue" icon="🏠" title="Manfaat Mata Air" desc="Kelola data Manfaat Mata Air" />
            <x-master-card route="omsetusaha" color="blue" icon="🏠" title="Omset Usaha" desc="Kelola data omset usaha" />
            <x-master-card route="partisipasisekolah" color="blue" icon="🏠" title="Partisipasi Sekolah" desc="Kelola data partisipasi sekolah" />
            <x-master-card route="pekerjaan" color="blue" icon="🏠" title="Pekerjaan" desc="Kelola data pekerjaan" />
            <x-master-card route="pembangunankeluarga" color="blue" icon="🏠" title="Pembangunan Keluarga" desc="Kelola data pembangunan keluarga" />
            <x-master-card route="pembuanganakhirtinja" color="blue" icon="🏠" title="Pembuangan Akhir Tinja" desc="Kelola data pembuangan akhir tinja" />
            <x-master-card route="pendapatanperbulan" color="blue" icon="🏠" title="Pendapatan Perbulan" desc="Kelola data pendapatan perbulan" />
            <x-master-card route="penyakitkronis" color="blue" icon="🏠" title="Penyakit Kronis" desc="Kelola data penyakit kronis" />
            <x-master-card route="pertolonganpersalinan" color="blue" icon="🏠" title="Pertolongan Persalinan" desc="Kelola data pertolongan persalinan" />
            <x-master-card route="programserta" color="blue" icon="🏠" title="Program Serta" desc="Kelola data program serta" />
            <x-master-card route="provinsi" color="blue" icon="🏠" title="Provinsi" desc="Kelola data provinsi" />
            <x-master-card route="sarpraskerja" color="blue" icon="🏠" title="Sarpras Kerja" desc="Kelola data sarpras kerja" />
            <x-master-card route="statuskawin" color="blue" icon="🏠" title="Status Kawin" desc="Kelola data status kawin" />
            <x-master-card route="statustinggal" color="blue" icon="🏠" title="Status Tinggal" desc="Kelola data status tinggal" />
            <x-master-card route="statuskedudukankerja" color="blue" icon="🏠" title="Status Kedudukan Kerja" desc="Kelola data status kedudukan kerja" />
            <x-master-card route="statuspemilikbangunan" color="blue" icon="🏠" title="Status Pemilik Bangunan" desc="Kelola data status pemilik bangunan" />
            <x-master-card route="statuspemiliklahan" color="blue" icon="🏠" title="Status Pemilik Lahan" desc="Kelola data status pemilik lahan" />
            <x-master-card route="sumberairminum" color="blue" icon="🏠" title="Sumber Air Minum" desc="Kelola data sumber air minum" />
            <x-master-card route="sumberpeneranganutama" color="blue" icon="🏠" title="Sumber Penerangan Utama" desc="Kelola data sumber penerangan utama" />
            <x-master-card route="sumberdayaterpasang" color="blue" icon="🏠" title="Sumber Daya Terpasang" desc="Kelola data sumber daya terpasang" />
            <x-master-card route="tingkatsulitdisabilitas" color="blue" icon="🏠" title="Tingkat Sulit Disabilitas" desc="Kelola data tingkat sulit disabilitas" />
            <x-master-card route="tempatusaha" color="blue" icon="🏠" title="Tempat Usaha" desc="Kelola data tempat usaha" />
            <x-master-card route="tempatpersalinan" color="blue" icon="🏠" title="Tempat Persalinan" desc="Kelola data tempat persalinan" />
            <x-master-card route="tercantumdalamkk" color="blue" icon="🏠" title="Tercantum Dalam KK" desc="Kelola data tercantum dalam KK" />
            <x-master-card route="typejawab" color="blue" icon="🏠" title="Type Jawab" desc="Kelola data type jawab" />
        </div>
    </div>

    <!-- Script pencarian -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const masterGrid = document.getElementById('masterGrid');
        const cards = masterGrid.querySelectorAll('a');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
