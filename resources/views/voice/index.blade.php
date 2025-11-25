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
                Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">7</span>
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
            padding: 1rem; border-radius: 1rem; text-align: center; justify-content: center;
        }
        .option-card:hover { background-color: #f0fdfa; border-color: #14b8a6; }
        .option-card.selected { background-color: #ccfbf1 !important; border-color: #14b8a6 !important; box-shadow: 0 0 0 3px rgba(20,184,166,.2); }
        .bangun .option-card {
            width: 150px; flex: 1; min-height: 80px; display: flex; align-items: center;
            justify-content: center; white-space: normal; word-wrap: break-word;
        }
        #startBtn.listening { background: linear-gradient(to bottom right, #ef4444, #dc2626) !important; transform: scale(1.15); }
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
            {type:"select",label:"Status pemilik bangunan?",field:"kdstatuspemilikbangunan",options:masters.status_pemilik_bangunan},
            {type:"select",label:"Tanah pemilik lahan bangunan?",field:"kdstatuspemiliklahan",options:masters.status_pemilik_lahan},
            {type:"select",label:"Jenis Fisik Bangunan?",field:"kdjenisfisikbangunan",options:masters.jenis_fisik_bangunan},
            {type:"select",label:"Jenis Lantai Bangunan?",field:"kdjenislantaibangunan",options:masters.jenis_lantai},
            {type:"select",label:"Kondisi Lantai Bangunan?",field:"kdkondisilantaibangunan",options:masters.kondisi_lantai},
            {type:"select",label:"Jenis Dinding Bangunan?",field:"kdjenisdindingbangunan",options:masters.jenis_dinding},
            {type:"select",label:"Kondisi Dinding Bangunan?",field:"kdkondisidindingbangunan",options:masters.kondisi_dinding},
            {type:"select",label:"Jenis Atap Bangunan?",field:"kdjenisatapbangunan",options:masters.jenis_atap},
            {type:"select",label:"Kondisi Atap Bangunan?",field:"kdkondisiatapbangunan",options:masters.kondisi_atap},
            {type:"select",label:"Sumber Air Minum?",field:"kdsumberairminum",options:masters.sumber_air_minum},
            {type:"select",label:"Kondisi Sumber Air Minum?",field:"kdkondisisumberair",options:masters.kondisi_sumber_air},
            {type:"select",label:"Cara Memperoleh Air Minum?",field:"kdcaraperolehanair",options:masters.cara_perolehan_air},
            {type:"select",label:"Sumber Penerangan Utama?",field:"kdsumberpeneranganutama",options:masters.sumber_penerangan},
            {type:"select",label:"Sumber Daya terpasang?",field:"kdsumberdayaterpasang",options:masters.daya_terpasang},
            {type:"select",label:"Bahan Bakar Memasak?",field:"kdbahanbakarmemasak",options:masters.bahan_bakar},
            {type:"select",label:"Penggunaan Fasilitas Tempat BAB?",field:"kdfasilitastempatbab",options:masters.fasilitas_bab},
            {type:"select",label:"Tempat Pembuangan Akhir Tinja?",field:"kdpembuanganakhirtinja",options:masters.pembuangan_tinja},
            {type:"select",label:"Cara Pembuangan Akhir Sampah?",field:"kdcarapembuangansampah",options:masters.pembuangan_sampah},
            {type:"select",label:"Manfaat Mata Air?",field:"kdmanfaatmataair",options:masters.manfaat_mataair},
            {type:"number",label:"Luas Lantai Rumah ini dalam meter persegi?",field:"prasdas_luaslantai"},
            {type:"number",label:"Ada berapa kamar tidur di rumah ini?",field:"prasdas_jumlahkamar"}
        ];

        Object.entries(asetKeluargaOptions).forEach(([kd,label])=>questions[3].push({type:"select",label:label+"?",field:`asetkeluarga_${kd}`,options:jawabOptions}));
        Object.entries(lahanOptions).forEach(([kd,label])=>questions[4].push({type:"text",label:`${label}? Berapa hektar?`,field:`asetlahan_${kd}`,isLahan:true}));
        Object.entries(asetTernakOptions).forEach(([kd,label])=>questions[5].push({type:"text",label:`Jumlah ${label.toLowerCase()} (ekor)?`,field:`asetternak_${kd}`,isTernak:true}));
        Object.entries(asetPerikananOptions).forEach(([kd,label])=>questions[6].push({type:"text",label:`Jumlah ${label.toLowerCase()}?`,field:`asetperikanan_${kd}`,isPerikanan:true}));
        Object.entries(sarprasOptions).forEach(([kd,label])=>questions[7].push({type:"select",label:`Memiliki ${label.toLowerCase()} :`,field:`sarpraskerja_${kd}`,options:jawabSarprasOptions}));
        Object.entries(bangunKeluargaOptions).slice(0,51).forEach(([kd,label])=>{ if(kd<=51) questions[8].push({type:"select",label:label+" :",field:`bangunkeluarga_${kd}`,options:jawabBangunOptions}); });
        [
            {field:"sejahterakeluarga_61",label:"Rata-rata uang saku anak untuk sekolah perhari?"},
            {field:"sejahterakeluarga_62",label:"Keluarga memiliki kebiasaan merokok? Jika ya, berapa bungkus perhari?"},
            {field:"sejahterakeluarga_63",label:"Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa kali?"},
            {field:"sejahterakeluarga_64",label:"Kepala keluarga memiliki kebiasaan minum kopi di kedai? Berapa jam perhari?"},
            {field:"sejahterakeluarga_65",label:"Rata-rata pulsa yang digunakan keluarga seminggu?"},
            {field:"sejahterakeluarga_66",label:"Rata-rata pendapatan atau penghasilan keluarga sebulan?"},
            {field:"sejahterakeluarga_67",label:"Rata-rata pengeluaran keluarga sebulan?"},
            {field:"sejahterakeluarga_68",label:"Rata-rata uang belanja keluarga sebulan?"}
        ].forEach(q=>questions[9].push({type:"text",label:q.label,field:q.field,isUraian:true}));
        Object.entries(konflikSosialOptions).forEach(([kd,label])=>questions[10].push({type:"select",label:`${label} ?`,field:`konfliksosial_${kd}`,options:jawabKonflikOptions}));
        Object.entries(kualitasIbuHamilOptions).forEach(([kd,label])=>{
            questions[11].push({
                type:"select",
                label: label + " :",
                field: `kualitasibuhamil_${kd}`,
                options: jawabKualitasIbuHamilOptions
            });
        });
        Object.entries(kualitasBayiOptions).forEach(([kd,label])=>{
            questions[12].push({
                type:"select",
                label: label + " :",
                field: `kualitasbayi_${kd}`,
                options: jawabKualitasBayiOptions
            });
        });
        const wilayahQuestions = [
            {type:"select",label:"Provinsi asalnya apa?",field:"kdprovinsi",options:provinsiOptions},
            {type:"select",label:"Kabupaten atau kota asalnya apa?",field:"kdkabupaten",dynamic:true,dynamicUrl:"/get-kabupaten/"},
            {type:"select",label:"Kecamatan asalnya apa?",field:"kdkecamatan",dynamic:true,dynamicUrl:"/get-kecamatan/"},
            {type:"select",label:"Desa atau kelurahan asalnya apa?",field:"kddesa",dynamic:true,dynamicUrl:"/get-desa/"}
        ];

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
            document.getElementById('startBtn').classList.remove('listening');
            document.getElementById('visualizer').style.opacity=0;
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

        function renderQuestion(){
            const q=questions[currentModul][step];
            let html='';

            if(currentModul===3&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong class="mx-2">YA</strong> / <strong class="mx-2">TIDAK</strong> / <strong class="mx-2">KOSONG</strong></div>`;
            if(currentModul===4&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab: <strong>"tidak punya"</strong> atau <strong>angka hektar</strong></div>`;
            if(currentModul===5&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan angka jumlah ekor, atau "tidak ada"</div>`;
            if(currentModul===6&&step===0) html+=`<div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan angka jumlah atau "tidak ada"</div>`;
            if(currentModul===7&&step===0) html+=`<div class="bg-orange-50 border border-orange-300 text-orange-800 p-4 rounded-xl text-center font-medium mb-6">Jawab dengan huruf <strong>a</strong> sampai <strong>f</strong> saja</div>`;
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
                let cols = currentModul===3?2:(currentModul===7?3:(currentModul===8?3:(currentModul===10?2:4)));
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

            // HANYA SEKALI DI AWAL MODUL 7 — JELASKAN PILIHAN a-f
            if(currentModul === 7 && step === 0){
                await speak("Jawab dengan huruf a sampai f sesuai pilihan berikut.");
                await speak("a. Milik sendiri bagus");
                await speak("b. Milik sendiri jelek");
                await speak("c. Milik kelompok");
                await speak("d. Milik orang lain sewa bayar");
                await speak("e. Milik orang lain sewa tidak bayar");
                await speak("f. Tidak memiliki");
                await new Promise(r=>setTimeout(r, 2500));
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

            // Baca pilihan hanya untuk modul yang butuh (kecuali modul 7)
            if(q.type==="select" && currentModul !== 7 && ![3,8,10,11].includes(currentModul)){
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

            // === DETEKSI MUTASI "DATANG" → TAMBAH 4 PERTANYAAN WILAYAH ===
            if(currentModul === 1 && q.field === "kdmutasimasuk" && normalize(text).includes('datang')){
                questions[1].splice(2, 0, ...wilayahQuestions); // insert setelah mutasi
                answers.wilayah_datang_required = true;
                
                saveData();
                setTimeout(async () => {
                    step = 2; // langsung ke provinsi
                    renderQuestion();
                    if(isListening) await speakQuestionAndOptions();
                }, 1200);
                return;
            }

            if(currentModul === 7){
                const n = normalize(text);
                const map = {
                    'a': '2', 'b': '3', 'c': '4', 'd': '5', 'e': '6', 'f': '7',
                    'satu': '2', 'dua': '3', 'tiga': '4', 'empat': '5', 'lima': '6', 'enam': '7'
                };
                const firstChar = n.charAt(0);
                if(map[firstChar]){
                    value = map[firstChar];
                }else{
                    await speak("Ulangi dengan huruf a sampai f saja");
                    return;
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

            if(currentModul===1 && q.field==="kdmutasimasuk" && normalize(text).includes('datang')){
                questions[1].push(...wilayahQuestions);
                document.getElementById('wilayahDatangReview').classList.remove('hidden');
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
            document.getElementById('quizArea').innerHTML='';
            document.getElementById('voice-status').innerText='Review data. Selesaikan semua modul untuk simpan.';
            document.getElementById('reviewForm').classList.remove('hidden');
            const container=document.getElementById('reviewFields'); container.innerHTML='';
            if(answers.wilayah_datang_required){
                container.innerHTML += `
                    <div class="col-span-1 md:col-span-2 bg-teal-50 p-4 rounded-xl">
                        <h4 class="font-bold text-sm mb-3">Wilayah Asal (Datang dari Luar)</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div><label>Provinsi</label>
                                <select name="kdprovinsi" id="review_kdprovinsi" class="w-full border rounded-lg p-2">
                                    <option value="">-- Pilih --</option>
                                    ${Object.entries(provinsiOptions).map(([k,v])=>`<option value="${k}" ${answers.kdprovinsi==k?'selected':''}>${v}</option>`).join('')}
                                </select>
                            </div>
                            <div><label>Kabupaten/Kota</label>
                                <select name="kdkabupaten" id="review_kdkabupaten" class="w-full border rounded-lg p-2">
                                    <option value="">-- Pilih Provinsi Dulu --</option>
                                </select>
                            </div>
                            <div><label>Kecamatan</label>
                                <select name="kdkecamatan" id="review_kdkecamatan" class="w-full border rounded-lg p-2">
                                    <option value="">-- Pilih Kab/Kota Dulu --</option>
                                </select>
                            </div>
                            <div><label>Desa/Kelurahan</label>
                                <select name="kddesa" id="review_kddesa" class="w-full border rounded-lg p-2">
                                    <option value="">-- Pilih Kecamatan Dulu --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;

                // Auto-load kabupaten jika provinsi sudah dipilih
                if(answers.kdprovinsi){
                    setTimeout(() => {
                        window.getWilayah('kabupaten', answers.kdprovinsi, 'review_kdkabupaten');
                        if(answers.kdkabupaten){
                            setTimeout(() => window.getWilayah('kecamatan', answers.kdkabupaten, 'review_kdkecamatan'), 500);
                            if(answers.kdkecamatan){
                                setTimeout(() => window.getWilayah('desa', answers.kdkecamatan, 'review_kddesa'), 1000);
                            }
                        }
                    }, 300);
                }

                // Event listener untuk review
                document.getElementById('review_kdprovinsi')?.addEventListener('change', function(){
                    window.getWilayah('kabupaten', this.value, 'review_kdkabupaten');
                    document.getElementById('review_kdkecamatan').innerHTML = '<option>-- Pilih Kab/Kota Dulu --</option>';
                    document.getElementById('review_kddesa').innerHTML = '<option>-- Pilih Kecamatan Dulu --</option>';
                });
                document.getElementById('review_kdkabupaten')?.addEventListener('change', function(){
                    window.getWilayah('kecamatan', this.value, 'review_kdkecamatan');
                    document.getElementById('review_kddesa').innerHTML = '<option>-- Pilih Kecamatan Dulu --</option>';
                });
                document.getElementById('review_kdkecamatan')?.addEventListener('change', function(){
                    window.getWilayah('desa', this.value, 'review_kddesa');
                });
            }
            questions[currentModul].forEach(q=>{
                if(!answers[q.field])return;
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
                container.innerHTML+=`<div><label class="block text-xs font-medium mb-1">${q.label.split('?')[0]}</label>${input}</div>`;
            });
        }

        document.getElementById('startBtn').addEventListener('click', async ()=>{
            if(isListening||isReviewMode)return;
            isListening=true;
            document.getElementById('startBtn').classList.add('listening');
            document.getElementById('visualizer').style.opacity=1;
            try{
                audioContext=new (window.AudioContext||window.webkitAudioContext)();
                analyser=audioContext.createAnalyser();
                canvas=document.getElementById('visualizer'); ctx=canvas.getContext('2d');
                dataArray=new Uint8Array(analyser.frequencyBinCount);
                const stream=await navigator.mediaDevices.getUserMedia({audio:true});
                const source=audioContext.createMediaStreamSource(stream);
                source.connect(analyser);
                drawVisualizer();
            }catch(err){alert("Gagal akses mikrofon!");stopListening();return;}
            await speakQuestionAndOptions();
            const SR=window.SpeechRecognition||window.webkitSpeechRecognition;
            recognition=new SR(); recognition.lang='id-ID'; recognition.continuous=true; recognition.interimResults=true;
            recognition.onresult=e=>{
                const r=e.results[e.results.length-1];
                if(r.isFinal && r[0].confidence>0.6){
                    const t=r[0].transcript.trim();
                    document.getElementById('voice-status').innerText=`Dengar: "${t}"`;
                    processVoiceAnswer(t.toLowerCase());
                }
            };
            recognition.onerror=()=>{setTimeout(()=>recognition?.start(),100);};
            recognition.onend=()=>{if(isListening)setTimeout(()=>recognition?.start(),100);};
            recognition.start();
        });

        function drawVisualizer(){
            if(!isListening)return;
            requestAnimationFrame(drawVisualizer);
            analyser.getByteFrequencyData(dataArray);
            ctx.fillStyle='rgba(255,255,255,0.1)'; ctx.fillRect(0,0,canvas.width,canvas.height);
            const bw=(canvas.width/dataArray.length)*2.5; let x=0;
            for(let i=0;i<dataArray.length;i++){
                const h=dataArray[i]/2;
                ctx.fillStyle=`rgb(${h+100},50,50)`;
                ctx.fillRect(x,canvas.height-h,bw,h);
                x+=bw+1;
            }
        }

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
                const res=await fetch("{{ route('voice.store-all') }}",{
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

        ['kdprovinsi','kdkabupaten','kdkecamatan'].forEach(id=>{
            document.getElementById(id)?.addEventListener('change',async function(){
                const val=this.value;
                const next={'kdprovinsi':'kdkabupaten','kdkabupaten':'kdkecamatan','kdkecamatan':'kddesa'}[id];
                if(!next)return;
                const sel=document.getElementById(next);
                sel.innerHTML='<option>-- Pilih --</option>';
                if(val){
                    const url=`/get-${next.replace('kd','').toLowerCase()}/${val}`;
                    const res=await fetch(url); const data=await res.json();
                    Object.entries(data).forEach(([k,v])=>sel.innerHTML+=`<option value="${k}">${v}</option>`);
                }
            });
        });

        // GANTI fungsi window.getWilayah jadi ini:
        window.getWilayah = async function(tipe, parentId, targetId) {
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
                    select.innerHTML += `<option value="${id}">${nama}</option>`;
                });
            } catch (err) {
                console.error(err);
                select.innerHTML = '<option value="">Gagal memuat data</option>';
            } finally {
                select.disabled = false;
            }
        };

        initFresh();
    </script>
</x-app-layout>