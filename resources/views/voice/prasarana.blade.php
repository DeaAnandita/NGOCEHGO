<x-app-layout>
<div class="min-h-screen bg-gradient-to-b from-green-50 to-white">
    {{-- PROGRESS STEP BAR ATAS - HORIZONTAL SCROLL --}}
    <div class="sticky top-0 z-20 bg-white shadow-lg border-b z-50">
        <div class="max-w-full overflow-x-auto scrollbar-hide">
            <div class="flex items-center space-x-8 px-6 py-6 min-w-max">
                @php
                    $modules = [
                        1 => ['name' => 'Data Keluarga',     'route' => '/admin/voice/keluarga',     'status' => 'done',   'color' => 'blue'],
                        2 => ['name' => 'Prasarana Dasar',  'route' => '/admin/voice/prasarana',    'status' => 'active', 'color' => 'green'],
                        3 => ['name' => 'Aset Keluarga',    'route' => '/admin/voice/aset',         'status' => 'todo',   'color' => 'gray'],
                        4 => ['name' => 'Aset Lahan Tanah', 'route' => '/admin/voice/lahan',        'status' => 'todo',   'color' => 'gray'],
                        5 => ['name' => 'Aset Ternak',      'route' => '/admin/voice/ternak',       'status' => 'todo',   'color' => 'gray'],
                        6 => ['name' => 'Aset Perikanan',   'route' => '/admin/voice/perikanan',    'status' => 'todo',   'color' => 'gray'],
                        7 => ['name' => 'Sarpras Kerja',    'route' => '/admin/voice/sarpras',      'status' => 'todo',   'color' => 'gray'],
                        8 => ['name' => 'Bangun Keluarga',  'route' => '/admin/voice/bangun',       'status' => 'todo',   'color' => 'gray'],
                        9 => ['name' => 'Sejahtera Keluarga','route' => '/admin/voice/sejahtera',   'status' => 'todo',   'color' => 'gray'],
                        10=> ['name' => 'Konflik Sosial',   'route' => '/admin/voice/konflik',      'status' => 'todo',   'color' => 'gray'],
                        11=> ['name' => 'Kualitas Ibu Hamil','route' => '/admin/voice/hamil',       'status' => 'todo',   'color' => 'gray'],
                        12=> ['name' => 'Kualitas Bayi',    'route' => '/admin/voice/bayi',         'status' => 'todo',   'color' => 'gray'],
                    ];
                @endphp

                @foreach($modules as $i => $mod)
                    @php
                        $isActive = $mod['status'] === 'active';
                        $isDone = $mod['status'] === 'done';
                        $bg = $isDone ? 'bg-blue-600' : ($isActive ? 'bg-green-600' : 'bg-gray-300');
                        $text = $isDone ? 'text-white' : ($isActive ? 'text-white' : 'text-gray-600');
                        $labelColor = $isDone ? 'text-blue-800' : ($isActive ? 'text-green-700' : 'text-gray-600');
                        $subColor = $isDone ? 'text-blue-600' : ($isActive ? 'text-green-600' : 'text-gray-500');
                    @endphp

                    @if(!$loop->first)
                        <div class="h-1 w-20 {{ $isDone ? 'bg-green-500' : 'bg-gray-300' }} self-center rounded-full"></div>
                    @endif

                    <a href="{{ $mod['route'] }}" class="flex items-center {{ !$isActive && !$isDone ? 'opacity-50' : '' }}">
                        <div class="w-12 h-12 {{ $bg }} rounded-full flex items-center justify-center font-bold {{ $text }}">{{ $i }}</div>
                        <div class="ml-3 min-w-max">
                            <div class="font-semibold {{ $labelColor }}">{{ $mod['name'] }}</div>
                            <div class="text-xs {{ $subColor }}">
                                {{ $isDone ? 'Selesai' : ($isActive ? 'Sedang diisi' : 'Belum') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto py-12 px-6">
        <h2 class="text-4xl font-bold text-center mb-8 text-green-800">Input Prasarana Dasar via Suara</h2>

        <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
            <div id="progressBar" class="bg-green-600 h-4 rounded-full transition-all duration-700" style="width: 0%"></div>
        </div>
        <div class="text-center text-lg text-gray-700 mb-8">
            Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">20</span>
        </div>

        <div id="voice-status" class="text-center text-2xl font-medium text-gray-700 mb-10"></div>
        <div id="quizArea" class="space-y-10"></div>

        <div class="flex flex-col items-center mt-16">
            <div class="relative">
                <button id="startBtn" class="relative w-40 h-40 bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                    <svg id="micIcon" xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                    </svg>
                </button>
                <canvas id="visualizer" class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity rounded-full" width="160" height="160"></canvas>
            </div>
            <p class="mt-6 text-lg text-gray-600 font-medium">Tekan untuk mulai merekam</p>
        </div>

        {{-- REVIEW FORM --}}
        <div id="reviewFormPrasarana" class="hidden mt-16">
            <h3 class="text-3xl font-bold text-center mb-10 text-green-700">Review & Edit Data Prasarana</h3>
            <form id="voiceForm" class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl p-10">
                @csrf
                <input type="hidden" name="no_kk" id="no_kk" value="{{ $no_kk }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @include('voice.partials.prasarana_fields')
                </div>

                <div class="flex justify-center gap-8 mt-12">
                    <button type="submit" id="simpanBtn" class="px-10 py-5 bg-green-600 text-white text-xl rounded-xl hover:bg-green-700 shadow-lg">Simpan & Lanjut Aset</button>
                    <a href="/admin/voice/keluarga" class="px-10 py-5 bg-gray-600 text-white text-xl rounded-xl hover:bg-gray-700 shadow-lg">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .option-card{transition:all .3s;border:2px solid #e5e7eb;}
    .option-card:hover{background-color:#f0fdfa;border-color:#14b8a6;}
    .option-card.selected{background-color:#ccfbf1!important;border-color:#14b8a6!important;box-shadow:0 0 0 4px rgba(20,184,166,.2);}
    #startBtn.listening{background:linear-gradient(to bottom right,#ef4444,#dc2626)!important;transform:scale(1.15);}
</style>

<script>
let recognition, step = 0, answers = { no_kk: "{{ $no_kk }}" }, totalQuestions = 20;
let isSpeaking = false, isListening = false;
let audioContext, analyser, dataArray, canvas, ctx;

const progressBar = document.getElementById("progressBar");
const currentQ = document.getElementById("currentQ");
const totalQ = document.getElementById("totalQ");
const voiceStatus = document.getElementById("voice-status");
const quizArea = document.getElementById("quizArea");
const startBtn = document.getElementById("startBtn");

// === SOAL INTERAKTIF & NATURAL (TANPA NO KK) ===
let questions = [
    { type: "select", label: "Status kepemilikan bangunan ini milik sendiri, sewa, atau pinjam?", field: "kdstatuspemilikbangunan", options: masters.kdstatuspemilikbangunan },
    { type: "select", label: "Tanah tempat bangunan ini berdiri, milik sendiri atau bukan?", field: "kdstatuspemiliklahan", options: masters.kdstatuspemiliklahan },
    { type: "select", label: "Bangunan ini termasuk rumah permanen, semi-permanen, atau darurat?", field: "kdjenisfisikbangunan", options: masters.kdjenisfisikbangunan },
    { type: "select", label: "Lantai di rumah ini terbuat dari keramik, semen, atau tanah?", field: "kdjenislantaibangunan", options: masters.kdjenislantaibangunan },
    { type: "select", label: "Kondisi lantai saat ini baik, rusak ringan, atau rusak berat?", field: "kdkondisilantaibangunan", options: masters.kdkondisilantaibangunan },
    { type: "select", label: "Dinding rumah ini dari bata, kayu, atau bambu?", field: "kdjenisdindingbangunan", options: masters.kdjenisdindingbangunan },
    { type: "select", label: "Dindingnya masih bagus atau sudah retak dan bolong?", field: "kdkondisidindingbangunan", options: masters.kdkondisidindingbangunan },
    { type: "select", label: "Atap rumah ini dari genteng, asbes, atau seng?", field: "kdjenisatapbangunan", options: masters.kdjenisatapbangunan },
    { type: "select", label: "Atapnya masih rapat atau sudah bocor?", field: "kdkondisiatapbangunan", options: masters.kdkondisiatapbangunan },
    { type: "select", label: "Air minum sehari-hari dari PDAM, sumur, atau sungai?", field: "kdsumberairminum", options: masters.kdsumberairminum },
    { type: "select", label: "Air minumnya jernih, keruh, atau berbau?", field: "kdkondisisumberair", options: masters.kdkondisisumberair },
    { type: "select", label: "Air minum diambil dengan cara dipompa, dibeli, atau diambil langsung?", field: "kdcaraperolehanair", options: masters.kdcaraperolehanair },
    { type: "select", label: "Penerangan utama di rumah ini pakai listrik PLN, genset, atau lampu minyak?", field: "kdsumberpeneranganutama", options: masters.kdsumberpeneranganutama },
    { type: "select", label: "Daya listrik yang terpasang berapa? 450 VA, 900 VA, atau lebih?", field: "kdsumberdayaterpasang", options: masters.kdsumberdayaterpasang },
    { type: "select", label: "Memasak sehari-hari pakai gas, kayu bakar, atau minyak tanah?", field: "kdbahanbakarmemasak", options: masters.kdbahanbakarmemasak },
    { type: "select", label: "Toilet atau jamban di rumah ini milik sendiri, umum, atau tidak ada?", field: "kdfasilitastempatbab", options: masters.kdfasilitastempatbab },
    { type: "select", label: "Limbah tinja dibuang ke septic tank, sungai, atau lubang tanah?", field: "kdpembuanganakhirtinja", options: masters.kdpembuanganakhirtinja },
    { type: "select", label: "Sampah rumah tangga dibuang ke TPS, dibakar, atau dikubur?", field: "kdcarapembuangansampah", options: masters.kdcarapembuangansampah },
    { type: "select", label: "Apakah memanfaatkan sungai atau mata air untuk mandi, cuci, atau irigasi?", field: "kdmanfaatmataair", options: masters.kdmanfaatmataair },
    { type: "number", label: "Berapa luas lantai rumah ini dalam meter persegi? Misalnya 60 m²", field: "prasdas_luaslantai" },
    { type: "number", label: "Ada berapa kamar tidur di rumah ini?", field: "prasdas_jumlahkamar" }
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

async function renderQuestion() {
    const q = questions[step];
    let html = `<div class="text-center mb-10"><h3 class="text-2xl font-medium text-gray-800">${q.label}</h3></div>`;

    if (q.type === "select") {
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">';
        const entries = Object.entries(q.options);
        entries.forEach(([id, nama]) => {
            html += `<div class="option-card rounded-2xl p-8 cursor-pointer text-center shadow-md" data-value="${id}" data-text="${nama}">
                <span class="text-xl font-medium">${nama}</span>
            </div>`;
        });
        html += '</div>';
    } else {
        html += `<div class="max-w-2xl mx-auto">
            <input type="text" id="inputAnswer" class="w-full border-2 border-gray-300 rounded-2xl p-6 text-2xl text-center" placeholder="Jawaban akan otomatis terisi..." readonly>
        </div>`;
    }

    quizArea.innerHTML = html;
    updateProgress();

    let speakText = q.label;
    if (q.type === "select" && q.options) {
        const examples = Object.values(q.options).slice(0, 6).join(", ");
        speakText += `. Contoh: ${examples}${Object.keys(q.options).length > 6 ? ", dan lainnya." : "."}`;
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

    recognition.onresult = (e) => {
        const result = e.results[e.results.length - 1];
        if (result.isFinal && result[0].confidence > 0.7) {
            const text = result[0].transcript.trim();
            voiceStatus.innerText = `Dengar: "${text}"`;
            processVoiceAnswer(text.toLowerCase());
        }
    };

    recognition.start();
}

function findBestMatch(text, options) {
    const cleanText = text.replace(/[^a-z0-9\s]/g, '').trim();
    let best = null, score = 0;
    Object.entries(options).forEach(([id, name]) => {
        const cleanName = name.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();
        const s = cleanName.split(' ').filter(w => cleanText.includes(w)).length;
        if (s > score) { score = s; best = [id, name]; }
    });
    return best;
}

async function processVoiceAnswer(text) {
    if (isSpeaking) return;
    const q = questions[step];
    let value = text;

    if (q.type === "select") {
        const match = findBestMatch(text, q.options);
        if (!match) { await speak("Maaf, tidak dikenali. Ulangi."); return; }
        value = match[0];
        answers[q.field] = value;
        const card = document.querySelector(`.option-card[data-value="${value}"]`);
        if (card) card.classList.add('selected');
    } else {
        const num = text.match(/\d+/);
        if (!num) { await speak("Tidak terdengar angka."); return; }
        value = num[0];
        answers[q.field] = value;
        document.getElementById('inputAnswer').value = value;
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
    voiceStatus.innerText = "Selesai! Menyiapkan data...";
    quizArea.innerHTML = "<div class='text-center text-3xl font-bold text-green-600'>SELESAI!</div>";

    setTimeout(() => {
        Object.keys(answers).forEach(field => {
            const el = document.getElementById(field);
            if (el) el.value = answers[field];
        });
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

document.getElementById('voiceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const simpanBtn = document.getElementById('simpanBtn');
    simpanBtn.disabled = true;
    simpanBtn.innerText = "Menyimpan...";

    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const save = await fetch("/admin/voice/prasarana/store", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken },
            body: formData
        });

        if (!save.ok) throw new Error("Gagal simpan");
        alert("DATA PRASARANA BERHASIL DISIMPAN!");
        window.location.href = "/admin/voice/aset";
    } catch (err) {
        alert("Error: " + err.message);
        simpanBtn.disabled = false;
        simpanBtn.innerText = "Simpan & Lanjut Aset";
    }
});
</script>
</x-app-layout>