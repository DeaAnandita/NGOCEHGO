<x-app-layout>
    @slot('progresspenduduk')
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
                Simpan Semua Data Penduduk
            </button>
        </div>
        <div class="mt-4">
            <button id="bypassValidation" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600">
                Lewati Validasi Suara (Testing Saja)
            </button>
        </div>

        <div id="inputArea" class="bg-white rounded-2xl shadow-lg p-6">
            <h2 id="modulTitle" class="text-2xl font-bold text-center mb-6 text-green-800">Input Data Penduduk via Suara</h2>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div id="progressBar" class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div class="text-center text-sm text-gray-600 mb-4">
                Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">7</span>
            </div>
            <div id="voice-status" class="text-center text-lg font-medium text-gray-700 mb-8">
                Tekan mic untuk mulai merekam...
            </div>
            <div id="quizArea" class="space-y-6"></div>
            <div class="flex items-center justify-center mt-10 space-x-4">
                <!-- Tombol Mic / Stop -->
                <button id="recordBtn" class="relative w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 z-10">
                    <svg id="recordIcon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                    </svg>
                    <span class="absolute inset-0 rounded-full animate-ping bg-blue-400 opacity-75 hidden" id="pulseRing"></span>
                </button>
                <!-- Visualizer + Placeholder Text -->
                <div class="relative w-72 h-16 bg-gradient-to-r from-gray-50 to-gray-100 rounded-full shadow-inner overflow-hidden flex items-center justify-center">
                    <canvas id="visualizer" class="absolute inset-0 w-full h-full px-6 hidden"></canvas>
                    <div id="visualizerPlaceholder" class="absolute text-gray-500 text-sm font-medium pointer-events-none">
                        Klik mic untuk mulai merekam
                    </div>
                </div>
            </div>
        </div>
        <div id="reviewForm" class="hidden bg-white rounded-2xl shadow-lg p-6 mt-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-center text-blue-700">Review & Edit Data Penduduk</h3>
                <button id="restartBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow">Ulang Data</button>
            </div>
            <form id="voiceForm" class="space-y-5">
                @csrf
                <div id="reviewFields" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm"></div>
            </form>
        </div>
    </div>
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 flex flex-col items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center transform transition-all duration-300 scale-100">
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4 overflow-hidden">
                <div id="loadingBar" class="bg-gradient-to-r from-blue-500 to-green-600 h-3 rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
            <p id="loadingText" class="text-lg font-semibold text-gray-800 mb-2">Menyimpan data...</p>
            <p class="text-sm text-gray-500">Proses penyimpanan 8 modul data penduduk</p>
        </div>
    </div>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .option-card {
            transition: all .3s; border: 2px solid #e5e7eb; cursor: pointer;
            padding: 1rem; border-radius: 1rem; text-align: center;
        }
        .option-card:hover { background-color: #eff6ff; border-color: #14b8a6; }
        .option-card.selected { background-color: #dbeafe !important; border-color: #14b8a6 !important; box-shadow: 0 0 0 3px rgba(37,99,235,.2); }
        /* Recording state */
        #recordBtn.recording {
            background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;
        }
        #recordBtn.recording #pulseRing { display: block; }
        /* Visualizer aktif → placeholder hilang */
        #visualizer.show { display: block !important; }
        #visualizerPlaceholder.hide { display: none !important; }
    </style>
    <script>
        // ==================== DATA FROM LARAVEL (SESUAI CONTROLLER PENDUDUK) ====================
        const masters = @json($masters);
        const mutasiOptions = @json($mutasi);
        const provinsiOptions = @json($provinsi);
        const kabupatenData = @json($kabupatens ?? []); // [kdprovinsi => [kdkab => nama]]
        const kecamatanData = @json($kecamatans ?? []); // [kdkab => [kdkec => nama]]
        const desaData = @json($desas ?? []); // [kdkec => [kddesa => nama]]
        // ==================== STATE ====================
        let currentModul = 1;
        let step = 0;
        let answers = { penduduk_tanggalmutasi: new Date().toISOString().split('T')[0] };
        let modulStatus = {
            1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending'
        };
        let recognition = null;
        let isListening = false;
        let audioContext = null, analyser = null, dataArray = null, canvas = null, ctx = null;
        let isSpeaking = false;
        let isReviewMode = false;
        // ==================== 8 MODUL PENDUDUK ====================
        const modules = [
            {id:1,name:"Data Penduduk"},
            {id:2,name:"Kelahiran"},
            {id:3,name:"Sosial Ekonomi"},
            {id:4,name:"Usaha ART"},
            {id:5,name:"Program Serta"},
            {id:6,name:"Lembaga Desa"},
            {id:7,name:"Lembaga Masyarakat"},
            {id:8,name:"Lembaga Ekonomi"}
        ];
        const questions = {1:[],2:[],3:[],4:[],5:[],6:[],7:[],8:[]};
        // ==================== BUILD QUESTIONS SESUAI CONTROLLER ====================
        // MODUL 1: DATA PENDUDUK
        questions[1] = [
            {type:"text",label:"Sebutkan nomor NIK",field:"nik"},
            {type:"text",label:"Sebutkan nama lengkap penduduk",field:"penduduk_namalengkap"},
            {type:"text",label:"Dimana tempat lahirnya?",field:"penduduk_tempatlahir"},
            {type:"date",label:"Tanggal lahir?",field:"penduduk_tanggallahir"},
            {type:"select",label:"Apa golongan darahnya?",field:"penduduk_goldarah",options:{"a":"A","b":"B","ab":"AB","o":"O"}},
            {type:"text",label:"Sebutkan nomor akta lahir",field:"penduduk_noaktalahir"},
            {type:"skip",label:"Kewarganegaraan",field:"penduduk_kewarganegaraan",default:"INDONESIA"},
            {type:"select",label:"Apa jenis kelaminnya?",field:"kdjeniskelamin",options:masters.jenis_kelamin},
            {type:"select",label:"Apa agamanya?",field:"kdagama",options:masters.agama},
            {type:"text",label:"Sebutkan nomor kartu keluarga",field:"no_kk"},
            {type:"text",label:"Sebutkan nomor urut dalam KK",field:"penduduk_nourutkk"},
            {type:"select",label:"Apa hubungan dalam keluarga?",field:"kdhubungankeluarga",options:masters.hubungan_keluarga},
            {type:"select",label:"Apa hubungan dengan kepala keluarga?",field:"kdhubungankepalakeluarga",options:masters.hubungan_kepala_keluarga},
            {type:"select",label:"Apa status perkawinannya?",field:"kdstatuskawin",options:masters.status_kawin},
            {type:"select",label:"Memiliki akta nikah?",field:"kdaktanikah",options:masters.akta_nikah},
            {type:"select",label:"Apakah tercantum dalam KK?",field:"kdtercantumdalamkk",options:masters.tercantum_kk},
            {type:"select",label:"Apa status tinggalnya?",field:"kdstatustinggal",options:masters.status_tinggal},
            {type:"select",label:"Jenis kartu identitas apa?",field:"kdkartuidentitas",options:masters.kartu_identitas},
            {type:"select",label:"Jenis mutasi apa?",field:"kdmutasimasuk",options:mutasiOptions},
            {type:"text",label:"Sebutkan nama ayah",field:"penduduk_namaayah"},
            {type:"text",label:"Sebutkan nama ibu",field:"penduduk_namaibu"},
            {type:"text",label:"Sebutkan nama tempat bekerja (jika ada)",field:"penduduk_namatempatbekerja"},
            {type:"select",label:"Apa pekerjaannya?",field:"kdpekerjaan",options:masters.pekerjaan}
        ];
        // MODUL 2: KELAHIRAN
        questions[2] = [
            {type:"text",label:"Sebutkan NIK bayi yang baru lahir",field:"nik_bayi"}, // kalau tidak dipakai, tetap dikomentari
            {type:"select",label:"Tempat persalinan bayi?",field:"kdtempatpersalinan",options:masters.tempat_persalinan},
            {type:"select",label:"Jenis kelahiran apa?",field:"kdjeniskelahiran",options:masters.jenis_kelahiran},
            {type:"select",label:"Siapa yang menolong persalinan?",field:"kdpertolonganpersalinan",options:masters.pertolongan_persalinan},
            {type:"text", label:"Jam berapa bayi lahir? (contoh: jam 21:00)", field:"kelahiran_jamkelahiran"},
            {type:"number",label:"Ini kelahiran ke berapa bagi ibunya?",field:"kelahiran_kelahiranke"},
            {type:"number",label:"Berat lahir bayi dalam gram?",field:"kelahiran_berat"},
            {type:"number",label:"Panjang lahir bayi dalam cm?",field:"kelahiran_panjang"},
            {type:"text",label:"Sebutkan NIK ibu kandung",field:"kelahiran_nikibu"},
            {type:"text",label:"Sebutkan NIK ayah",field:"kelahiran_nikayah", required:false}
        ];
        // MODUL 3: SOSIAL EKONOMI
        questions[3] = [
            {type:"select",label:"Partisipasi sekolah saat ini?",field:"kdpartisipasisekolah",options:masters.partisipasi_sekolah},
            {type:"select",label:"Ijasah tertinggi yang dimiliki?",field:"kdijasahterakhir",options:masters.ijasah_terakhir},
            {type:"select",label:"Status kedudukan dalam pekerjaan utama?",field:"kdstatuskedudukankerja",options:masters.status_kedudukan_kerja},
            {type:"select",label:"Lapangan usaha pekerjaan utama?",field:"kdlapanganusaha",options:masters.lapangan_usaha},
            {type:"select",label:"Pendapatan per bulan?",field:"kdpendapatanperbulan",options:masters.pendapatan_perbulan},
            {type:"select",label:"Penyakit kronis yang diderita?",field:"kdpenyakitkronis",options:masters.penyakit_kronis},
            {type:"select",label:"Jenis disabilitas yang dialami?",field:"kdjenisdisabilitas",options:masters.jenis_disabilitas},
            {type:"select",label:"Tingkat kesulitan disabilitas?",field:"kdtingkatsulitdisabilitas",options:masters.tingkat_sulit_disabilitas},
            {type:"select",label:"Imunisasi apa saja yang sudah diberikan?",field:"kdimunisasi",options:masters.imunisasi}
        ];
        // MODUL 4: USAHA ART
        questions[4] = [
            {type:"text",label:"Nama usaha ART?",field:"usahaart_namausaha"},
            {type:"select",label:"Ada tempat usaha tetap?",field:"kdtempatusaha",options:masters.tempat_usaha},
            {type:"select",label:"Lapangan usaha ART?",field:"kdlapanganusaha",options:masters.lapangan_usaha},
            {type:"select",label:"Omset usaha per bulan?",field:"kdomsetusaha",options:masters.omset_usaha},
            {type:"number",label:"Jumlah pekerja di usaha ini?",field:"usahaart_jumlahpekerja"}
        ];
        // MODUL 5: PROGRAM SERTA
        questions[5] = [
            {type:"select",label:"Apakah memiliki Kartu Keluarga Sejahtera (KKS) atau Kartu Perlindungan Sosial (KPS)?",field:"programserta_1",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah memiliki Kartu Indonesia Pintar (KIP)?",field:"programserta_2",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah memiliki Kartu Indonesia Sehat (KIS)?",field:"programserta_3",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah terdaftar sebagai peserta BPJS Kesehatan mandiri (bukan PBI/bantuan pemerintah)?",field:"programserta_4",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah pernah atau sedang terdaftar di BPJS Ketenagakerjaan (Jamsostek)?",field:"programserta_5",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah memiliki asuransi kesehatan lain selain BPJS?",field:"programserta_6",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah saat ini menerima bantuan Program Keluarga Harapan (PKH)?",field:"programserta_7",options:masters.jawab_program_serta},
            {type:"select",label:"Apakah pernah atau sedang menerima bantuan beras miskin (Raskin)?",field:"programserta_8",options:masters.jawab_program_serta}
        ];
        // MODUL 6: LEMBAGA DESA
        questions[6] = [
            {type:"select",label:"Apakah saat ini menjabat sebagai Kepala Desa atau Lurah?",field:"lemdes_1",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Sekretaris Desa?",field:"lemdes_2",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Kepala Urusan di kantor desa?",field:"lemdes_3",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Kepala Dusun (Kadus)?",field:"lemdes_4",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai staf atau pegawai kantor desa?",field:"lemdes_5",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Ketua BPD?",field:"lemdes_6",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Wakil Ketua BPD?",field:"lemdes_7",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Sekretaris BPD?",field:"lemdes_8",options:masters.jawab_lemdes},
            {type:"select",label:"Apakah saat ini menjabat sebagai Anggota BPD?",field:"lemdes_9",options:masters.jawab_lemdes}
        ];
        // MODUL 7: LEMBAGA MASYARAKAT
        questions[7] = [           
            {type:"select",label:"Apakah bapak/ibu pengurus RT?",field:"lemmas_1",options:masters.jawab_lemmas}

                    ];
        // MODUL 8: LEMBAGA EKONOMI
        questions[8] = [
            {type:"select",label:"Apakah memiliki atau terlibat dalam Koperasi?",field:"lemek_1",options:masters.jawab_lemek}
        ];
       
        const wilayahQuestions = [
            {type:"select",label:"Provinsi asalnya apa?",field:"kdprovinsi",options:provinsiOptions},
            {type:"select",label:"Kabupaten atau kota asalnya apa?",field:"kdkabupaten",dynamic:true,parentField:"kdprovinsi",dynamicUrl:"/voice/kabupaten/"},
            {type:"select",label:"Kecamatan asalnya apa?",field:"kdkecamatan",dynamic:true,parentField:"kdkabupaten",dynamicUrl:"/voice/kecamatan/"},
            {type:"select",label:"Desa atau kelurahan asalnya apa?",field:"kddesa",dynamic:true,parentField:"kdkecamatan",dynamicUrl:"/voice/desa/"}
        ];
        // Insert wilayah jika mutasi datang (akan ditangani dinamis)
        function injectWilayahIfNeeded() {
            const idx = questions[1].findIndex(q => q.field === "kdmutasimasuk");
            const hasWilayah = questions[1].some(q => q.field === "kdprovinsi");
            if (answers.kdmutasimasuk === '3' && !hasWilayah) {
                // Sisipkan 4 pertanyaan wilayah setelah mutasi
                questions[1].splice(idx + 1, 0,
                    {type:"select", label:"Provinsi asalnya apa?", field:"kdprovinsi", options: provinsiOptions},
                    {type:"select", label:"Kabupaten atau kota asalnya apa?", field:"kdkabupaten", dynamic:true, parentField:"kdprovinsi", dynamicUrl:"/voice/get-kabupaten/"},
                    {type:"select", label:"Kecamatan asalnya apa?", field:"kdkecamatan", dynamic:true, parentField:"kdkabupaten", dynamicUrl:"/voice/get-kecamatan/"},
                    {type:"select", label:"Desa atau kelurahan asalnya apa?", field:"kddesa", dynamic:true, parentField:"kdkecamatan", dynamicUrl:"/voice/get-desa/"}
                );
                // Reset jawaban wilayah
                ["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].forEach(f => delete answers[f]);
                // Trigger render ulang
                if (currentModul === 1) {
                    updateProgress();
                    renderQuestion(); // ini akan trigger load provinsi langsung karena options sudah ada
                }
            }
            else if (answers.kdmutasimasuk !== '3' && hasWilayah) {
                // Hapus pertanyaan wilayah
                questions[1] = questions[1].filter(q =>
                    !["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].includes(q.field)
                );
                ["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].forEach(f => delete answers[f]);
               
                if (currentModul === 1) {
                    updateProgress();
                    renderQuestion();
                }
            }
        }
        // ==================== UTILITIES ====================
        function capitalize(t){return t.replace(/\b\w/g,l=>l.toUpperCase());}
        function pad3(n){return String(n).padStart(3,'0');}
        function normalize(t){return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim();}
        function cleanOptionText(t){return t.replace(/\//g,' atau ').replace(/_/g,' ').replace(/\-/g, ' ke ');}
        function findBestMatch(text,options){
            const n=normalize(text); let best=null,score=0;
            Object.entries(options).forEach(([id,name])=>{
                const nn=normalize(name); let s=0;
                if(nn.includes(n))s=1000;else if(n.includes(nn))s=800;
                else nn.split(' ').forEach(w=>{if(n.includes(w))s+=w.length*3;});
                if(s>score){score=s;best=[id,name];}
            });
            return score>3?best:null;
        }
        function mapOmsetToCode(omset) {
            let input = (omset ?? '').toString().toLowerCase().trim();

            // Deteksi kasus "tidak berpenghasilan" atau variasinya
            if (input === '' || 
                /tidak ada|tidak berpenghasilan|nol|kosong|tidak punya|tanpa penghasilan/i.test(input)) {
                return '0'; // Kode untuk "tidak berpenghasilan"
            }

            let angka = parseFloat(input.replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;

            // Jika ada kata "juta" atau "jutaan"
            if (/juta|jutaan|milyar/i.test(input)) {
                angka *= 1000000;
            }
            // Jika ada kata "ribu" dan angkanya kecil (misal: 500 ribu)
            else if (/ribu/i.test(input) && angka < 10000) {
                angka *= 1000;
            }

            if (angka <= 1000000) return '1';        // ≤ 1 juta
            if (angka <= 5000000) return '2';        // 1–5 juta
            if (angka <= 10000000) return '3';       // 5–10 juta
            return '4';                              // > 10 juta
        }
        function mapPendapatanToCode(pendapatan) {
            if (!pendapatan) return '0';

            let input = pendapatan.toString().toLowerCase().trim();

            // Deteksi kasus "tidak berpendapatan" atau variasinya
            if (input === '' || 
                /tidak ada|tidak berpendapatan|nol|tidak punya|tanpa pendapatan|belum bekerja|belum berpenghasilan/i.test(input)) {
                return '0'; // Kode untuk "tidak berpendapatan"
            }

            // Ambil angka saja
            let angkaStr = input.replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.');
            let nilai = parseFloat(angkaStr) || 0;

            // Deteksi "juta" atau "milyar"
            if (/juta|jutaan|milyar/i.test(input)) {
                nilai *= 1000000;
            }
            // Deteksi "ribu" untuk kasus seperti "800 ribu"
            else if (/ribu/i.test(input) && nilai < 10000) {
                nilai *= 1000;
            }

            if (nilai <= 1000000) return '1';   // ≤ 1 juta
            if (nilai <= 1500000) return '2';   // ≤ 1,5 juta
            if (nilai <= 2000000) return '3';   // ≤ 2 juta
            if (nilai <= 3000000) return '4';   // ≤ 3 juta
            return '5';                         // > 3 juta
        }
        function speak(t){
            return new Promise(r=>{
                if(isSpeaking)return r(); isSpeaking=true;
                const u=new SpeechSynthesisUtterance(t);
                u.lang='id-ID'; u.rate=1.2;
                u.onend=()=>{isSpeaking=false;r();};
                speechSynthesis.speak(u);
            });
        }
        // ==================== CORE FUNCTIONS (LENGKAP) ====================
        function initFresh(){
            localStorage.clear();
            currentModul=1; step=0;
            answers={
                penduduk_tanggalmutasi: new Date().toISOString().split('T')[0],
                no_kk: ''
            };
            modulStatus={1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending'};
            isReviewMode=false;
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('inputArea').classList.remove('hidden');
            document.getElementById('simpanBtn').style.backgroundColor='#9ca3af';
            document.getElementById('simpanBtn').disabled=true;
            document.getElementById('quizArea').innerHTML='';
            document.getElementById('voice-status').innerText='Tekan mic untuk mulai merekam...';
            updateProgressSteps(); renderQuestion(); checkAllCompletedAndShowSimpanBtn();
        }
        function saveData(){
            localStorage.setItem('voiceAnswersPenduduk',JSON.stringify(answers));
            localStorage.setItem('modulStatusPenduduk',JSON.stringify(modulStatus));
            localStorage.setItem(`step_${currentModul}_penduduk`,step);
            localStorage.setItem(`review_${currentModul}_penduduk`,isReviewMode);
            localStorage.setItem('currentModulPenduduk',currentModul);
        }
        function loadModulData(id){
            const a=localStorage.getItem('voiceAnswersPenduduk'); if(a)answers=JSON.parse(a);
            const s=localStorage.getItem('modulStatusPenduduk'); if(s)modulStatus=JSON.parse(s);
            const st=localStorage.getItem(`step_${id}_penduduk`);
            const rv=localStorage.getItem(`review_${id}_penduduk`);
            if(modulStatus[id] === 'completed') {
                isReviewMode = true;
                step = questions[id].length;
            } else {
                isReviewMode = false;
                step = st ? parseInt(st) : 0;
            }
            currentModul=id;
            Object.keys(modulStatus).forEach(k => {
                if (k == id) {
                    modulStatus[k] = modulStatus[k] === 'completed' ? 'completed' : 'active';
                } else {
                    modulStatus[k] = modulStatus[k] === 'completed' ? 'completed' : 'pending';
                }
            });
        }
        function updateProgressSteps(){
            const c=document.getElementById('progressSteps'); c.innerHTML='';
            modules.forEach((m,i)=>{
                if(i>0){const l=document.createElement('div'); l.className=`h-0.5 w-12 self-center rounded-full ${modulStatus[m.id]==='completed'?'bg-blue-600':'bg-gray-300'}`; c.appendChild(l);}
                const d=document.createElement('div'); d.className='flex items-center cursor-pointer'; d.onclick=()=>switchModul(m.id);
                const circle=document.createElement('div'); circle.className=`w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${modulStatus[m.id]==='completed'?'bg-blue-600':modulStatus[m.id]==='active'?'bg-green-600':'bg-gray-300 text-gray-600'}`;
                circle.textContent=m.id;
                const txt=document.createElement('div'); txt.className='ml-2 text-left';
                txt.innerHTML=`<div class="font-medium text-sm">${m.name}</div><div class="text-xs ${modulStatus[m.id]==='completed'?'text-blue-600':modulStatus[m.id]==='active'?'text-green-600':'text-gray-500'}">${modulStatus[m.id]==='completed'?'Selesai':modulStatus[m.id]==='active'?'Aktif':'Belum'}</div>`;
                d.appendChild(circle); d.appendChild(txt); c.appendChild(d);
            });
        }
        function switchModul(id){
            stopListening(); saveData(); loadModulData(id);
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('inputArea').classList.remove('hidden');
            document.getElementById('quizArea').innerHTML='';
            document.getElementById('voice-status').innerText='Tekan mic untuk mulai merekam...';
            document.getElementById('modulTitle').textContent=modules.find(m=>m.id===id).name;
            updateProgressSteps(); checkAllCompletedAndShowSimpanBtn();
            if(modulStatus[id] === 'completed') {
                showReviewForm();
            } else {
                renderQuestion();
            }
        }
        function stopListening(){
            if(recognition){recognition.stop();recognition=null;}
            if(audioContext){audioContext.close().catch(()=>{});audioContext=null;}
            analyser=null; isListening=false;
           
            document.getElementById('recordBtn').classList.remove('recording');
            document.getElementById('visualizer').classList.remove('show');
            document.getElementById('visualizerPlaceholder').classList.remove('hide');
           
            document.getElementById('recordIcon').innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
            `;
            document.getElementById('voice-status').innerText = 'Merekam dihentikan. Klik mic untuk mulai lagi...';
        }
        function checkAllCompletedAndShowSimpanBtn(){
            const allDone=Object.values(modulStatus).every(s=>s==='completed');
            const b=document.getElementById('simpanBtn');
            if(allDone){b.style.backgroundColor='#14b8a6';b.disabled=false;}else{b.style.backgroundColor='#9ca3af';b.disabled=true;}
        }
        function updateProgress(){
            const total=questions[currentModul].length;
            const pct=((step+1)/total)*100;
            document.getElementById('progressBar').style.width=pct+'%';
            document.getElementById('currentQ').textContent=step+1;
            document.getElementById('totalQ').textContent=total;
        }
        async function renderQuestion(){
            const q=questions[currentModul][step];
            if (q.dynamic && !q.options && answers[q.parentField]) {
                const parentValue = answers[q.parentField];
                const url = q.dynamicUrl + parentValue;
                try {
                    const res = await fetch(url);
                    if (!res.ok) throw new Error('Network error');
                    q.options = await res.json();
                } catch (err) {
                    console.error('Gagal load wilayah:', err);
                    q.options = {};
                    await speak("Gagal memuat data wilayah. Mohon coba lagi.");
                }
            }
            let html='';
            // Hint khusus per modul
       
            html+=`<h3 class="text-lg font-medium text-center mb-6 text-gray-800">${q.label}</h3>`;
            if(q.type==="select"){
                let cols = 3;
                let gridClass = `grid grid-cols-2 sm:grid-cols-3 md:grid-cols-${cols} gap-3 max-w-5xl mx-auto`;
                html+=`<div class="${gridClass}">`;
                Object.entries(q.options).forEach(([id,nama])=>{
                    const sel=answers[q.field]==id?'selected':'';
                    html+=`<div class="option-card ${sel}" data-value="${id}" data-text="${nama}"><span class="text-sm font-medium">${nama}</span></div>`;
                });
                html+='</div>';
            }else{
                const val = answers[q.field]||'';
                html+=`<div class="max-w-md mx-auto"><input type="text" id="inputAnswer" class="w-full border border-gray-300 rounded-xl p-3 text-center text-lg" readonly value="${val}" placeholder="Jawaban muncul di sini..."></div>`;
            }
            document.getElementById('quizArea').innerHTML=html;
            updateProgress();
            attachCardListeners();
        }
        function attachCardListeners(){
            document.querySelectorAll('.option-card').forEach(c=>{
                c.onclick=()=>{
                    document.querySelectorAll('.option-card').forEach(x=>x.classList.remove('selected'));
                    c.classList.add('selected');
                    processVoiceAnswer(c.dataset.text.toLowerCase());
                };
            });
        }
        async function speakQuestionAndOptions() {
    const q = questions[currentModul][step];

    // Hint pembuka modul
    if (currentModul === 1 && step === 0) await speak("Modul Data Penduduk dimulai. Jawab pertanyaan satu per satu.");
    if (currentModul === 2 && step === 0) await speak("Modul Kelahiran. Pilih jawaban yang sesuai.");
    if (currentModul === 3 && step === 0) await speak("Modul Sosial Ekonomi. Jawab sesuai kondisi penduduk.");
    if (currentModul === 4 && step === 0) await speak("Modul Usaha A Er Te. Isi data usaha jika ada.");
    if (currentModul === 5 && step === 0) await speak("Modul Program Serta. Jawab 1 untuk Ya, 2 pernah, 3 tidak.");
    if ([6,7,8].includes(currentModul) && step === 0) await speak("Modul Lembaga. Jawab 1 ya, 2 PERNAH, 3 TIDAK.");

    // Baca pertanyaan
    let pertanyaanDibaca = q.label
        .replace(/\//g, ' atau ')
        .replace(/\&/g, ' dan ')
        .replace(/\-/g, ' ke ')
        .replace(/\s+/g, ' ')
        .trim();

    pertanyaanDibaca = pertanyaanDibaca
    .replace(/\bART\b/g, 'A Er Te');

    await speak(pertanyaanDibaca);

    // ========== KHUSUS AGAMA: PAKSA DIBACA BENAR ==========
    if (q.field === "kdagama") {
        const teksAgamaBenar = [
            "1. Islam",
            "2. Kristen Protestan",
            "3. Kristen Katolik",
            "4. Hindu",
            "5. Buddha",
            "6. Khonghucu",     // ini yang paling penting
            "7. Lainnya"
        ];

        for (let i = 0; i < teksAgamaBenar.length; i++) {
            await speak(teksAgamaBenar[i]);
            if (i < teksAgamaBenar.length - 1) await new Promise(r => setTimeout(r, 250));
        }

        document.getElementById('voice-status').innerText = 'Mendengarkan...';
        return; // keluar fungsi setelah selesai baca agama
    }

                // === TRIGGER REKAMAN VALIDASI SUARA DI PERTANYAAN PERTAMA ===
            if (currentModul === 1 && step === 0) {
                if (!voiceValidated) {
                    await speak("Sebelum memulai, saya akan memvalidasi suara Anda untuk mencegah duplikasi data.");
                    await speak("Rekaman ini hanya untuk validasi suara.");
                    startVoiceValidationRecording();
                } else {
                    await speak("");
                }
            }

    // ========== PILIHAN UMUM (kecuali yang dikecualikan) ==========
    if (
        q.type === "select" &&
        currentModul !== 5 &&                                    // Program Serta tidak dibacakan (terlalu banyak)
        ![6, 7, 8].includes(currentModul) &&                     // Lembaga juga tidak (ratusan pertanyaan)
        !["kdprovinsi", "kdkabupaten", "kdkecamatan", "kddesa", "kdpekerjaan", "kdlapanganusaha"].includes(q.field)
    ) {
        const opts = Object.values(q.options);
        for (let i = 0; i < opts.length; i++) {
            // Fix Buddha & Konghucu juga di master lain kalau ada
            let nama = opts[i];
            if (nama === "BUDHA") nama = "Buddha";
            if (nama === "KONGHUCHU") nama = "Khonghucu";
            if (nama === "PERIKANAN BUDIDAYA") nama = "perikanan budidaya";
            if (nama === "TBC (TUBERCULLOSIS)") nama = "TBC (tuberkulosis)";
            if (nama === "RP. 1 JUTA S/D RP. 1,5 JUTA") nama = "RP. 1 JUTA Sampai Dengan RP. 1,5 JUTA";
            if (nama === "RP. 1,5 JUTA S/D RP. 2 JUTA") nama = "RP. 1,5 JUTA Sampai Dengan RP. 2 JUTA";
            if (nama === "RP. 2 JUTA S/D RP. 3 JUTA") nama = "RP. 2 JUTA Sampai Dengan RP. 3 JUTA";
            if (nama === "RP. 1 JUTA S/D RP. 5 JUTA") nama = "RP. 1 JUTA Sampai Dengan RP. 5 JUTA";
            if (nama === "RP. 5 JUTA S/D RP. 10 JUTA") nama = "RP. 5 JUTA Sampai Dengan RP. 10 JUTA";
            if (nama === "PASPORT") nama = "paspor";
            if (nama === "LEVER") nama = "lever";
            
            await speak(`${i + 1}. ${cleanOptionText(nama)}`);
            if (i < opts.length - 1) await new Promise(r => setTimeout(r, 150));
        }
    }

    document.getElementById('voice-status').innerText = 'Mendengarkan...';
}
        // ===================================================================
        // PROSES JAWABAN – VERSI FINAL & RAPIH
        // ===================================================================
        async function processVoiceAnswer(text) {
            if (isSpeaking) return;

            const q = questions[currentModul][step];
            let value = text.trim();
            const lowerText = text.toLowerCase().trim();

            // ===================================================================
            // 1. JAM KELAHIRAN (kolom: kelahiran_jamkelahiran → time)
            // ===================================================================
            if (q.field === "kelahiran_jamkelahiran") {
                let cleaned = lowerText
                    .replace(/\b(jam|pukul|lewat|kurang|setengah|malam|pagi|siang|sore|wib|wita|wit|dan|nol|menit)\b/gi, ' ')
                    .replace(/\s+/g, ' ').trim();

                const kataAngka = {
                    satu:1, dua:2, tiga:3, empat:4, lima:5, enam:6, tujuh:7, delapan:8, sembilan:9, sepuluh:10,
                    sebelas:11, "dua belas":12, "tiga belas":13, "empat belas":14, "lima belas":15,
                    "enam belas":16, "tujuh belas":17, "delapan belas":18, "sembilan belas":19,
                    "dua puluh":20, "tiga puluh":30, "empat puluh":40, "lima puluh":50
                };
                for (const [kata, num] of Object.entries(kataAngka)) {
                    cleaned = cleaned.replace(new RegExp('\\b' + kata + '\\b', 'gi'), num + ' ');
                }

                const nums = cleaned.match(/\d+/g)?.map(Number) || [];
                let jam = 0, menit = 0;

                if (nums.length === 0) {
                    await speak("Jam kelahiran tidak terdeteksi. Ulangi dengan jelas.");
                    return;
                }
                if (nums.length === 1) {
                    const n = nums[0];
                    if (n >= 0 && n <= 2359) { jam = Math.floor(n / 100); menit = n % 100; }
                    else if (n <= 23) jam = n;
                } else {
                    jam = nums[0] || 0;
                    menit = nums[1] || 0;
                }

                if (jam < 0 || jam > 23 || menit < 0 || menit > 59) {
                    await speak("Jam tidak valid. Harus 00–23 untuk jam, 00–59 untuk menit.");
                    return;
                }

                value = `${jam.toString().padStart(2,'0')}:${menit.toString().padStart(2,'0')}:00`;
                await speak(`Jam kelahiran ${jam} lewat ${menit} menit. Lanjut.`);
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value.substring(0, 5);
                saveData();
                nextQuestion(1500);
                return;
            }

            // ===================================================================
            // 2. GOLONGAN DARAH
            // ===================================================================
            if (q.field === "penduduk_goldarah") {
                const map = { "a": "a", "b": "b", "ab": "ab", "o": "o", "a b": "ab", "abe": "ab", "a be": "ab" };
                const key = lowerText.replace(/\s+/g, ' ');
                if (map[key]) {
                    value = map[key];
                    selectCard(value);
                    answers[q.field] = value;
                    saveData();
                    nextQuestion(1200);
                    return;
                }
            }

            // ===================================================================
            // 3. STATUS TINGGAL
            // ===================================================================
            if (q.field === "kdstatustinggal") {
                if (lowerText.includes("dalam kota") || lowerText.includes("di dalam kota")) value = '2';
                else if (lowerText.includes("luar kota") || lowerText.includes("di luar kota")) value = '1';
                else if (lowerText.includes("tinggal bersama") || lowerText.includes("bersama")) value = '3';
                else {
                    await speak("Maaf tidak dikenali. Ulangi: tinggal bersama, tidak tinggal bersama di dalam kota, atau di luar kota.");
                    return;
                }
                selectCard(value);
                answers[q.field] = value;
                saveData();
                nextQuestion(1200);
                return;
            }

            // ===================================================================
            // 4. WILAYAH DATANG (Provinsi, Kab, Kec, Desa)
            // ===================================================================
            if (["kdprovinsi", "kdkabupaten", "kdkecamatan", "kddesa"].includes(q.field)) {
                const match = findBestMatch(text, q.options);
                if (!match) {
                    await speak("Maaf, nama wilayah tidak dikenali. Ulangi dengan jelas.");
                    return;
                }
                value = match[0];
                selectCard(value);
                answers[q.field] = value;
                saveData();
                nextQuestion(1200);
                return;
            }

            // ===================================================================
            // 5. SKIP FIELD (contoh: kewarganegaraan)
            // ===================================================================
            if (q.type === "skip") {
                answers[q.field] = q.default || "INDONESIA";
                saveData();
                nextQuestion(600);
                return;
            }

            // ===================================================================
            // 6. VALIDASI ANGKA KHUSUS (NIK, KK, Akta Lahir, No Urut KK)
            // ===================================================================
            if (["nik", "no_kk"].includes(q.field)) {
                const angka = text.replace(/\D/g, '').slice(0, 16);
                if (angka.length !== 16) {
                    await speak("Harus 16 digit angka. Mohon ulangi dengan jelas.");
                    return;
                }
                value = angka;
                document.getElementById('inputAnswer').value = value;
            }

            else if (q.field === "penduduk_nourutkk") {
                const num = text.replace(/\D/g, '');
                if (!num || parseInt(num) < 1 || parseInt(num) > 99) {
                    await speak("Nomor urut KK harus dari 1 sampai 99.");
                    return;
                }
                value = num.padStart(2, '0'); // otomatis jadi 01, 02, ..., 99
                document.getElementById('inputAnswer').value = value;
            }

            else if (q.field === "penduduk_noaktalahir") {
                const num = text.replace(/\D/g, '');
                if (num.length < 6) {
                    await speak("Nomor akta lahir minimal 6 digit angka.");
                    return;
                }
                value = `AKL-${num}`;
                document.getElementById('inputAnswer').value = value;
                await speak(`Nomor akta lahir ${value}. Lanjut.`);
            }

            // ===================================================================
            // 7. OMSET USAHA
            // ===================================================================
            if (q.field === "kdomsetusaha") {
                const kode = mapOmsetToCode(text);
                const konfirmasi = {'1': '≤ 1 juta', '2': '1–5 juta', '3': '5–10 juta', '4': '> 10 juta' };
                answers[q.field] = kode;
                await speak(`Omset ${konfirmasi[kode]} rupiah. Lanjut.`);
                saveData();
                nextQuestion(1500);
                return;
            }

            // ===================================================================
            // 8. PENDAPATAN PER BULAN
            // ===================================================================
            if (q.field === "kdpendapatanperbulan") {
                const kode = mapPendapatanToCode(text);
                if (kode === '0') {
                    await speak("Pendapatan tidak terdeteksi. Ulangi dengan bilang angka, contoh: satu juta, dua setengah juta.");
                    return;
                }
                const konfirmasi = {
                    '1': '≤ 1 juta', '2': '1–1,5 juta', '3': '1,5–2 juta', '4': '2–3 juta', '5': '> 3 juta'
                };
                answers[q.field] = kode;
                await speak(`Pendapatan ${konfirmasi[kode]} per bulan. Lanjut.`);
                const inputEl = document.getElementById('inputAnswer');
                if (inputEl) inputEl.value = konfirmasi[kode];
                saveData();
                nextQuestion(1800);
                return;
            }

            // ===================================================================
            // 9. TANGGAL LAHIR & TANGGAL MUTASI
            // ===================================================================
            if (q.field === "penduduk_tanggallahir" || q.field === "penduduk_tanggalmutasi") {
                const formatted = convertTanggalIndoToISO(lowerText);
                if (!formatted) {
                    await speak("Tanggal tidak dikenali. Ulangi, contoh: dua puluh lima desember dua ribu dua puluh empat.");
                    return;
                }
                value = formatted;
                answers[q.field] = value;
                document.getElementById('inputAnswer').value = value.split('-').reverse().join('/');
                await speak(`Tanggal ${q.field === "penduduk_tanggallahir" ? "lahir" : "mutasi"} ${value.split('-').reverse().join(' ')}. Lanjut.`);
                saveData();
                nextQuestion(1500);
                return;
            }

            // ===================================================================
            // 10. TIPE UMUM (select / text / number)
            // ===================================================================
            if (q.type === "select") {
                const match = findBestMatch(text, q.options);
                if (!match) {
                    await speak("Pilihan tidak dikenali. Mohon ulangi.");
                    return;
                }
                value = match[0];
            }
            else if (q.type === "number") {
                const num = text.match(/\d+/g);
                if (!num) {
                    await speak("Harus berupa angka. Ulangi.");
                    return;
                }
                value = num.join('');
            }
            else if (q.type === "text") {
                value = capitalize(text.trim());
            }
            // ===================================================================
            // 11. JUMLAH PEKERJA USAHA ART (terbilang → angka)
            // ===================================================================
            if (q.field === "usahaart_jumlahpekerja") {
                // Coba deteksi angka langsung
                const numMatch = text.match(/\d+/g);
                let nilai = null;

                if (numMatch) {
                    nilai = parseInt(numMatch.join(''));
                } else {
                    // Coba konversi terbilang
                    nilai = terbilangKeAngka(text);
                }

                if (nilai === null || isNaN(nilai) || nilai < 0) {
                    await speak("Jumlah pekerja tidak terdeteksi. Ulangi dengan bilang angka atau terbilang, contoh: lima, sepuluh, dua puluh satu.");
                    return;
                }

                // Batas wajar jumlah pekerja (misal maksimal 999)
                if (nilai > 999) nilai = 999;

                value = nilai.toString();
                document.getElementById('inputAnswer').value = value;
                await speak(`Jumlah pekerja ${value} orang. Lanjut.`);
                answers[q.field] = value;
                saveData();
                nextQuestion(1500);
                return;
            }

            // ===================================================================
            // UPDATE JAWABAN & UI
            // ===================================================================
            answers[q.field] = value;

            if (q.type === "select") {
                selectCard(value);
            } else {
                const input = document.getElementById('inputAnswer');
                if (input) input.value = value;
            }

            // Inject wilayah jika mutasi datang
            if (currentModul === 1 && q.field === "kdmutasimasuk") {
                injectWilayahIfNeeded();
                if (answers.kdmutasimasuk === '3') {
                    setTimeout(() => {
                        step = questions[1].findIndex(x => x.field === "kdprovinsi");
                        if (step !== -1) {
                            renderQuestion();
                            if (isListening) speakQuestionAndOptions();
                        }
                    }, 1200);
                    return;
                }
            }

            saveData();
            nextQuestion(1200);
        }

        // ===================================================================
        // FUNGSI BANTUAN
        // ===================================================================
        function selectCard(value) {
            const card = document.querySelector(`.option-card[data-value="${value}"]`);
            if (card) {
                document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
            }
        }

        function nextQuestion(delay = 1200) {
            setTimeout(async () => {
                step++;
                if (step < questions[currentModul].length) {
                    renderQuestion();
                    if (isListening) await speakQuestionAndOptions();
                } else {
                    finishModul();
                }
            }, delay);
        }

        function finishModul() {
            isReviewMode = true;
            modulStatus[currentModul] = 'completed';
            saveData();
            updateProgressSteps();
            checkAllCompletedAndShowSimpanBtn();
            showReviewForm();
        }

        // Fungsi konversi tanggal Indo → ISO (pindahkan ke luar kalau belum ada)
        function convertTanggalIndoToISO(text) {
            let input = text.replace(/tanggal|tgl|lahir|mutasi|tahun|thn\.?/gi, '').trim();
            const bulanMap = {
                januari: '01', februari: '02', maret: '03', april: '04', mei: '05', juni: '06',
                juli: '07', agustus: '08', september: '09', oktober: '10', november: '11', desember: '12'
            };
            for (const [k, v] of Object.entries(bulanMap)) {
                input = input.replace(new RegExp('\\b' + k + '\\b', 'gi'), v);
            }
            const match = input.match(/(\d{1,2})[\/\.\-\s]+(\d{1,2})[\/\.\-\s]+(\d{2,4})/);
            if (match) {
                let [_, d, m, y] = match;
                if (y.length === 2) y = '20' + y;
                return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
            }
            return null;
        }
        // Konversi terbilang Indonesia sederhana ke angka (sampai ratusan cukup untuk jumlah pekerja)
        function terbilangKeAngka(text) {
            const lower = text.toLowerCase().trim();
            const satuan = {
                nol: 0, satu: 1, dua: 2, tiga: 3, empat: 4, lima: 5,
                enam: 6, tujuh: 7, delapan: 8, sembilan: 9, sepuluh: 10,
                sebelas: 11, belas: 10 // untuk "dua belas" dst.
            };
            const puluhan = {
                dua: 20, tiga: 30, empat: 40, lima: 50,
                enam: 60, tujuh: 70, delapan: 80, sembilan: 90
            };
            const ratusan = { seratus: 100, ratus: 100 };

            let angka = 0;
            let temp = 0;

            // Pecah kata-kata
            const kata = lower.split(/\s+/);

            for (let i = 0; i < kata.length; i++) {
                const k = kata[i];

                if (satuan[k] !== undefined) {
                    temp += satuan[k];
                    // Jika kata berikutnya "belas" (contoh: dua belas)
                    if (kata[i + 1] === 'belas') {
                        temp += 10; // sudah ditambah satuan sebelumnya
                        i++;
                    }
                } else if (puluhan[k]) {
                    temp += puluhan[k];
                } else if (ratusan[k]) {
                    temp += ratusan[k];
                    // Jika ada satuan setelah ratus (seratus satu → 101)
                    if (i + 1 < kata.length && satuan[kata[i + 1]] !== undefined) {
                        temp += satuan[kata[i + 1]];
                        i++;
                    }
                } else if (k.match(/^\d+$/)) {
                    // Jika langsung bilang angka numerik
                    return parseInt(k);
                }
            }

            angka += temp;
            return angka > 0 ? angka : null;
        }

        function showReviewForm() {
            stopListening();
            document.getElementById('inputArea').classList.add('hidden');
            document.getElementById('reviewForm').classList.remove('hidden');
            document.getElementById('quizArea').innerHTML = '';
            document.getElementById('voice-status').innerText = 'Review & edit data. Selesai semua modul untuk simpan.';

            const container = document.getElementById('reviewFields');
            container.innerHTML = '';

            // ===================================================================
            // MODUL 1: DATA PENDUDUK (Sudah bagus, tetap pakai versi custom)
            // ===================================================================
            if (currentModul === 1) {
                const isMutasiDatang = answers.kdmutasimasuk === '3';
                let html = '';

                // 1. NIK (16 digit, hanya angka)
                const cleanNik = (answers.nik || '').replace(/\D/g, '').slice(0, 16);
                html += `<div><label class="block text-xs font-medium mb-1 text-red-600">NIK (Nomor Induk Kependudukan) *</label>
                    <input type="text" name="nik" value="${cleanNik}" maxlength="16"
                        oninput="this.value = this.value.replace(/\\D/g,'')"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 2. Nama Lengkap
                html += `<div><label class="block text-xs font-medium mb-1">Nama Lengkap *</label>
                    <input type="text" name="penduduk_namalengkap" value="${answers.penduduk_namalengkap || ''}"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 3. Tempat Lahir
                html += `<div><label class="block text-xs font-medium mb-1">Tempat Lahir</label>
                    <input type="text" name="penduduk_tempatlahir" value="${answers.penduduk_tempatlahir || ''}"
                        class="w-full border rounded-lg p-2.5"></div>`;

                // 4. Tanggal Lahir
                html += `<div><label class="block text-xs font-medium mb-1">Tanggal Lahir *</label>
                    <input type="date" name="penduduk_tanggallahir" value="${answers.penduduk_tanggallahir || ''}"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 5. Golongan Darah
                const golDarah = (answers.penduduk_goldarah || '').toString().trim().toUpperCase();
                html += `<div><label class="block text-xs font-medium mb-1">Golongan Darah</label>
                    <select name="penduduk_goldarah" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih --</option>
                        ${['A','B','AB','O'].map(v => `<option value="${v}" ${golDarah===v?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 6. No. Akta Lahir (otomatis AKL-)
                const aktaRaw = (answers.penduduk_noaktalahir || '').replace(/[^0-9]/g, '');
                const aktaTampil = aktaRaw.length >= 6 ? `AKL-${aktaRaw}` : aktaRaw;
                html += `<div><label class="block text-xs font-medium mb-1">Nomor Akta Lahir</label>
                    <input type="text" name="penduduk_noaktalahir" value="${aktaTampil}"
                        oninput="let n = this.value.replace(/[^0-9]/g,''); 
                                    if(n.length >= 6) this.value = 'AKL-' + n; 
                                    else this.value = n;"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 7. Kewarganegaraan (readonly)
                html += `<div><label class="block text-xs font-medium mb-1">Kewarganegaraan</label>
                    <input type="text" value="INDONESIA" readonly class="w-full border rounded-lg p-2.5 bg-gray-100"></div>`;

                // 8. Jenis Kelamin
                html += `<div><label class="block text-xs font-medium mb-1">Jenis Kelamin *</label>
                    <select name="kdjeniskelamin" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.jenis_kelamin).map(([k,v]) => `<option value="${k}" ${answers.kdjeniskelamin===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 9. Agama
                html += `<div><label class="block text-xs font-medium mb-1">Agama *</label>
                    <select name="kdagama" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.agama).map(([k,v]) => `<option value="${k}" ${answers.kdagama===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 10. No. KK (16 digit)
                const cleanKK = (answers.no_kk || '').replace(/\D/g, '').slice(0, 16);
                html += `<div><label class="block text-xs font-medium mb-1 text-red-600">Nomor Kartu Keluarga *</label>
                    <input type="text" name="no_kk" value="${cleanKK}" maxlength="16"
                        oninput="this.value = this.value.replace(/\\D/g,'')"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 11. No. Urut KK (selalu 2 digit)
                const noUrut = (answers.penduduk_nourutkk || '').toString().padStart(2, '0').slice(0, 2);
                html += `<div><label class="block text-xs font-medium mb-1">Nomor Urut dalam KK</label>
                    <input type="text" name="penduduk_nourutkk" value="${noUrut}" maxlength="2"
                        oninput="let v = this.value.replace(/\\D/g,''); 
                                    if(v && v !== '0') v = v.padStart(2,'0'); 
                                    this.value = v.slice(0,2);"
                        class="w-full border rounded-lg p-2.5" required></div>`;

                // 12. Hubungan dalam Keluarga
                html += `<div><label class="block text-xs font-medium mb-1">Hubungan dalam Keluarga</label>
                    <select name="kdhubungankeluarga" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.hubungan_keluarga).map(([k,v]) => `<option value="${k}" ${answers.kdhubungankeluarga===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 13. Hubungan dengan Kepala Keluarga
                html += `<div><label class="block text-xs font-medium mb-1">Hubungan dengan Kepala Keluarga</label>
                    <select name="kdhubungankepalakeluarga" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.hubungan_kepala_keluarga).map(([k,v]) => `<option value="${k}" ${answers.kdhubungankepalakeluarga===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 14. Status Perkawinan
                html += `<div><label class="block text-xs font-medium mb-1">Status Perkawinan</label>
                    <select name="kdstatuskawin" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.status_kawin).map(([k,v]) => `<option value="${k}" ${answers.kdstatuskawin===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 15. Akta Nikah
                html += `<div><label class="block text-xs font-medium mb-1">Memiliki Akta Nikah?</label>
                    <select name="kdaktanikah" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.akta_nikah).map(([k,v]) => `<option value="${k}" ${answers.kdaktanikah===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 16. Tercantum dalam KK
                html += `<div><label class="block text-xs font-medium mb-1">Tercantum dalam KK?</label>
                    <select name="kdtercantumdalamkk" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.tercantum_kk).map(([k,v]) => `<option value="${k}" ${answers.kdtercantumdalamkk===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 17. Status Tinggal
                html += `<div><label class="block text-xs font-medium mb-1">Status Tinggal</label>
                    <select name="kdstatustinggal" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.status_tinggal).map(([k,v]) => `<option value="${k}" ${answers.kdstatustinggal===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 18. Kartu Identitas
                html += `<div><label class="block text-xs font-medium mb-1">Jenis Kartu Identitas</label>
                    <select name="kdkartuidentitas" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.kartu_identitas).map(([k,v]) => `<option value="${k}" ${answers.kdkartuidentitas===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 19. Jenis Mutasi
                html += `<div><label class="block text-xs font-medium mb-1">Jenis Mutasi</label>
                    <select name="kdmutasimasuk" id="review_mutasi" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(mutasiOptions).map(([k,v]) => `<option value="${k}" ${answers.kdmutasimasuk===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;

                // 20. Tanggal Mutasi
                html += `<div><label class="block text-xs font-medium mb-1">Tanggal Mutasi</label>
                    <input type="date" name="penduduk_tanggalmutasi" value="${answers.penduduk_tanggalmutasi || ''}" class="w-full border rounded-lg p-2.5"></div>`;

                // 21. Nama Ayah
                html += `<div><label class="block text-xs font-medium mb-1">Nama Ayah</label>
                    <input type="text" name="penduduk_namaayah" value="${answers.penduduk_namaayah || ''}" class="w-full border rounded-lg p-2.5"></div>`;

                // 22. Nama Ibu
                html += `<div><label class="block text-xs font-medium mb-1">Nama Ibu</label>
                    <input type="text" name="penduduk_namaibu" value="${answers.penduduk_namaibu || ''}" class="w-full border rounded-lg p-2.5"></div>`;

                // 23. Tempat Bekerja
                html += `<div><label class="block text-xs font-medium mb-1">Nama Tempat Bekerja (jika ada)</label>
                    <input type="text" name="penduduk_namatempatbekerja" value="${answers.penduduk_namatempatbekerja || ''}" class="w-full border rounded-lg p-2.5"></div>`;

                // 24. Pekerjaan
                html += `<div><label class="block text-xs font-medium mb-1">Pekerjaan</label>
                    <select name="kdpekerjaan" class="w-full border rounded-lg p-2.5">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(masters.pekerjaan).map(([k,v]) => `<option value="${k}" ${answers.kdpekerjaan===k?'selected':''}>${v}</option>`).join('')}
                    </select></div>`;
            // === WILAYAH ASAL (jika mutasi datang) ===
            html += `<div id="wilayahDatangSection" class="${isMutasiDatang ? '' : 'hidden'} col-span-3 bg-teal-50 p-6 rounded-xl border border-teal-200 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <h4 class="font-bold text-teal-800 mb-4 col-span-2">Wilayah Datang</h4>
                
                <div>
                    <label class="block text-xs font-medium mb-1">Provinsi</label>
                    <select name="kdprovinsi" id="review_kdprovinsi" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih Provinsi --</option>
                        ${Object.entries(provinsiOptions).map(([k,v])=>`<option value="${k}" ${answers.kdprovinsi === k ? 'selected' : ''}>${v}</option>`).join('')}
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-medium mb-1">Kabupaten/Kota</label>
                    <select name="kdkabupaten" id="review_kdkabupaten" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih Provinsi Dahulu --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-medium mb-1">Kecamatan</label>
                    <select name="kdkecamatan" id="review_kdkecamatan" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih Kabupaten Dahulu --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-medium mb-1">Desa/Kelurahan</label>
                    <select name="kddesa" id="review_kddesa" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih Kecamatan Dahulu --</option>
                    </select>
                </div>
            </div>`;

            container.innerHTML = html;

            // ===================================================================
            // 1. Toggle Wilayah Datang saat ubah Jenis Mutasi
            // ===================================================================
            const mutasiSelect = document.getElementById('review_mutasi');
            if (mutasiSelect) {
                mutasiSelect.addEventListener('change', function () {
                    answers.kdmutasimasuk = this.value;
                    const section = document.getElementById('wilayahDatangSection');

                    if (this.value === '3') {
                        section.classList.remove('hidden');
                        injectWilayahIfNeeded(); // penting untuk suara
                    } else {
                        section.classList.add('hidden');
                        ["kdprovinsi", "kdkabupaten", "kdkecamatan", "kddesa"].forEach(f => {
                            delete answers[f];
                            const el = document.querySelector(`[name="${f}"]`);
                            if (el) el.value = '';
                        });
                        // Hapus dari daftar pertanyaan suara
                        questions[1] = questions[1].filter(q => !["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].includes(q.field));
                    }
                    saveData();
                });
            }

           

            // ===================================================================
            // 2. Cascade Wilayah – PAKAI URL YANG BENAR: /voice/get-kabupaten/...
            // ===================================================================
            const provEl = document.getElementById('review_kdprovinsi');
            const kabEl  = document.getElementById('review_kdkabupaten');
            const kecEl  = document.getElementById('review_kdkecamatan');
            const desEl  = document.getElementById('review_kddesa');

            if (provEl) {
                provEl.addEventListener('change', function () {
                    const val = this.value;
                    answers.kdprovinsi = val;
                    saveData();

                    if (val) {
                        window.getWilayah('get-kabupaten', val, 'review_kdkabupaten');
                    } else {
                        kabEl.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
                    }
                    kecEl.innerHTML = '<option value="">-- Pilih Kabupaten Dahulu --</option>';
                    desEl.innerHTML = '<option value="">-- Pilih Kecamatan Dahulu --</option>';
                });
            }

            if (kabEl) {
                kabEl.addEventListener('change', function () {
                    const val = this.value;
                    answers.kdkabupaten = val;
                    saveData();

                    if (val) {
                        window.getWilayah('get-kecamatan', val, 'review_kdkecamatan');
                    } else {
                        kecEl.innerHTML = '<option value="">-- Pilih Kabupaten Dahulu --</option>';
                    }
                    desEl.innerHTML = '<option value="">-- Pilih Kecamatan Dahulu --</option>';
                });
            }

            if (kecEl) {
                kecEl.addEventListener('change', function () {
                    const val = this.value;
                    answers.kdkecamatan = val;
                    saveData();

                    if (val) {
                        window.getWilayah('get-desa', val, 'review_kddesa');
                    } else {
                        desEl.innerHTML = '<option value="">-- Pilih Kecamatan Dahulu --</option>';
                    }
                });
            }

            if (desEl) {
                desEl.addEventListener('change', function () {
                    answers.kddesa = this.value;
                    saveData();
                });
            }

            // ===================================================================
            // 3. AUTO-LOAD WILAYAH DATANG SAAT MASUK REVIEW (INI YANG BIKIN OTOMATIS ISI)
            // ===================================================================
            if (answers.kdprovinsi && answers.kdmutasimasuk === '3') {
                setTimeout(() => {
                    // Load Kabupaten
                    window.getWilayah('get-kabupaten', answers.kdprovinsi, 'review_kdkabupaten', answers.kdkabupaten);

                    // Setelah kabupaten muncul → set value + load kecamatan
                    if (answers.kdkabupaten) {
                        setTimeout(() => {
                            kabEl.value = answers.kdkabupaten;
                            window.getWilayah('get-kecamatan', answers.kdkabupaten, 'review_kdkecamatan', answers.kdkecamatan);

                            if (answers.kdkecamatan) {
                                setTimeout(() => {
                                    kecEl.value = answers.kdkecamatan;
                                    window.getWilayah('get-desa', answers.kdkecamatan, 'review_kddesa', answers.kddesa);

                                    if (answers.kddesa) {
                                        setTimeout(() => {
                                            desEl.value = answers.kddesa;
                                        }, 400);
                                    }
                                }, 500);
                            }
                        }, 700);
                    }
                }, 300);
            }

            }
            // ===================================================================
            // MODUL 2: KELAHIRAN
            // ===================================================================
            else if (currentModul === 2) {
                let html = '';

                // 1. Tempat Persalinan
                html += `<div><label class="block text-xs font-medium mb-1">Tempat Persalinan</label>
                    <select name="kdtempatpersalinan" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih Tempat Persalinan --</option>
                        ${Object.entries(masters.tempat_persalinan).map(([k,v]) => 
                            `<option value="${k}" ${answers.kdtempatpersalinan===k?'selected':''}>${v}</option>`
                        ).join('')}
                    </select></div>`;

                // 2. Jenis Kelahiran
                html += `<div><label class="block text-xs font-medium mb-1">Jenis Kelahiran</label>
                    <select name="kdjeniskelahiran" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih Jenis Kelahiran --</option>
                        ${Object.entries(masters.jenis_kelahiran).map(([k,v]) => 
                            `<option value="${k}" ${answers.kdjeniskelahiran===k?'selected':''}>${v}</option>`
                        ).join('')}
                    </select></div>`;

                // 3. Penolong Persalinan
                html += `<div><label class="block text-xs font-medium mb-1">Penolong Persalinan</label>
                    <select name="kdpertolonganpersalinan" class="w-full border rounded-lg p-2.5" required>
                        <option value="">-- Pilih Penolong --</option>
                        ${Object.entries(masters.pertolongan_persalinan).map(([k,v]) => 
                            `<option value="${k}" ${answers.kdpertolonganpersalinan===k?'selected':''}>${v}</option>`
                        ).join('')}
                    </select></div>`;

                // 4. Jam Kelahiran
                // 4. Jam Kelahiran – VERSI BENAR 100% TETAP 24-JAM
                let jamValue = '';
                if (answers.kelahiran_jamkelahiran) {
                    const time = answers.kelahiran_jamkelahiran.trim();
                    const hhmm = time.substring(0, 5); // "21:00" atau "09:00"

                    // Validasi sederhana bahwa ini format HH:mm yang valid
                    if (/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(hhmm)) {
                        jamValue = hhmm; // LANGSUNG PAKAI STRING ASLI, JANGAN LEWAT DATE!
                    } else {
                        jamValue = hhmm; // fallback tetap
                    }
                }

                html += `<div><label class="block text-xs font-medium mb-1">Jam Kelahiran</label>
                    <input type="time" name="kelahiran_jamkelahiran" value="${jamValue}" class="w-full border rounded-lg p-2.5"></div>`;

                // 5. Kelahiran Ke-
                html += `<div><label class="block text-xs font-medium mb-1">Anak Ke-</label>
                    <input type="number" name="kelahiran_kelahiranke" value="${answers.kelahiran_kelahiranke || ''}" min="1" max="20" class="w-full border rounded-lg p-2.5"></div>`;

                // 6. Berat & Panjang Lahir (satu baris)
                html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-xs font-medium mb-1">Berat Lahir (gram)</label>
                        <input type="number" name="kelahiran_berat" value="${answers.kelahiran_berat || ''}" min="0" max="10000" class="w-full border rounded-lg p-2.5"></div>
                    <div><label class="block text-xs font-medium mb-1">Panjang Lahir (cm)</label>
                        <input type="number" name="kelahiran_panjang" value="${answers.kelahiran_panjang || ''}" min="0" max="100" class="w-full border rounded-lg p-2.5"></div>
                </div>`;

                // 7. NIK Ibu Kandung (wajib, merah)
                html += `<div><label class="block text-xs font-medium mb-1">NIK Ibu</label>
                    <input type="text" name="kelahiran_nikibu" value="${answers.kelahiran_nikibu || ''}" maxlength="16" class="w-full border rounded-lg p-2.5" required placeholder="16 digit NIK ibu"></div>`;

                // 8. NIK Ayah (opsional)
                html += `<div><label class="block text-xs font-medium mb-1">NIK Ayah</label>
                    <input type="text" name="kelahiran_nikayah" value="${answers.kelahiran_nikayah || ''}" maxlength="16" class="w-full border rounded-lg p-2.5" required placeholder="16 digit NIK ayah"></div>`;

                html += `</div></div>`; // tutup space-y-5 & container
                container.innerHTML = html;
            }
            // ===================================================================
            // MODUL LAIN (3-8): PAKAI GENERIK
            // ===================================================================
            else {
                questions[currentModul].forEach(q => {
                    if (!answers[q.field]) return;
                    let input = '';
                    if (q.type === "select") {
                        input = `<select name="${q.field}" class="w-full border rounded-lg p-2.5 text-sm">
                            <option value="">-- Pilih --</option>
                            ${Object.entries(q.options||{}).map(([k,v]) => `<option value="${k}" ${answers[q.field]===k?'selected':''}>${v}</option>`).join('')}
                        </select>`;
                    } else if (q.field === "kelahiran_jamkelahiran") {
                        const val = (answers[q.field] || '').substring(0,5);
                        input = `<input type="time" name="${q.field}" value="${val}" class="w-full border rounded-lg p-2.5 text-sm">`;
                    } else {
                        input = `<input type="${q.type==='number'?'number':'text'}" name="${q.field}" value="${answers[q.field]||''}" class="w-full border rounded-lg p-2.5 text-sm">`;
                    }
                    container.innerHTML += `<div><label class="block text-xs font-medium mb-1">${q.label}</label>${input}</div>`;
                });
            }

            // Update answers saat edit
            document.querySelectorAll('#reviewFields input, #reviewFields select').forEach(el => {
                el.addEventListener('change', e => {
                    answers[e.target.name] = e.target.value;
                    saveData();
                });
            });
        }
        // VISUALIZER
        function drawWaveVisualizer() {
            if (!isListening) return;
            requestAnimationFrame(drawWaveVisualizer);
            analyser.getByteFrequencyData(dataArray);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const barWidth = (canvas.width / dataArray.length) * 2.8;
            let x = canvas.width * 0.1;
            for (let i = 0; i < dataArray.length; i++) {
                let barHeight = (dataArray[i] / 255) * canvas.height * 0.8;
                const gradient = ctx.createLinearGradient(0, canvas.height/2 - barHeight/2, 0, canvas.height/2 + barHeight/2);
                gradient.addColorStop(0, '#14b8a6');
                gradient.addColorStop(1, '#4f46e5');
                ctx.fillStyle = gradient;
                ctx.roundRect(x, canvas.height/2 - barHeight/2, barWidth * 0.7, barHeight, 6).fill();
                x += barWidth + 2;
            }
        }
        // Canvas polyfill
        CanvasRenderingContext2D.prototype.roundRect = function(x, y, w, h, r) {
            if (w < 2 * r) r = w / 2;
            if (h < 2 * r) r = h / 2;
            this.beginPath();
            this.moveTo(x + r, y);
            this.arcTo(x + w, y, x + w, y + h, r);
            this.arcTo(x + w, y + h, x, y + h, r);
            this.arcTo(x, y + h, x, y, r);
            this.arcTo(x, y, x + w, y, r);
            this.closePath();
            return this;
        };
        // WILAYAH CASCADE
        window.getWilayah = async function(tipe, parentId, targetId, selectedValue = null) {
            if (!parentId) {
                document.getElementById(targetId).innerHTML = '<option value="">-- Pilih Dulu --</option>';
                return;
            }
            const select = document.getElementById(targetId);
            select.innerHTML = '<option>-- Memuat... --</option>';
            select.disabled = true;
            try {
                let url = `/voice/${tipe}/${parentId}`;
                const res = await fetch(url);
                const data = await res.json();
                select.innerHTML = '<option value="">-- Pilih --</option>';
                Object.entries(data).forEach(([id, nama]) => {
                    const sel = (id === selectedValue) ? 'selected' : '';
                    select.innerHTML += `<option value="${id}" ${sel}>${nama}</option>`;
                });
            } catch (err) {
                select.innerHTML = '<option>Gagal memuat</option>';
            } finally {
                select.disabled = false;
            }
        };
        // EVENT LISTENERS
        document.getElementById('recordBtn').addEventListener('click', async () => {
            if (isReviewMode) return;
            if (isListening) {
                stopListening();
                return;
            }
            isListening = true;
            const btn = document.getElementById('recordBtn');
            btn.classList.add('recording');
           
            document.getElementById('visualizer').classList.add('show');
            document.getElementById('visualizerPlaceholder').classList.add('hide');
            document.getElementById('recordIcon').innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M10 9h4v6h-4z" fill="white" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M12 21a9 9 0 100-18 9 9 0 000 18z" stroke="white" fill="none" />
            `;
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                analyser = audioContext.createAnalyser();
                analyser.fftSize = 256;
                canvas = document.getElementById('visualizer');
                ctx = canvas.getContext('2d');
                const resizeCanvas = () => {
                    canvas.width = canvas.offsetWidth * window.devicePixelRatio;
                    canvas.height = canvas.offsetHeight * window.devicePixelRatio;
                };
                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);
                dataArray = new Uint8Array(analyser.frequencyBinCount);
                const stream = await navigator.mediaDevices.getUserMedia({audio: true});
                const source = audioContext.createMediaStreamSource(stream);
                source.connect(analyser);
                drawWaveVisualizer();
            } catch (err) {
                alert("Gagal akses mikrofon! Pastikan izin diberikan.");
                stopListening();
                return;
            }
            await speakQuestionAndOptions();
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SR();
            recognition.lang = 'id-ID';
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.onresult = e => {
                const r = e.results[e.results.length - 1];
                if (r.isFinal && r[0].confidence > 0.6) {
                    const t = r[0].transcript.trim();
                    document.getElementById('voice-status').innerText = `Dengar: "${t}"`;
                    processVoiceAnswer(t.toLowerCase());
                }
            };
            recognition.onerror = () => setTimeout(() => recognition?.start(), 100);
            recognition.onend = () => { if(isListening) setTimeout(() => recognition?.start(), 100); };
            recognition.start();
        });
        // FORM SUBMIT
        document.getElementById('voiceForm').addEventListener('submit', async function(e){
            e.preventDefault();
           
            // TAMBAHKAN NO_KK YANG WAJIB
            const noKKInput = document.createElement('input');
            noKKInput.type = 'hidden';
            noKKInput.name = 'no_kk';
            noKKInput.value = answers.no_kk || prompt('Masukkan No. KK Keluarga:');
            if(!noKKInput.value) return alert('No. KK wajib diisi!');
            this.appendChild(noKKInput);
            const btn=document.getElementById('simpanBtn');
            btn.disabled=true;
            btn.innerText="Menyimpan...";
            Object.keys(answers).forEach(k=>{
                let el=document.querySelector(`[name="${k}"]`);
                if(!el){
                    el=document.createElement('input');
                    el.type='hidden';
                    el.name=k;
                    this.appendChild(el);
                }
                el.value=answers[k];
            });
            const fd=new FormData(this);
            const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.getElementById('loadingOverlay').classList.remove('hidden');
           
            let progress = 0;
            const interval = setInterval(() => {
                progress += 2;
                if(progress > 95) progress = 95;
                document.getElementById('loadingBar').style.width = progress + '%';
                document.getElementById('loadingText').innerText = `Menyimpan data... ${progress}%`;
            }, 100);
            try{
                const res=await fetch("{{ route('voice.penduduk.store-all') }}",{
                    method:"POST",
                    headers:{"X-CSRF-TOKEN":token,"Accept":"application/json"},
                    body:fd
                });
                const data=await res.json();
                clearInterval(interval);
                document.getElementById('loadingBar').style.width = '100%';
                document.getElementById('loadingText').innerText = 'Data tersimpan! 100%';
                setTimeout(() => {
                    document.getElementById('loadingOverlay').classList.add('hidden');
                }, 1500);
               
                if(data.success){
                    localStorage.clear();
                    alert("DATA PENDUDUK BERHASIL DISIMPAN! 🎉");
                    location.reload();
                } else {
                    alert("Gagal: "+(data.error||JSON.stringify(data)));
                }
            }catch(err){
                clearInterval(interval);
                document.getElementById('loadingOverlay').classList.add('hidden');
                alert("Error: "+err.message);
            } finally{
                btn.disabled=false;
                btn.innerText="Simpan Semua Data Penduduk";
            }
        });
        // RESTART BUTTON
        document.getElementById('restartBtn').addEventListener('click', function() {
            questions[currentModul].forEach(q => {
                delete answers[q.field];
            });
            document.getElementById('inputArea').classList.remove('hidden');
            document.getElementById('reviewForm').classList.add('hidden');
            isReviewMode = false;
            step = 0;
            modulStatus[currentModul] = 'active';
            updateProgressSteps();
            renderQuestion();
        });

                // ==================== TAMBAHAN: VOICE VALIDATION ====================
    let voiceValidated = false;         // status validasi suara
    let mediaRecorder = null;           // untuk rekam audio khusus validasi
    let audioChunks = [];

    // Fungsi mulai rekam audio untuk validasi (terpisah dari recognition biasa)
    async function startVoiceValidationRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm;codecs=opus' });
            audioChunks = [];
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);

            mediaRecorder.onstop = async () => {
                // Langsung validasi saat rekaman selesai
                await validateVoicePrint();
            };

            mediaRecorder.start();
            document.getElementById('voice-status').innerText = 'Merekam untuk validasi suara...';

            // TIDAK ADA setTimeout lagi
            // Rekaman akan di-stop otomatis saat recognition dapat hasil final

        } catch (err) {
            console.error("Gagal akses mikrofon untuk validasi:", err);
            await speak("Gagal mengakses mikrofon. Pendataan tidak dapat dilanjutkan.");
            stopListening();
        }
    }

   async function validateVoicePrint() {
    if (audioChunks.length === 0) {
        await speak("Tidak ada rekaman suara. Silakan ulangi.");
        document.getElementById('voice-status').innerText = 'Klik mic untuk coba lagi.';
        return false;
    }

    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
    const formData = new FormData();
    formData.append('voice_sample', audioBlob, 'validation.webm');

    try {
        document.getElementById('voice-status').innerText = 'Memvalidasi suara...';

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await Promise.race([
            fetch("{{ route('voice.validate') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                    // JANGAN SET "Content-Type": "multipart/form-data" → biarkan browser handle
                },
                body: formData  // <--- WAJIB ADA!
            }),
            new Promise((_, reject) =>
                setTimeout(() => reject(new Error('Timeout validasi')), 15000)
            )
        ]);

        const result = await response.json();

        if (result.allowed === true) {
            voiceValidated = true;

            await speak("Validasi suara berhasil. Sekarang sebutkan nomor Kartu Keluarga 16 digit Anda untuk pendataan.");

            document.getElementById('voice-status').innerText = 'Mendengarkan nomor KK...';

            renderQuestion();
            if (isListening) await speakQuestionAndOptions();

            return true;
        } else {
            voiceValidated = false;
            await speak(result.message || "Maaf, suara Anda sudah terdaftar sebelumnya atau validasi gagal. Pendataan tidak dapat dilanjutkan saat ini.");

            document.getElementById('voice-status').innerText = 'Validasi gagal. Klik mic jika ingin coba lagi, atau hubungi petugas.';

            // JANGAN restart otomatis → biarkan user putuskan
            document.getElementById('recordBtn').disabled = false;
            document.getElementById('recordBtn').classList.remove('opacity-50', 'cursor-not-allowed');

            return false;
        }
    } catch (err) {
        console.error("Error validasi suara:", err);

        voiceValidated = false;
        await speak("Gagal memvalidasi suara karena masalah teknis. Silakan coba lagi atau hubungi petugas.");

        document.getElementById('voice-status').innerText = 'Error validasi. Klik mic untuk coba lagi.';

        // Aktifkan mic kembali, tapi TIDAK restart otomatis
        document.getElementById('recordBtn').disabled = false;
        document.getElementById('recordBtn').classList.remove('opacity-50', 'cursor-not-allowed');

        return false;
    }
}
document.getElementById('bypassValidation')?.addEventListener('click', async () => {
    voiceValidated = true;
    await speak("Validasi suara dilewati (mode testing).");
    renderQuestion();
    if (isListening) await speakQuestionAndOptions();
});
        initFresh();
    </script>
</x-app-layout>
