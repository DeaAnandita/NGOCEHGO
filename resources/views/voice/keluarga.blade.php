<x-app-layout>
<div class="flex gap-6 max-w-7xl mx-auto py-8">
    {{-- SIDEBAR --}}
    <div id="sidebar" class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4">
        <div id="modul1" class="flex flex-col items-center bg-green-100 text-green-800 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-user-group class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center font-semibold">Keluarga</span>
        </div>
        <div id="modul2" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-home-modern class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Prasarana</span>
        </div>
        <div id="modul3" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-tv class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Aset Keluarga</span>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 bg-white rounded-2xl shadow-md p-8 min-h-screen">
        <h2 class="text-2xl font-bold mb-6 text-center">Input Data Keluarga via Suara</h2>

        {{-- PROGRESS BAR --}}
        <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
            <div id="progressBar" class="bg-teal-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
        <div class="text-center text-sm text-gray-600 mb-6">
            Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">7</span>
        </div>

        <div id="voice-status" class="text-center text-gray-700 mb-6 text-lg font-medium"></div>

        {{-- QUIZ AREA --}}
        <div id="quizArea" class="space-y-8"></div>

        {{-- MICROPHONE BUTTON --}}
        <div class="flex flex-col items-center mt-12">
            <div class="relative">
                <button id="startBtn" class="relative w-32 h-32 bg-gradient-to-br from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800 text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                    <svg id="micIcon" xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                    </svg>
                </button>
                <canvas id="visualizer" class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity rounded-full" width="128" height="128"></canvas>
            </div>
            <p class="mt-4 text-sm text-gray-500">Tekan untuk mulai merekam</p>
        </div>

        {{-- REVIEW FORM --}}
        <div id="reviewForm" class="hidden mt-12">
            <h3 class="text-2xl font-bold mb-6 text-center">Review & Edit Data</h3>
            <form id="voiceForm" class="max-w-4xl mx-auto space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium">Jenis Mutasi</label>
                        <select name="kdmutasimasuk" id="kdmutasimasuk" class="w-full border rounded-lg p-3" required>
                            <option value="">-- Pilih Mutasi --</option>
                            @foreach($mutasi as $m)
                                <option value="{{ $m->kdmutasimasuk }}">{{ $m->mutasimasuk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium">Tanggal Mutasi</label>
                        <input type="date" name="keluarga_tanggalmutasi" id="keluarga_tanggalmutasi" class="w-full border rounded-lg p-3" required>
                    </div>
                    <div class="relative">
                        <label class="block font-medium">No KK</label>
                        <input type="text" name="no_kk" id="no_kk" maxlength="16" class="w-full border rounded-lg p-3" required>
                        <p id="noKkError" class="text-red-600 text-sm mt-1 hidden">No KK sudah terdaftar!</p>
                    </div>
                    <div>
                        <label class="block font-medium">Kepala Rumah Tangga</label>
                        <input type="text" name="keluarga_kepalakeluarga" id="keluarga_kepalakeluarga" class="w-full border rounded-lg p-3" required>
                    </div>
                    <div>
                        <label class="block font-medium">Dusun/Lingkungan</label>
                        <select name="kddusun" id="kddusun" class="w-full border rounded-lg p-3" required>
                            <option value="">-- Silahkan Pilih --</option>
                            @foreach($dusun as $d)
                                <option value="{{ $d->kddusun }}">{{ $d->dusun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                    <div>
                        <label class="block font-medium">RW</label>
                        <input type="text" name="keluarga_rw" id="keluarga_rw" maxlength="3" class="w-full border rounded-lg p-3" required>
                    </div>
                    <div>
                        <label class="block font-medium">RT</label>
                        <input type="text" name="keluarga_rt" id="keluarga_rt" maxlength="3" class="w-full border rounded-lg p-3" required>
                    </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-medium">Alamat Lengkap</label>
                        <textarea name="keluarga_alamatlengkap" id="keluarga_alamatlengkap" class="w-full border rounded-lg p-3 h-32" required></textarea>
                    </div>
                </div>

                <div id="wilayahDatangReview" class="hidden bg-teal-50 p-6 rounded-xl">
                    <h4 class="font-bold text-lg mb-4">Wilayah Datang</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label>Provinsi</label><select name="kdprovinsi" id="kdprovinsi" class="w-full border rounded-lg p-3"><option value="">-- Silahkan Pilih --</option>@foreach($provinsi as $p)<option value="{{ $p->kdprovinsi }}">{{ $p->provinsi }}</option>@endforeach</select></div>
                        <div><label>Kabupaten</label><select name="kdkabupaten" id="kdkabupaten" class="w-full border rounded-lg p-3"><option>-- Pilih Provinsi Dahulu --</option></select></div>
                        <div><label>Kecamatan</label><select name="kdkecamatan" id="kdkecamatan" class="w-full border rounded-lg p-3"><option>-- Pilih Kabupaten Dahulu --</option></select></div>
                        <div><label>Desa</label><select name="kddesa" id="kddesa" class="w-full border rounded-lg p-3"><option>-- Pilih Kecamatan Dahulu --</option></select></div>
                    </div>
                </div>

                <div class="flex justify-center gap-6 mt-8">
                    <button type="submit" id="simpanBtn" class="px-8 py-4 bg-green-600 text-white text-lg rounded-xl hover:bg-green-700 shadow-lg">Simpan Data</button>
                    <button type="button" id="nextBtn" class="px-8 py-4 bg-purple-600 text-white text-lg rounded-xl hover:bg-purple-700 shadow-lg hidden">Lanjut Modul Selanjutnya</button>
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
let recognition;
let step = 0;
let answers = { keluarga_tanggalmutasi: new Date().toISOString().split('T')[0] };
let totalQuestions = 7;
let isSpeaking = false;
let isListening = false;
let audioContext, analyser, dataArray, canvas, ctx;

// DOM Elements
const progressBar = document.getElementById("progressBar");
const currentQ = document.getElementById("currentQ");
const totalQ = document.getElementById("totalQ");
const voiceStatus = document.getElementById("voice-status");
const quizArea = document.getElementById("quizArea");
const startBtn = document.getElementById("startBtn");

// Data
const mutasiOptions = @json($mutasi->pluck('mutasimasuk', 'kdmutasimasuk'));
const dusunOptions   = @json($dusun->pluck('dusun', 'kddusun'));

let questions = [
    { type: "select", label: "Jenis mutasi apa?", field: "kdmutasimasuk", options: mutasiOptions },
    { type: "text",   label: "Sebutkan nomor kartu keluarga, 16 digit", field: "no_kk" },
    { type: "text",   label: "Siapa nama kepala rumah tangga?", field: "keluarga_kepalakeluarga" },
    { type: "select", label: "Dusun atau lingkungan apa?", field: "kddusun", options: dusunOptions },
    { type: "number", label: "RW berapa?", field: "keluarga_rw" },
    { type: "number", label: "RT berapa?", field: "keluarga_rt" },
    { type: "text",   label: "Sebutkan alamat lengkapnya", field: "keluarga_alamatlengkap" }
];

const wilayahQuestions = [
    { type: "select", label: "Provinsi asalnya apa?", field: "kdprovinsi", options: @json($provinsi->pluck('provinsi','kdprovinsi')), skipReadOptions: true },
    { type: "select", label: "Kabupaten atau kota asalnya apa?", field: "kdkabupaten", dynamic: true, dynamicUrl: "/get-kabupaten/", skipReadOptions: true },
    { type: "select", label: "Kecamatan asalnya apa?", field: "kdkecamatan", dynamic: true, dynamicUrl: "/get-kecamatan/", skipReadOptions: true },
    { type: "select", label: "Desa atau kelurahan asalnya apa?", field: "kddesa", dynamic: true, dynamicUrl: "/get-desa/", skipReadOptions: true }
];

function updateProgress() {
    const percent = (step / totalQuestions) * 100;
    progressBar.style.width = percent + "%";
    currentQ.textContent = step + 1;
    totalQ.textContent = totalQuestions;
}

function speak(text) {
    return new Promise(r => {
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = 'id-ID';
        utter.rate = 0.9;
        isSpeaking = true;
        utter.onend = () => { isSpeaking = false; r(); };
        speechSynthesis.speak(utter);
    });
}

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

async function renderQuestion() {
    const q = questions[step];
    let html = `<div class="text-center mb-10"><h3 class="text-2xl font-medium text-gray-800">${q.label}</h3></div>`;

    if (q.dynamic) {
        const prevKey = q.field === "kdkabupaten" ? "kdprovinsi" : q.field === "kdkecamatan" ? "kdkabupaten" : "kdkecamatan";
        const prevVal = answers[prevKey];
        if (!prevVal) return;
        try {
            const res = await fetch(q.dynamicUrl + prevVal);
            const data = await res.json();
            q.options = {};
            data.forEach(item => {
                const key = q.field === "kdkabupaten" ? "kdkabupaten" : q.field === "kdkecamatan" ? "kdkecamatan" : "kddesa";
                const val = q.field === "kdkabupaten" ? "kabupaten" : q.field === "kdkecamatan" ? "kecamatan" : "desa";
                q.options[item[key]] = item[val];
            });
        } catch (e) {
            html += `<div class="text-center text-red-600">Gagal memuat data.</div>`;
            quizArea.innerHTML = html;
            return;
        }
    }

    if (q.type === "select" && q.options && Object.keys(q.options).length > 0) {
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">';
        Object.entries(q.options).forEach(([id, nama]) => {
            html += `<div class="option-card rounded-2xl p-8 cursor-pointer text-center shadow-md" data-value="${id}" data-text="${nama}">
                <span class="text-xl font-medium">${nama}</span>
            </div>`;
        });
        html += '</div>';
    } else if (q.type === "select") {
        html += `<div class="text-center text-red-600">Memuat data...</div>`;
    } else {
        html += `<div class="max-w-2xl mx-auto">
            <input type="text" id="inputAnswer" class="w-full border-2 border-gray-300 rounded-2xl p-6 text-2xl text-center" placeholder="Jawaban akan otomatis terisi..." readonly>
        </div>`;
    }

    quizArea.innerHTML = html;
    updateProgress();

    let speakText = q.label;
    if (q.type === "select" && !q.skipReadOptions && q.options) {
        const choices = Object.values(q.options).slice(0, 6).join(", ");
        speakText += `. Beberapa contoh: ${choices}${Object.keys(q.options).length > 6 ? ", dan lainnya." : "."}`;
    }
    await speak(speakText);
    voiceStatus.innerText = "Silakan jawab...";
    attachCardListeners();
}

function attachCardListeners() {
    document.querySelectorAll('.option-card').forEach(card => {
        card.onclick = () => {
            document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            processVoiceAnswer(card.dataset.text.toLowerCase());
        };
    });
}

function startListening() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) return alert("Browser tidak mendukung suara");

    recognition = new SpeechRecognition();
    recognition.lang = 'id-ID';
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onresult = (e) => {
        const result = e.results[e.results.length - 1];
        if (result.isFinal && result[0].confidence > 0.7) {
            const text = result[0].transcript.trim();
            voiceStatus.innerText = `Dengar: "${text}"`;
            processVoiceAnswer(text.toLowerCase());
        }
    };

    recognition.onerror = (e) => console.log("Error:", e.error);
    recognition.start();
}

function findBestMatch(text, options) {
    const cleanText = text.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();
    let best = null, score = 0;
    Object.entries(options).forEach(([id, name]) => {
        const cleanName = name.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();
        if (cleanText.includes(cleanName) || cleanName.includes(cleanText)) {
            const s = cleanName.split(' ').filter(w => cleanText.includes(w)).length;
            if (s > score) { score = s; best = [id, name]; }
        }
    });
    return best;
}

async function processVoiceAnswer(text) {
    if (isSpeaking) return;

    const q = questions[step];
    let value = text;

    if (q.type === "select") {
        const match = findBestMatch(text, q.options);
        if (!match) {
            await speak("Maaf, tidak dikenali. Ulangi dengan jelas.");
            return;
        }
        value = match[0];
        answers[q.field] = value;
        answers[q.field + '_display'] = match[1];

        const card = document.querySelector(`.option-card[data-value="${value}"]`);
        if (card) {
            document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
        }

        if (q.field === "kdmutasimasuk" && match[1].toLowerCase().includes('datang')) {
            questions.push(...wilayahQuestions);
            totalQuestions = questions.length;
            totalQ.textContent = totalQuestions;
            document.getElementById('wilayahDatangReview').classList.remove('hidden');
        }

        if (q.field === "kdprovinsi") {
            document.getElementById('kdprovinsi').value = value;
            document.getElementById('kdprovinsi').dispatchEvent(new Event('change'));
        }
        if (q.field === "kdkabupaten") document.getElementById('kdkabupaten').value = value;
        if (q.field === "kdkecamatan") document.getElementById('kdkecamatan').value = value;
        if (q.field === "kddesa") document.getElementById('kddesa').value = value;
    }
    else if (q.field === "no_kk") {
        value = text.replace(/\D/g, '').slice(0,16);
        if (value.length !== 16) { await speak("Harus 16 digit."); return; }
        answers[q.field] = value;
        document.getElementById('inputAnswer').value = value;
    }
    else if (q.field.includes("rw") || q.field.includes("rt")) {
        const num = text.match(/\d+/);
        if (!num) { await speak("Tidak terdengar angka."); return; }
        value = num[0].padStart(3,'0').slice(0,3);
        answers[q.field] = value;
        document.getElementById('inputAnswer').value = value;
    }
    else {
        answers[q.field] = text;
        document.getElementById('inputAnswer').value = text;
    }

    setTimeout(() => {
        step++;
        if (step < questions.length) renderQuestion();
        else finishQuiz();
    }, 1500);
}

function finishQuiz() {
    if (recognition) recognition.stop();
    isListening = false;
    startBtn.classList.remove('listening');
    document.getElementById('visualizer').style.opacity = 0;
    voiceStatus.innerText = "Semua selesai! Menyiapkan data...";
    quizArea.innerHTML = "<div class='text-center  text-3xl font-bold text-green-600'>SELESAI!</div>";

    setTimeout(() => {
        document.getElementById('keluarga_tanggalmutasi').value = answers.keluarga_tanggalmutasi;
        document.getElementById('kdmutasimasuk').value = answers.kdmutasimasuk || '';
        document.getElementById('no_kk').value = answers.no_kk || '';
        document.getElementById('keluarga_kepalakeluarga').value = answers.keluarga_kepalakeluarga || '';
        document.getElementById('kddusun').value = answers.kddusun || '';
        document.getElementById('keluarga_rw').value = answers.keluarga_rw || '';
        document.getElementById('keluarga_rt').value = answers.keluarga_rt || '';
        document.getElementById('keluarga_alamatlengkap').value = answers.keluarga_alamatlengkap || '';

        if (answers.kdprovinsi) {
            document.getElementById('kdprovinsi').value = answers.kdprovinsi;
            document.getElementById('kdprovinsi').dispatchEvent(new Event('change'));
            setTimeout(() => {
                if (answers.kdkabupaten) {
                    document.getElementById('kdkabupaten').value = answers.kdkabupaten;
                    document.getElementById('kdkabupaten').dispatchEvent(new Event('change'));
                    setTimeout(() => {
                        if (answers.kdkecamatan) {
                            document.getElementById('kdkecamatan').value = answers.kdkecamatan;
                            document.getElementById('kdkecamatan').dispatchEvent(new Event('change'));
                            setTimeout(() => {
                                if (answers.kddesa) document.getElementById('kddesa').value = answers.kddesa;
                            }, 500);
                        }
                    }, 500);
                }
            }, 500);
        }

        document.getElementById('reviewForm').classList.remove('hidden');
        voiceStatus.innerText = "Silakan review & simpan";
    }, 1500);
}

startBtn.addEventListener('click', async () => {
    if (isListening) return;
    isListening = true;
    startBtn.classList.add('listening');
    document.getElementById('visualizer').style.opacity = 1;

    audioContext = new (window.AudioContext || window.webkitAudioContext)();
    analyser = audioContext.createAnalyser();
    canvas = document.getElementById('visualizer');
    ctx = canvas.getContext('2d');
    dataArray = new Uint8Array(analyser.frequencyBinCount);

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const source = audioContext.createMediaStreamSource(stream);
        source.connect(analyser);
        drawVisualizer();
    } catch (err) {
        alert("Gagal akses mikrofon!");
        isListening = false;
        startBtn.classList.remove('listening');
        return;
    }

    startListening();
    await renderQuestion();
});

// SIMPAN DATA – SUDAH 100% BENAR
document.getElementById('voiceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const simpanBtn = document.getElementById('simpanBtn');
    const errorEl = document.getElementById('noKkError');
    const noKk = document.getElementById('no_kk').value.trim();

    errorEl.classList.add('hidden');
    simpanBtn.disabled = true;
    simpanBtn.innerText = "Mengecek No KK...";

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const cek = await fetch("/admin/voice/keluarga/cek-no-kk", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ no_kk: noKk })
        });

        if (!cek.ok) throw new Error("Server error: " + cek.status);
        const cekData = await cek.json();

        if (cekData.exists) {
            errorEl.classList.remove('hidden');
            simpanBtn.disabled = false;
            simpanBtn.innerText = "Simpan Data";
            return;
        }
    } catch (err) {
        alert("Error cek No KK: " + err.message);
        simpanBtn.disabled = false;
        simpanBtn.innerText = "Simpan Data";
        return;
    }

    simpanBtn.innerText = "Menyimpan ke database...";
    const formData = new FormData(this);

    try {
        const save = await fetch("/admin/voice/keluarga/store", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken },
            body: formData
        });

        if (!save.ok) {
            const err = await save.text();
            throw new Error(err || "HTTP " + save.status);
        }

        const result = await save.json();

        if (result.success) {
            alert("DATA BERHASIL DISIMPAN KE DATABASE!");
            document.getElementById('nextBtn').classList.remove('hidden');
            simpanBtn.innerText = "SUKSES!";
            simpanBtn.style.backgroundColor = "#16a34a";
            simpanBtn.disabled = true;
        } else {
            alert("Gagal simpan: " + (result.error || "Unknown"));
        }
    } catch (err) {
        alert("Koneksi error: " + err.message);
        console.error(err);
    } finally {
        if (!document.getElementById('nextBtn').classList.contains('hidden')) {
            simpanBtn.disabled = true;
        } else {
            simpanBtn.disabled = false;
            simpanBtn.innerText = "Simpan Data";
        }
    }
});

// Dynamic dropdown
['kdprovinsi', 'kdkabupaten', 'kdkecamatan'].forEach(id => {
    document.getElementById(id).addEventListener('change', async function() {
        const val = this.value;
        const nextId = id === 'kdprovinsi' ? 'kdkabupaten' : id === 'kdkabupaten' ? 'kdkecamatan' : 'kddesa';
        const url = id === 'kdprovinsi' ? '/get-kabupaten/' : id === 'kdkabupaten' ? '/get-kecamatan/' : '/get-desa/';
        const sel = document.getElementById(nextId);
        sel.innerHTML = '<option>-- Memuat... --</option>';
        if (!val) return;
        const res = await fetch(url + val);
        const data = await res.json();
        sel.innerHTML = '<option value="">-- Pilih --</option>';
        data.forEach(item => {
            const key = nextId === 'kdkabupaten' ? 'kdkabupaten' : nextId === 'kdkecamatan' ? 'kdkecamatan' : 'kddesa';
            const txt = nextId === 'kdkabupaten' ? 'kabupaten' : nextId === 'kdkecamatan' ? 'kecamatan' : 'desa';
            sel.innerHTML += `<option value="${item[key]}">${item[txt]}</option>`;
        });
    });
});

document.getElementById('nextBtn').addEventListener('click', () => {
    document.getElementById('modul1').className = 'flex flex-col items-center bg-blue-100 text-blue-800 rounded-lg px-2 py-1';
    document.getElementById('modul2').className = 'flex flex-col items-center bg-green-100 text-green-800 rounded-lg px-2 py-1';
    speak("Modul selanjutnya siap.");
});
</script>
</x-app-layout>