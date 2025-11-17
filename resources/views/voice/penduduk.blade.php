<x-app-layout>
<div class="flex gap-6 max-w-7xl mx-auto py-8">
    {{-- SIDEBAR --}}
    <div id="sidebar" class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4">
        <div id="modul1" class="flex flex-col items-center bg-blue-100 text-blue-800 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-user class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center font-semibold">Penduduk</span>
        </div>
        <div id="modul2" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-user-plus class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Kelahiran</span>
        </div>
        <div id="modul3" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-document-text class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Sosial Ekonomi</span>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 bg-white rounded-2xl shadow-md p-8 min-h-screen">
        <h2 class="text-2xl font-bold mb-6 text-center">Input Data Penduduk via Suara</h2>

        {{-- PROGRESS BAR --}}
        <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
            <div id="progressBar" class="bg-teal-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
        <div class="text-center text-sm text-gray-600 mb-6">
            Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">24</span>
        </div>

        <div id="voice-status" class="text-center text-gray-700 mb-6 text-lg font-medium"></div>

        {{-- QUIZ AREA --}}
        <div id="quizArea" class="space-y-8"></div>

        {{-- MICROPHONE BUTTON --}}
        <div class="flex flex-col items-center mt-12">
            <div class="relative">
                <button id="startBtn" class="relative w-32 h-32 bg-gradient-to-br from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800 text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                    <svg id="micIcon" xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v11a3 3 0 106 0V1m-6 22v-4m0 0H8m4 4h4" />
                    </svg>
                </button>
                <canvas id="visualizer" class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity rounded-full" width="128" height="128"></canvas>
            </div>
            <p class="mt-4 text-sm text-gray-500">Tekan untuk mulai merekam / klik jawaban — setelah suara diisi, edit jika perlu lalu tekan Next</p>
        </div>

        {{-- REVIEW FORM --}}
        <div id="reviewForm" class="hidden mt-12">
            <h3 class="text-2xl font-bold mb-6 text-center">Review & Edit Data</h3>
            <form id="voiceForm" class="max-w-5xl mx-auto space-y-6" method="POST" action="/voice/penduduk/store">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left column --}}
                    <div>
                        <label class="block font-medium">NIK</label>
                        <input type="text" name="nik" id="nik" maxlength="16" class="w-full border rounded-lg p-3" required>
                        <p id="nikError" class="text-red-600 text-sm mt-1 hidden">NIK sudah terdaftar!</p>
                    </div>

                    <div>
                        <label class="block font-medium">No KK</label>
                        <input type="text" name="no_kk" id="no_kk" maxlength="16" class="w-full border rounded-lg p-3" required>
                        <p id="noKkError" class="text-red-600 text-sm mt-1 hidden">No KK tidak ditemukan!</p>
                    </div>

                    <div>
                        <label class="block font-medium">Jenis Mutasi</label>
                        <select name="kdmutasimasuk" id="kdmutasimasuk" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Mutasi --</option>
                            @foreach($mutasi_masuks as $m)
                                <option value="{{ $m->kdmutasimasuk }}">{{ $m->mutasimasuk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Tanggal Mutasi</label>
                        <input type="date" name="penduduk_tanggalmutasi" id="penduduk_tanggalmutasi" class="w-full border rounded-lg p-3" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                    </div>

                    <div>
                        <label class="block font-medium">No. Urut dalam KK</label>
                        <input type="text" name="penduduk_nourutkk" id="penduduk_nourutkk" maxlength="2"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').padStart(2,'0')"
                                class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Golongan Darah</label>
                        <input type="text" name="penduduk_goldarah" id="penduduk_goldarah" class="w-full border rounded-lg p-3" placeholder="A / B / AB / O">
                    </div>

                    <div>
                        <label class="block font-medium">No Akta Lahir</label>
                        <input type="text" name="penduduk_noaktalahir" id="penduduk_noaktalahir" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Nama Lengkap</label>
                        <input type="text" name="penduduk_namalengkap" id="penduduk_namalengkap" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Tempat Lahir</label>
                        <input type="text" name="penduduk_tempatlahir" id="penduduk_tempatlahir" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Tanggal Lahir</label>
                        <input type="date" name="penduduk_tanggallahir" id="penduduk_tanggallahir" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Kewarganegaraan</label>
                        <input type="text" name="penduduk_kewarganegaraan" id="penduduk_kewarganegaraan" class="w-full border rounded-lg p-3" value="INDONESIA">
                    </div>

                    <div>
                        <label class="block font-medium">Jenis Kelamin</label>
                        <select name="kdjeniskelamin" id="kdjeniskelamin" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            @foreach($jenis_kelamins as $jk)
                                <option value="{{ $jk->kdjeniskelamin }}">{{ $jk->jeniskelamin }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Agama</label>
                        <select name="kdagama" id="kdagama" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Agama --</option>
                            @foreach($agamas as $a)
                                <option value="{{ $a->kdagama }}">{{ $a->agama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Hubungan Dalam Keluarga</label>
                        <select name="kdhubungankeluarga" id="kdhubungankeluarga" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Hubungan --</option>
                            @foreach($hubungan_keluargas as $h)
                                <option value="{{ $h->kdhubungankeluarga }}">{{ $h->hubungankeluarga }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Hubungan Dalam KK (kepala keluarga)</label>
                        <select name="kdhubungankepalakeluarga" id="kdhubungankepalakeluarga" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($hubungan_kepala_keluargas as $h)
                                <option value="{{ $h->kdhubungankepalakeluarga }}">{{ $h->hubungankepalakeluarga }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Status Kawin</label>
                        <select name="kdstatuskawin" id="kdstatuskawin" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Status --</option>
                            @foreach($status_kawins as $s)
                                <option value="{{ $s->kdstatuskawin }}">{{ $s->statuskawin }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Kepemilikan Akta Nikah</label>
                        <select name="kdaktanikah" id="kdaktanikah" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($akta_nikahs as $ak)
                                <option value="{{ $ak->kdaktanikah }}">{{ $ak->aktanikah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Tercantum Dalam KK</label>
                        <select name="kdtercantumdalamkk" id="kdtercantumdalamkk" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($tercantum_dalam_kks as $t)
                                <option value="{{ $t->kdtercantumdalamkk }}">{{ $t->tercantumdalamkk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Status Tinggal</label>
                        <select name="kdstatustinggal" id="kdstatustinggal" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($status_tinggals as $st)
                                <option value="{{ $st->kdstatustinggal }}">{{ $st->statustinggal }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Kartu Identitas</label>
                        <select name="kdkartuidentitas" id="kdkartuidentitas" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($kartu_identitass as $ki)
                                <option value="{{ $ki->kdkartuidentitas }}">{{ $ki->kartuidentitas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Pekerjaan</label>
                        <select name="kdpekerjaan" id="kdpekerjaan" class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih --</option>
                            @foreach($pekerjaans as $p)
                                <option value="{{ $p->kdpekerjaan }}">{{ $p->pekerjaan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Nama Ayah</label>
                        <input type="text" name="penduduk_namaayah" id="penduduk_namaayah" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Nama Ibu</label>
                        <input type="text" name="penduduk_namaibu" id="penduduk_namaibu" class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block font-medium">Nama Tempat Bekerja</label>
                        <input type="text" name="penduduk_namatempatbekerja" id="penduduk_namatempatbekerja" class="w-full border rounded-lg p-3">
                    </div>

                    {{-- Wilayah datang (akan terlihat jika mutasi datang) --}}
                    <div id="wilayahDatang" class="hidden md:col-span-2 bg-teal-50 p-6 rounded-xl">
                        <h4 class="font-bold text-lg mb-4">Wilayah Datang</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label>Provinsi</label>
                                <select name="kdprovinsi" id="kdprovinsi" class="w-full border rounded-lg p-3">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsis as $prov) <option value="{{ $prov->kdprovinsi }}">{{ $prov->provinsi }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Kabupaten</label>
                                <select name="kdkabupaten" id="kdkabupaten" class="w-full border rounded-lg p-3"><option value="">-- Pilih Provinsi Dahulu --</option></select>
                            </div>
                            <div>
                                <label>Kecamatan</label>
                                <select name="kdkecamatan" id="kdkecamatan" class="w-full border rounded-lg p-3"><option value="">-- Pilih Kabupaten Dahulu --</option></select>
                            </div>
                            <div>
                                <label>Desa</label>
                                <select name="kddesa" id="kddesa" class="w-full border rounded-lg p-3"><option value="">-- Pilih Kecamatan Dahulu --</option></select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-center gap-6 mt-8">
                    <button type="submit" id="simpanBtn" class="px-8 py-4 bg-green-600 text-white text-lg rounded-xl hover:bg-green-700 shadow-lg">Simpan Data</button>
                    <button type="button" id="resetBtn" class="px-8 py-4 bg-gray-300 text-gray-800 text-lg rounded-xl hover:bg-gray-400 shadow-lg">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .option-card{transition:all .3s;border:2px solid #e5e7eb;}
    .option-card:hover{background-color:#f0fdfa;border-color:#14b8a6;}
    .option-card.selected{background-color:#ccfbf1!important;border-color:#14b8a6!important;box-shadow:0 0 0 4px rgba(20,184,166,.2);}
    #startBtn.listening{background:linear-gradient(to bottom right,#ef4444,#dc2626)!important;transform:scale(1.15);}
</style>

<script>
/*
  Voice Penduduk script — final corrected version
  - Manual Next/Back navigation (so user can edit)
  - SpeechSynthesis uses Indonesian female voice if available (Google)
  - SpeechRecognition runs continuously while the UI is active and fills inputs
  - Master options are read aloud and can be selected by click OR voice
  - skip fields auto-fill but still editable
*/

let recognition = null;
let isListening = false;
let isSpeaking = false;
let step = 0;
let answers = {};
let audioContext, analyser, dataArray, canvas, ctx;
let synthVoices = [];
let preferredVoice = null;

// DOM
const quizArea = document.getElementById('quizArea');
const progressBar = document.getElementById('progressBar');
const currentQ = document.getElementById('currentQ');
const totalQ = document.getElementById('totalQ');
const voiceStatus = document.getElementById('voice-status');
const startBtn = document.getElementById('startBtn');

// Load master options from server-passed data (Blade -> JS)
const mutasiOptions = @json($mutasi_masuks->pluck('mutasimasuk','kdmutasimasuk'));
const jenisKelaminOptions = @json($jenis_kelamins->pluck('jeniskelamin','kdjeniskelamin'));
const agamaOptions = @json($agamas->pluck('agama','kdagama'));
const hubunganOptions = @json($hubungan_keluargas->pluck('hubungankeluarga','kdhubungankeluarga'));
const hubunganKepalaOptions = @json($hubungan_kepala_keluargas->pluck('hubungankepalakeluarga','kdhubungankepalakeluarga'));
const statusKawinOptions = @json($status_kawins->pluck('statuskawin','kdstatuskawin'));
const aktaNikahOptions = @json($akta_nikahs->pluck('aktanikah','kdaktanikah'));
const tercantumOptions = @json($tercantum_dalam_kks->pluck('tercantumdalamkk','kdtercantumdalamkk'));
const statusTinggalOptions = @json($status_tinggals->pluck('statustinggal','kdstatustinggal'));
const kartuIdOptions = @json($kartu_identitass->pluck('kartuidentitas','kdkartuidentitas'));
const pekerjaanOptions = @json($pekerjaans->pluck('pekerjaan','kdpekerjaan'));

// QUESTIONS (sesuai urutan yang Anda inginkan)
let questions = [
    // A. INFORMASI PRIBADI
    { type: "text", label: "Sebutkan nomor NIK", field: "nik" },
    { type: "text", label: "Sebutkan nama lengkap penduduk", field: "penduduk_namalengkap" },
    { type: "text", label: "Dimana tempat lahirnya?", field: "penduduk_tempatlahir" },
    { type: "date", label: "Tanggal lahir?", field: "penduduk_tanggallahir" },
    { type: "master", label: "Apa golongan darahnya?", field: "penduduk_goldarah", options: { "A":"A", "B":"B", "AB":"AB", "O":"O" } },
    { type: "text", label: "Sebutkan nomor akta lahir", field: "penduduk_noaktalahir" },
    { type: "skip", label: "Kewarganegaraan (INDONESIA)", field: "penduduk_kewarganegaraan", default: "INDONESIA" },
    { type: "master", label: "Apa jenis kelaminnya?", field: "kdjeniskelamin", options: jenisKelaminOptions },
    { type: "master", label: "Apa agamanya?", field: "kdagama", options: agamaOptions },

    // B. INFORMASI KELUARGA
    { type: "text", label: "Sebutkan nomor kartu keluarga", field: "no_kk" },
    { type: "text", label: "Sebutkan nomor urut dalam KK", field: "penduduk_nourutkk" },
    { type: "master", label: "Apa hubungan dalam keluarga?", field: "kdhubungankeluarga", options: hubunganOptions },
    { type: "master", label: "Apa hubungan dengan kepala keluarga?", field: "kdhubungankepalakeluarga", options: hubunganKepalaOptions },

    // C. STATUS KEPENDUDUKAN
    { type: "master", label: "Apa status perkawinannya?", field: "kdstatuskawin", options: statusKawinOptions },
    { type: "master", label: "Memiliki akta nikah?", field: "kdaktanikah", options: aktaNikahOptions },
    { type: "master", label: "Apakah tercantum dalam KK?", field: "kdtercantumdalamkk", options: tercantumOptions },
    { type: "master", label: "Apa status tinggalnya?", field: "kdstatustinggal", options: statusTinggalOptions },
    { type: "master", label: "Jenis kartu identitas apa?", field: "kdkartuidentitas", options: kartuIdOptions },

    // D. MUTASI
    { type: "master", label: "Jenis mutasi apa?", field: "kdmutasimasuk", options: mutasiOptions },
    { type: "date", label: "Tanggal mutasi", field: "penduduk_tanggalmutasi" },
    // (If Mutasi Datang -> insert dynamic wilayah after this in runtime)

    // E. DATA TAMBAHAN
    { type: "text", label: "Sebutkan nama ayah", field: "penduduk_namaayah" },
    { type: "text", label: "Sebutkan nama ibu", field: "penduduk_namaibu" },
    { type: "text", label: "Sebutkan nama tempat bekerja (jika ada)", field: "penduduk_namatempatbekerja" },
    { type: "master", label: "Apa pekerjaannya?", field: "kdpekerjaan", options: pekerjaanOptions },
];

totalQuestions = questions.length;
document.getElementById('totalQ').innerText = totalQuestions;

/// --- Utility: choose Indonesian female voice (Google) if available ---
function refreshVoices() {
    synthVoices = speechSynthesis.getVoices();

    preferredVoice =
        // Prioritas 1 — Google Female Indonesia
        synthVoices.find(v =>
            v.lang === "id-ID" &&
            v.name.toLowerCase().includes("google") &&
            v.name.toLowerCase().includes("female")
        )

        // Prioritas 2 — Google Indonesia (laki/laki atau tidak diketahui)
        || synthVoices.find(v =>
            v.lang === "id-ID" &&
            v.name.toLowerCase().includes("google")
        )

        // Prioritas 3 — Suara lain bahasa Indonesia
        || synthVoices.find(v =>
            v.lang === "id-ID"
        )

        // Prioritas 4 — fallback ke suara pertama yang tersedia
        || synthVoices[0];
    }

window.speechSynthesis.onvoiceschanged = refreshVoices;
refreshVoices();

function speak(text, opts = {}) {
    return new Promise(resolve => {
        if (!text) return resolve();
        const u = new SpeechSynthesisUtterance(text);
        u.lang = 'id-ID';
        u.rate = opts.rate || 0.95;
        if (preferredVoice) u.voice = preferredVoice;
        isSpeaking = true;
        u.onend = () => { isSpeaking = false; resolve(); };
        speechSynthesis.cancel(); // ensure fresh
        speechSynthesis.speak(u);
    });
}

// --- Visualizer ---
function startVisualizer(stream) {
    try {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        analyser = audioContext.createAnalyser();
        const source = audioContext.createMediaStreamSource(stream);
        source.connect(analyser);
        canvas = document.getElementById('visualizer');
        ctx = canvas.getContext('2d');
        analyser.fftSize = 128;
        dataArray = new Uint8Array(analyser.frequencyBinCount);
        canvas.style.opacity = 1;
        (function draw() {
            if (!isListening) { ctx.clearRect(0,0,canvas.width,canvas.height); canvas.style.opacity = 0; return; }
            requestAnimationFrame(draw);
            analyser.getByteFrequencyData(dataArray);
            ctx.clearRect(0,0,canvas.width,canvas.height);
            const barWidth = (canvas.width / dataArray.length) * 1.6;
            let x = 0;
            for (let i = 0; i < dataArray.length; i++) {
                const h = dataArray[i] / 2;
                ctx.fillStyle = `rgba(${Math.min(255,h+50)},50,80,0.9)`;
                ctx.fillRect(x, canvas.height - h, barWidth, h);
                x += barWidth + 1;
            }
        })();
    } catch(err) {
        console.warn('Visualizer not available', err);
    }
}

// --- Recognition setup (single instance) ---
function startRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert("Browser tidak mendukung SpeechRecognition (pakai Chrome/Edge terbaru).");
        return;
    }
    if (recognition) return; // already started

    recognition = new SpeechRecognition();
    recognition.lang = 'id-ID';
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onstart = () => {
        isListening = true;
        startBtn.classList.add('listening');
        voiceStatus.innerText = "Mendengarkan...";
        try { document.getElementById('visualizer').style.opacity = 1; } catch(e){}
    };

    recognition.onresult = (e) => {
        const result = e.results[e.results.length - 1];
        const text = result[0].transcript.trim();
        voiceStatus.innerText = `Dengar: "${text}"`;
        // Put transcript into current input (if exists) and try to match masters
        const q = questions[step];
        if (!q) return;
        // For master types, attempt to match and highlight (but don't auto-next)
        if (q.type === 'master') {
            const match = findBestMatch(text, q.options || {});
            if (match) {
                // set selection but still allow editing / confirm by Next
                answers[q.field] = match[0];
                answers[q.field + '_display'] = match[1];
                // highlight UI card if present
                const card = document.querySelector(`.option-card[data-value="${match[0]}"]`);
                if (card) {
                    document.querySelectorAll('.option-card').forEach(c=>c.classList.remove('selected'));
                    card.classList.add('selected');
                }
            } else {
                // no match: do nothing (user can click)
            }
        } else if (q.type === 'selectDynamic') {
            // ignore here; handled when user clicks dynamic items (we will fill by fetch)
        } else if (q.type === 'date') {
            // For date, we don't auto-parse complex natural language here; user can say "12 10 1990" etc.
            const found = text.match(/(\d{1,2})\D+(\d{1,2})\D+(\d{2,4})/);
            if (found) {
                let d = parseInt(found[1]).toString().padStart(2,'0');
                let m = parseInt(found[2]).toString().padStart(2,'0');
                let y = found[3].length === 2 ? ('20'+found[3]) : found[3];
                answers[q.field] = `${y}-${m}-${d}`;
                const inp = document.getElementById('inputAnswer');
                if (inp) inp.value = answers[q.field];
            }
        } 
        else {
    // semua field text termasuk nama ayah & ibu terisi otomatis dari suara
    answers[q.field] = text;
    const inp = document.getElementById('inputAnswer');
    if (inp) {
        inp.value = text;
    }
}
    };

    recognition.onerror = (e) => {
        console.log('recognition error', e);
    };

    recognition.onend = () => {
        isListening = false;
        startBtn.classList.remove('listening');
        voiceStatus.innerText = "Speech recognition berhenti.";
        try { document.getElementById('visualizer').style.opacity = 0; } catch(e){}
        // Do not destroy recognition object - allow restart
    };

    recognition.start();
}

// Stop recognition gracefully
function stopRecognition() {
    try {
        if (recognition) recognition.stop();
    } catch(e){}
    isListening = false;
    startBtn.classList.remove('listening');
}

// --- Matching helper for master options ---
function findBestMatch(text, options) {
    if (!text || !options) return null;
    const cleanText = text.toLowerCase().replace(/[^a-z0-9\s]/g,' ').trim();
    let best = null, score = 0;
    Object.entries(options).forEach(([id,name])=>{
        const cleanName = String(name).toLowerCase().replace(/[^a-z0-9\s]/g,' ').trim();
        if (!cleanName) return;
        // exact include check
        if (cleanText.includes(cleanName) || cleanName.includes(cleanText)) {
            const s = cleanName.split(' ').filter(w => cleanText.includes(w)).length;
            if (s > score) { score = s; best = [id, name]; }
        }
        // partial word overlap
        const words = cleanName.split(' ');
        const overlap = words.filter(w => cleanText.includes(w)).length;
        if (overlap > score) { score = overlap; best = [id, name]; }
    });
    return best;
}

// --- Render question (manual Next/Back navigation) ---
function updateProgress() {
    const percent = (step / totalQuestions) * 100;
    progressBar.style.width = percent + '%';
    currentQ.textContent = Math.min(step+1, totalQuestions);
    totalQ.textContent = totalQuestions;
}

async function renderQuestion() {
    if (step < 0) step = 0;
    if (step >= questions.length) {
        finishQuiz();
        return;
    }
    updateProgress();
    const q = questions[step];

    // Build HTML
    let html = `
        <div class="text-center mb-6">
            <h3 class="text-2xl font-medium text-gray-800">${q.label}</h3>
        </div>
    `;

    // If skip -> show default editable input
    if (q.type === 'skip') {
        // ensure default in answers
        if (answers[q.field] === undefined) answers[q.field] = q.default || '';
        html += `<div class="max-w-2xl mx-auto"><input type="text" id="inputAnswer" class="w-full border-2 border-gray-300 rounded-2xl p-4 text-xl text-center" value="${answers[q.field]}"></div>`;
    }
    else if (q.type === 'master') {
        const opts = q.options || {};
        if (Object.keys(opts).length === 0) {
            html += `<div class="text-center text-red-600">Tidak ada pilihan.</div>`;
        } else {
            html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">`;
            Object.entries(opts).forEach(([id, name]) => {
                const selected = (answers[q.field] && String(answers[q.field]) === String(id)) ? 'selected' : '';
                html += `<div class="option-card rounded-2xl p-6 cursor-pointer text-center shadow-md ${selected}" data-value="${id}" data-text="${name}"><span class="text-xl font-medium">${name}</span></div>`;
            });
            html += `</div>`;
            // Guidance (read options)
            const optList = Object.values(opts).slice(0,6).join(', ');
            html += `<div class="mt-4 text-center text-sm text-gray-600">Contoh pilihan: ${optList}${Object.keys(opts).length > 6 ? ', dan lainnya.' : ''}</div>`;
        }
    }
    else if (q.type === 'date') {
        html += `<div class="max-w-2xl mx-auto"><input type="date" id="inputAnswer" class="w-full border-2 border-gray-300 rounded-2xl p-4 text-xl text-center" value="${answers[q.field] || ''}"></div>`;
    }
    else if (q.type === 'selectDynamic') {
        // dynamic selects will be displayed similarly to master but loaded via fetch
        html += `<div class="max-w-3xl mx-auto"><div id="dynamicList" class="grid grid-cols-1 gap-3"></div></div>`;
    }
    else { // text
        html += `<div class="max-w-3xl mx-auto"><input type="text" id="inputAnswer" class="w-full border-2 border-gray-300 rounded-2xl p-6 text-2xl text-center" placeholder="Jawaban akan otomatis terisi..." value="${answers[q.field] || ''}"></div>`;
    }

    // Navigation
    html += `
        <div class="flex justify-between max-w-3xl mx-auto mt-8">
            <button id="backBtn" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-xl shadow-lg ${step === 0 ? 'opacity-50 cursor-not-allowed' : ''}">Back</button>
            <div class="flex gap-3">
                <button id="listenFieldBtn" class="px-6 py-3 bg-yellow-500 text-white rounded-xl shadow-lg">Rekam/Ulang Jawaban</button>
                <button id="nextBtn" class="px-6 py-3 bg-blue-600 text-white rounded-xl shadow-lg">Next</button>
            </div>
        </div>
    `;

    quizArea.innerHTML = html;

    // Speak question + options (if master)
    let speakText = q.label;
    if (q.type === 'master' && q.options) {
        const choices = Object.values(q.options).slice(0,6).join(", ");
        speakText += `. Pilihan: ${choices}. Silakan pilih dengan mengucapkan atau klik pilihan.`;
    }
    await speak(speakText);

    attachListeners();
}

function attachListeners() {
    const q = questions[step];
    // Back
    const backBtn = document.getElementById('backBtn');
    backBtn.onclick = () => {
        if (step === 0) return;
        step--;
        renderQuestion();
    };

    // Next
    document.getElementById('nextBtn').onclick = async () => {
        // validate presence unless skip
        if (q.type !== 'skip') {
            const val = answers[q.field];
            if (val === undefined || val === null || String(val).trim() === '') {
                alert('Jawaban belum diisi. Silakan rekam atau isi jawabannya kemudian tekan Next.');
                return;
            }
        } else {
            // ensure skip value is set
            if (answers[q.field] === undefined) answers[q.field] = q.default || '';
        }

        // If current is kdmutasimasuk and value includes 'datang' -> insert wilayah dynamic after current index (if not exist)
        if (q.field === 'kdmutasimasuk' && String(answers[q.field + '_display'] || answers[q.field]).toLowerCase().includes('datang')) {
            const hasProv = questions.some(x => x.field === 'kdprovinsi');
            if (!hasProv) {
                const wilayah = [
                    { type: "master", label: "Provinsi asalnya apa?", field: "kdprovinsi", options: @json($provinsis->pluck('provinsi','kdprovinsi')) },
                    { type: "selectDynamic", label: "Kabupaten atau kota asalnya apa?", field: "kdkabupaten", dynamicUrl: "/get-kabupaten/" },
                    { type: "selectDynamic", label: "Kecamatan asalnya apa?", field: "kdkecamatan", dynamicUrl: "/get-kecamatan/" },
                    { type: "selectDynamic", label: "Desa atau kelurahan asalnya apa?", field: "kddesa", dynamicUrl: "/get-desa/" }
                ];
                questions.splice(step+1, 0, ...wilayah);
                totalQuestions = questions.length;
                document.getElementById('totalQ').innerText = totalQuestions;
            }
        }

        step++;
        renderQuestion();
    };

    // Rekam/Ulang Jawaban button (focus voice to this field)
    const listenFieldBtn = document.getElementById('listenFieldBtn');
    listenFieldBtn.onclick = () => {
        // Start recognition if not started
        if (!recognition) {
            startRecognition();
        } else {
            // toggle listening state
            if (!isListening) recognition.start();
        }
        // speak prompt for the current field
        const q = questions[step];
        let prompt = `Silakan ucapkan jawaban untuk: ${q.label}`;
        if (q.type === 'master' && q.options) {
            const optionStrings = Object.values(q.options).slice(0,6).join(', ');
            prompt += `. Pilihan misalnya: ${optionStrings}.`;
        }
        speak(prompt);
    };

    // Attach for master cards
    document.querySelectorAll('.option-card').forEach(card => {
        card.onclick = () => {
            document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            const val = card.dataset.value;
            const txt = card.dataset.text;
            answers[q.field] = val;
            answers[q.field + '_display'] = txt;
            // reflect in hidden review form if visible
            const reviewEl = document.querySelector(`[name="${q.field}"], #${q.field}`);
            if (reviewEl) reviewEl.value = val;
            // speak feedback
            speak(`Dipilih: ${txt}`);
        };
    });

    // Attach for dynamic select type (render list by fetching)
    if (q.type === 'selectDynamic') {
        const dyn = document.getElementById('dynamicList');
        dyn.innerHTML = '<div class="text-sm text-gray-500">Memuat daftar... jika lambat, tunggu sebentar.</div>';
        const prevField = q.field === 'kdkabupaten' ? 'kdprovinsi' : q.field === 'kdkecamatan' ? 'kdkabupaten' : 'kdkecamatan';
        const prevVal = answers[prevField];
        if (!prevVal) {
            dyn.innerHTML = `<div class="text-red-600">Silakan lengkapi wilayah sebelumnya terlebih dahulu.</div>`;
        } else {
            // fetch from provided dynamicUrl
            fetch(q.dynamicUrl + prevVal).then(r => r.json()).then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    dyn.innerHTML = `<div class="text-red-600">Tidak ada data wilayah.</div>`;
                    return;
                }
                dyn.innerHTML = '';
                data.forEach(item => {
                    let valueKey = "";
                    let labelKey = "";

                    if (q.field === "kdkabupaten") {
                        valueKey = "kdkabupaten";
                        labelKey = "kabupaten";
                    }
                    if (q.field === "kdkecamatan") {
                        valueKey = "kdkecamatan";
                        labelKey = "kecamatan";
                    }
                    if (q.field === "kddesa") {
                        valueKey = "kddesa";
                        labelKey = "desa";
                    }

                    const value = item[valueKey];
                    const label = item[labelKey];

                    const node = document.createElement('div');
                    node.className = 'option-card rounded-2xl p-4 cursor-pointer text-center shadow-md';
                    node.dataset.value = value;
                    node.dataset.text = label;
                    node.innerHTML = `<span class="text-base">${label}</span>`;

                    node.onclick = () => {
                        answers[q.field] = value;
                        answers[q.field + '_display'] = label;
                        document.querySelectorAll('#dynamicList .option-card').forEach(c=>c.classList.remove('selected'));
                        node.classList.add('selected');
                        speak(`Dipilih: ${label}`);
                    };

                    dyn.appendChild(node);
                });
            }).catch(err => {
                dyn.innerHTML = `<div class="text-red-600">Gagal memuat data wilayah.</div>`;
            });
        }
    }

    // Attach for inputAnswer (text/date/skip)
    const input = document.getElementById('inputAnswer');
    if (input) {
        // allow user to edit
        input.oninput = () => {
            answers[q.field] = input.value;
        };
        // If date, keep format
        if (q.type === 'date') {
            input.onchange = () => {
                answers[q.field] = input.value;
            };
        }
    }
}

// --- finish: put answers to review form and show review area ---
function finishQuiz() {
    stopRecognition();
    voiceStatus.innerText = "Selesai. Menyiapkan data untuk direview...";
    quizArea.innerHTML = "<div class='text-center text-3xl font-bold text-green-600'>SELESAI!</div>";

    // populate review form after short delay
    setTimeout(() => {
        // Map answers into review form inputs
        for (const k in answers) {
            // try both name and id selectors
            const el = document.querySelector(`[name="${k}"], #${k}`);
            if (el) {
                // if element is select, set value and dispatch change
                if (el.tagName === 'SELECT' || el.type === 'select-one') {
                    el.value = answers[k];
                    el.dispatchEvent(new Event('change'));
                } else {
                    el.value = answers[k];
                }
            } else {
                // maybe it's a *_display field; ignore.
            }
        }

        // If kdprovinsi set show wilayah section and trigger cascading load
        if (answers.kdprovinsi) {
            document.getElementById('wilayahDatang').classList.remove('hidden');
            const provEl = document.getElementById('kdprovinsi');
            if (provEl) {
                provEl.value = answers.kdprovinsi;
                provEl.dispatchEvent(new Event('change'));
                setTimeout(()=> {
                    if (answers.kdkabupaten) {
                        const kabEl = document.getElementById('kdkabupaten');
                        if (kabEl) { kabEl.value = answers.kdkabupaten; kabEl.dispatchEvent(new Event('change')); }
                        setTimeout(()=> {
                            if (answers.kdkecamatan) {
                                const kecEl = document.getElementById('kdkecamatan');
                                if (kecEl) { kecEl.value = answers.kdkecamatan; kecEl.dispatchEvent(new Event('change')); }
                                setTimeout(()=> {
                                    if (answers.kddesa) {
                                        const desaEl = document.getElementById('kddesa');
                                        if (desaEl) desaEl.value = answers.kddesa;
                                    }
                                },500);
                            }
                        },500);
                    }
                },500);
            }
        }

        document.getElementById('reviewForm').classList.remove('hidden');
        voiceStatus.innerText = "Silakan review & simpan.";
        // scroll to review form
        document.getElementById('reviewForm').scrollIntoView({ behavior: 'smooth' });
    }, 600);
}

// --- Start button handler (init mic + recognition + render first question) ---
startBtn.addEventListener('click', async () => {
    if (isListening) {
        // toggle off
        stopRecognition();
        return;
    }

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        startVisualizer(stream);
    } catch (err) {
        alert("Gagal akses mikrofon! Periksa izin mikrofon pada browser.");
        return;
    }

    // Start recognition (if not already)
    startRecognition();

    // reset state and show first question
    step = 0;
    answers = {};
    totalQuestions = questions.length;
    document.getElementById('totalQ').innerText = totalQuestions;
    renderQuestion();
});

// --- Submission (review form) ---
const voiceForm = document.getElementById('voiceForm');
voiceForm.addEventListener('submit', async function(e){
    e.preventDefault();
    const simpanBtn = document.getElementById('simpanBtn');
    simpanBtn.disabled = true;
    simpanBtn.innerText = "Mengecek & Menyimpan...";

    // Basic check NIK & KK via endpoints
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const nikVal = document.getElementById('nik').value.trim();
    const noKkVal = document.getElementById('no_kk').value.trim();

    // cek nik
    try {
        if (nikVal) {
            const r = await fetch("/voice/penduduk/cek-nik", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ nik: nikVal })
            });
            const d = await r.json();
            if (d.exists) {
                document.getElementById('nikError').classList.remove('hidden');
                simpanBtn.disabled = false;
                simpanBtn.innerText = "Simpan Data";
                return;
            }
        }
    } catch (err) {
        console.error(err);
    }

    // cek kk exists
    try {
        if (noKkVal) {
            const r2 = await fetch("/voice/penduduk/cek-kk", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ no_kk: noKkVal })
            });
            const d2 = await r2.json();
            if (!d2.exists) {
                document.getElementById('noKkError').classList.remove('hidden');
                simpanBtn.disabled = false;
                simpanBtn.innerText = "Simpan Data";
                return;
            }
        }
    } catch (err) {
        console.error(err);
    }

    // build formData from inputs
    const form = new FormData(this);

    try {
        const save = await fetch("/voice/penduduk/store", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken },
            body: form
        });
        if (!save.ok) throw new Error("HTTP " + save.status);
        let res;
        try { 
            res = await save.json(); 
        } catch(e) { 
            alert("Data tersimpan, mengarahkan ulang..."); 
            window.location.href = "/admin/penduduk";
            return;
        }

        if (res.success) {
            alert("Data penduduk berhasil disimpan!");
            window.location.href = "/admin/penduduk";
        } else {
            alert("Gagal menyimpan: " + (res.error || "Tidak diketahui"));
        }
    } catch (err) {
        alert("Koneksi error: " + err.message);
        console.error(err);
    } finally {
        simpanBtn.disabled = false;
        simpanBtn.innerText = "Simpan Data";
    }
});

// reset button
document.getElementById('resetBtn').addEventListener('click', ()=>{ location.reload(); });

// dynamic dropdown for wilayah (review form)
['kdprovinsi','kdkabupaten','kdkecamatan'].forEach(id=>{
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('change', async function(){
        const val = this.value;
        const nextId = id === 'kdprovinsi' ? 'kdkabupaten' : id === 'kdkabupaten' ? 'kdkecamatan' : 'kddesa';
        const url = id === 'kdprovinsi' ? '/get-kabupaten/' : id === 'kdkabupaten' ? '/get-kecamatan/' : '/get-desa/';
        const sel = document.getElementById(nextId);
        if (!sel) return;
        sel.innerHTML = '<option>-- Memuat... --</option>';
        if (!val) { sel.innerHTML = '<option value="">-- Pilih --</option>'; return; }
        try {
            const res = await fetch(url + val);
            const data = await res.json();
            sel.innerHTML = '<option value="">-- Pilih --</option>';
            data.forEach(item=>{
                const key = Object.keys(item).find(k=> k.includes(nextId)) || Object.keys(item)[0];
                const textKey = Object.keys(item).find(k=> k!==key) || key;
                const txt = item[textKey] || item[key];
                sel.innerHTML += `<option value="${item[key]}">${txt}</option>`;
            });
        } catch (e) {
            sel.innerHTML = '<option value="">-- Gagal memuat --</option>';
        }
    });
});
</script>
</x-app-layout>
