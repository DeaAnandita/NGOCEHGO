<x-app-layout>
    @slot('progresskeluarga')
        <div class="sticky top-16 left-0 right-0 z-40 bg-white shadow-md border-b">
            <div class="max-w-7xl mx-auto overflow-x-auto scrollbar-hide">
                <div id="progressSteps" class="flex items-center space-x-6 px-6 py-4 min-w-max">
                    <!-- JS generate -->
                </div>
            </div>
        </div>
    @endslot
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto py-8 px-6 space-y-6">
        <div class="flex justify-center">
            <button type="submit" id="simpanBtn" form="voiceForm"
                class="px-8 py-3 text-white text-lg font-medium rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                style="background-color: #9ca3af;" disabled>
                Simpan Semua Data
            </button>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 id="modulTitle" class="text-2xl font-bold text-center mb-6 text-green-800">Input Data Keluarga via Suara</h2>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div id="progressBar" class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div class="text-center text-sm text-gray-600 mb-4">
                Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ"></span>
            </div>
            <div id="voice-status" class="text-center text-lg font-medium text-gray-700 mb-6">
                Tekan mic untuk mulai merekam...
            </div>
            <div id="quizArea" class="space-y-6"></div>
            <div class="flex flex-col items-center mt-10">
                <div class="relative">
                    <button id="startBtn" class="relative w-28 h-28 bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                        <svg id="micIcon" xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                        </svg>
                    </button>
                    <canvas id="visualizer" class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity rounded-full" width="112" height="112"></canvas>
                </div>
                <p class="mt-3 text-sm text-gray-500">Tekan untuk mulai merekam</p>
            </div>
        </div>
        <div id="reviewForm" class="hidden bg-white rounded-2xl shadow-lg p-6 mt-6">
            <h3 class="text-xl font-bold text-center mb-6 text-green-700">Review & Edit Data</h3>
            <form id="voiceForm" class="space-y-5">
                @csrf
                <div id="reviewFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"></div>
                <div id="wilayahDatangReview" class="hidden bg-teal-50 p-4 rounded-xl md:col-span-2">
                    <h4 class="font-bold text-sm mb-3">Wilayah Datang</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><label>Provinsi</label>
                            <select name="kdprovinsi" id="kdprovinsi" class="w-full border rounded-lg p-2">
                                <option value="">-- Pilih --</option>
                                @foreach($provinsi as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label>Kabupaten</label><select name="kdkabupaten" id="kdkabupaten" class="w-full border rounded-lg p-2"><option>-- Pilih Provinsi --</option></select></div>
                        <div><label>Kecamatan</label><select name="kdkecamatan" id="kdkecamatan" class="w-full border rounded-lg p-2"><option>-- Pilih Kabupaten --</option></select></div>
                        <div><label>Desa</label><select name="kddesa" id="kddesa" class="w-full border rounded-lg p-2"><option>-- Pilih Kecamatan --</option></select></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .option-card {
            transition: all .3s; border: 2px solid #e5e7eb; cursor: pointer;
            padding: 1rem; border-radius: 1rem; text-align: center;
        }
        .option-card:hover { background-color: #f0fdfa; border-color: #14b8a6; }
        .option-card.selected { background-color: #ccfbf1 !important; border-color: #14b8a6 !important; box-shadow: 0 0 0 3px rgba(20,184,166,.2); }
        /* TAMBAHAN: Untuk modul Bangun Keluarga (8), buat lebar fixed & teks wrap agar panjang sama */
        .bangun .option-card {
            width: 150px; /* Lebar fixed agar 2 card sama panjang */
            flex: 1; /* Merata di grid */
            min-height: 80px; /* Tinggi minimal sama */
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: normal; /* Teks wrap jika panjang */
            word-wrap: break-word;
        }
        #startBtn.listening { background: linear-gradient(to bottom right, #ef4444, #dc2626) !important; transform: scale(1.15); }
    </style>
    <script>
        const masters = @json($masters);
        const mutasiOptions = @json($mutasi);
        const dusunOptions = @json($dusun);
        const provinsiOptions = @json($provinsi);
        const asetKeluargaOptions = @json($asetKeluarga);
        const jawabOptions = @json($jawab);
        const lahanOptions = @json($lahan);
        const jawabLahanOptions = @json($jawabLahan);
        const asetTernakOptions = @json($asetTernak);
        const asetPerikananOptions = @json($asetPerikanan);
        const sarprasOptions = @json($sarprasOptions);
        const jawabSarprasOptions = @json($jawabSarprasOptions);
        const bangunKeluargaOptions = @json($bangunKeluarga);
        const jawabBangunOptions = @json($jawabBangunOptions);
        const konflikSosialOptions = @json($konflikSosialOptions);
        const jawabKonflikOptions = @json($jawabKonflikOptions);
        let currentModul = 1;
        let step = 0;
        let answers = { keluarga_tanggalmutasi: new Date().toISOString().split('T')[0] };
        let modulStatus = {1: 'active', 2: 'pending', 3: 'pending', 4: 'pending', 5: 'pending', 6: 'pending', 7: 'pending', 8: 'pending', 9: 'pending', 10:'pending'};
        let recognition = null;
        let isListening = false;
        let audioContext = null, analyser = null, dataArray = null, canvas = null, ctx = null;
        let isSpeaking = false;
        let isReviewMode = false;
        const modules = [
            {id: 1, name: "Data Keluarga"},
            {id: 2, name: "Prasarana Dasar"},
            {id: 3, name: "Aset Keluarga"},
            {id: 4, name: "Aset Lahan Tanah"},
            {id: 5, name: "Aset Ternak"},
            {id: 6, name: "Aset Perikanan"},
            {id: 7, name: "Sarpras Kerja"},
            {id: 8, name: "Bangun Keluarga"},
            {id: 9, name: "Sejahtera Keluarga"},
            {id:10, name:"Konflik Sosial"}
        ];
        const questions = {
            1: [
                { type: "text", label: "Sebutkan nomor kartu keluarga, 16 digit", field: "no_kk" },
                { type: "select", label: "Jenis mutasi apa?", field: "kdmutasimasuk", options: mutasiOptions },
                { type: "text", label: "Siapa nama kepala rumah tangga?", field: "keluarga_kepalakeluarga" },
                { type: "select", label: "Dusun atau lingkungan apa?", field: "kddusun", options: dusunOptions },
                { type: "number", label: "RW berapa?", field: "keluarga_rw" },
                { type: "number", label: "RT berapa?", field: "keluarga_rt" },
                { type: "text", label: "Sebutkan alamat lengkapnya", field: "keluarga_alamatlengkap" }
            ],
            2: [
                { type: "select", label: "Status pemilik bangunan?", field: "kdstatuspemilikbangunan", options: masters.status_pemilik_bangunan },
                { type: "select", label: "Tanah pemilik lahan bangunan?", field: "kdstatuspemiliklahan", options: masters.status_pemilik_lahan },
                { type: "select", label: "Jenis Fisik Bangunan?", field: "kdjenisfisikbangunan", options: masters.jenis_fisik_bangunan },
                { type: "select", label: "Jenis Lantai Bangunan?", field: "kdjenislantaibangunan", options: masters.jenis_lantai },
                { type: "select", label: "Kondisi Lantai Bangunan?", field: "kdkondisilantaibangunan", options: masters.kondisi_lantai },
                { type: "select", label: "Jenis Dinding Bangunan?", field: "kdjenisdindingbangunan", options: masters.jenis_dinding },
                { type: "select", label: "Kondisi Dinding Bangunan?", field: "kdkondisidindingbangunan", options: masters.kondisi_dinding },
                { type: "select", label: "Jenis Atap Bangunan?", field: "kdjenisatapbangunan", options: masters.jenis_atap },
                { type: "select", label: "Kondisi Atap Bangunan?", field: "kdkondisiatapbangunan", options: masters.kondisi_atap },
                { type: "select", label: "Sumber Air Minum?", field: "kdsumberairminum", options: masters.sumber_air_minum },
                { type: "select", label: "Kondisi Sumber Air Minum?", field: "kdkondisisumberair", options: masters.kondisi_sumber_air },
                { type: "select", label: "Cara Memperoleh Air Minum?", field: "kdcaraperolehanair", options: masters.cara_perolehan_air },
                { type: "select", label: "Sumber Penerangan Utama?", field: "kdsumberpeneranganutama", options: masters.sumber_penerangan },
                { type: "select", label: "Sumber Daya terpasang?", field: "kdsumberdayaterpasang", options: masters.daya_terpasang },
                { type: "select", label: "Bahan Bakar Memasak?", field: "kdbahanbakarmemasak", options: masters.bahan_bakar },
                { type: "select", label: "Penggunaan Fasilitas Tempat BAB?", field: "kdfasilitastempatbab", options: masters.fasilitas_bab },
                { type: "select", label: "Tempat Pembuangan Akhir Tinja?", field: "kdpembuanganakhirtinja", options: masters.pembuangan_tinja },
                { type: "select", label: "Cara Pembuangan Akhir Sampah?", field: "kdcarapembuangansampah", options: masters.pembuangan_sampah },
                { type: "select", label: "Manfaat Mata Air?", field: "kdmanfaatmataair", options: masters.manfaat_mataair },
                { type: "number", label: "Luas Lantai Rumah ini dalam meter persegi?", field: "prasdas_luaslantai" },
                { type: "number", label: "Ada berapa kamar tidur di rumah ini?", field: "prasdas_jumlahkamar" }
            ],
            3: [],
            4: [],
            5: [],
            6: [],
            7: [],
            8: [],
            9: [
                { type: "text", label: "Rata-rata uang saku anak untuk sekolah perhari?", field: "sejahterakeluarga_61", isUraian: true },
                { type: "text", label: "Keluarga memiliki kebiasaan merokok? Jika ya, berapa bungkus perhari?", field: "sejahterakeluarga_62", isUraian: true },
                { type: "text", label: "Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa kali?", field: "sejahterakeluarga_63", isUraian: true },
                { type: "text", label: "Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa jam perhari?", field: "sejahterakeluarga_64", isUraian: true },
                { type: "text", label: "Rata-rata pulsa yang digunakan keluarga seminggu?", field: "sejahterakeluarga_65", isUraian: true },
                { type: "text", label: "Rata-rata pendapatan atau penghasilan keluarga sebulan?", field: "sejahterakeluarga_66", isUraian: true },
                { type: "text", label: "Rata-rata pengeluaran keluarga sebulan?", field: "sejahterakeluarga_67", isUraian: true },
                { type: "text", label: "Rata-rata uang belanja keluarga sebulan?", field: "sejahterakeluarga_68", isUraian: true }
            ],
            10: [],
        };
        // TAMBAH ASET KELUARGA
        Object.entries(asetKeluargaOptions).forEach(([kd, label]) => {
            questions[3].push({
                type: "select",
                label: label + "?",
                field: `asetkeluarga_${kd}`,
                options: jawabOptions
            });
        });
        // TAMBAH ASET LAHAN
        Object.entries(lahanOptions).forEach(([kd, label]) => {
            questions[4].push({
                type: "text",
                label: `${label}? Berapa hektar?`,
                field: `asetlahan_${kd}`,
                isLahan: true
            });
        });
        // TAMBAH ASET TERNAK
        Object.entries(asetTernakOptions).forEach(([kd, label]) => {
            questions[5].push({
                type: "text",
                label: `Jumlah ${label.toLowerCase()} (ekor)?`,
                field: `asetternak_${kd}`,
                isTernak: true
            });
        });
        // TAMBAH ASET PERIKANAN
        Object.entries(asetPerikananOptions).forEach(([kd, label]) => {
            questions[6].push({
                type: "text",
                label: `Jumlah ${label.toLowerCase()}?`,
                field: `asetperikanan_${kd}`,
                isPerikanan: true
            });
        });
        // TAMBAH SARPRAS KERJA
        Object.entries(sarprasOptions).forEach(([kd, label]) => {
            questions[7].push({
                type: "select",
                label: `Memiliki ${label.toLowerCase()} :`,
                field: `sarpraskerja_${kd}`,
                options: jawabSarprasOptions
            });
        });
        // TAMBAH BANGUN KELUARGA (HANYA 51 FIELD PILIHAN, KDTYPEJAWAB=1)
        Object.entries(bangunKeluargaOptions).slice(0, 51).forEach(([kd, label]) => {
            if (kd <= 51) {
                questions[8].push({
                    type: "select",
                    label: label + " :",
                    field: `bangunkeluarga_${kd}`,
                    options: jawabBangunOptions
                });
            }
        });
        // TAMBAH KONFLIK SOSIAL (1-32 FIELDS)
        Object.entries(konflikSosialOptions).forEach(([kd, label]) => {
            questions[10].push({
                type: "select",
                label: `${label} ?`,
                field: `konfliksosial_${kd}`,
                options: jawabKonflikOptions
            });
        });
        const wilayahQuestions = [
            { type: "select", label: "Provinsi asalnya apa?", field: "kdprovinsi", options: provinsiOptions },
            { type: "select", label: "Kabupaten atau kota asalnya apa?", field: "kdkabupaten", dynamic: true, dynamicUrl: "/get-kabupaten/" },
            { type: "select", label: "Kecamatan asalnya apa?", field: "kdkecamatan", dynamic: true, dynamicUrl: "/get-kecamatan/" },
            { type: "select", label: "Desa atau kelurahan asalnya apa?", field: "kddesa", dynamic: true, dynamicUrl: "/get-desa/" }
        ];
        // === UTILITIES ===
        function capitalize(text) { return text.replace(/\b\w/g, l => l.toUpperCase()); }
        function pad3(num) { return String(num).padStart(3, '0'); }
        function normalize(text) { return text.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim(); }
        function cleanOptionText(text) { return text.replace(/\//g, ' atau ').replace(/_/g, ' '); }
        function findBestMatch(text, options) {
            const normText = normalize(text);
            let best = null, score = 0;
            Object.entries(options).forEach(([id, name]) => {
                const normName = normalize(name);
                let s = 0;
                if (normName.includes(normText)) s = 1000;
                else if (normText.includes(normName)) s = 800;
                else normName.split(' ').forEach(w => { if (normText.includes(w)) s += w.length * 3; });
                if (s > score) { score = s; best = [id, name]; }
            });
            return score > 3 ? best : null;
        }
        function mapLahanToCode(ha) {
            if (ha <= 0) return '0';
            if (ha <= 0.2) return '1';
            if (ha <= 0.3) return '2';
            if (ha <= 0.4) return '3';
            if (ha <= 0.5) return '4';
            if (ha <= 0.6) return '5';
            if (ha <= 0.7) return '6';
            if (ha <= 0.8) return '7';
            if (ha <= 0.9) return '8';
            if (ha <= 1.0) return '9';
            if (ha <= 5.0) return '10';
            return '11';
        }
        // Fungsi parse rupiah (handle juta, ribu, dll, hanya angka)
        function parseRupiah(text) {
            const norm = normalize(text);
            let num = 0;
            const numMatch = norm.match(/(\d+(?:\.\d+)?)/);
            if (numMatch) {
                num = parseFloat(numMatch[1]);
            }
            if (norm.includes('juta')) num *= 1000000;
            else if (norm.includes('ribu')) num *= 1000;
            // Handle kata bilangan sederhana
            const wordMap = {
                'satu': 1, 'dua': 2, 'tiga': 3, 'empat': 4, 'lima': 5, 'enam': 6, 'tujuh': 7, 'delapan': 8, 'sembilan': 9, 'sepuluh': 10
            };
            for (let [word, val] of Object.entries(wordMap)) {
                if (norm.includes(word)) {
                    num = val * (norm.includes('juta') ? 1000000 : (norm.includes('ribu') ? 1000 : 1));
                    break;
                }
            }
            return Math.round(num).toString();
        }
        // Fungsi parse angka bulat (hanya angka)
        function parseAngka(text) {
            const norm = normalize(text);
            const numMatch = norm.match(/(\d+)/);
            if (!numMatch) return '0';
            return numMatch[1];
        }
        function speak(text) {
            return new Promise(r => {
                if (isSpeaking) return r();
                isSpeaking = true;
                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = 'id-ID';
                utter.rate = 1.2;
                utter.onend = () => { isSpeaking = false; r(); };
                speechSynthesis.speak(utter);
            });
        }
        // === INIT ===
        function initFresh() {
            localStorage.clear();
            currentModul = 1;
            step = 0;
            answers = { keluarga_tanggalmutasi: new Date().toISOString().split('T')[0] };
            modulStatus = {1: 'active', 2: 'pending', 3: 'pending', 4: 'pending', 5: 'pending', 6: 'pending', 7: 'pending', 8: 'pending', 9: 'pending', 10: 'pending'};
            isReviewMode = false;
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('simpanBtn').style.backgroundColor = '#9ca3af';
            document.getElementById('simpanBtn').disabled = true;
            document.getElementById('quizArea').innerHTML = '';
            document.getElementById('voice-status').innerText = 'Tekan mic untuk mulai merekam...';
            updateProgressSteps();
            renderQuestion();
            checkAllCompletedAndShowSimpanBtn();
        }
        initFresh();
        // === SAVE & LOAD ===
        function saveData() {
            localStorage.setItem('voiceAnswers', JSON.stringify(answers));
            localStorage.setItem('modulStatus', JSON.stringify(modulStatus));
            localStorage.setItem(`step_${currentModul}`, step);
            localStorage.setItem(`review_${currentModul}`, isReviewMode);
            localStorage.setItem('currentModul', currentModul);
        }
        function loadModulData(modId) {
            const savedAnswers = localStorage.getItem('voiceAnswers');
            const savedStatus = localStorage.getItem('modulStatus');
            const savedStep = localStorage.getItem(`step_${modId}`);
            const savedReview = localStorage.getItem(`review_${modId}`);
            if (savedAnswers) answers = JSON.parse(savedAnswers);
            if (savedStatus) modulStatus = JSON.parse(savedStatus);
            if (modulStatus[modId] !== 'completed') {
                step = savedStep !== null ? parseInt(savedStep) : 0;
                isReviewMode = false;
            } else {
                step = savedStep !== null ? parseInt(savedStep) : 0;
                isReviewMode = savedReview === 'true';
            }
            currentModul = modId;
            Object.keys(modulStatus).forEach(k => {
                if (k == modId) modulStatus[k] = 'active';
                else if (modulStatus[k] !== 'completed') modulStatus[k] = 'pending';
            });
        }
        // === PROGRESS STEP BAR ===
        function updateProgressSteps() {
            const container = document.getElementById('progressSteps');
            container.innerHTML = '';
            modules.forEach((mod, i) => {
                if (i > 0) {
                    const line = document.createElement('div');
                    line.className = `h-0.5 w-12 self-center rounded-full ${modulStatus[mod.id] === 'completed' ? 'bg-blue-600' : 'bg-gray-300'}`;
                    container.appendChild(line);
                }
                const div = document.createElement('div');
                div.className = 'flex items-center cursor-pointer';
                div.onclick = () => switchModul(mod.id);
                const status = modulStatus[mod.id] || 'pending';
                const circle = document.createElement('div');
                circle.className = `w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${
                    status === 'completed' ? 'bg-blue-600' : status === 'active' ? 'bg-green-600' : 'bg-gray-300 text-gray-600'
                }`;
                circle.textContent = mod.id;
                const text = document.createElement('div');
                text.className = 'ml-2 text-left';
                text.innerHTML = `<div class="font-medium text-sm">${mod.name}</div>
                                 <div class="text-xs ${status === 'completed' ? 'text-blue-600' : status === 'active' ? 'text-green-600' : 'text-gray-500'}">
                                     ${status === 'completed' ? 'Selesai' : status === 'active' ? 'Aktif' : 'Belum'}
                                 </div>`;
                div.appendChild(circle);
                div.appendChild(text);
                container.appendChild(div);
            });
        }
        // === GANTI MODUL ===
        function switchModul(modId) {
            stopListening();
            saveData();
            loadModulData(modId);
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('quizArea').innerHTML = '';
            document.getElementById('voice-status').innerText = 'Tekan mic untuk mulai merekam...';
            const currentModName = modules.find(m => m.id === modId).name;
            document.getElementById('modulTitle').textContent = currentModName;
            updateProgressSteps();
            checkAllCompletedAndShowSimpanBtn();
            if (modulStatus[modId] === 'completed' && isReviewMode) {
                showReviewForm();
            } else {
                renderQuestion();
            }
        }
        // === STOP LISTENING ===
        function stopListening() {
            if (recognition) { recognition.stop(); recognition = null; }
            if (audioContext) { audioContext.close().catch(() => {}); audioContext = null; }
            analyser = null;
            isListening = false;
            document.getElementById('startBtn').classList.remove('listening');
            document.getElementById('visualizer').style.opacity = 0;
        }
        // === CEK SEMUA SELESAI ===
        function checkAllCompletedAndShowSimpanBtn() {
            const allDone = Object.values(modulStatus).every(s => s === 'completed');
            const btn = document.getElementById('simpanBtn');
            if (allDone) {
                btn.style.backgroundColor = '#2563eb';
                btn.disabled = false;
            } else {
                btn.style.backgroundColor = '#9ca3af';
                btn.disabled = true;
            }
        }
        // === RENDER QUESTION ===
        function renderQuestion() {
            const q = questions[currentModul][step];
            let html = '';
            if (currentModul === 3 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab: <strong class="mx-2">YA</strong> / <strong class="mx-2">TIDAK</strong> / <strong class="mx-2">KOSONG</strong>
                </div>`;
            }
            if (currentModul === 4 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab: <strong>"tidak punya"</strong> atau <strong>angka hektar</strong> (contoh: "3 hektar")
                </div>`;
            }
            if (currentModul === 5 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab dengan angka jumlah ekor, atau "tidak ada", "nol", atau "tidak punya".
                </div>`;
            }
            if (currentModul === 6 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab dengan angka jumlah atau "tidak ada", "nol", "tidak punya".
                </div>`;
            }
            if (currentModul === 7 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab dengan huruf a sampai f sesuai pilihan berikut:<br>
                    a. MILIK SENDIRI(BAGUS/ KONDISI BAIK)<br>
                    b. MILIK SENDIRI(JELEK/ KONDISI TIDAK BAIK)<br>
                    c. MILIK KELOMPOK(SEWA TIDAK BAYAR)<br>
                    d. MILIK ORANG LAIN(SEWA BAYAR)<br>
                    e. MILIK ORANG LAIN(SEWA TIDAK BAYAR)<br>
                    f. TIDAK MEMILIKI
                </div>`;
            }
            if (currentModul === 8 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab: <strong class="mx-2">YA</strong> / <strong class="mx-2">TIDAK</strong>
                </div>`;
            }
            if (currentModul === 9 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab dengan angka saja, atau "tidak ada", "nol" (contoh: "1000000", "2").
                </div>`;
            }
            if (currentModul === 10 && step === 0) {
                html += `<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                    Jawab: <strong class="mx-2">A</strong> untuk ADA / <strong class="mx-2">B</strong> untuk TIDAK ADA
                </div>`;
            }
            html += `<h3 class="text-lg font-medium text-center mb-6 text-gray-800">${q.label}</h3>`;
            if (q.type === "select") {
                let cols = currentModul === 3 ? 3 : (currentModul === 7 ? 3 : (currentModul === 8 ? 2 : (currentModul === 10 ? 3 : 4)));
                let gridClass = currentModul === 8 ? 'bangun grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-3 max-w-md mx-auto justify-items-center' : `grid grid-cols-2 sm:grid-cols-3 md:grid-cols-${cols} gap-3 max-w-5xl mx-auto`;
                html += `<div class="${gridClass}">`;
                Object.entries(q.options).forEach(([id, nama]) => {
                    const selected = answers[q.field] == id ? 'selected' : '';
                    html += `<div class="option-card ${selected}" data-value="${id}" data-text="${nama}">
                        <span class="text-sm font-medium">${nama}</span>
                    </div>`;
                });
                html += '</div>';
            } else {
                const val = answers[q.field] || '';
                html += `<div class="max-w-md mx-auto">
                    <input type="text" id="inputAnswer" class="w-full border border-gray-300 rounded-xl p-3 text-center text-lg" readonly value="${val}" placeholder="Jawaban muncul di sini...">
                </div>`;
            }
            document.getElementById('quizArea').innerHTML = html;
            updateProgress();
            attachCardListeners();
        }
        // === UPDATE PROGRESS ===
        function updateProgress() {
            const total = questions[currentModul].length;
            const percent = ((step + 1) / total) * 100;
            document.getElementById('progressBar').style.width = percent + "%";
            document.getElementById('currentQ').textContent = step + 1;
            document.getElementById('totalQ').textContent = total;
        }
        // === CARD LISTENER ===
        function attachCardListeners() {
            document.querySelectorAll('.option-card').forEach(card => {
                card.onclick = () => {
                    document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    processVoiceAnswer(card.dataset.text.toLowerCase());
                };
            });
        }
        // === BICARA PERTANYAAN + PILIHAN ===
        async function speakQuestionAndOptions() {
            const q = questions[currentModul][step];
            if (currentModul === 3 && step === 0) {
                await speak("Modul Aset Keluarga dimulai. Jawab setiap pertanyaan dengan: YA, TIDAK, atau KOSONG.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 4 && step === 0) {
                await speak("Modul Aset Lahan Tanah. Jawab dengan 'tidak punya' atau angka hektar.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 5 && step === 0) {
                await speak("Modul Aset Ternak. Jawab dengan jumlah ekor atau 'tidak ada'.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 6 && step === 0) {
                await speak("Modul Aset Perikanan. Jawab dengan jumlah atau 'tidak ada'.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 7 && step === 0) {
                await speak("Modul Sarpras Kerja dimulai. Jawab sesuai pilihan berikut.");
                const sarprasOpts = Object.entries(jawabSarprasOptions).slice(1);
                const letters = ['a', 'b', 'c', 'd', 'e', 'f'];
                for (let i = 0; i < sarprasOpts.length; i++) {
                    const [id, name] = sarprasOpts[i];
                    await speak(`${letters[i]}. ${cleanOptionText(name)}`);
                    await new Promise(r => setTimeout(r, 500));
                }
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 8 && step === 0) {
                await speak("Modul Bangun Keluarga dimulai. Jawab setiap pertanyaan dengan: YA atau TIDAK.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 9 && step === 0) {
                await speak("Modul Sejahtera Keluarga dimulai. Jawab setiap pertanyaan dengan angka saja atau 'tidak ada'.");
                await new Promise(r => setTimeout(r, 800));
            }
            if (currentModul === 10 && step === 0) {
                await speak("Modul Konflik Sosial dimulai. Untuk setiap pertanyaan, jawab dengan huruf A jika ADA, atau huruf B jika TIDAK ADA.");
                await new Promise(r => setTimeout(r, 1000));
            }
            await speak(q.label);
            if (q.type === "select" && currentModul !== 3 && currentModul !== 7 && currentModul !== 8 && currentModul !== 10) {
                const options = Object.values(q.options);
                for (let i = 0; i < options.length; i++) {
                    const cleanText = cleanOptionText(options[i]);
                    await speak(`${i + 1}. ${cleanText}`);
                    if (i < options.length - 1) await new Promise(r => setTimeout(r, 100));
                }
            }
            if (q.type === "select" && currentModul === 10) {
                await speak("A. ADA");
                await new Promise(r => setTimeout(r, 400));
                await speak("B. TIDAK ADA");
            }
            document.getElementById('voice-status').innerText = 'Mendengarkan...';
        }
        // === PROCESS VOICE ANSWER ===
        async function processVoiceAnswer(text) {
            if (isSpeaking) return;
            const q = questions[currentModul][step];
            let value = text;
            if (q.isLahan) {
                const norm = normalize(text);
                if (norm.includes('tidak') || norm.includes('ga') || norm.includes('nggak')) {
                    value = '0';
                } else {
                    const numMatch = text.match(/[\d.,]+/);
                    if (!numMatch) { await speak("Ulangi dengan angka hektar atau 'tidak punya'"); return; }
                    const ha = parseFloat(numMatch[0].replace(',', '.'));
                    value = mapLahanToCode(ha);
                }
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = jawabLahanOptions[value] || value;
            } else if (q.isTernak || q.isPerikanan) {
                const norm = normalize(text);
                if (norm.includes('tidak') || norm.includes('ga') || norm.includes('nggak') || norm.includes('nol') || norm.includes('kosong')) {
                    value = '0';
                } else {
                    const numMatch = text.match(/[\d]+/);
                    if (!numMatch) { await speak("Ulangi dengan angka atau 'tidak ada'"); return; }
                    value = numMatch[0];
                }
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            } else if (q.isUraian) {
                const norm = normalize(text);
                let parsedValue = '0';
                if (norm.includes('tidak') || norm.includes('ga') || norm.includes('nggak') || norm.includes('nol') || norm.includes('kosong')) {
                    parsedValue = '0';
                } else {
                    if (q.field.includes('_61') || q.field.includes('_65') || q.field.includes('_66') || q.field.includes('_67') || q.field.includes('_68')) {
                        parsedValue = parseRupiah(text);
                    } else {
                        parsedValue = parseAngka(text);
                    }
                    if (parsedValue === '0') {
                        await speak("Ulangi dengan angka yang jelas atau 'tidak ada'.");
                        return;
                    }
                }
                value = parsedValue;
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            } else if (q.type === "select" && (currentModul === 3 || currentModul === 8)) {
                const norm = normalize(text);
                if (norm.includes('ya') || norm.includes('punya') || norm.includes('memiliki') || norm.includes('ada')) value = '1';
                else if (norm.includes('tidak') || norm.includes('ga') || norm.includes('nggak') || norm.includes('belum')) value = '2';
                else { await speak("Jawab ya atau tidak."); return; }
                answers[q.field] = value;
            } else if (q.type === "select" && currentModul === 7) {
                const norm = normalize(text);
                const letterMap = {
                    'a': '2', 'satu': '2',
                    'b': '3', 'dua': '3',
                    'c': '4', 'tiga': '4',
                    'd': '5', 'empat': '5',
                    'e': '6', 'lima': '6',
                    'f': '7', 'enam': '7'
                };
                value = null;
                for (let [key, id] of Object.entries(letterMap)) {
                    if (norm.includes(key)) {
                        value = id;
                        break;
                    }
                }
                if (!value) {
                    const match = findBestMatch(text, q.options);
                    if (!match) { await speak("Maaf, tidak dikenali. Ulangi dengan huruf a sampai f."); return; }
                    value = match[0];
                }
                answers[q.field] = value;
            } else if (q.type === "select" && currentModul === 10) {
                const norm = normalize(text);
                if (norm.includes('a') || norm.includes('ada')) value = '1';
                else if (norm.includes('b') || norm.includes('tidak ada')) value = '2';
                else { await speak("Jawab A untuk ada atau B untuk tidak ada."); return; }
                answers[q.field] = value;
            } else if (q.type === "select") {
                const match = findBestMatch(text, q.options);
                if (!match) { await speak("Maaf, tidak dikenali. Ulangi."); return; }
                value = match[0];
                answers[q.field] = value;
            } else if (q.field === "no_kk") {
                value = text.replace(/\D/g, '').slice(0,16);
                if (value.length !== 16) { await speak("Harus 16 digit."); return; }
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            } else if (q.field === "keluarga_rw" || q.field === "keluarga_rt") {
                const num = text.match(/\d+/g);
                if (!num) { await speak("Tidak ada angka."); return; }
                value = pad3(num.join(''));
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            } else if (q.type === "text") {
                value = capitalize(text.trim());
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            } else {
                const num = text.match(/\d+/g);
                if (!num) { await speak("Tidak ada angka."); return; }
                value = num.join('');
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value;
            }
            // Select the card visually for select types
            if (q.type === "select") {
                const card = document.querySelector(`.option-card[data-value="${value}"]`);
                if (card) {
                    document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                }
            }
            if (currentModul === 1 && q.field === "kdmutasimasuk" && normalize(text).includes("datang")) {
                questions[1].push(...wilayahQuestions);
                document.getElementById('wilayahDatangReview').classList.remove('hidden');
            }
            saveData();
            setTimeout(async () => {
                step++;
                if (step < questions[currentModul].length) {
                    renderQuestion();
                    if (isListening) await speakQuestionAndOptions();
                } else {
                    isReviewMode = true;
                    modulStatus[currentModul] = 'completed';
                    saveData();
                    updateProgressSteps();
                    checkAllCompletedAndShowSimpanBtn();
                    showReviewForm();
                }
            }, 1200);
        }
        // === SHOW REVIEW FORM ===
        function showReviewForm() {
            stopListening();
            document.getElementById('quizArea').innerHTML = '';
            document.getElementById('voice-status').innerText = 'Review data. Selesaikan semua modul untuk simpan.';
            document.getElementById('reviewForm').classList.remove('hidden');
            const container = document.getElementById('reviewFields');
            container.innerHTML = '';
            if (currentModul === 1) {
                container.innerHTML += `<div><label class="block text-xs font-medium mb-1">Tanggal Mutasi</label>
                    <input type="date" name="keluarga_tanggalmutasi" value="${answers.keluarga_tanggalmutasi}" class="w-full border rounded-lg p-2 text-sm">
                </div>`;
            }
            const currentQuestions = questions[currentModul];
            currentQuestions.forEach(q => {
                if (!answers[q.field]) return;
                let input = '';
                if (q.isLahan) {
                    input = `<select name="${q.field}" class="w-full border rounded-lg p-2 text-sm"><option value="">-- Pilih --</option>`;
                    Object.entries(jawabLahanOptions).forEach(([k, v]) => {
                        input += `<option value="${k}" ${answers[q.field] == k ? 'selected' : ''}>${v}</option>`;
                    });
                    input += `</select>`;
                } else if (q.isTernak || q.isPerikanan) {
                    input = `<input type="number" name="${q.field}" value="${answers[q.field] || '0'}" class="w-full border rounded-lg p-2 text-sm" placeholder="0" min="0">`;
                } else if (q.isUraian) {
                    input = `<input type="number" name="${q.field}" value="${answers[q.field] || '0'}" class="w-full border rounded-lg p-2 text-sm" placeholder="0" min="0">`;
                } else if (q.type === "select") {
                    input = `<select name="${q.field}" class="w-full border rounded-lg p-2 text-sm"><option value="">-- Pilih --</option>`;
                    Object.entries(q.options).forEach(([k, v]) => {
                        input += `<option value="${k}" ${answers[q.field] == k ? 'selected' : ''}>${v}</option>`;
                    });
                    input += `</select>`;
                } else {
                    input = `<input type="${q.type === 'number' ? 'number' : 'text'}" name="${q.field}" value="${answers[q.field] || ''}" class="w-full border rounded-lg p-2 text-sm">`;
                }
                container.innerHTML += `<div><label class="block text-xs font-medium mb-1">${q.label.split('?')[0]}</label>${input}</div>`;
            });
            Object.keys(answers).forEach(key => {
                const el = document.querySelector(`[name="${key}"]`);
                if (el && !el.value) el.value = answers[key];
            });
        }
        // === MIC CLICK ===
        document.getElementById('startBtn').addEventListener('click', async () => {
            if (isListening || isReviewMode) return;
            isListening = true;
            document.getElementById('startBtn').classList.add('listening');
            document.getElementById('visualizer').style.opacity = 1;
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                analyser = audioContext.createAnalyser();
                canvas = document.getElementById('visualizer');
                ctx = canvas.getContext('2d');
                dataArray = new Uint8Array(analyser.frequencyBinCount);
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                const source = audioContext.createMediaStreamSource(stream);
                source.connect(analyser);
                drawVisualizer();
            } catch (err) {
                alert("Gagal akses mikrofon!");
                stopListening();
                return;
            }
            await speakQuestionAndOptions();
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.onresult = e => {
                const result = e.results[e.results.length - 1];
                if (result.isFinal && result[0].confidence > 0.6) {
                    const text = result[0].transcript.trim();
                    document.getElementById('voice-status').innerText = `Dengar: "${text}"`;
                    processVoiceAnswer(text.toLowerCase());
                }
            };
            recognition.onerror = () => setTimeout(() => recognition?.start(), 100);
            recognition.onend = () => { if (isListening) setTimeout(() => recognition?.start(), 100); };
            recognition.start();
        });
        // === VISUALIZER ===
        function drawVisualizer() {
            if (!isListening) return;
            requestAnimationFrame(drawVisualizer);
            analyser.getByteFrequencyData(dataArray);
            ctx.fillStyle = 'rgba(255,255,255,0.1)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            const barWidth = (canvas.width / dataArray.length) * 2.5;
            let x = 0;
            for (let i = 0; i < dataArray.length; i++) {
                const h = dataArray[i] / 2;
                ctx.fillStyle = `rgb(${h + 100}, 50, 50)`;
                ctx.fillRect(x, canvas.height - h, barWidth, h);
                x += barWidth + 1;
            }
        }
        // === SIMPAN SEMUA ===
        document.getElementById('voiceForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('simpanBtn');
            btn.disabled = true;
            btn.innerText = "Menyimpan...";
            Object.keys(answers).forEach(key => {
                let el = document.querySelector(`[name="${key}"]`);
                if (!el) {
                    el = document.createElement('input');
                    el.type = 'hidden';
                    el.name = key;
                    el.value = answers[key];
                    this.appendChild(el);
                } else {
                    el.value = answers[key];
                }
            });
            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const res = await fetch("{{ route('voice.store-all') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": token,
                        "Accept": "application/json"
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    localStorage.clear();
                    alert("SEMUA DATA BERHASIL DISIMPAN!");
                    location.reload();
                } else {
                    alert("Gagal: " + (data.error || JSON.stringify(data)));
                }
            } catch (err) {
                alert("Error: " + err.message);
            } finally {
                btn.disabled = false;
                btn.innerText = "Simpan Semua Data";
            }
        });
        // === CHAINED SELECT ===
        ['kdprovinsi', 'kdkabupaten', 'kdkecamatan'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', async function() {
                const val = this.value;
                const nextMap = { kdprovinsi: 'kdkabupaten', kdkabupaten: 'kdkecamatan', kdkecamatan: 'kddesa' };
                const nextId = nextMap[id];
                if (!nextId) return;
                const nextSelect = document.getElementById(nextId);
                nextSelect.innerHTML = '<option>-- Pilih --</option>';
                if (val) {
                    const url = `/get-${nextId.replace('kd', '').toLowerCase()}/${val}`;
                    const res = await fetch(url);
                    const data = await res.json();
                    Object.entries(data).forEach(([k, v]) => {
                        nextSelect.innerHTML += `<option value="${k}">${v}</option>`;
                    });
                }
            });
        });
    </script>
</x-app-layout>