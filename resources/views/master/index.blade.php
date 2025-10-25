<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

        <div id="masterGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

            @php
                $cards = [
                    ['route'=>'agama','title'=>'Agama','desc'=>'Kelola data agama','icon'=>'🕌','color'=>'blue'],
                    ['route'=>'aktanikah','title'=>'Akta Nikah','desc'=>'Kelola data akta nikah','icon'=>'💍','color'=>'indigo'],
                    ['route'=>'asetkeluarga','title'=>'Aset Keluarga','desc'=>'Kelola data aset keluarga','icon'=>'🏠','color'=>'green'],
                    ['route'=>'asetlahan','title'=>'Aset Lahan','desc'=>'Kelola data aset lahan & tanah','icon'=>'🌾','color'=>'yellow'],
                    ['route'=>'asetternak','title'=>'Aset Ternak','desc'=>'Kelola data aset ternak','icon'=>'🐄','color'=>'red'],
                    ['route'=>'asetperikanan','title'=>'Aset Perikanan','desc'=>'Kelola data aset perikanan','icon'=>'🐟','color'=>'teal'],
                    ['route'=>'bahanbakarmemasak','title'=>'Bahan Bakar Memasak','desc'=>'Kelola data bahan bakar','icon'=>'🔥','color'=>'orange'],
                    ['route'=>'carapembuangansampah','title'=>'Cara Buang Sampah','desc'=>'Kelola data cara buang sampah','icon'=>'🗑️','color'=>'purple'],
                    ['route'=>'caraperolehanair','title'=>'Cara Perolehan Air','desc'=>'Kelola data cara perolehan air','icon'=>'💧','color'=>'cyan'],
                    ['route'=>'desa','title'=>'Desa','desc'=>'Kelola data desa','icon'=>'🏘️','color'=>'pink'],
                    ['route'=>'dusun','title'=>'Dusun','desc'=>'Kelola data dusun','icon'=>'🏡','color'=>'lime'],
                    ['route'=>'hubungankeluarga','title'=>'Hubungan Keluarga','desc'=>'Kelola data hubungan keluarga','icon'=>'👪','color'=>'blue'],
                    ['route'=>'hubungankepalakeluarga','title'=>'Hubungan Kep Keluarga','desc'=>'Kelola data hubungan kepala keluarga','icon'=>'👨‍👩‍👧‍👦','color'=>'indigo'],
                    ['route'=>'ijasahterakhir','title'=>'Ijasah Terakhir','desc'=>'Kelola data ijasah terakhir','icon'=>'📜','color'=>'green'],
                    ['route'=>'imunisasi','title'=>'Imunisasi','desc'=>'Kelola data imunisasi','icon'=>'💉','color'=>'yellow'],
                    ['route'=>'inventaris','title'=>'Inventaris','desc'=>'Kelola data inventaris','icon'=>'📦','color'=>'red'],
                    ['route'=>'jabatan','title'=>'Jabatan','desc'=>'Kelola data jabatan','icon'=>'🧑‍💼','color'=>'teal'],
                    ['route'=>'jawab','title'=>'Jawab','desc'=>'Kelola data jawab','icon'=>'✍️','color'=>'orange'],
                    ['route'=>'jawabbangun','title'=>'Jawab Bangun','desc'=>'Kelola data jawab bangun','icon'=>'🏗️','color'=>'purple'],
                    ['route'=>'jawabkonflik','title'=>'Jawab Konflik','desc'=>'Kelola data jawab konflik','icon'=>'⚔️','color'=>'cyan'],
                    ['route'=>'jawabkualitasbayi','title'=>'Jawab Kualitas Bayi','desc'=>'Kelola data jawab kualitas bayi','icon'=>'👶','color'=>'pink'],
                    ['route'=>'jawabkualitasibuhamil','title'=>'Jawab Kualitas Ibu Hamil','desc'=>'Kelola data jawab kualitas ibu hamil','icon'=>'🤰','color'=>'lime'],
                    ['route'=>'jawablemdes','title'=>'Jawab Lemdes','desc'=>'Kelola data jawab lemdes','icon'=>'📝','color'=>'blue'],
                    ['route'=>'jawablemek','title'=>'Jawab Lemek','desc'=>'Kelola data jawab lemek','icon'=>'📝','color'=>'indigo'],
                    ['route'=>'jawablemmas','title'=>'Jawab Lemmas','desc'=>'Kelola data jawab lemmas','icon'=>'📝','color'=>'green'],
                    ['route'=>'jawabsarpras','title'=>'Jawab Sarpras','desc'=>'Kelola data jawab sarpras','icon'=>'🏫','color'=>'yellow'],
                    ['route'=>'jawabprogramserta','title'=>'Jawab Program Serta','desc'=>'Kelola data jawab program serta','icon'=>'📋','color'=>'red'],
                    ['route'=>'jawabtempatpersalinan','title'=>'Jawab Temp Persalinan','desc'=>'Kelola data jawab temp persalinan','icon'=>'🏥','color'=>'teal'],
                    ['route'=>'jenisatapbangunan','title'=>'Jenis Atap Bangunan','desc'=>'Kelola data jenis atap bangunan','icon'=>'🏠','color'=>'orange'],
                    ['route'=>'jenisbahangalian','title'=>'Jenis Bahan Galian','desc'=>'Kelola data jenis bahan galian','icon'=>'⛏️','color'=>'purple'],
                    ['route'=>'jenisdindingbangunan','title'=>'Jenis Dinding Bangunan','desc'=>'Kelola data jenis dinding bangunan','icon'=>'🏢','color'=>'cyan'],
                    ['route'=>'jenisdisabilitas','title'=>'Jenis Disabilitas','desc'=>'Kelola data jenis disabilitas','icon'=>'♿','color'=>'pink'],
                    ['route'=>'jenisfisikbangunan','title'=>'Jenis Fisik Bangunan','desc'=>'Kelola data jenis fisik bangunan','icon'=>'🏗️','color'=>'lime'],
                    ['route'=>'jeniskelahiran','title'=>'Jenis Kelahiran','desc'=>'Kelola data jenis kelahiran','icon'=>'👶','color'=>'blue'],
                    ['route'=>'jeniskelamin','title'=>'Jenis Kelamin','desc'=>'Kelola data jenis kelamin','icon'=>'♂️♀️','color'=>'indigo'],
                    ['route'=>'jenislantaibangunan','title'=>'Jenis Lantai Bangunan','desc'=>'Kelola data jenis lantai bangunan','icon'=>'🧱','color'=>'green'],
                    ['route'=>'jenislembaga','title'=>'Jenis Lembaga','desc'=>'Kelola data jenis lembaga','icon'=>'🏛️','color'=>'yellow'],
                    ['route'=>'kartuidentitas','title'=>'Kartu Identitas','desc'=>'Kelola data kartu identitas','icon'=>'🆔','color'=>'red'],
                    ['route'=>'kabupaten','title'=>'Kabupaten','desc'=>'Kelola data kabupaten','icon'=>'🏘️','color'=>'teal'],
                    ['route'=>'kecamatan','title'=>'Kecamatan','desc'=>'Kelola data kecamatan','icon'=>'🏘️','color'=>'orange'],
                    ['route'=>'kondisiatapbangunan','title'=>'Kondisi Atap Bangunan','desc'=>'Kelola data kondisi atap bangunan','icon'=>'🏠','color'=>'purple'],
                    ['route'=>'kondisidindingbangunan','title'=>'Kondisi Dinding Bangunan','desc'=>'Kelola data kondisi dinding bangunan','icon'=>'🏢','color'=>'cyan'],
                    ['route'=>'kondisilantaibangunan','title'=>'Kondisi Lantai Bangunan','desc'=>'Kelola data kondisi lantai bangunan','icon'=>'🧱','color'=>'pink'],
                    ['route'=>'kondisilapanganusaha','title'=>'Kondisi Lapangan Usaha','desc'=>'Kelola data kondisi lapangan usaha','icon'=>'🏬','color'=>'lime'],
                    ['route'=>'lapanganusaha','title'=>'Lapangan Usaha','desc'=>'Kelola data lapangan usaha','icon'=>'🏢','color'=>'blue'],
                    ['route'=>'kondisisumberair','title'=>'Kondisi Sumber Air','desc'=>'Kelola data kondisi sumber air','icon'=>'💧','color'=>'indigo'],
                    ['route'=>'konfliksosial','title'=>'Konflik Sosial','desc'=>'Kelola data konflik sosial','icon'=>'⚔️','color'=>'green'],
                    ['route'=>'kualitasbayi','title'=>'Kualitas Bayi','desc'=>'Kelola data kualitas bayi','icon'=>'👶','color'=>'yellow'],
                    ['route'=>'kualitasibuhamil','title'=>'Kualitas Ibu Hamil','desc'=>'Kelola data kualitas ibu hamil','icon'=>'🤰','color'=>'red'],
                    ['route'=>'lembaga','title'=>'Lembaga','desc'=>'Kelola data lembaga','icon'=>'🏛️','color'=>'teal'],
                    ['route'=>'mutasikeluar','title'=>'Mutasi Keluar','desc'=>'Kelola data mutasi keluar','icon'=>'📤','color'=>'orange'],
                    ['route'=>'mutasimasuk','title'=>'Mutasi Masuk','desc'=>'Kelola data mutasi masuk','icon'=>'📥','color'=>'purple'],
                    ['route'=>'manfaatmataair','title'=>'Manfaat Mata Air','desc'=>'Kelola data Manfaat Mata Air','icon'=>'💦','color'=>'cyan'],
                    ['route'=>'omsetusaha','title'=>'Omset Usaha','desc'=>'Kelola data omset usaha','icon'=>'💰','color'=>'pink'],
                    ['route'=>'partisipasisekolah','title'=>'Partisipasi Sekolah','desc'=>'Kelola data partisipasi sekolah','icon'=>'🏫','color'=>'lime'],
                    ['route'=>'pekerjaan','title'=>'Pekerjaan','desc'=>'Kelola data pekerjaan','icon'=>'💼','color'=>'blue'],
                    ['route'=>'pembangunankeluarga','title'=>'Pembangunan Keluarga','desc'=>'Kelola data pembangunan keluarga','icon'=>'🏘️','color'=>'indigo'],
                    ['route'=>'pembuanganakhirtinja','title'=>'Pembuangan Akhir Tinja','desc'=>'Kelola data pembuangan akhir tinja','icon'=>'🚽','color'=>'green'],
                    ['route'=>'pendapatanperbulan','title'=>'Pendapatan Perbulan','desc'=>'Kelola data pendapatan perbulan','icon'=>'💵','color'=>'yellow'],
                    ['route'=>'penyakitkronis','title'=>'Penyakit Kronis','desc'=>'Kelola data penyakit kronis','icon'=>'⚕️','color'=>'red'],
                    ['route'=>'pertolonganpersalinan','title'=>'Pertolongan Persalinan','desc'=>'Kelola data pertolongan persalinan','icon'=>'🏥','color'=>'teal'],
                    ['route'=>'programserta','title'=>'Program Serta','desc'=>'Kelola data program serta','icon'=>'📋','color'=>'orange'],
                    ['route'=>'provinsi','title'=>'Provinsi','desc'=>'Kelola data provinsi','icon'=>'🏘️','color'=>'purple'],
                    ['route'=>'sarpraskerja','title'=>'Sarpras Kerja','desc'=>'Kelola data sarpras kerja','icon'=>'🏫','color'=>'cyan'],
                    ['route'=>'statuskawin','title'=>'Status Kawin','desc'=>'Kelola data status kawin','icon'=>'💑','color'=>'pink'],
                    ['route'=>'statustinggal','title'=>'Status Tinggal','desc'=>'Kelola data status tinggal','icon'=>'🏡','color'=>'lime'],
                    ['route'=>'statuskedudukankerja','title'=>'Status Kedudukan Kerja','desc'=>'Kelola data status kedudukan kerja','icon'=>'💼','color'=>'blue'],
                    ['route'=>'statuspemilikbangunan','title'=>'Status Pemilik Bangunan','desc'=>'Kelola data status pemilik bangunan','icon'=>'🏠','color'=>'indigo'],
                    ['route'=>'statuspemiliklahan','title'=>'Status Pemilik Lahan','desc'=>'Kelola data status pemilik lahan','icon'=>'🌾','color'=>'green'],
                    ['route'=>'sumberairminum','title'=>'Sumber Air Minum','desc'=>'Kelola data sumber air minum','icon'=>'💧','color'=>'yellow'],
                    ['route'=>'sumberpeneranganutama','title'=>'Sumber Penerangan Utama','desc'=>'Kelola data sumber penerangan utama','icon'=>'💡','color'=>'red'],
                    ['route'=>'sumberdayaterpasang','title'=>'Sumber Daya Terpasang','desc'=>'Kelola data sumber daya terpasang','icon'=>'🔌','color'=>'teal'],
                    ['route'=>'tingkatsulitdisabilitas','title'=>'Tingkat Sulit Disabilitas','desc'=>'Kelola data tingkat sulit disabilitas','icon'=>'♿','color'=>'orange'],
                    ['route'=>'tempatusaha','title'=>'Tempat Usaha','desc'=>'Kelola data tempat usaha','icon'=>'🏬','color'=>'purple'],
                    ['route'=>'tempatpersalinan','title'=>'Tempat Persalinan','desc'=>'Kelola data tempat persalinan','icon'=>'🏥','color'=>'cyan'],
                    ['route'=>'tercantumdalamkk','title'=>'Tercantum Dalam KK','desc'=>'Kelola data tercantum dalam KK','icon'=>'🧾','color'=>'pink'],
                    ['route'=>'typejawab','title'=>'Type Jawab','desc'=>'Kelola data type jawab','icon'=>'✏️','color'=>'lime'],
                ];
            @endphp

            @foreach ($cards as $card)
                <x-master-card 
                    route="{{ $card['route'] }}" 
                    color="{{ $card['color'] }}" 
                    icon="{{ $card['icon'] }}" 
                    title="{{ $card['title'] }}" 
                    desc="{{ $card['desc'] }}" 
                />
            @endforeach

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
