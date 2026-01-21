<x-app-layout>
    @slot('progresspelayanan')
        <div class="sticky top-16 left-0 right-0 z-10 bg-white shadow-md border-b">
            <div class="max-w-7xl mx-auto overflow-x-auto scrollbar-hide">
                <div id="progressSteps" class="flex items-center space-x-6 px-6 py-4 min-w-max">
                    <!-- JS generate -->
                </div>
            </div>
        </div>
    @endslot

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="max-w-7xl mx-auto py-8 px-6 space-y-6">
        <div id="inputArea" class="bg-white rounded-2xl shadow-lg p-6">
            <h2 id="modulTitle" class="text-2xl font-bold text-center mb-6 text-green-800">
                Input Data Kelembagaan via Suara
            </h2>

            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div id="progressBar" class="bg-green-600 h-3 rounded-full transition-all duration-500"
                    style="width: 0%"></div>
            </div>

            <div class="text-center text-sm text-gray-600 mb-4">
                Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">1</span>
            </div>

            <div id="voice-status" class="text-center text-lg font-medium text-gray-700 mb-8">
                Tekan mic untuk mulai merekam...
            </div>

            <div id="quizArea" class="space-y-6"></div>

            <div class="flex items-center justify-center mt-10 space-x-4">
                <!-- Tombol Mic / Stop -->
                <button id="recordBtn"
                    class="relative w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 transform hover:scale-110 z-10">
                    <svg id="recordIcon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 transition-all duration-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                    </svg>
                    <span class="absolute inset-0 rounded-full animate-ping bg-blue-400 opacity-75 hidden"
                        id="pulseRing"></span>
                </button>

                <!-- Visualizer + Placeholder Text -->
                <div
                    class="relative w-72 h-16 bg-gradient-to-r from-gray-50 to-gray-100 rounded-full shadow-inner overflow-hidden flex items-center justify-center">
                    <canvas id="visualizer" class="absolute inset-0 w-full h-full px-6 hidden"></canvas>
                    <div id="visualizerPlaceholder"
                        class="absolute text-gray-500 text-sm font-medium pointer-events-none">
                        Klik mic untuk mulai merekam
                    </div>
                </div>
            </div>
        </div>

        <div id="reviewForm" class="hidden bg-white rounded-2xl shadow-lg p-6 mt-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-blue-700">
                    Review & Simpan Modul
                </h3>

                <div class="flex gap-2">
                    <button id="saveModulBtn" class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow">
                        Tambah ke Database
                    </button>

                    <button id="restartBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow">
                        Ulang Data
                    </button>
                </div>
            </div>

            <form id="voiceForm">
                @csrf
                <div id="reviewFields" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
            </form>
        </div>


    </div>
    <div id="loadingOverlay"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 flex flex-col items-center justify-center z-50">
        <div
            class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center transform transition-all duration-300 scale-100">
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4 overflow-hidden">
                <div id="loadingBar"
                    class="bg-gradient-to-r from-blue-500 to-green-600 h-3 rounded-full transition-all duration-300"
                    style="width:0%"></div>
            </div>
            <p id="loadingText" class="text-lg font-semibold text-gray-800 mb-2">
                Menyimpan data...
            </p>
            <p class="text-sm text-gray-500">
                Proses penyimpanan modul kelembagaan
            </p>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .option-card {
            transition: all .3s;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            padding: 1rem;
            border-radius: 1rem;
            text-align: center;
        }

        .option-card:hover {
            background-color: #eff6ff;
            border-color: #14b8a6;
        }

        .option-card.selected {
            background-color: #dbeafe !important;
            border-color: #14b8a6 !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .2);
        }

        #recordBtn.recording {
            background: linear-gradient(to bottom right, #ef4444, #dc2626) !important;
        }

        #recordBtn.recording #pulseRing {
            display: block;
        }

        #visualizer.show {
            display: block !important;
        }

        #visualizerPlaceholder.hide {
            display: none !important;
        }
    </style>
    <script>
        /* ============================================================
                                                                                                                                                                                                       STATE
                                                                                                                                                                                                    ============================================================ */
        let currentModul = 1;
        let lastTranscript = "";
        let isRendering = false;
        let step = 0;
        let answers = {};
        let allowListen = true;
        let silenceTimer = null;
        let ttsCooldownUntil = 0;
        let ignoreSpeechInput = false;

        // ===============================
        // NIK AUTOFILL STATE
        // ===============================
        let nikFound = false;
        let nikData = null;
        let awaitingNikConfirm = false;

        // NIK debounce + anti duplikat
        let nikTimer = null;
        let nikPauseCount = 0;
        let nikDigitsLive = ""; // ✅ digit NIK TERBARU (replace, bukan append)
        let nikLastDigits = ""; // ✅ untuk deteksi perubahan digit

        const NIK_DELAY = 4000; // proses setelah diam 2.5 detik
        const NIK_WARN_AFTER = 3; // baru ngomong "kurang digit" setelah 3 kali jeda

        // kata ya/tidak sederhana
        function isYes(text) {
            const t = normalize(text);
            return ["ya", "iya", "betul", "benar", "y", "oke", "ok", "lanjut"].includes(t) || t.includes("ya");
        }

        function isNo(text) {
            const t = normalize(text);
            return ["tidak", "nggak", "ga", "gak", "salah", "bukan", "no"].includes(t) || t.includes("tidak");
        }

        function indexOfField(modulId, fieldName) {
            return (questions[modulId] || []).findIndex(q => q.field === fieldName);
        }

        async function fetchNikData(nik) {
            const url = `{{ route('pelayanan.surat.cek-nik') }}?nik=${encodeURIComponent(nik)}`;
            console.log("CEK NIK URL:", url); // ✅ tambah ini

            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();

            console.log("CEK NIK RESPONSE:", res.status, json); // ✅ tambah ini
            if (!res.ok) return {
                found: false
            };
            return json;
        }

        /* ============================================================
           VOICE STATE
        ============================================================ */
        let recognition = null;
        let isListening = false;
        let audioContext = null;
        let analyser = null;
        let dataArray = null;
        let canvas = null;
        let ctx = null;
        let isSpeaking = false;
        let isPaused = true;
        let isProcessingAnswer = false;

        /* ============================================================
           MODUL
        ============================================================ */
        const modules = [{
            id: 1,
            name: "Pengajuan Surat"
        }];
        let modulStatus = {
            1: 'active'
        };
        let stepByModul = {
            1: 0
        };
        const reviewableModules = [1];

        /* ============================================================
           QUESTIONS
        ============================================================ */
        const questions = {
            1: [{
                    type: "text",
                    label: "Silakan sebutkan Nomor Induk Kependudukan Anda",
                    field: "nik"
                },
                {
                    type: "text",
                    label: "Siapa nama lengkap Anda sesuai KTP",
                    field: "nama"
                },
                {
                    type: "text",
                    label: "Di mana tempat lahir Anda",
                    field: "tempat_lahir"
                },
                {
                    type: "date",
                    label: "Kapan tanggal lahir Anda",
                    field: "tanggal_lahir"
                },
                {
                    type: "select",
                    label: "Apa jenis kelamin Anda, laki-laki atau perempuan",
                    field: "jenis_kelamin",
                    options: {
                        L: "Laki-laki",
                        P: "Perempuan"
                    }
                },
                {
                    type: "text",
                    label: "Apa kewarganegaraan Anda",
                    field: "kewarganegaraan"
                },
                {
                    type: "text",
                    label: "Apa agama Anda",
                    field: "agama"
                },
                {
                    type: "text",
                    label: "Apa pekerjaan Anda saat ini",
                    field: "pekerjaan"
                },
                {
                    type: "textarea",
                    label: "Di mana alamat tempat tinggal Anda saat ini",
                    field: "alamat"
                },
                {
                    type: "textarea",
                    label: "Apa keperluan pengajuan surat ini",
                    field: "keperluan"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan? Jika tidak ada, katakan tidak ada",
                    field: "keterangan_lain"
                }
            ]
        };

        const validators = {
            nik: () => true, // NIK ditangani khusus
            nama: v => /^[a-zA-Z\s]+$/.test(v) ? true : "Nama tidak boleh mengandung angka",
            tempat_lahir: v => v.length > 2 ? true : "Tempat lahir terlalu pendek",
            tanggal_lahir: v => /^\d{4}-\d{2}-\d{2}$/.test(v) ? true : "Tanggal tidak valid",
            kewarganegaraan: v => v.length > 2 ? true : "Kewarganegaraan wajib diisi",
            agama: v => v.length > 2 ? true : "Agama wajib diisi",
            pekerjaan: v => v.length > 2 ? true : "Pekerjaan wajib diisi",
            alamat: v => v.length > 5 ? true : "Alamat terlalu pendek",
            keperluan: v => v.length > 5 ? true : "Keperluan wajib diisi"
        };

        const requiredFields = [
            "nik", "nama", "tempat_lahir", "tanggal_lahir",
            "jenis_kelamin", "kewarganegaraan", "agama",
            "pekerjaan", "alamat", "keperluan"
        ];

        /* ============================================================
           UTIL
        ============================================================ */
        function normalize(t) {
            return (t || "").toLowerCase().replace(/[^a-z0-9 ]/g, '').trim();
        }

        function normalizeEmpty(text) {
            if (!text) return null;
            const t = text.trim().toLowerCase();
            if (t === "" || t === "kosong" || t === "tidak ada" || t === "lewati") return null;
            return text;
        }

        function extractDigits(text) {
            if (!text) return "";

            const direct = (text + "").replace(/\D/g, ""); // hasil kalau speech keluar angka "3319 0120..."
            const words = wordsToDigitsID(text); // hasil kalau speech keluar kata "tiga tiga..."

            // Pilih kandidat yang paling panjang (paling mungkin lengkap)
            let pick = direct.length >= words.length ? direct : words;

            // Guard: kalau pick masih pendek tapi salah satunya lumayan panjang, pakai yang panjang
            if (pick.length < 8 && (direct.length >= 8 || words.length >= 8)) {
                pick = direct.length >= words.length ? direct : words;
            }

            return pick;
        }

        function pickNik16(rawDigits) {
            const s = (rawDigits || "").replace(/\D/g, "");

            if (s.length === 16) return s;

            // kalau lebih dari 16 digit, ambil kandidat 16 digit yang paling "masuk akal"
            // strategy: coba cari window 16 digit yang paling sering muncul / stabil
            // paling simpel & efektif: ambil 16 digit TERAKHIR (biasanya angka tambahan kebawa di depan)
            if (s.length > 16) return s.slice(-16);

            // kalau kurang dari 16, balikin apa adanya (nanti divalidasi)
            return s;
        }
        async function repeatQuestionFlow() {
            if (isPaused || isSpeaking || isProcessingAnswer) return;

            if (awaitingNikConfirm) {
                await speak("Jawab ya atau tidak");
                return;
            }

            // ✅ ulang pertanyaan aman
            await askQuestionWithMicOff(true);

            resetSilenceTimer();
        }


        function resetSilenceTimer() {
            if (silenceTimer) clearTimeout(silenceTimer);

            silenceTimer = setTimeout(() => {
                repeatQuestionFlow();
            }, 7000); // 🔥 7 detik lebih enak dari 5 (biar ga cerewet)
        }


        function findBestMatch(text, options) {
            if (!options) return null;
            const spoken = normalize(text);

            let best = null;
            let bestScore = 0;

            Object.entries(options).forEach(([id, label]) => {
                const opt = normalize(label);
                let score = 0;

                if (spoken === opt) score = 100;
                else if (spoken.includes(opt) || opt.includes(spoken)) score = 80;
                else {
                    const words = spoken.split(" ");
                    words.forEach(w => {
                        if (opt.includes(w)) score = Math.max(score, 50);
                    });
                }

                if (score > bestScore) {
                    bestScore = score;
                    best = [id, label];
                }
            });

            return bestScore >= 50 ? best : null;
        }

        function normalizeDate(text) {
            if (!text) return null;
            text = text.toLowerCase().trim();
            const now = new Date();
            const thisYear = now.getFullYear();

            const bulan = {
                januari: "01",
                februari: "02",
                maret: "03",
                april: "04",
                mei: "05",
                juni: "06",
                juli: "07",
                agustus: "08",
                september: "09",
                oktober: "10",
                november: "11",
                desember: "12"
            };

            if (/^\d{4}-\d{2}-\d{2}$/.test(text)) return text;

            let p = text.split(" ");
            if (p.length >= 3 && bulan[p[1]] && /^\d{4}$/.test(p[2])) {
                return `${p[2]}-${bulan[p[1]]}-${p[0].padStart(2,"0")}`;
            }
            if (p.length === 2 && bulan[p[1]]) {
                return `${thisYear}-${bulan[p[1]]}-${p[0].padStart(2,"0")}`;
            }
            return null;
        }

        function wordsToDigitsID(text) {
            if (!text) return "";

            let t = (text + "").toLowerCase();

            // normalisasi umum
            t = t.replace(/-/g, " ");
            t = t.replace(/[^a-z0-9\s]/g, " "); // buang tanda baca
            t = t.replace(/\s+/g, " ").trim();

            // beberapa variasi yang sering muncul
            t = t
                .replace(/\bno+l\b/g, "nol") // "nolll" -> "nol"
                .replace(/\bkosong\b/g, "nol") // "kosong" -> "nol"
                .replace(/\boh\b/g, "nol"); // "oh" kadang dianggap 0

            // mapping angka dasar
            const map = {
                "nol": "0",
                "zero": "0",
                "satu": "1",
                "dua": "2",
                "tiga": "3",
                "empat": "4",
                "lima": "5",
                "enam": "6",
                "tujuh": "7",
                "delapan": "8",
                "sembilan": "9",
            };

            // tokenisasi
            const tokens = t.split(" ");

            // helper: ubah frasa bilangan Indonesia jadi digit (minimal yang sering kejadian)
            // - "sepuluh" 10
            // - "sebelas" 11
            // - "dua belas" 12
            // - dst sampai 19
            function parseTeen(i) {
                const a = tokens[i];
                const b = tokens[i + 1];
                const c = tokens[i + 2];

                if (a === "sepuluh") return {
                    val: "10",
                    step: 1
                };
                if (a === "sebelas") return {
                    val: "11",
                    step: 1
                };

                // "dua belas" -> 12, ... "sembilan belas" -> 19
                if (map[a] && b === "belas") return {
                    val: "1" + map[a],
                    step: 2
                };

                // kadang speech jadi "dua bel as" aneh → abaikan
                return null;
            }

            let out = "";

            for (let i = 0; i < tokens.length; i++) {
                const tk = tokens[i];

                // kalau sudah digit langsung ambil
                if (/^\d+$/.test(tk)) {
                    out += tk;
                    continue;
                }

                // teen / belasan
                const teen = parseTeen(i);
                if (teen) {
                    out += teen.val;
                    i += teen.step - 1;
                    continue;
                }

                // angka satuan
                if (map[tk]) {
                    out += map[tk];
                    continue;
                }

                // kalau bukan angka, skip
            }

            return out.replace(/\D/g, "");
        }


        // opsional: biar tampilan enak dibaca 4-4-4-4
        function formatNik(nik) {
            const s = (nik || "").replace(/\D/g, "").slice(0, 16);
            return s.replace(/(\d{4})(?=\d)/g, "$1 ");
        }

        function resetToListening() {
            const input = document.getElementById("inputAnswer");
            if (input) input.value = "";
            document.getElementById("voice-status").innerText = "Mendengarkan...";
            resetSilenceTimer();
        }

        function setAnswerStatus(rawText, ok, msg) {
            const input = document.getElementById("inputAnswer");
            if (input) input.value = rawText || "";

            document.getElementById("voice-status").innerText = msg || (ok ? "✅ Benar" : "❌ Salah");

            if (input) {
                input.classList.remove("border-green-500", "border-red-500", "ring-2", "ring-green-200", "ring-red-200");
                input.classList.add("ring-2", ok ? "border-green-500" : "border-red-500", ok ? "ring-green-200" :
                    "ring-red-200");
            }
        }

        async function goNextQuestionFlow() {
            step++;
            updateQuestionCounter();

            if (step >= (questions[currentModul] || []).length) {
                finishModul();
                return;
            }

            renderQuestion();

            document.getElementById("voice-status").innerText = "Menyiapkan pertanyaan...";
            await new Promise(r => setTimeout(r, 150));
            await speakQuestion();

            resetToListening();
        }

        /* ============================================================
           TTS
        ============================================================ */
        function speak(text) {
            return new Promise(resolve => {
                if (!text) return resolve();

                isSpeaking = true;
                ignoreSpeechInput = true; // ✅ blok input SR saat TTS
                ttsCooldownUntil = Date.now() + 1200; // ✅ anti-echo setelah TTS

                if (recognition) {
                    try {
                        recognition.onend = null;
                    } catch (e) {}
                    try {
                        recognition.stop();
                    } catch (e) {}
                }

                const u = new SpeechSynthesisUtterance(text);
                u.lang = "id-ID";

                u.onend = () => {
                    isSpeaking = false;

                    // ✅ tunggu sebentar biar suara TTS benar-benar hilang
                    setTimeout(() => {
                        ignoreSpeechInput = false;

                        if (!isPaused && allowListen) {
                            try {
                                recognition.start();
                            } catch (e) {}
                        }

                        resetSilenceTimer();
                        resolve();
                    }, 700);
                };

                speechSynthesis.cancel(); // optional biar tidak numpuk
                speechSynthesis.speak(u);
            });
        }


        function isEchoOfQuestion(transcript) {
            const q = questions[currentModul][step];
            if (!q) return false;

            const a = normalize(transcript);
            const b = normalize(q.label);

            // kalau sama persis / mengandung sebagian besar, anggap echo
            return a === b || (a.length > 8 && b.includes(a)) || (b.length > 8 && a.includes(b));
        }

        async function speakQuestion() {
            const q = questions[currentModul][step];
            if (!q) return;
            await speak(q.label);
        }

        function updateQuestionCounter() {
            const total = questions[currentModul].length;
            document.getElementById('currentQ').innerText = step + 1;
            document.getElementById('totalQ').innerText = total;
        }

        /* ============================================================
           UI / FLOW
        ============================================================ */
        function updateProgressSteps() {
            const c = document.getElementById('progressSteps');
            c.innerHTML = '';

            modules.forEach((m, i) => {
                if (i > 0) {
                    const l = document.createElement('div');
                    l.className =
                        `h-0.5 w-12 self-center rounded-full ${modulStatus[m.id] === 'completed' ? 'bg-blue-600' : 'bg-gray-300'}`;
                    c.appendChild(l);
                }

                const d = document.createElement('div');
                d.className = 'flex items-center cursor-pointer';
                d.onclick = () => switchModul(m.id);

                const circle = document.createElement('div');
                circle.className = `w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${
      modulStatus[m.id] === 'completed' ? 'bg-blue-600' :
      modulStatus[m.id] === 'active' ? 'bg-green-600' : 'bg-gray-300 text-gray-600'
    }`;
                circle.textContent = m.id;

                const txt = document.createElement('div');
                txt.className = 'ml-2 text-left';
                txt.innerHTML = `
      <div class="font-medium text-sm">${m.name}</div>
      <div class="text-xs ${
        modulStatus[m.id] === 'completed' ? 'text-blue-600' :
        modulStatus[m.id] === 'active' ? 'text-green-600' : 'text-gray-500'
      }">
        ${modulStatus[m.id] === 'completed' ? 'Selesai' : modulStatus[m.id] === 'active' ? 'Aktif' : 'Belum'}
      </div>
    `;

                d.appendChild(circle);
                d.appendChild(txt);
                c.appendChild(d);
            });
        }

        function updateModulTitle() {
            const m = modules.find(x => x.id === currentModul);
            document.getElementById('modulTitle').innerText = "Input " + m.name + " via Suara";
        }

        function finishModul() {
            modulStatus[currentModul] = 'completed';
            stepByModul[currentModul] = step;

            updateProgressSteps();
            stopListening();

            const modul = modules.find(m => m.id === currentModul);

            if (reviewableModules.includes(currentModul)) {
                openReviewForm();
                return;
            }

            document.getElementById("voice-status").innerText = `${modul.name} selesai`;
            document.getElementById("quizArea").innerHTML = `
    <div class="text-center space-y-3">
      <div class="text-green-600 text-xl font-semibold">${modul.name} selesai ✔</div>
      <div class="text-gray-500 text-sm">Silakan pilih modul lain di bagian atas</div>
    </div>
  `;
        }

        function switchModul(id) {
            stopListening();
            stepByModul[currentModul] = step;

            Object.keys(modulStatus).forEach(k => {
                if (modulStatus[k] === 'active') modulStatus[k] = 'pending';
            });
            if (modulStatus[id] !== 'completed') modulStatus[id] = 'active';

            currentModul = id;
            step = stepByModul[id] || 0;

            updateModulTitle();
            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('inputArea').classList.remove('hidden');

            renderQuestion();
            updateProgressSteps();
        }

        function renderQuestion() {
            if (isRendering) return;
            isRendering = true;

            const q = questions[currentModul][step];
            if (!q) {
                isRendering = false;
                return finishModul();
            }

            let h = `<div class="flex flex-col items-center space-y-4 mb-6">
    <h3 class="text-xl font-semibold text-gray-800 text-center">${q.label}</h3>`;

            if (q.type === "select") {
                h += `<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-3xl mx-auto">`;
                Object.entries(q.options || {}).forEach(([id, n]) => {
                    h += `<div class="option-card" data-text="${n}">${n}</div>`;
                });
                h += `</div>`;
            } else {
                h += `<div class="w-full max-w-md">
      <input id="inputAnswer" readonly
        class="w-full border border-gray-300 rounded-xl p-4 text-center text-lg bg-white shadow-sm"
        placeholder="Jawaban muncul di sini...">
    </div>`;
            }

            h += `</div>`;

            const qa = document.getElementById("quizArea");
            qa.innerHTML = h;

            document.querySelectorAll('.option-card').forEach(c => {
                c.onclick = () => processVoiceAnswer(c.innerText);
            });

            requestAnimationFrame(() => {
                isRendering = false;
            });
        }

        function openReviewForm() {
            stopListening();
            document.getElementById('inputArea').classList.add('hidden');
            document.getElementById('reviewForm').classList.remove('hidden');

            const c = document.getElementById('reviewFields');
            c.innerHTML = "";

            questions[1].forEach(q => {
                const val = answers[q.field] || "";
                let input = "";

                if (q.type === "select") {
                    input = `<select name="${q.field}" class="w-full border rounded p-2">`;
                    Object.entries(q.options || {}).forEach(([id, label]) => {
                        const sel = val == id ? "selected" : "";
                        input += `<option value="${id}" ${sel}>${label}</option>`;
                    });
                    input += `</select>`;
                } else if (q.type === "textarea") {
                    input = `<textarea name="${q.field}" class="w-full border rounded p-2">${val}</textarea>`;
                } else {
                    input = `<input name="${q.field}" value="${val}" class="w-full border rounded p-2"/>`;
                }

                c.innerHTML += `
      <div>
        <label class="text-gray-600 text-xs">${q.label}</label>
        ${input}
      </div>
    `;
            });
        }

        /* ============================================================
           PROCESS
        ============================================================ */
        async function processVoiceAnswer(text) {
            if (isProcessingAnswer) return;
            isProcessingAnswer = true;
            clearTimeout(silenceTimer);
            silenceTimer = null;
            try {
                // ambil pertanyaan aktif (bisa berubah setelah step lompat)
                const q = questions[currentModul][step];

                // ===============================
                // MODE KONFIRMASI NIK (YA/TIDAK)
                // ===============================
                if (awaitingNikConfirm) {
                    const t = normalizeEmpty(text);
                    if (t === null) {
                        await speak("Jawab ya atau tidak");
                        return;
                    }

                    // bersihin lastTranscript supaya tidak ngeblok "ya" yang sama
                    lastTranscript = "";

                    if (isYes(t)) {
                        answers["nama"] = nikData?.nama || "";
                        answers["tempat_lahir"] = nikData?.tempat_lahir || "";
                        answers["tanggal_lahir"] = nikData?.tanggal_lahir || "";
                        answers["jenis_kelamin"] = nikData?.jenis_kelamin || "";
                        answers["kewarganegaraan"] = nikData?.kewarganegaraan || "INDONESIA";
                        answers["agama"] = nikData?.agama || "";

                        nikFound = true;
                        awaitingNikConfirm = false;

                        // ✅ reset state NIK supaya tidak kebaca NIK lagi
                        nikDigitsLive = "";
                        nikLastDigits = "";
                        nikPauseCount = 0;
                        if (nikTimer) {
                            clearTimeout(nikTimer);
                            nikTimer = null;
                        }

                        // lompat ke pekerjaan
                        const idx = indexOfField(currentModul, "pekerjaan");
                        step = (idx >= 0) ? idx : step + 1;

                        updateQuestionCounter();
                        renderQuestion();

                        document.getElementById("voice-status").innerText = "Menyiapkan pertanyaan...";
                        await new Promise(r => setTimeout(r, 150));

                        await speak("Baik. Lanjut ke pekerjaan.");
                        await speakQuestion();

                        // ✅ ini yang bikin "ya" hilang & placeholder balik
                        resetToListening();

                        return;

                    }

                    if (isNo(t)) {
                        nikFound = false;
                        nikData = null;
                        awaitingNikConfirm = false;

                        // reset state nik
                        nikDigitsLive = "";
                        nikLastDigits = "";
                        nikPauseCount = 0;
                        if (nikTimer) {
                            clearTimeout(nikTimer);
                            nikTimer = null;
                        }

                        await speak("Baik, kita isi manual.");
                        step++; // lanjut ke nama
                        updateQuestionCounter();
                        renderQuestion();

                        document.getElementById("voice-status").innerText = "Menyiapkan pertanyaan...";
                        await new Promise(r => setTimeout(r, 150));
                        await speakQuestion();

                        // ✅ clear "tidak" / teks lama
                        resetToListening();

                        return;

                    }

                    await speak("Jawab ya atau tidak");
                    return;
                }

                // ===============================
                // NORMALISASI kosong / lewati
                // ===============================
                text = normalizeEmpty(text);

                if (text === null) {
                    if (requiredFields.includes(q.field)) {
                        await speak("Data ini wajib diisi");
                        return;
                    }

                    answers[q.field] = null;
                    await speak("Dikosongkan");

                    step++;
                    updateQuestionCounter();

                    if (step < questions[currentModul].length) {
                        renderQuestion();
                        await new Promise(r => setTimeout(r, 150));
                        allowListen = true;
                        await speakQuestion();
                    } else {
                        finishModul();
                    }
                    return;
                }

                // ===============================
                // KHUSUS NIK (dipanggil dari debounce)
                // ===============================
                if (q.field === "nik") {
                    let digits = extractDigits(text);
                    digits = pickNik16(digits);
                    if (digits.length !== 16) {
                        setAnswerStatus(formatNik(digits), false, `❌ NIK harus 16 digit (terbaca ${digits.length})`);
                        await speak(`NIK harus 16 digit. Sekarang terbaca ${digits.length} digit. Silakan ulangi.`);
                        resetToListening();
                        return;
                    }

                    answers["nik"] = digits;
                    document.getElementById("voice-status").innerText = `Cek NIK: ${formatNik(digits)}`;

                    nikDigitsLive = "";
                    nikLastDigits = "";
                    nikPauseCount = 0;
                    if (nikTimer) {
                        clearTimeout(nikTimer);
                        nikTimer = null;
                    }

                    await speak("Sebentar, saya cek data NIK.");

                    let result = null;
                    try {
                        result = await fetchNikData(digits);
                    } catch (err) {
                        console.error("fetchNikData error:", err);
                        await speak("Koneksi bermasalah saat cek NIK. Kita isi manual.");
                        step++;
                        updateQuestionCounter();
                        renderQuestion();
                        await new Promise(r => setTimeout(r, 150));
                        await speakQuestion();
                        return;
                    }

                    if (result && result.found && result.data) {
                        nikData = result.data;

                        const jk = (nikData.jenis_kelamin || "").toUpperCase();
                        const sapaan = jk === "P" ? "Ibu" : (jk === "L" ? "Bapak" : "Bapak atau Ibu");

                        awaitingNikConfirm = true;
                        lastTranscript = "";

                        await speak(
                            `Data ditemukan atas nama ${sapaan} ${nikData.nama}. Apakah benar? Jawab ya atau tidak.`
                        );
                        return;
                    }

                    setAnswerStatus(formatNik(digits), false, "❌ NIK tidak ditemukan");
                    await speak("Data NIK tidak ditemukan. Kita isi manual.");
                    await goNextQuestionFlow(); // lanjut ke pertanyaan berikutnya (nama)
                    return;

                }

                // ===============================
                // NORMAL FLOW selain NIK
                // ===============================
                let value = text;

                if (q.type === "date") {
                    const d = normalizeDate(text);
                    if (!d) {
                        await speak("Format tanggal tidak valid, ulangi");
                        return;
                    }
                    value = d;
                }

                if (q.type === "select") {
                    const m = findBestMatch(text, q.options);
                    if (!m) {
                        await speak("Tidak dikenali, ulangi");
                        return;
                    }
                    value = m[0];
                }

                if (validators[q.field]) {
                    const res = validators[q.field](value);
                    if (res !== true) {
                        await speak(res);
                        return;
                    }
                }

                answers[q.field] = value;

                // tampilkan status benar
                setAnswerStatus(text, true, "✅ Jawaban diterima");
                await new Promise(r => setTimeout(r, 500));

                lastTranscript = "";
                await goNextQuestionFlow();
                return;

            } finally {
                setTimeout(() => isProcessingAnswer = false, 50);
            }
        }
        async function askQuestionWithMicOff(repeat = false) {
            if (!recognition) return;

            // ambil pertanyaan aktif SEKARANG
            const q = questions[currentModul]?.[step];
            if (!q) return;

            // matiin mic dulu
            try {
                recognition.abort();
            } catch (e) {}

            // jeda biar stop bener
            await new Promise(r => setTimeout(r, 120));

            if (repeat) {
                await speak("Saya ulangi pertanyaannya");
                await speak(q.label); // ✅ sekarang q sudah ada
            } else {
                await speak(q.label);
            }
        }

        /* ============================================================
           VOICE
        ============================================================ */
        function startListening() {
            if (!isPaused) return;

            isPaused = false;
            isListening = true;

            // UI tombol mic
            document.getElementById('recordBtn').classList.add('recording');
            document.getElementById('recordIcon').innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
      d="M10 9h4v6h-4z" fill="white"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
      d="M12 21a9 9 0 100-18 9 9 0 000 18z" stroke="white" fill="none"/>
  `;

            document.getElementById('visualizer').classList.add('show');
            document.getElementById('visualizerPlaceholder').classList.add('hide');

            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SR();
            recognition.lang = "id-ID";
            recognition.continuous = true;
            recognition.interimResults = true;

            recognition.onresult = e => {
                const r = e.results[e.results.length - 1];
                const text = (r[0].transcript || "").trim();
                if (!text) return;

                // ✅ blok input kalau TTS sedang/baru selesai (hindari "jawab sendiri")
                if (typeof ignoreSpeechInput !== "undefined" && ignoreSpeechInput) return;
                if (Date.now() < ttsCooldownUntil) return;

                // ✅ reset timer diam HANYA kalau ini input yang boleh diproses
                resetSilenceTimer();

                // =========================================
                // 1) MODE KONFIRMASI NIK (YA/TIDAK)
                // =========================================
                if (awaitingNikConfirm) {
                    // boleh tampilkan interim di voice-status biar user lihat
                    document.getElementById("voice-status").innerText = text;

                    // proses hanya saat final
                    if (!r.isFinal) return;

                    // tampilkan jawaban final user di input
                    const input = document.getElementById("inputAnswer");
                    if (input) input.value = text;

                    document.getElementById("voice-status").innerText = "Mendengarkan...";

                    // anti dobel
                    if (text === lastTranscript) return;
                    lastTranscript = text;

                    processVoiceAnswer(text);
                    return;
                }

                const q = questions[currentModul][step];

                // =========================================
                // 2) KHUSUS NIK: butuh INTERIM + DEBOUNCE
                // =========================================
                if (q && q.field === "nik") {
                    const digits = extractDigits(text);

                    if (digits) {
                        const candidate = pickNik16(digits);

                        // simpan kandidat paling panjang/stabil
                        if (candidate.length >= nikDigitsLive.length) {
                            nikDigitsLive = candidate;
                        } else {
                            const prefixSame = nikDigitsLive.slice(0, 4) === candidate.slice(0, 4);
                            if (!prefixSame) nikDigitsLive = candidate;
                        }

                        // tampilkan progress NIK di voice-status
                        document.getElementById("voice-status").innerText = formatNik(nikDigitsLive);
                    }

                    // debounce timer (hanya submit sekali saat jeda)
                    if (nikTimer) clearTimeout(nikTimer);

                    nikTimer = setTimeout(async () => {
                        if (awaitingNikConfirm) return;

                        if (nikDigitsLive.length === 16) {
                            const fullNik = nikDigitsLive;

                            // reset state NIK live
                            nikDigitsLive = "";
                            nikLastDigits = "";
                            nikPauseCount = 0;

                            // anti dobel
                            lastTranscript = "";

                            // ✅ submit sekali di sini
                            await processVoiceAnswer(fullNik);
                            return;
                        }

                        nikPauseCount++;
                        if (nikPauseCount < NIK_WARN_AFTER) return;

                        await speak(`NIK belum lengkap. Masih kurang ${16 - nikDigitsLive.length} digit.`);
                    }, NIK_DELAY);

                    return;
                }

                // =========================================
                // 3) SELAIN NIK: tampilkan live, submit saat FINAL
                // =========================================

                // (opsional) echo pertanyaan: cek hanya saat FINAL
                if (r.isFinal && isEchoOfQuestion(text)) return;

                // tampilkan live transcript di input
                const input = document.getElementById("inputAnswer");
                if (input) input.value = text;

                document.getElementById("voice-status").innerText = "Mendengarkan...";

                // submit hanya saat final
                if (!r.isFinal) return;

                if (text === lastTranscript) return;
                lastTranscript = text;

                processVoiceAnswer(text);
            };

            recognition.onend = () => {
                // restart SR kalau masih mode listen dan tidak sedang speak/proses
                if (!isPaused && !isProcessingAnswer && !isSpeaking && allowListen) {
                    setTimeout(() => {
                        try {
                            recognition.start();
                        } catch (e) {}
                    }, 300);

                    document.getElementById('recordBtn').classList.add('recording');
                    document.getElementById('recordIcon').innerHTML = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
          d="M10 9h4v6h-4z" fill="white"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
          d="M12 21a9 9 0 100-18 9 9 0 000 18z" stroke="white" fill="none"/>
      `;
                }
            };

            resetSilenceTimer();
            recognition.start();
            initVisualizer();

            // tanya pertama kali
            setTimeout(() => {
                speakQuestion();
            }, 300);
        }


        function stopListening() {
            isPaused = true;
            isListening = false;

            if (silenceTimer) {
                clearTimeout(silenceTimer);
                silenceTimer = null;
            }
            if (nikTimer) {
                clearTimeout(nikTimer);
                nikTimer = null;
            }

            // reset nik state
            nikDigitsLive = "";
            nikLastDigits = "";
            nikPauseCount = 0;

            if (recognition) {
                recognition.onend = null;
                recognition.stop();
                recognition = null;
            }

            if (audioContext) {
                audioContext.close();
                audioContext = null;
            }

            document.getElementById('recordBtn').classList.remove('recording');
            document.getElementById('visualizer').classList.remove('show');
            document.getElementById('visualizerPlaceholder').classList.remove('hide');
            document.getElementById('recordIcon').innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
  `;
        }

        /* ============================================================
           VISUALIZER
        ============================================================ */
        async function initVisualizer() {
            audioContext = new AudioContext();
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 256;
            canvas = document.getElementById('visualizer');
            ctx = canvas.getContext('2d');

            const resize = () => {
                canvas.width = canvas.offsetWidth * window.devicePixelRatio;
                canvas.height = canvas.offsetHeight * window.devicePixelRatio;
            };
            resize();
            window.addEventListener('resize', resize);

            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
            const src = audioContext.createMediaStreamSource(stream);
            src.connect(analyser);
            dataArray = new Uint8Array(analyser.frequencyBinCount);
            drawWaveVisualizer();
        }

        function drawWaveVisualizer() {
            if (!isListening) return;
            requestAnimationFrame(drawWaveVisualizer);
            analyser.getByteFrequencyData(dataArray);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / dataArray.length) * 2.8;
            let x = canvas.width * 0.1;

            for (let i = 0; i < dataArray.length; i++) {
                const h = (dataArray[i] / 255) * canvas.height * 0.8;
                const g = ctx.createLinearGradient(0, canvas.height / 2 - h / 2, 0, canvas.height / 2 + h / 2);
                g.addColorStop(0, "#14b8a6");
                g.addColorStop(1, "#4f46e5");
                ctx.fillStyle = g;
                ctx.roundRect(x, canvas.height / 2 - h / 2, barWidth * 0.7, h, 6).fill();
                x += barWidth + 2;
            }
        }

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

        /* ============================================================
           INIT
        ============================================================ */
        document.getElementById('recordBtn').onclick = () => {
            if (isPaused) startListening();
            else stopListening();
        };

        updateProgressSteps();
        updateModulTitle();
        updateQuestionCounter();
        renderQuestion();

        document.getElementById("saveModulBtn").onclick = async () => {
            const form = new FormData(document.getElementById("voiceForm"));
            form.append("modul", currentModul);

            questions[currentModul].forEach(q => {
                if (answers[q.field] !== undefined) form.append(q.field, answers[q.field]);
            });

            document.getElementById("loadingOverlay").classList.remove("hidden");

            const res = await fetch("{{ route('pelayanan.surat.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                },
                body: form
            });

            if (res.redirected) window.location.href = res.url;
            else alert("Gagal menyimpan data");

            document.getElementById("loadingOverlay").classList.add("hidden");
        };
    </script>

</x-app-layout>
