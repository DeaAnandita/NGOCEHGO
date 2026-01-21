<x-app-layout>
    @slot('progresspembangunan')
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
                Input Data Pembangunan via Suara
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
                <div id="reviewFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
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
                Proses penyimpanan modul pembangunan
            </p>
        </div>
    </div>
    <div id="successModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center animate-scaleIn">

            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Berhasil
            </h2>

            <p id="successText" class="text-gray-600 mb-6">
                Data berhasil disimpan
            </p>

            <button id="successOkBtn"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl text-lg font-semibold transition">
                OK
            </button>

        </div>
    </div>
    <div id="errorModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center animate-scaleIn">

            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Gagal Menyimpan
            </h2>

            <div id="errorText" class="text-left text-sm text-red-600 mb-6 max-h-40 overflow-y-auto"></div>

            <button id="errorOkBtn"
                class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl text-lg font-semibold transition">
                Tutup
            </button>

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

        @keyframes scaleIn {
            from {
                transform: scale(.85);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .animate-scaleIn {
            animation: scaleIn .25s ease-out;
        }
    </style>
    <script>
        /* ============================================================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   DATA
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ============================================================ */
        const masters = @json($masters);

        /* ============================================================
           STATE
        ============================================================ */
        let currentModul = 1;
        let lastTranscript = "";
        let isRendering = false;
        let step = 0;
        let answersByModul = {
            1: {},
            2: {},
            3: {}
        };
        let nikDigitsLive = ""; // kandidat terbaru (replace, bukan append)
        let nikTimer = null;
        let nikPauseCount = 0;

        const NIK_DELAY = 4200; // proses setelah diam 2.2 detik
        const NIK_WARN_AFTER = 2; // baru ngomong kurang digit setelah 2 kali jeda



        let modulStatus = {
            1: 'active',
            2: 'pending',
            3: 'pending',
        };
        let stepByModul = {
            1: 0,
            2: 0,
            3: 0,
        };



        /* ============================================================
           VOICE STATE
        ============================================================ */
        let recognition = null;
        let ignoreSpeechInput = false;
        let nikLocked = false;
        let allowListen = true;

        let isListening = false;
        let audioContext = null;
        let analyser = null;
        let dataArray = null;
        let canvas = null;
        let kegiatanIndex = null;
        let ctx = null;
        let isSpeaking = false;
        let isPaused = true
        let questionTimeout = null;
        let isProcessingAnswer = false;
        let micStream = null;
        const reviewableModules = [1, 2, 3];

        /* ============================================================
           MODUL
        ============================================================ */
        const modules = [{
                id: 1,
                name: "Proyek Pembangunan"
            },
            {
                id: 2,
                name: "Kader Pembangunan"
            },
            {
                id: 3,
                name: "Bantuan Pembangunan"
            }
        ];

        /* ============================================================
           QUESTIONS
        ============================================================ */
        const questions = {
            1: [

                {
                    type: "text",
                    label: "Sebutkan kode proyek",
                    field: "kdproyek",
                    validation: /^[A-Za-z0-9\-]+$/,
                    error: "Kode proyek tidak valid"
                },

                {
                    type: "date",
                    label: "Kapan tanggal proyek ini dicatat",
                    field: "proyek_tanggal"
                },

                {
                    type: "select",
                    label: "Kegiatan apa yang terkait dengan proyek ini",
                    field: "kdkegiatan",
                    options: masters.kegiatan
                },

                {
                    type: "select",
                    label: "Siapa pelaksana proyek ini",
                    field: "kdpelaksana",
                    options: masters.pelaksana
                },

                {
                    type: "select",
                    label: "Di mana lokasi proyek ini",
                    field: "kdlokasi",
                    options: masters.lokasi
                },

                {
                    type: "select",
                    label: "Sumber dana proyek ini dari mana",
                    field: "kdsumber",
                    options: masters.sumber
                },

                {
                    type: "text",
                    label: "Berapa nominal anggaran proyek ini",
                    field: "proyek_nominal"
                },

                {
                    type: "text",
                    label: "Apa manfaat dari proyek ini, jika tidak ada katakan tidak ada",
                    field: "proyek_manfaat"
                },

                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan proyek ini, jika tidak ada katakan tidak ada",
                    field: "proyek_keterangan"
                }

            ],
            2: [

                {
                    type: "text",
                    label: "Sebutkan kode kader",
                    field: "kdkader",
                    validation: /^\d+$/,
                    error: "Kode kader harus berupa angka"
                },

                {
                    type: "date",
                    label: "Kapan tanggal pencatatan kader ini",
                    field: "kader_tanggal"
                },

                {
                    type: "text",
                    label: "Sebutkan NIK penduduk kader",
                    field: "kdpenduduk",
                    validation: /^\d{16}$/,
                    error: "NIK harus 16 digit"
                },

                {
                    type: "select",
                    label: "Apa pendidikan terakhir kader",
                    field: "kdpendidikan",
                    options: masters.pendidikan
                },

                {
                    type: "select",
                    label: "Bidang apa yang dikuasai kader",
                    field: "kdbidang",
                    options: masters.bidang
                },

                {
                    type: "select",
                    label: "Apa status kader saat ini",
                    field: "kdstatuskader",
                    options: masters.status_kader
                },

                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan tentang kader ini, jika tidak ada katakan tidak ada",
                    field: "kader_keterangan"
                }

            ],
            3: [

                {
                    type: "text",
                    label: "Sebutkan nama bantuan",
                    field: "bantuan_nama",
                    validation: /^.{3,}$/,
                    error: "Nama bantuan minimal 3 karakter"
                },

                {
                    type: "select",
                    label: "Siapa sasaran dari bantuan ini",
                    field: "kdsasaran",
                    options: masters.sasaran
                },

                {
                    type: "select",
                    label: "Apa jenis bantuan ini",
                    field: "kdbantuan",
                    options: masters.bantuan
                },

                {
                    type: "select",
                    label: "Sumber dana bantuan ini dari mana",
                    field: "kdsumber",
                    options: masters.sumber
                },

                {
                    type: "date",
                    label: "Kapan tanggal mulai bantuan ini",
                    field: "bantuan_awal"
                },

                {
                    type: "date",
                    label: "Kapan tanggal akhir bantuan ini, jika tidak ada katakan tidak ada",
                    field: "bantuan_akhir"
                },

                {
                    type: "text",
                    label: "Berapa jumlah bantuan ini",
                    field: "bantuan_jumlah"
                }

            ],
        };

        /* ============================================================
           UTIL
        ============================================================ */
        function normalize(t) {
            return t.toLowerCase().replace(/[^a-z0-9 ]/g, '').trim();
        }

        function pushNikDigits(text) {
            if (nikLocked) return;

            const digits = extractDigits(text);
            if (!digits) return;

            const candidate = pickNik16(digits);

            // simpan kandidat yang paling panjang / paling stabil
            if (candidate.length >= nikDigitsLive.length) {
                nikDigitsLive = candidate;
            } else {
                // kalau kandidat baru beda prefix, anggap yang baru lebih bener
                const prefixSame = nikDigitsLive.slice(0, 4) === candidate.slice(0, 4);
                if (!prefixSame) nikDigitsLive = candidate;
            }

            // tampilkan live
            const pretty = formatNik(nikDigitsLive);
            setVoiceStatus(pretty);

            const input = document.getElementById("inputAnswer");
            if (input) input.value = pretty;

            // debounce
            if (nikTimer) clearTimeout(nikTimer);
            nikTimer = setTimeout(async () => {
                if (nikLocked) return;

                if (nikDigitsLive.length === 16) {
                    nikLocked = true;
                    const full = nikDigitsLive;

                    // reset state sebelum proses
                    nikDigitsLive = "";
                    nikPauseCount = 0;

                    await processVoiceAnswer(full); // submit sekali setelah diam
                    return;
                }

                nikPauseCount++;
                if (nikPauseCount >= NIK_WARN_AFTER) {
                    await speak(`NIK belum lengkap, masih kurang ${16 - nikDigitsLive.length} digit.`);
                }
            }, NIK_DELAY);
        }


        function resetNikBuffer() {
            nikDigitsLive = "";
            nikLocked = false;
            nikPauseCount = 0;
            if (nikTimer) {
                clearTimeout(nikTimer);
                nikTimer = null;
            }
        }

        function extractDigits(text) {
            if (!text) return "";
            return String(text).replace(/\D/g, "");
        }

        // ambil 16 digit paling masuk akal
        function pickNik16(rawDigits) {
            const s = (rawDigits || "").replace(/\D/g, "");
            if (s.length === 16) return s;
            if (s.length > 16) return s.slice(-16); // ambil 16 terakhir
            return s;
        }

        // optional: tampilan 4-4-4-4
        function formatNik(nik) {
            const s = String(nik || "").replace(/\D/g, "").slice(0, 16);
            return s.replace(/(.{4})/g, "$1 ").trim();
        }

        function findBestMatch(text, options) {
            if (!options) return null;

            const spoken = normalize(text);

            let best = null;
            let bestScore = 0;

            Object.entries(options).forEach(([id, label]) => {
                const opt = normalize(label);

                let score = 0;

                // 100 = exact
                if (spoken === opt) score = 100;

                // 80 = sebagian cocok (ketua umum vs ketua)
                else if (spoken.includes(opt) || opt.includes(spoken)) score = 80;

                // 50 = kata mirip (sekretaris vs sekretaris umum)
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

            // hanya terima kalau cukup yakin
            return bestScore >= 50 ? best : null;
        }

        function speak(text) {
            return new Promise(resolve => {
                if (!text) return resolve();

                isSpeaking = true;
                ignoreSpeechInput = true; // ✅

                if (recognition) {
                    recognition.onend = null;
                    try {
                        recognition.stop();
                    } catch (e) {}
                }

                const u = new SpeechSynthesisUtterance(text);
                u.lang = "id-ID";

                u.onend = () => {
                    isSpeaking = false;

                    // kasih jeda biar suara TTS benar-benar hilang
                    setTimeout(() => {
                        ignoreSpeechInput = false; // ✅
                        if (!isPaused) {
                            try {
                                recognition.start();
                            } catch (e) {}
                        }
                        resolve();
                    }, 600); // <- bisa 400-800
                };

                speechSynthesis.speak(u);
            });
        }

        async function speakQuestion() {
            const q = questions[currentModul][step];
            if (!q) return;

            let text = q.label;
            if (typeof q.label === "function") {
                text = q.label();
            }

            await speak(text);
        }


        function updateQuestionCounter() {
            const list = questions[currentModul] || [];
            const total = list.length || 0;

            document.getElementById('currentQ').innerText = total === 0 ? 0 : (step + 1);
            document.getElementById('totalQ').innerText = total;
        }
        /* ============================================================
           UI
        ============================================================ */
        function updateProgressSteps() {
            const c = document.getElementById('progressSteps');
            c.innerHTML = '';

            modules.forEach((m, i) => {
                if (i > 0) {
                    const l = document.createElement('div');
                    l.className = `h-0.5 w-12 self-center rounded-full ${
                modulStatus[m.id] === 'completed' ? 'bg-blue-600' : 'bg-gray-300'
            }`;
                    c.appendChild(l);
                }

                const d = document.createElement('div');
                d.className = 'flex items-center cursor-pointer';
                d.onclick = () => {
                    switchModul(m.id);
                };

                const circle = document.createElement('div');
                circle.className =
                    `w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${
                modulStatus[m.id] === 'completed'
                    ? 'bg-blue-600'
                    : modulStatus[m.id] === 'active'
                    ? 'bg-green-600'
                    : 'bg-gray-300 text-gray-600'
            }`;
                circle.textContent = m.id;

                const txt = document.createElement('div');
                txt.className = 'ml-2 text-left';
                txt.innerHTML = `
            <div class="font-medium text-sm">${m.name}</div>
            <div class="text-xs ${
                modulStatus[m.id] === 'completed'
                    ? 'text-blue-600'
                    : modulStatus[m.id] === 'active'
                    ? 'text-green-600'
                    : 'text-gray-500'
            }">
                ${
                    modulStatus[m.id] === 'completed'
                        ? 'Selesai'
                        : modulStatus[m.id] === 'active'
                        ? 'Aktif'
                        : 'Belum'
                }
            </div>
        `;

                d.appendChild(circle);
                d.appendChild(txt);
                c.appendChild(d);
            });
        }

        function finishModul() {
            modulStatus[currentModul] = 'completed';
            stepByModul[currentModul] = step;

            updateProgressSteps();
            stopListening();

            const modul = modules.find(m => m.id === currentModul);

            // Kalau modul ini butuh review manual
            if (reviewableModules.includes(currentModul)) {
                openReviewForm();
                return;
            }

            // Modul voice-only
            document.getElementById("voice-status").innerText =
                `${modul.name} selesai`;

            document.getElementById("quizArea").innerHTML = `
        <div class="text-center space-y-3">
            <div class="text-green-600 text-xl font-semibold">
                ${modul.name} selesai ✔
            </div>
            <div class="text-gray-500 text-sm">
                Silakan pilih modul lain di bagian atas
            </div>
        </div>
    `;

        }



        function buildKegiatanIndex() {
            kegiatanIndex = {};
            Object.entries(masters.kegiatan).forEach(([id, name]) => {
                const key = normalize(name);
                kegiatanIndex[key] = id;
            });
        }

        function switchModul(id) {
            stopListening();
            sisaKegiatan = null;
            stepByModul[currentModul] = step;

            Object.keys(modulStatus).forEach(k => {
                if (modulStatus[k] === 'active') modulStatus[k] = 'pending';
            });

            if (modulStatus[id] !== 'completed') {
                modulStatus[id] = 'active';
            }

            currentModul = id;
            step = stepByModul[id] || 0;

            updateModulTitle();

            document.getElementById('reviewForm').classList.add('hidden');
            document.getElementById('inputArea').classList.remove('hidden');

            updateProgressSteps();
            updateQuestionCounter();

            // Jangan render pertanyaan dulu
            showCurrentQuestion();

            document.getElementById("voice-status").innerText =
                "Tekan mic untuk mulai merekam...";
        }


        /* ============================================================
           RENDER
        ============================================================ */
        function renderQuestion() {
            if (isRendering) return;
            isRendering = true;

            const q = questions[currentModul][step];
            if (!q) {
                isRendering = false;
                return finishModul();
            }

            let labelText = typeof q.label === "function" ? q.label() : q.label;

            let h = `<div class="flex flex-col items-center space-y-4 mb-6">
    <h3 class="text-xl font-semibold text-gray-800 text-center">${labelText}</h3>`;


            if (q.type === "select") {
                h += `<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-3xl mx-auto">`;
                Object.entries(q.options || {}).forEach(([id, n]) => {
                    h += `<div class="option-card" data-text="${n}">${n}</div>`;
                });
                h += `</div>`;
            } else {
                h += `<div class="w-full max-w-md">
            <input id="inputAnswer" readonly class="w-full border border-gray-300 rounded-xl p-4 text-center text-lg bg-white shadow-sm"
            placeholder="Jawaban muncul di sini...">
        </div>`;
            }

            h += `</div>`;

            const qa = document.getElementById("quizArea");
            qa.innerHTML = "";
            qa.innerHTML = h;
            document.querySelectorAll('.option-card').forEach(c => {
                c.onclick = async () => {
                    hardStopInteraction(); // ✅ stop mic + timeout + tts
                    await processVoiceAnswer(c.innerText);
                };
            });


            // 🔥 pastikan browser commit DOM
            requestAnimationFrame(() => {
                isRendering = false;
            });
        }

        function startQuestionTimeout() {
            clearQuestionTimeout();

            questionTimeout = setTimeout(async () => {
                if (isSpeaking || isProcessingAnswer || isPaused) return;

                await speak("Saya ulangi pertanyaannya");
                await speakQuestion();
                startQuestionTimeout(); // set ulang
            }, 9000); // 9 detik
        }

        function hardStopInteraction() {
            clearQuestionTimeout();

            try {
                speechSynthesis.cancel();
            } catch (e) {}
            isSpeaking = false;
            ignoreSpeechInput = false;

            allowListen = false;

            if (recognition) {
                try {
                    recognition.onresult = null;
                } catch (e) {}
                try {
                    recognition.onend = null;
                } catch (e) {}
                try {
                    recognition.abort();
                } catch (e) {}
            }

            // optional: jangan matiin mic stream kalau kamu masih mau visualizer tetap hidup
            // tapi untuk "stack" parah, ini membantu:
            // if (micStream) { micStream.getTracks().forEach(t=>t.stop()); micStream=null; }
        }

        function clearQuestionTimeout() {
            if (questionTimeout) {
                clearTimeout(questionTimeout);
                questionTimeout = null;
            }
        }

        function normalizeDate(text) {
            if (!text) return null;

            text = text.toLowerCase().trim();

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

            // 1) Jika sudah format YYYY-MM-DD
            if (/^\d{4}-\d{2}-\d{2}$/.test(text)) return text;

            // 2) Format angka: 20251217
            if (/^\d{8}$/.test(text)) {
                const y = text.substring(0, 4);
                const m = text.substring(4, 6);
                const d = text.substring(6, 8);
                return `${y}-${m}-${d}`;
            }

            // 3) Format: 17 desember 2025
            const parts = text.split(" ");
            if (parts.length >= 3) {
                const day = parts[0].padStart(2, "0");
                const monthName = parts[1];
                const year = parts[2];

                if (bulan[monthName] && /^\d{4}$/.test(year)) {
                    return `${year}-${bulan[monthName]}-${day}`;
                }
            }

            return null;
        }

        function openReviewForm() {
            stopListening();

            document.getElementById('inputArea').classList.add('hidden');
            document.getElementById('reviewForm').classList.remove('hidden');

            const c = document.getElementById('reviewFields');
            c.innerHTML = "";

            const data = answersByModul[currentModul];

            const add = (label, input) => {
                c.innerHTML += `
            <div>
                <label class="text-sm font-medium">${label}</label>
                ${input}
            </div>
        `;
            };

            // =====================================================
            // =============== MODUL 1 : PROYEK ====================
            // =====================================================
            if (currentModul === 1) {

                add("Kode Proyek",
                    `<input name="kdproyek" value="${data.kdproyek || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );

                add("Tanggal Proyek",
                    `<input type="date" name="proyek_tanggal" value="${data.proyek_tanggal || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );

                add("Kegiatan",
                    buildSelect("kdkegiatan", masters.kegiatan, data.kdkegiatan)
                );

                add("Pelaksana",
                    buildSelect("kdpelaksana", masters.pelaksana, data.kdpelaksana)
                );

                add("Lokasi",
                    buildSelect("kdlokasi", masters.lokasi, data.kdlokasi)
                );

                add("Sumber Dana",
                    buildSelect("kdsumber", masters.sumber, data.kdsumber)
                );

                add("Nominal",
                    `<input name="proyek_nominal" value="${data.proyek_nominal || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );

                c.innerHTML += `
            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Manfaat</label>
                <input name="proyek_manfaat"
                    value="${data.proyek_manfaat || ''}"
                    class="w-full rounded-lg border-gray-300"/>
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Keterangan</label>
                <textarea name="proyek_keterangan"
                    class="w-full rounded-lg border-gray-300">${data.proyek_keterangan || ''}</textarea>
            </div>
        `;
            }

            // =====================================================
            // =============== MODUL 2 : KADER =====================
            // =====================================================
            if (currentModul === 2) {

                add("Kode Kader",
                    `<input name="kdkader"
             value="${data.kdkader || ''}"
             class="w-full rounded-lg border-gray-300"/>`
                );

                add("Tanggal Pencatatan",
                    `<input type="date" name="kader_tanggal"
             value="${data.kader_tanggal || ''}"
             class="w-full rounded-lg border-gray-300"/>`
                );

                add("NIK Penduduk",
                    `<input name="kdpenduduk"
             value="${data.kdpenduduk || ''}"
             class="w-full rounded-lg border-gray-300"/>`
                );

                add("Pendidikan",
                    buildSelect("kdpendidikan", masters.pendidikan, data.kdpendidikan)
                );

                add("Bidang",
                    buildSelect("kdbidang", masters.bidang, data.kdbidang)
                );

                add("Status Kader",
                    buildSelect("kdstatuskader", masters.status_kader, data.kdstatuskader)
                );

                c.innerHTML += `
            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Keterangan</label>
                <textarea name="kader_keterangan"
                    class="w-full rounded-lg border-gray-300">${data.kader_keterangan || ''}</textarea>
            </div>
        `;
            }

            // =====================================================
            // =============== MODUL 3 : BANTUAN ===================
            // =====================================================
            if (currentModul === 3) {

                c.innerHTML += `
            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Nama Bantuan</label>
                <input name="bantuan_nama"
                    value="${data.bantuan_nama || ''}"
                    class="w-full rounded-lg border-gray-300"/>
            </div>
        `;

                add("Sasaran",
                    buildSelect("kdsasaran", masters.sasaran, data.kdsasaran)
                );

                add("Jenis Bantuan",
                    buildSelect("kdbantuan", masters.bantuan, data.kdbantuan)
                );

                add("Sumber Dana",
                    buildSelect("kdsumber", masters.sumber, data.kdsumber)
                );

                add("Tanggal Mulai",
                    `<input type="date" name="bantuan_awal" value="${data.bantuan_awal || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );

                add("Tanggal Akhir",
                    `<input type="date" name="bantuan_akhir" value="${data.bantuan_akhir || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jumlah Bantuan",
                    `<input name="bantuan_jumlah" value="${data.bantuan_jumlah || ''}"
                class="w-full rounded-lg border-gray-300"/>`
                );
            }
        }



        /* ============================================================
           VOICE
        ============================================================ */
        function startListening() {
            if (!isPaused) return;

            isPaused = false;
            isListening = true;
            document.getElementById('recordBtn').classList.add('recording');
            // GANTI ICON MIC → STOP
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
            recognition.continuous = false;
            recognition.onresult = e => {
                const r = e.results[e.results.length - 1];
                const text = r[0].transcript.trim();

                // ✅ abaikan semua input yang berasal dari suara TTS
                if (ignoreSpeechInput) return;

                // voice-status = suara user
                setVoiceStatus(text);

                clearQuestionTimeout();

                // ✅ tambahan pengaman (kalau masih speaking)
                if (isSpeaking) return;

                const q = questions[currentModul][step];

                // 🔥 KHUSUS NIK
                if (q && q.field === "kdpenduduk") {
                    pushNikDigits(text);
                    return;
                }

                if (r.isFinal) {
                    lastTranscript = "";
                    processVoiceAnswer(text);
                }

            };


            recognition.onend = () => {
                if (!isPaused && !isProcessingAnswer && !isSpeaking && allowListen) {
                    try {
                        recognition.start();
                    } catch (e) {}

                    // paksa icon tetap STOP
                    document.getElementById('recordBtn').classList.add('recording');
                    document.getElementById('recordIcon').innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                d="M10 9h4v6h-4z" fill="white"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                d="M12 21a9 9 0 100-18 9 9 0 000 18z" stroke="white" fill="none"/>
        `;
                }
            };


            recognition.start();
            initVisualizer();
            setTimeout(async () => {
                await askQuestionWithMicOff();
            }, 300);


        }

        function setVoiceStatus(msg) {
            const el = document.getElementById("voice-status");
            if (el) el.innerText = msg || "";
        }

        function pauseMic() {
            allowListen = false;
            if (recognition) {
                try {
                    recognition.abort();
                } catch (e) {}
            }
        }

        function resumeMic() {
            allowListen = true;
            if (recognition && !isPaused && !isProcessingAnswer && !isSpeaking) {
                try {
                    recognition.start();
                } catch (e) {}
            }
        }

        // ✅ tanya dengan mic mati dulu, biar TTS gak ketangkep
        async function askQuestionWithMicOff() {
            pauseMic();
            setVoiceStatus("Menyiapkan pertanyaan...");
            await new Promise(r => setTimeout(r, 120));
            await speakQuestion();
            resumeMic();
            setVoiceStatus("Mendengarkan...");
            startQuestionTimeout();
        }

        function stopListening() {
            isPaused = true;
            isListening = false;

            // 🔥 STOP SPEECH RECOGNITION
            if (recognition) {
                recognition.onend = null;
                try {
                    recognition.abort();
                } catch (e) {}
                recognition = null;
            }

            // 🔥 STOP MEDIASTREAM (INI YANG MATIIN MIC CHROME)
            if (micStream) {
                micStream.getTracks().forEach(track => track.stop());
                micStream = null;
            }

            // 🔥 STOP AUDIO CONTEXT
            if (audioContext) {
                audioContext.close();
                audioContext = null;
            }

            // UI reset
            document.getElementById('recordBtn').classList.remove('recording');
            document.getElementById('visualizer').classList.remove('show');
            document.getElementById('visualizerPlaceholder').classList.remove('hide');
            document.getElementById('recordIcon').innerHTML = `
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
`;
        }


        /* ============================================================
           VISUALIZER (SAMA PERSIS SEPERTI PENDUDUK)
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
            micStream = stream; // 🔥 simpan
            const src = audioContext.createMediaStreamSource(stream);
            src.connect(analyser);
            dataArray = new Uint8Array(analyser.frequencyBinCount);
            drawWaveVisualizer();
        }

        function updateModulTitle() {
            const m = modules.find(x => x.id === currentModul);
            document.getElementById('modulTitle').innerText =
                "Input " + m.name + " via Suara";
        }

        function normalizeNullable(text) {
            if (!text) return null;

            const t = text.toLowerCase().trim();

            const kosong = [
                "tidak ada",
                "nggak ada",
                "ga ada",
                "gak ada",
                "kosong",
                "nihil",
                "tidak diisi",
                "tidak perlu",
                "skip",
                "lewati"
            ];

            if (kosong.includes(t)) {
                return null; // simpan sebagai NULL
            }

            return text;
        }

        function normalizeTime(text) {
            if (!text) return null;

            text = text.toLowerCase().replace("pukul", "").trim();

            // 9 → 09:00
            if (/^\d{1,2}$/.test(text)) {
                return text.padStart(2, "0") + ":00";
            }

            // 13 30 → 13:30
            if (/^\d{1,2}\s+\d{1,2}$/.test(text)) {
                const [h, m] = text.split(" ");
                return h.padStart(2, "0") + ":" + m.padStart(2, "0");
            }

            // 13.30 atau 13:30
            if (/^\d{1,2}[:.]\d{2}$/.test(text)) {
                return text.replace(".", ":");
            }

            return null;
        }

        function normalizeKeterangan(text) {
            if (!text) return "";

            const t = text.toLowerCase().trim();

            const kosong = [
                "tidak ada",
                "nggak ada",
                "ga ada",
                "gak ada",
                "tidak",
                "kosong",
                "nihil",
                "tidak ada keterangan"
            ];

            if (kosong.includes(t)) {
                return ""; // simpan kosong
            }

            return text; // simpan normal
        }

        function normalizeEmail(text) {
            if (!text) return "";

            return text
                .toLowerCase()
                .replace(/\s*(at|ad|et)\s*/g, "@") // "at" → "@"
                .replace(/\s*(dot|titik)\s*/g, ".") // "dot" → "."
                .replace(/\s+/g, "") // hapus spasi
                .trim();
        }

        function validateReviewForm() {



            return true;
        }

        function normalizeMoney(text) {
            if (!text) return "";

            let t = text.toLowerCase().trim();

            // 🔥 FIX INI
            if (/^[\d.,]+$/.test(t)) {
                return t.replace(/[.,]/g, "");
            }

            const numbers = {
                "nol": 0,
                "satu": 1,
                "dua": 2,
                "tiga": 3,
                "empat": 4,
                "lima": 5,
                "enam": 6,
                "tujuh": 7,
                "delapan": 8,
                "sembilan": 9,
                "sepuluh": 10,
                "sebelas": 11,
                "dua belas": 12,
                "tiga belas": 13,
                "empat belas": 14,
                "lima belas": 15,
                "enam belas": 16,
                "tujuh belas": 17,
                "delapan belas": 18,
                "sembilan belas": 19,
                "dua puluh": 20,
                "tiga puluh": 30,
                "empat puluh": 40,
                "lima puluh": 50,
                "enam puluh": 60,
                "tujuh puluh": 70,
                "delapan puluh": 80,
                "sembilan puluh": 90,
                "seratus": 100
            };

            const multipliers = {
                "ribu": 1000,
                "juta": 1000000,
                "miliar": 1000000000,
                "milyar": 1000000000,
                "triliun": 1000000000000
            };

            Object.entries(numbers)
                .sort((a, b) => b[0].length - a[0].length)
                .forEach(([k, v]) => {
                    t = t.replace(new RegExp("\\b" + k + "\\b", "gi"), v.toString());
                });

            let total = 0;

            Object.entries(multipliers).forEach(([k, v]) => {
                const r = new RegExp("(\\d+)\\s*" + k, "gi");
                t = t.replace(r, (m, n) => {
                    total += parseInt(n) * v;
                    return "";
                });
            });

            const rest = t.match(/\b\d+\b/g);
            if (rest) {
                rest.forEach(n => {
                    const v = parseInt(n);
                    if (v < 1000) total += v;
                });
            }

            return total.toString();
        }


        function normalizeSk(text) {
            if (!text) return "";

            let t = text.toLowerCase().trim();

            // ===========================
            // 1. Kata simbol → simbol
            // ===========================
            const symbols = {
                "garis miring": "/",
                "slash": "/",
                "miring": "/",
                "titik": ".",
                "strip": "-",
                "dash": "-",
                "minus": "-"
            };

            Object.entries(symbols).forEach(([k, v]) => {
                const r = new RegExp("\\b" + k + "\\b", "gi");
                t = t.replace(r, v);
            });

            // ===========================
            // 2. Kata angka → digit (full)
            // ===========================
            const angka = {
                "nol": 0,
                "satu": 1,
                "dua": 2,
                "tiga": 3,
                "empat": 4,
                "lima": 5,
                "enam": 6,
                "tujuh": 7,
                "delapan": 8,
                "sembilan": 9,
                "sepuluh": 10,
                "sebelas": 11,
                "dua belas": 12,
                "tiga belas": 13,
                "empat belas": 14,
                "lima belas": 15,
                "enam belas": 16,
                "tujuh belas": 17,
                "delapan belas": 18,
                "sembilan belas": 19,
                "dua puluh": 20,
                "tiga puluh": 30,
                "empat puluh": 40,
                "lima puluh": 50,
                "enam puluh": 60,
                "tujuh puluh": 70,
                "delapan puluh": 80,
                "sembilan puluh": 90,
                "seratus": 100,
                "seribu": 1000
            };

            Object.entries(angka)
                .sort((a, b) => b[0].length - a[0].length)
                .forEach(([k, v]) => {
                    const r = new RegExp("\\b" + k + "\\b", "gi");
                    t = t.replace(r, v.toString());
                });

            // ===========================
            // 3. Hitung angka gabungan
            // ===========================
            // contoh: "20 3" → "23"
            t = t.replace(/\b(\d+)\s+(\d+)\b/g, (m, a, b) => {
                if (a.length === 1 && b.length === 1) return a + b;
                if (a.length >= 2 && b.length === 1) return a + b;
                return m;
            });

            // ===========================
            // 4. Rapikan simbol & spasi
            // ===========================
            t = t.replace(/\s*([\/\.\-])\s*/g, "$1");
            t = t.replace(/\s+/g, " ").trim();

            return t.toUpperCase();
        }

        function resetToListening() {
            lastTranscript = "";
            const input = document.getElementById("inputAnswer");
            if (input) input.value = "";
            setVoiceStatus(isPaused ? "Tekan mic untuk mulai merekam..." : "Mendengarkan...");
        }

        async function processVoiceAnswer(text) {
            if (isProcessingAnswer) return;
            isProcessingAnswer = true;
            clearQuestionTimeout();

            try {
                const q = questions[currentModul][step];
                if (!q) throw "retry";

                let value = text;

                // ===============================
                // NULLABLE (tidak ada / skip)
                // ===============================
                value = normalizeNullable(value);

                if (value === null) {
                    await speak("Baik, dikosongkan");
                    answersByModul[currentModul][q.field] = null;
                    step++;
                    updateQuestionCounter();
                    finishOrNext();
                    return;
                }

                // ===============================
                // TEXTAREA / KETERANGAN
                // ===============================
                if (q.type === "textarea") {
                    value = normalizeKeterangan(text);
                }

                // ===============================
                // NOMINAL (4 juta → 4000000)
                // ===============================
                if (["proyek_nominal", "bantuan_jumlah"].includes(q.field)) {
                    value = normalizeMoney(text);

                    if (!value || Number(value) <= 0) {
                        await speak("Jumlah tidak valid, ulangi");
                        resetToListening();
                        throw "retry";
                    }
                }

                // ===============================
                // NIK — WAJIB 16 DIGIT
                // ===============================
                if (q.field === "kdpenduduk") {
                    value = text.replace(/\D/g, "");

                    if (value.length !== 16) {
                        // ❌ JANGAN speak ulang
                        // ❌ JANGAN resetToListening
                        throw "retry";
                    }

                    resetNikBuffer(); // ✅ clear setelah sukses
                }


                // ===============================
                // KODE KADER — ANGKA SAJA
                // ===============================
                if (q.field === "kdkader") {
                    value = text.replace(/\D/g, "");

                    if (!value) {
                        await speak("Kode kader harus berupa angka");
                        resetToListening();
                        throw "retry";
                    }
                }

                // ===============================
                // FIELD DENGAN MIRING / SLASH
                // ===============================
                if (["no_sk", "no_sp2d"].includes(q.field)) {
                    value = normalizeSk(text); // otomatis: miring → /
                }

                // ===============================
                // NO HP
                // ===============================
                if (q.field === "no_hp") {
                    value = normalizeSk(text).replace(/\D/g, "");

                    if (value.length < 10) {
                        await speak("Nomor handphone tidak valid");
                        resetToListening();
                        throw "retry";
                    }
                }

                // ===============================
                // NAMA — TIDAK BOLEH ADA ANGKA
                // ===============================
                if (["nama_lengkap", "judul_keputusan", "nama_kegiatan", "judul_agenda"].includes(q.field)) {
                    if (/\d/.test(value)) {
                        await speak("Nama tidak boleh mengandung angka");
                        resetToListening();
                        throw "retry";
                    }
                }

                // ===============================
                // JAM
                // ===============================
                if (q.field === "jam_mulai" || q.field === "jam_selesai") {
                    const t = normalizeTime(text);
                    if (!t && q.field === "jam_mulai") {
                        await speak("Format jam tidak valid, ulangi");
                        resetToListening();
                        throw "retry";
                    }
                    value = t;
                }

                // ===============================
                // EMAIL
                // ===============================
                if (q.type === "email") {
                    value = normalizeEmail(text);
                }

                // ===============================
                // TANGGAL
                // ===============================
                if (q.type === "date") {
                    const d = normalizeDate(text);
                    if (!d) {
                        await speak("Format tanggal tidak valid, ulangi");
                        resetToListening();
                        throw "retry";
                    }
                    value = d;
                }

                // ===============================
                // SIMPAN JAWABAN
                // ===============================
                answersByModul[currentModul][q.field] = value;

                const input = document.getElementById("inputAnswer");
                if (input) input.value = text;

                await new Promise(r => setTimeout(r, 400));

                step++;
                updateQuestionCounter();
                finishOrNext();

            } catch (e) {
                // retry flow
            } finally {
                isProcessingAnswer = false;
            }
        }

        // helper biar rapi
        function finishOrNext() {
            allowListen = false;

            if (step < questions[currentModul].length) {
                renderQuestion();
                setTimeout(async () => {
                    await askQuestionWithMicOff();
                }, 250);
            } else {
                finishModul();
            }
        }


        function updateProgressBar() {
            const total = questions[currentModul].length;
            const percent = Math.round((step / total) * 100);
            document.getElementById("progressBar").style.width = percent + "%";
        }

        function buildSelect(name, options, selected) {
            let h = `<select name="${name}" class="w-full rounded-lg border-gray-300">`;
            Object.entries(options).forEach(([id, label]) => {
                h += `<option value="${id}" ${id==selected?"selected":""}>${label}</option>`;
            });
            return h + `</select>`;
        }

        function showCurrentQuestion() {
            const q = questions[currentModul][step];
            if (!q) return;
            let labelText = typeof q.label === "function" ? q.label() : q.label;

            let h = `<div class="flex flex-col items-center space-y-4 mb-6">
    <h3 class="text-xl font-semibold text-gray-800 text-center">${labelText}</h3>`;
            if (q.type === "select") {
                h += `<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-3xl mx-auto">`;
                Object.entries(q.options || {}).forEach(([id, n]) => {
                    h += `<div class="option-card opacity-50 pointer-events-none">${n}</div>`;
                });
                h += `</div>`;
            } else {
                h += `<div class="w-full max-w-md">
            <input readonly
                class="w-full border border-gray-300 rounded-xl p-4 text-center text-lg bg-white shadow-sm"
                placeholder="Jawaban muncul di sini...">
        </div>`;
            }

            h += `</div>`;

            document.getElementById("quizArea").innerHTML = h;
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

        /* roundRect polyfill */
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
            if (isPaused) {
                startListening(); // 🎤 → ⏹
            } else {
                stopListening(); // ⏹ → 🎤
            }
        };
        updateProgressSteps();
        updateModulTitle();
        updateQuestionCounter();
        renderQuestion();
        document.getElementById("saveModulBtn").onclick = async () => {
            if (!validateReviewForm()) return;

            // === KHUSUS MODUL 5 ===

            const form = new FormData(document.getElementById("voiceForm"));
            form.append("modul", currentModul);

            document.getElementById("loadingOverlay").classList.remove("hidden");

            try {
                const res = await fetch("{{ route('voice.pembangunan.store-all') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                    },
                    body: form
                });

                const data = await res.json();

                if (!res.ok) {
                    let msg = "";

                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, errors]) => {
                            errors.forEach(err => {
                                msg += `<div class="error-item text-red-600">• ${err}</div>`;
                            });
                        });
                    } else {
                        msg = data.message || "Terjadi kesalahan";
                    }

                    document.getElementById("errorText").innerHTML = msg;
                    document.getElementById("errorModal").classList.remove("hidden");
                    return;
                }

                // ===== SUKSES =====
                const modul = modules.find(m => m.id === currentModul);

                document.getElementById("successText").innerText =
                    modul.name + " berhasil disimpan";

                document.getElementById("successModal").classList.remove("hidden");

                // reset
                step = 0;
                stepByModul[currentModul] = 0;
                answersByModul[currentModul] = {};

                modulStatus[currentModul] = "active";


                updateProgressSteps();
                updateQuestionCounter();
                stopListening();

            } catch (e) {
                alert("Terjadi kesalahan jaringan");
                console.error(e);
            } finally {
                document.getElementById("loadingOverlay").classList.add("hidden");
            }
        };

        document.getElementById("successOkBtn").onclick = () => {
            document.getElementById("successModal").classList.add("hidden");

            document.getElementById("reviewForm").classList.add("hidden");
            document.getElementById("inputArea").classList.remove("hidden");

            renderQuestion(); // kembali ke pertanyaan awal
            stopListening(); // mic tetap mati

            document.getElementById("voice-status").innerText =
                "Tekan mic untuk mulai merekam...";
        };
        document.getElementById("errorOkBtn").onclick = () => {
            document.getElementById("errorModal").classList.add("hidden");
        };
    </script>


</x-app-layout>
