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
        <div id="inputArea" class="bg-white rounded-2xl shadow-lg p-6">
            <h2 id="modulTitle" class="text-2xl font-bold text-center mb-6 text-green-800">Input Data Keluarga via Suara</h2>
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
                <span class="absolute inset-0 rounded-full animate-ping bg-green-400 opacity-75 hidden" id="pulseRing"></span>
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
                <h3 class="text-xl font-bold text-center text-green-700">Review & Edit Data</h3>
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
                <div id="loadingBar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
            <p id="loadingText" class="text-lg font-semibold text-gray-800 mb-2">Menyimpan data...</p>
            <p class="text-sm text-gray-500">Proses penyimpanan 12 modul data keluarga</p>
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
        // ==================== DATA FROM LARAVEL ====================
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
        const kualitasIbuHamilOptions = @json($kualitasIbuHamilOptions);
        const jawabKualitasIbuHamilOptions = @json($jawabKualitasIbuHamilOptions);
        const kualitasBayiOptions = @json($kualitasBayiOptions);
        const jawabKualitasBayiOptions = @json($jawabKualitasBayiOptions);
        // ==================== STATE ====================
        let currentModul = 1;
        let step = 0;
        let answers = { keluarga_tanggalmutasi: new Date().toISOString().split('T')[0] };
        let modulStatus = {1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending',9:'pending',10:'pending',11:'pending',12:'pending'};
        let recognition = null;
        let isListening = false;
        let audioContext = null, analyser = null, dataArray = null, canvas = null, ctx = null;
        let isSpeaking = false;
        let isReviewMode = false;
        const modules = [
            {id:1,name:"Data Keluarga"},{id:2,name:"Prasarana Dasar"},{id:3,name:"Aset Keluarga"},
            {id:4,name:"Aset Lahan Tanah"},{id:5,name:"Aset Ternak"},{id:6,name:"Aset Perikanan"},
            {id:7,name:"Sarpras Kerja"},{id:8,name:"Bangun Keluarga"},{id:9,name:"Sejahtera Keluarga"},
            {id:10,name:"Konflik Sosial"},{id:11,name:"Kualitas Ibu Hamil"},{id:12,name:"Kualitas Bayi"}
        ];
        const questions = {1:[],2:[],3:[],4:[],5:[],6:[],7:[],8:[],9:[],10:[],11:[],12:[]};
        // ==================== BUILD QUESTIONS ====================
        questions[1] = [
            {type:"text",label:"Sebutkan nomor kartu keluarga, 16 digit",field:"no_kk"},
            {type:"select",label:"Jenis mutasi apa?",field:"kdmutasimasuk",options:mutasiOptions},
            {type:"text",label:"Siapa nama kepala rumah tangga?",field:"keluarga_kepalakeluarga"},
            {type:"select",label:"Dusun atau lingkungan apa?",field:"kddusun",options:dusunOptions},
            {type:"number",label:"RW berapa?",field:"keluarga_rw"},
            {type:"number",label:"RT berapa?",field:"keluarga_rt"},
            {type:"text",label:"Sebutkan alamat lengkapnya",field:"keluarga_alamatlengkap"}
        ];
        questions[2] = [
            {type:"select",label:"Status Pemilik Bangunan",field:"kdstatuspemilikbangunan",options:masters.status_pemilik_bangunan},
            {type:"select",label:"Status Pemilik Lahan",field:"kdstatuspemiliklahan",options:masters.status_pemilik_lahan},
            {type:"select",label:"Jenis Fisik Bangunan",field:"kdjenisfisikbangunan",options:masters.jenis_fisik_bangunan},
            {type:"select",label:"Jenis Lantai Bangunan",field:"kdjenislantaibangunan",options:masters.jenis_lantai},
            {type:"select",label:"Kondisi Lantai Bangunan",field:"kdkondisilantaibangunan",options:masters.kondisi_lantai},
            {type:"select",label:"Jenis Dinding Bangunan",field:"kdjenisdindingbangunan",options:masters.jenis_dinding},
            {type:"select",label:"Kondisi Dinding Bangunan",field:"kdkondisidindingbangunan",options:masters.kondisi_dinding},
            {type:"select",label:"Jenis Atap Bangunan",field:"kdjenisatapbangunan",options:masters.jenis_atap},
            {type:"select",label:"Kondisi Atap Bangunan",field:"kdkondisiatapbangunan",options:masters.kondisi_atap},
            {type:"select",label:"Sumber Air Minum",field:"kdsumberairminum",options:masters.sumber_air_minum},
            {type:"select",label:"Kondisi Sumber Air Minum",field:"kdkondisisumberair",options:masters.kondisi_sumber_air},
            {type:"select",label:"Cara Memperoleh Air Minum",field:"kdcaraperolehanair",options:masters.cara_perolehan_air},
            {type:"select",label:"Sumber Penerangan Utama",field:"kdsumberpeneranganutama",options:masters.sumber_penerangan},
            {type:"select",label:"Sumber Daya Terpasang",field:"kdsumberdayaterpasang",options:masters.daya_terpasang},
            {type:"select",label:"Bahan Bakar Memasak",field:"kdbahanbakarmemasak",options:masters.bahan_bakar},
            {type:"select",label:"Penggunaan Fasilitas Tempat BAB",field:"kdfasilitastempatbab",options:masters.fasilitas_bab},
            {type:"select",label:"Tempat Pembuangan Akhir Tinja",field:"kdpembuanganakhirtinja",options:masters.pembuangan_tinja},
            {type:"select",label:"Cara Pembuangan Akhir Sampah",field:"kdcarapembuangansampah",options:masters.pembuangan_sampah},
            {type:"select",label:"Manfaat Mata Air",field:"kdmanfaatmataair",options:masters.manfaat_mataair},
            {type:"number",label:"Luas Lantai Rumah ini dalam meter persegi",field:"prasdas_luaslantai"},
            {type:"number",label:"Ada berapa kamar tidur di rumah ini",field:"prasdas_jumlahkamar"}
        ];
        Object.entries(asetKeluargaOptions).forEach(([kd,label])=>questions[3].push({type:"select",label:label,field:`asetkeluarga_${kd}`,options:jawabOptions}));
        Object.entries(lahanOptions).forEach(([kd,label])=>questions[4].push({type:"text",label:label,field:`asetlahan_${kd}`,isLahan:true}));
        Object.entries(asetTernakOptions).forEach(([kd,label])=>questions[5].push({type:"text",label:label,field:`asetternak_${kd}`,isTernak:true}));
        Object.entries(asetPerikananOptions).forEach(([kd,label])=>questions[6].push({type:"text",label:label,field:`asetperikanan_${kd}`,isPerikanan:true}));
        Object.entries(sarprasOptions).forEach(([kd,label])=>questions[7].push({type:"select",label:label,field:`sarpraskerja_${kd}`,options:jawabSarprasOptions}));
        Object.entries(bangunKeluargaOptions).slice(0,51).forEach(([kd,label])=>{ if(kd<=51) questions[8].push({type:"select",label:label,field:`bangunkeluarga_${kd}`,options:jawabBangunOptions}); });
        [
            {field:"sejahterakeluarga_61",label:"Rata-rata uang saku anak untuk sekolah perhari"},
            {field:"sejahterakeluarga_62",label:"Keluarga memiliki kebiasaan merokok? Jika ya, berapa bungkus perhari"},
            {field:"sejahterakeluarga_63",label:"Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa kali"},
            {field:"sejahterakeluarga_64",label:"Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa jam perhari"},
            {field:"sejahterakeluarga_65",label:"Rata-rata pulsa yang digunakan keluarga seminggu"},
            {field:"sejahterakeluarga_66",label:"Rata-rata pendapatan atau penghasilan keluarga sebulan"},
            {field:"sejahterakeluarga_67",label:"Rata-rata pengeluaran keluarga sebulan"},
            {field:"sejahterakeluarga_68",label:"Rata-rata uang belanja keluarga sebulan"}
        ].forEach(q=>questions[9].push({type:"text",label:q.label,field:q.field,isUraian:true}));
        Object.entries(konflikSosialOptions).forEach(([kd,label])=>questions[10].push({type:"select",label:label,field:`konfliksosial_${kd}`,options:jawabKonflikOptions}));
        Object.entries(kualitasIbuHamilOptions).forEach(([kd,label])=>{
            questions[11].push({
                type:"select",
                label: label,
                field: `kualitasibuhamil_${kd}`,
                options: jawabKualitasIbuHamilOptions
            });
        });
        Object.entries(kualitasBayiOptions).forEach(([kd,label])=>{
            questions[12].push({
                type:"select",
                label: label,
                field: `kualitasbayi_${kd}`,
                options: jawabKualitasBayiOptions
            });
        });
        const wilayahQuestions = [
            {type:"select",label:"Provinsi asalnya apa?",field:"kdprovinsi",options:provinsiOptions},
            {type:"select",label:"Kabupaten atau kota asalnya apa?",field:"kdkabupaten",dynamic:true,parentField:"kdprovinsi",dynamicUrl:"/get-kabupaten/"},
            {type:"select",label:"Kecamatan asalnya apa?",field:"kdkecamatan",dynamic:true,parentField:"kdkabupaten",dynamicUrl:"/get-kecamatan/"},
            {type:"select",label:"Desa atau kelurahan asalnya apa?",field:"kddesa",dynamic:true,parentField:"kdkecamatan",dynamicUrl:"/get-desa/"}
        ];
       
        // Insert wilayah jika mutasi datang (akan ditangani dinamis)
        function injectWilayahIfNeeded() {
            const idx = questions[1].findIndex(q => q.field === "kdmutasimasuk");
            const hasWilayah = questions[1].some(q => q.field === "kdprovinsi");
            if (answers.kdmutasimasuk === '3' && !hasWilayah) {
                questions[1].splice(idx + 1, 0, ...wilayahQuestions);
            } else if (answers.kdmutasimasuk !== '3' && hasWilayah) {
                questions[1] = questions[1].filter(q => !["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].includes(q.field));
                ["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].forEach(f=>delete answers[f]);
            }
        }
        // ==================== UTILITIES ====================
        function capitalize(t){return t.replace(/\b\w/g,l=>l.toUpperCase());}
        function pad3(n){return String(n).padStart(3,'0');}
        function normalize(t){return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim();}
        function cleanOptionText(t){return t.replace(/\//g,' atau ').replace(/_/g,' ');}
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
        function mapLahanToCode(ha){
            if(ha<=0)return'0';if(ha<=0.2)return'1';if(ha<=0.3)return'2';if(ha<=0.4)return'3';
            if(ha<=0.5)return'4';if(ha<=0.6)return'5';if(ha<=0.7)return'6';if(ha<=0.8)return'7';
            if(ha<=0.9)return'8';if(ha<=1.0)return'9';if(ha<=5.0)return'10';return'11';
        }
        function parseRupiah(t){
            const n=normalize(t); let num=0;
            const m=n.match(/(\d+(?:\.\d+)?)/); if(m)num=parseFloat(m[1]);
            if(n.includes('juta'))num*=1000000;else if(n.includes('ribu'))num*=1000;
            const w={satu:1,dua:2,tiga:3,empat:4,lima:5,enam:6,tujuh:7,delapan:8,sembilan:9,sepuluh:10};
            for(let[k,v]of Object.entries(w))if(n.includes(k)){num=v*(n.includes('juta')?1000000:(n.includes('ribu')?1000:1));break;}
            return Math.round(num).toString();
        }
        function parseAngka(t){const m=normalize(t).match(/(\d+)/);return m?m[1]:'0';}
        function speak(t){
            return new Promise(r=>{
                if(isSpeaking)return r(); isSpeaking=true;
                const u=new SpeechSynthesisUtterance(t);
                u.lang='id-ID'; u.rate=1.2;
                u.onend=()=>{isSpeaking=false;r();};
                speechSynthesis.speak(u);
            });
        }
        // ==================== CORE FUNCTIONS ====================
        function initFresh(){
            localStorage.clear(); currentModul=1; step=0;
            answers={keluarga_tanggalmutasi:new Date().toISOString().split('T')[0]};
            modulStatus={1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending',9:'pending',10:'pending',11:'pending',12:'pending'};
            isReviewMode=false;
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('inputArea').classList.remove('hidden');
            document.getElementById('simpanBtn').style.backgroundColor='#9ca3af'; document.getElementById('simpanBtn').disabled=true;
            document.getElementById('quizArea').innerHTML=''; document.getElementById('voice-status').innerText='Tekan mic untuk mulai merekam...';
            updateProgressSteps(); renderQuestion(); checkAllCompletedAndShowSimpanBtn();
        }
        function saveData(){
            localStorage.setItem('voiceAnswers',JSON.stringify(answers));
            localStorage.setItem('modulStatus',JSON.stringify(modulStatus));
            localStorage.setItem(`step_${currentModul}`,step);
            localStorage.setItem(`review_${currentModul}`,isReviewMode);
            localStorage.setItem('currentModul',currentModul);
        }
        function loadModulData(id){
            const a=localStorage.getItem('voiceAnswers'); if(a)answers=JSON.parse(a);
            const s=localStorage.getItem('modulStatus'); if(s)modulStatus=JSON.parse(s);
            const st=localStorage.getItem(`step_${id}`); const rv=localStorage.getItem(`review_${id}`);
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
            document.getElementById('quizArea').innerHTML=''; document.getElementById('voice-status').innerText='Tekan mic untuk mulai merekam...';
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
            if(allDone){b.style.backgroundColor='#2563eb';b.disabled=false;}else{b.style.backgroundColor='#9ca3af';b.disabled=true;}
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
            if(q.dynamic && !q.options){
                const parentValue = answers[q.parentField];
                if(parentValue){
                    try {
                        const res = await fetch(`${q.dynamicUrl}${parentValue}`);
                        q.options = await res.json();
                    } catch (err) {
                        console.error('Gagal load options dinamis:', err);
                        q.options = {};
                    }
                } else {
                    q.options = {};
                }
            }
            let html='';
            if(currentModul===3&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong class="mx-2">YA</strong> / <strong class="mx-2">TIDAK</strong> / <strong class="mx-2">KOSONG</strong></div>`;
            if(currentModul===4&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong>"tidak punya"</strong> atau <strong>angka hektar</strong></div>`;
            if(currentModul===5&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan angka jumlah ekor, atau "tidak ada"</div>`;
            if(currentModul===6&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan angka jumlah atau "tidak ada"</div>`;
            if(currentModul===7&&step===0) html+=`<div class="bg-blue-50 border border-blue-300 text-blue-800 p-5 rounded-xl text-center font-medium mb-6 leading-relaxed">
                <strong class="block mb-2">Jawab dengan angka 1 sampai 6 saja:</strong>
                <div class="text-sm grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                    <span>1. Milik sendiri (bagus)</span>
                    <span>2. Milik sendiri (jelek)</span>
                    <span>3. Milik kelompok (sewa gratis)</span>
                    <span>4. Milik orang lain (sewa bayar)</span>
                    <span>5. Milik orang lain (sewa gratis)</span>
                    <span>6. Tidak memiliki</span>
                </div>
            </div>`;
            if(currentModul===8&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong class="mx-2">YA</strong> / <strong class="mx-2">TIDAK</strong></div>`;
            if(currentModul===9&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan angka saja atau "tidak ada"</div>`;
            if(currentModul===10&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong class="mx-2"></strong>ADA <strong class="mx-2">atau</strong> TIDAK ADA</div>`;
            if(currentModul===11&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong class="mx-2">1</strong> ADA / <strong class="mx-2">2</strong> PERNAH ADA / <strong class="mx-2">3</strong> TIDAK ADA</div>`;
            if(currentModul === 12 && step===0){
                html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">
                        Jawab: <strong class="mx-2">1</strong> ADA /
                            <strong class="mx-2">2</strong> PERNAH ADA /
                            <strong class="mx-2">3</strong> TIDAK ADA
                    </div>`;
            }
            html+=`<h3 class="text-lg font-medium text-center mb-6 text-gray-800">${q.label}</h3>`;
            if(q.type==="select"){
                let cols = currentModul===3?2:(currentModul===8?2:(currentModul===7?3:(currentModul===8?3:(currentModul===10?2:3))));
                let gridClass = `grid grid-cols-2 sm:grid-cols-3 md:grid-cols-${cols} gap-3 max-w-5xl mx-auto`;
                html+=`<div class="${gridClass}">`;
                Object.entries(q.options).forEach(([id,nama])=>{
                    const sel=answers[q.field]==id?'selected':'';
                    html+=`<div class="option-card ${sel}" data-value="${id}" data-text="${nama}"><span class="text-sm font-medium">${nama}</span></div>`;
                });
                html+='</div>';
            }else{
                const val = q.isLahan?(jawabLahanOptions[answers[q.field]]||''):(answers[q.field]||'');
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
        // ==================== PERUBAHAN UTAMA: SARPRAS KERJA (MODUL 7) ====================
        async function speakQuestionAndOptions(){
            //await speak(`Pendataan data ${modules.find(m=>m.id===currentModul).name} dimulai.`);
            const q = questions[currentModul][step];
            // MODUL 7: Jelaskan pilihan 1-6 HANYA SEKALI DI AWAL
            if(currentModul === 7 && step === 0){
                await speak("Modul Sarpras Kerja dimulai.");
                await speak("Untuk setiap pertanyaan, jawab dengan angka 1 sampai 6 sesuai pilihan berikut:");
                await speak("1. Milik sendiri kondisi baik");
                await speak("2. Milik sendiri kondisi tidak baik");
                await speak("3. Milik kelompok sewa tidak bayar");
                await speak("4. Milik orang lain sewa bayar");
                await speak("5. Milik orang lain sewa tidak bayar");
                await speak("6. Tidak memiliki");
                await new Promise(r => setTimeout(r, 4000)); // beri waktu user menghafal
            }
            // Hint suara biasa untuk modul lain
            if(currentModul===3&&step===0){await speak("Modul Aset Keluarga. Jawab YA, TIDAK, atau KOSONG.");}
            if(currentModul===4&&step===0){await speak("Modul Aset Lahan Tanah. Jawab 'tidak punya' atau angka hektar.");}
            if(currentModul===5&&step===0){await speak("Modul Aset Ternak. Jawab jumlah ekor atau 'tidak ada'.");}
            if(currentModul===6&&step===0){await speak("Modul Aset Perikanan. Jawab jumlah atau 'tidak ada'.");}
            if(currentModul===8&&step===0){await speak("Modul Bangun Keluarga. Jawab YA atau TIDAK.");}
            if(currentModul===9&&step===0){await speak("Modul Sejahtera Keluarga. Jawab dengan angka atau 'tidak ada'.");}
            if(currentModul===10&&step===0){await speak("Modul Konflik Sosial. Jawab ADA, atau TIDAK ADA.");}
            // === HANYA SEKALI DI AWAL MODUL 11: Jelaskan pilihan jawaban ===
            if(currentModul === 11 && step === 0){
                await speak("Modul Kualitas Ibu Hamil dimulai.");
                await speak("Untuk setiap pertanyaan, jawab dengan:");
                await speak("Satu untuk ADA");
                await speak("Dua untuk PERNAH ADA");
                await speak("Tiga untuk TIDAK ADA");
                await new Promise(r => setTimeout(r, 2500));
            }
            if(currentModul === 12 && step === 0){
                await speak("Modul Kualitas Bayi dimulai.");
                await speak("Untuk setiap pertanyaan, jawab dengan:");
                await speak("Satu untuk ADA");
                await speak("Dua untuk PERNAH ADA");
                await speak("Tiga untuk TIDAK ADA");
                await new Promise(r => setTimeout(r, 2500));
            }
            let pertanyaanDibaca = q.label
                .replace(/\//g, ' atau ')
                .replace(/\&/g, ' dan ')
                .replace(/SARA/g, 'sara')
                .replace(/HIV\/AIDS/g, 'HIV AIDS')
                .replace(/\s+/g, ' ')
                .trim();
            await speak(pertanyaanDibaca);
            // Baca pilihan hanya untuk modul yang butuh (kecuali modul 7, dan skip untuk wilayah datang)
            if(q.type==="select" && currentModul !== 7 && ![3,8,10,11].includes(currentModul) && !["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].includes(q.field)){
                const opts=Object.values(q.options);
                for(let i=0;i<opts.length;i++){
                    await speak(`${i+1}. ${cleanOptionText(opts[i])}`);
                    if(i<opts.length-1)await new Promise(r=>setTimeout(r,100));
                }
            }
            document.getElementById('voice-status').innerText='Mendengarkan...';
        }
        async function processVoiceAnswer(text){
            if(isSpeaking) return;
            const q = questions[currentModul][step];
            let value = text;
            // === KHUSUS WILAYAH DATANG (provinsi, kab, kec, desa) ===
            if(["kdprovinsi", "kdkabupaten", "kdkecamatan", "kddesa"].includes(q.field)){
                const match = findBestMatch(text, q.options);
                if(!match){
                    await speak("Maaf, nama wilayah tidak dikenali. Ulangi dengan jelas.");
                    return;
                }
                value = match[0];
                answers[q.field] = value;
                const card = document.querySelector(`.option-card[data-value="${value}"]`);
                if(card){
                    document.querySelectorAll('.option-card').forEach(c=>c.classList.remove('selected'));
                    card.classList.add('selected');
                }
                saveData();
                setTimeout(async()=>{
                    step++;
                    if(step < questions[currentModul].length){
                        renderQuestion();
                        if(isListening) await speakQuestionAndOptions();
                    }else{
                        isReviewMode=true;
                        modulStatus[currentModul]='completed';
                        saveData();
                        updateProgressSteps();
                        checkAllCompletedAndShowSimpanBtn();
                        showReviewForm();
                    }
                },1200);
                return;
            }
            else if(currentModul === 7){
            const n = normalize(text);
            // Prioritas deteksi kata kunci
            if (n.includes('milik sendiri') && (n.includes('bagus') || n.includes('baik') || n.includes('kondisi baik'))) {
                value = '1'; // MILIK SENDIRI (BAGUS)
            }
            else if (n.includes('milik sendiri') && (n.includes('jelek') || n.includes('tidak baik') || n.includes('rusak'))) {
                value = '2'; // MILIK SENDIRI (JELEK)
            }
            else if (n.includes('milik kelompok') || (n.includes('kelompok') && n.includes('sewa tidak bayar'))) {
                value = '3'; // MILIK KELOMPOK (SEWA TIDAK BAYAR)
            }
            else if (n.includes('milik orang lain') && (n.includes('sewa bayar') || n.includes('bayar'))) {
                value = '4'; // MILIK ORANG LAIN (SEWA BAYAR)
            }
            else if (n.includes('milik orang lain') && (n.includes('sewa tidak bayar') || n.includes('gratis') || n.includes('tidak bayar'))) {
                value = '5'; // MILIK ORANG LAIN (SEWA TIDAK BAYAR)
            }
            else if (n.includes('tidak memiliki') || n.includes('tidak punya') || n.includes('nggak punya') || n.includes('ga punya') || n.includes('kosong')) {
                value = '6'; // TIDAK MEMILIKI
            }
            else {
                await speak("Maaf, jawaban tidak dikenali. Ulangi dengan mengucapkan salah satu pilihan seperti 'milik sendiri bagus' atau 'tidak memiliki'");
                return;
            }
            // Tampilkan teks jawaban yang dipilih di input (lebih informatif)
            const jawabanTeks = {
                '1': 'Milik sendiri (bagus)',
                '2': 'Milik sendiri (jelek)',
                '3': 'Milik kelompok',
                '4': 'Milik orang lain (sewa bayar)',
                '5': 'Milik orang lain (sewa tidak bayar)',
                '6': 'Tidak memiliki'
            };
            if (document.getElementById('inputAnswer')) {
                document.getElementById('inputAnswer').value = jawabanTeks[value];
            }
        }
            // SEMUA LOGIKA LAIN TETAP SAMA
            else if(q.isLahan){
                const n=normalize(text);
                if(n.includes('tidak')||n.includes('ga')||n.includes('nggak'))value='0';
                else{
                    const m=text.match(/[\d.,]+/);
                    if(!m){await speak("Ulangi dengan angka hektar atau 'tidak punya'");return;}
                    const ha=parseFloat(m[0].replace(',','.'));
                    value=mapLahanToCode(ha);
                }
                document.getElementById('inputAnswer').value=jawabLahanOptions[value]||value;
            }
            else if(q.isTernak || q.isPerikanan){
                const n = normalize(text);
                if(n.includes('tidak') || n.includes('ga') || n.includes('nggak') || n.includes('nol') || n.includes('kosong') || n.includes('ada')){
                    value = '0';
                } else {
                    const m = text.match(/\d+/);
                    if(!m){
                        await speak("Ulangi dengan angka jumlah atau 'tidak ada'");
                        return;
                    }
                    value = m[0];
                }
                document.getElementById('inputAnswer').value = value;
            }
            else if(q.isUraian){
                const n=normalize(text);
                let v='0';
                if(n.includes('tidak')||n.includes('ga')||n.includes('nol'))v='0';
                else v = q.field.includes('_61')||q.field.includes('_65')||q.field.includes('_66')||q.field.includes('_67')||q.field.includes('_68') ? parseRupiah(text) : parseAngka(text);
                if(v==='0'){await speak("Ulangi dengan angka yang jelas");return;}
                value=v; document.getElementById('inputAnswer').value=value;
            }
            else if(q.type==="select" && (currentModul===3||currentModul===8)){
                const n=normalize(text);
                value = n.includes('ya')||n.includes('punya')||n.includes('ada') ? '1' : '2';
                if(!['1','2'].includes(value)){await speak("Jawab ya atau tidak");return;}
            }
            else if(q.type==="select" && currentModul===10){
                const n = normalize(text);
               
                // Prioritas: "tidak ada" harus menang atas "ada"
                if(n.includes('tidak ada') || n.includes('nggak ada') || n.includes('ga ada') || n.includes('belum pernah') || n.includes('tidak pernah')){
                    value = '2'; // TIDAK ADA
                }
                else if(n.includes('ada') || n.includes('pernah') || n.includes('iya') || n.includes('ya')){
                    value = '1'; // ADA
                }
                else if(n.includes('b') || n.includes('dua')){
                    value = '2';
                }
                else if(n.includes('a') || n.includes('satu')){
                    value = '1';
                }
                else {
                    await speak("Maaf, jawab dengan ADA atau TIDAK ADA");
                    return;
                }
            }
            else if(q.type==="select"){
                const m=findBestMatch(text,q.options);
                if(!m){await speak("Maaf, tidak dikenali. Ulangi.");return;}
                value=m[0];
            }
            else if(q.field==="no_kk"){
                value=text.replace(/\D/g,'').slice(0,16);
                if(value.length!==16){await speak("Harus 16 digit");return;}
                document.getElementById('inputAnswer').value=value;
            }
            else if(q.field==="keluarga_rw"||q.field==="keluarga_rt"){
                const m=text.match(/\d+/g);
                if(!m){await speak("Tidak ada angka");return;}
                value=pad3(m.join('')); document.getElementById('inputAnswer').value=value;
            }
            else if(q.type==="text"){
                value=capitalize(text.trim()); document.getElementById('inputAnswer').value=value;
            }
            else{
                const m=text.match(/\d+/g);
                if(!m){await speak("Tidak ada angka");return;}
                value=m.join(''); document.getElementById('inputAnswer').value=value;
            }
            answers[q.field]=value;
            if(q.type==="select"){
                const card=document.querySelector(`.option-card[data-value="${value}"]`);
                if(card){
                    document.querySelectorAll('.option-card').forEach(c=>c.classList.remove('selected'));
                    card.classList.add('selected');
                }
            }
            // === DETEKSI MUTASI "DATANG" → TAMBAH 4 PERTANYAAN WILAYAH ===
            if(currentModul===1 && q.field==="kdmutasimasuk"){
                injectWilayahIfNeeded();
            }
            // === KHUSUS MODUL 11: KUALITAS IBU HAMIL ===
        if(currentModul === 11 || currentModul === 12){
            const n = normalize(text);
            if(n.includes('ada') && !n.includes('pernah') && !n.includes('tidak')){
                value = '1'; // ADA
            }
            else if(n.includes('pernah')){
                value = '2'; // PERNAH ADA
            }
            else if(n.includes('tidak ada') || n.includes('nggak') || n.includes('ga ada') || n.includes('ga') || n.includes('tidak')){
                value = '3'; // TIDAK ADA
            }
            else if(n.includes('satu') || n.includes('1')){
                value = '1';
            }
            else if(n.includes('dua') || n.includes('2')){
                value = '2';
            }
            else if(n.includes('tiga') || n.includes('3')){
                value = '3';
            }
            else {
                await speak("Maaf tidak dikenali. Ulangi: ADA, PERNAH ADA, atau TIDAK ADA");
                return;
            }
        }
            saveData();
            setTimeout(async()=>{
                step++;
                if(step<questions[currentModul].length){
                    renderQuestion();
                    if(isListening) await speakQuestionAndOptions();
                }else{
                    isReviewMode=true; modulStatus[currentModul]='completed'; saveData();
                    updateProgressSteps(); checkAllCompletedAndShowSimpanBtn(); showReviewForm();
                }
            },1200);
        }
        function showReviewForm(){
            stopListening();
            document.getElementById('inputArea').classList.add('hidden');
            document.getElementById('reviewForm').classList.remove('hidden');
            document.getElementById('quizArea').innerHTML='';
            document.getElementById('voice-status').innerText='Review data. Selesaikan semua modul untuk simpan.';
            const container = document.getElementById('reviewFields');
            container.innerHTML = '';
            const isMutasiDatang = answers.kdmutasimasuk === '3';
            if(currentModul === 1){
                let html = '';
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Nomor Kartu Keluarga</label>
                    <input type="text" name="no_kk" value="${answers.no_kk||''}" maxlength="16" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Jenis Mutasi</label>
                    <select name="kdmutasimasuk" id="review_kdmutasimasuk" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(mutasiOptions).map(([k,v])=>`<option value="${k}" ${answers.kdmutasimasuk === k ? 'selected' : ''}>${v}</option>`).join('')}
                    </select>
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Kepala Rumah Tangga</label>
                    <input type="text" name="keluarga_kepalakeluarga" value="${answers.keluarga_kepalakeluarga||''}" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Dusun</label>
                    <select name="kddusun" class="w-full border rounded-lg p-2.5 text-sm">
                        <option value="">-- Pilih --</option>
                        ${Object.entries(dusunOptions).map(([k,v])=>`<option value="${k}" ${answers.kddusun === k ? 'selected' : ''}>${v}</option>`).join('')}
                    </select>
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">RW</label>
                    <input type="number" name="keluarga_rw" value="${answers.keluarga_rw||''}" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">RT</label>
                    <input type="number" name="keluarga_rt" value="${answers.keluarga_rt||''}" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Alamat Lengkap</label>
                    <input type="text" name="keluarga_alamatlengkap" value="${answers.keluarga_alamatlengkap||''}" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                html += `<div>
                    <label class="block text-xs font-medium mb-1">Tanggal Mutasi</label>
                    <input type="date" name="keluarga_tanggalmutasi" value="${answers.keluarga_tanggalmutasi}" class="w-full border rounded-lg p-2.5 text-sm">
                </div>`;
                // Wilayah Datang
                html += `<div id="wilayahDatangSection" class="${isMutasiDatang ? '' : 'hidden'} col-span-3 bg-teal-50 p-6 rounded-xl border border-teal-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <h4 class="font-bold text-teal-800 mb-4 col-span-2">Wilayah Datang</h4>
                    <div>
                        <label class="block text-xs font-medium mb-1">Provinsi</label>
                        <select name="kdprovinsi" id="review_kdprovinsi" class="w-full border rounded-lg p-2.5 text-sm">
                            <option value="">-- Pilih --</option>
                            ${Object.entries(provinsiOptions).map(([k,v])=>`<option value="${k}" ${answers.kdprovinsi === k ? 'selected' : ''}>${v}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Kabupaten/Kota</label>
                        <select name="kdkabupaten" id="review_kdkabupaten" class="w-full border rounded-lg p-2.5 text-sm">
                            <option>-- Pilih Provinsi Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Kecamatan</label>
                        <select name="kdkecamatan" id="review_kdkecamatan" class="w-full border rounded-lg p-2.5 text-sm">
                            <option>-- Pilih Kabupaten Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Desa/Kelurahan</label>
                        <select name="kddesa" id="review_kddesa" class="w-full border rounded-lg p-2.5 text-sm">
                            <option>-- Pilih Kecamatan Dahulu --</option>
                        </select>
                    </div>
                </div>`;
                container.innerHTML = html;
                // Event listener untuk toggle wilayah datang di review
                document.getElementById('review_kdmutasimasuk').addEventListener('change', function(){
                    answers.kdmutasimasuk = this.value;
                    const section = document.getElementById('wilayahDatangSection');
                    if(this.value === '3'){
                        section.classList.remove('hidden');
                        injectWilayahIfNeeded();
                    } else {
                        section.classList.add('hidden');
                        ["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].forEach(f=>{
                            delete answers[f];
                            const el = document.querySelector(`[name="${f}"]`);
                            if(el) el.value = '';
                        });
                        questions[1] = questions[1].filter(q => !["kdprovinsi","kdkabupaten","kdkecamatan","kddesa"].includes(q.field));
                    }
                    saveData();
                });
                // Event cascade wilayah datang
                document.getElementById('review_kdprovinsi')?.addEventListener('change', function(){
                    window.getWilayah('kabupaten', this.value, 'review_kdkabupaten');
                    document.getElementById('review_kdkecamatan').innerHTML = '<option>-- Pilih Kabupaten --</option>';
                    document.getElementById('review_kddesa').innerHTML = '<option>-- Pilih Kecamatan --</option>';
                });
                document.getElementById('review_kdkabupaten')?.addEventListener('change', function(){
                    window.getWilayah('kecamatan', this.value, 'review_kdkecamatan');
                    document.getElementById('review_kddesa').innerHTML = '<option>-- Pilih Kecamatan --</option>';
                });
                document.getElementById('review_kdkecamatan')?.addEventListener('change', function(){
                    window.getWilayah('desa', this.value, 'review_kddesa');
                });
                // Auto-load jika sudah ada nilai
                if(answers.kdprovinsi){
                    setTimeout(()=>{
                        window.getWilayah('kabupaten', answers.kdprovinsi, 'review_kdkabupaten', answers.kdkabupaten);
                        if(answers.kdkabupaten) setTimeout(()=>window.getWilayah('kecamatan', answers.kdkabupaten, 'review_kdkecamatan', answers.kdkecamatan),400);
                        if(answers.kdkecamatan) setTimeout(()=>window.getWilayah('desa', answers.kdkecamatan, 'review_kddesa', answers.kddesa),800);
                    },300);
                }
            } else {
                questions[currentModul].forEach(q=>{
                    if(!answers[q.field]) return;
                    let input='';
                    if(q.isLahan){
                        input=`<select name="${q.field}" class="w-full border rounded-lg p-2 text-sm"><option value="">-- Pilih --</option>`;
                        Object.entries(jawabLahanOptions).forEach(([k,v])=>input+=`<option value="${k}" ${answers[q.field]==k?'selected':''}>${v}</option>`);
                        input+='</select>';
                    }else if(q.isTernak||q.isPerikanan||q.isUraian){
                        input=`<input type="number" name="${q.field}" value="${answers[q.field]||'0'}" class="w-full border rounded-lg p-2 text-sm" min="0">`;
                    }else if(q.type==="select"){
                        input=`<select name="${q.field}" class="w-full border rounded-lg p-2 text-sm"><option value="">-- Pilih --</option>`;
                        Object.entries(q.options).forEach(([k,v])=>input+=`<option value="${k}" ${answers[q.field]==k?'selected':''}>${v}</option>`);
                        input+='</select>';
                    }else{
                        input=`<input type="${q.type==='number'?'number':'text'}" name="${q.field}" value="${answers[q.field]||''}" class="w-full border rounded-lg p-2 text-sm">`;
                    }
                    container.innerHTML+=`<div><label class="block text-xs font-medium mb-1">${q.label}</label>${input}</div>`;
                });
            }
            // Tambahkan listener untuk update answers saat edit di review
            document.querySelectorAll('#reviewFields input, #reviewFields select').forEach(el => {
                el.addEventListener('change', e => {
                    answers[e.target.name] = e.target.value;
                    saveData();
                });
            });
        }
        document.getElementById('recordBtn').addEventListener('click', async () => {
            if (isReviewMode) return;
            if (isListening) {
                stopListening();
                return;
            }
            isListening = true;
            const btn = document.getElementById('recordBtn');
            btn.classList.add('recording');
            // Saat mulai merekam
            document.getElementById('visualizer').classList.add('show');
            document.getElementById('visualizerPlaceholder').classList.add('hide');
            // Ganti ikon jadi stop (kotak putih)
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
                // Responsive canvas
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
                gradient.addColorStop(0, '#10b981');
                gradient.addColorStop(1, '#34d399');
                ctx.fillStyle = gradient;
                ctx.roundRect(x, canvas.height/2 - barHeight/2, barWidth * 0.7, barHeight, 6).fill();
                x += barWidth + 2;
            }
        }
        // Tambahkan roundRect untuk canvas (polyfill)
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
        document.getElementById('voiceForm').addEventListener('submit', async function(e){
            e.preventDefault();
            const btn=document.getElementById('simpanBtn'); btn.disabled=true; btn.innerText="Menyimpan...";
            Object.keys(answers).forEach(k=>{
                let el=document.querySelector(`[name="${k}"]`);
                if(!el){el=document.createElement('input');el.type='hidden';el.name=k;this.appendChild(el);}
                el.value=answers[k];
            });
            const fd=new FormData(this);
            const token=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.getElementById('loadingOverlay').classList.remove('hidden');
            let progress = 0;
            const interval = setInterval(() => {
                progress += 1;
                if(progress > 95) progress = 95;
                document.getElementById('loadingBar').style.width = progress + '%';
                document.getElementById('loadingText').innerText = `Menyimpan data... ${progress}%`;
            }, 100);
            try{
                const res=await fetch("{{ route('voice.keluarga.store-all') }}",{
                    method:"POST", headers:{"X-CSRF-TOKEN":token,"Accept":"application/json"}, body:fd
                });
                const data=await res.json();
                clearInterval(interval);
                document.getElementById('loadingBar').style.width = '100%';
                document.getElementById('loadingText').innerText = 'Data tersimpan! 100%';
                setTimeout(() => {
                    document.getElementById('loadingOverlay').classList.add('hidden');
                }, 1000);
                if(data.success){localStorage.clear();alert("SEMUA DATA BERHASIL DISIMPAN!");location.reload();}
                else alert("Gagal: "+(data.error||JSON.stringify(data)));
            }catch(err){
                clearInterval(interval);
                document.getElementById('loadingOverlay').classList.add('hidden');
                alert("Error: "+err.message);
            }
            finally{btn.disabled=false;btn.innerText="Simpan Semua Data";}
        });
        // GANTI fungsi window.getWilayah jadi ini:
        window.getWilayah = async function(tipe, parentId, targetId, selectedValue) {
            if (!parentId) {
                document.getElementById(targetId).innerHTML = '<option value="">-- Pilih Dulu --</option>';
                return;
            }
            const select = document.getElementById(targetId);
            select.innerHTML = '<option>-- Memuat... --</option>';
            select.disabled = true;
            try {
                let url;
                if (tipe === 'kabupaten') url = `/get-kabupaten/${parentId}`;
                if (tipe === 'kecamatan') url = `/get-kecamatan/${parentId}`;
                if (tipe === 'desa') url = `/get-desa/${parentId}`;
                const res = await fetch(url);
                const data = await res.json();
                select.innerHTML = '<option value="">-- Pilih --</option>';
                Object.entries(data).forEach(([id, nama]) => {
                    const selected = (id === selectedValue) ? ' selected' : '';
                    select.innerHTML += `<option value="${id}"${selected}>${nama}</option>`;
                });
            } catch (err) {
                console.error(err);
                select.innerHTML = '<option value="">Gagal memuat data</option>';
            } finally {
                select.disabled = false;
            }
        };
        document.getElementById('restartBtn').addEventListener('click', function() {
            // Reset hanya data modul ini
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
        // ==================== VALIDASI FREKUENSI SUARA (ANTI-MANIPULASI) ====================
        let voiceFingerprint = null;
        const FINGERPRINT_KEY = 'voiceFingerprint';
        const SIMILARITY_THRESHOLD = 0.90; // 90% kemiripan dianggap suara sama
        function generateFingerprintFromDataArray(dataArray) {
            // Ambil sampel dari 32 bin pertama (suara manusia biasanya di frekuensi rendah-menengah)
            const sample = dataArray.slice(0, 32);
            const sum = sample.reduce((a, b) => a + b, 0);
            const avg = sum / sample.length;
            const variance = sample.reduce((a, b) => a + Math.pow(b - avg, 2), 0) / sample.length;
           
            // Buat string fingerprint sederhana
            return btoa(String.fromCharCode(...sample)) + '|' + avg.toFixed(2) + '|' + variance.toFixed(2);
        }
        function compareFingerprints(fp1, fp2) {
            if (!fp1 || !fp2) return 0;
            const [hash1, avg1, var1] = fp1.split('|');
            const [hash2, avg2, var2] = fp2.split('|');
           
            const avgDiff = Math.abs(parseFloat(avg1) - parseFloat(avg2));
            const varDiff = Math.abs(parseFloat(var1) - parseFloat(var2));
           
            // Semakin kecil diff, semakin mirip
            const avgSimilarity = 1 - (avgDiff / 255);
            const varSimilarity = 1 - (varDiff / 10000);
           
            return (avgSimilarity + varSimilarity) / 2;
        }
        async function validateVoiceUniqueness() {
            if (!analyser || !dataArray) return true;
            // Ambil 50 frame sampel untuk akurasi
            const samples = [];
            for (let i = 0; i < 50; i++) {
                analyser.getByteFrequencyData(dataArray);
                samples.push([...dataArray]); // copy array
                await new Promise(r => setTimeout(r, 50)); // 50ms x 50 = 2.5 detik sampling
            }
            // Hitung fingerprint dari rata-rata semua sampel
            const avgSample = new Uint8Array(dataArray.length);
            for (let i = 0; i < dataArray.length; i++) {
                avgSample[i] = samples.reduce((sum, arr) => sum + arr[i], 0) / samples.length;
            }
            voiceFingerprint = generateFingerprintFromDataArray(avgSample);
            const savedFp = localStorage.getItem(FINGERPRINT_KEY);
            if (savedFp) {
                const similarity = compareFingerprints(voiceFingerprint, savedFp);
                if (similarity >= SIMILARITY_THRESHOLD) {
                    await speak("Peringatan: Suara Anda terdeteksi sudah pernah digunakan untuk pendataan sebelumnya. Manipulasi pendataan tidak diperbolehkan.");
                    alert("⚠️ DETEKSI SUARA SAMA!\n\nSuara Anda sudah pernah digunakan untuk pendataan di perangkat ini.\nIni dapat dianggap sebagai manipulasi data.\n\nProses dihentikan untuk menjaga integritas pendataan.");
                    stopListening();
                    document.getElementById('voice-status').innerText = 'Pendataan dihentikan karena suara terdeteksi sama.';
                    return false;
                }
            }
            // Simpan fingerprint baru (hanya jika lolos validasi)
            localStorage.setItem(FINGERPRINT_KEY, voiceFingerprint);
            return true;
        }
        // Modifikasi speakQuestionAndOptions untuk menjalankan validasi hanya di pertanyaan pertama modul 1
        const originalSpeakQuestionAndOptions = speakQuestionAndOptions;
        speakQuestionAndOptions = async function() {
            const q = questions[currentModul][step];
            // Hanya jalankan validasi di pertanyaan pertama modul 1
            if (currentModul === 1 && step === 0 && q.field === "no_kk") {
                document.getElementById('voice-status').innerText = 'Memvalidasi suara Anda... Harap bicara sebentar...';
               
                const valid = await validateVoiceUniqueness();
                if (!valid) {
                    return; // hentikan proses
                }
               
                document.getElementById('voice-status').innerText = 'Suara valid. Lanjutkan menjawab...';
                await new Promise(r => setTimeout(r, 1000));
            }
            // Lanjutkan membaca pertanyaan seperti biasa
            await originalSpeakQuestionAndOptions();
        };
        initFresh();
    </script>
</x-app-layout>