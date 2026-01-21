<x-app-layout>
    @slot('progresskelembagaan')
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
        <div id="modalAnggaranItem" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl p-6 w-full max-w-md">

                <h3 class="font-bold mb-4">Tambah Kegiatan Anggaran</h3>

                <label class="text-sm">Kegiatan</label>
                <select id="item_kegiatan" class="w-full border rounded p-2 mb-3">
                    <option value="">-- Pilih --</option>
                    @foreach ($masters['keputusan'] ?? [] as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>

                <label class="text-sm">Sumber Dana</label>
                <select id="item_sumber" class="w-full border rounded p-2 mb-3">
                    @foreach ($masters['sumber_dana'] as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>

                <label class="text-sm">Nilai</label>
                <input id="item_nilai" type="number" class="w-full border rounded p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button onclick="closeTambahKegiatanAnggaran()" class="px-3 py-2 bg-gray-200 rounded">Batal</button>
                    <button onclick="simpanItemAnggaran()"
                        class="px-3 py-2 bg-green-600 text-white rounded">Tambah</button>
                </div>

            </div>
        </div>
        <div id="modalRealisasi" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl p-6 w-full max-w-md">

                <h3 class="font-bold mb-4">Tambah Realisasi Pengeluaran</h3>

                <label class="text-sm">Tanggal</label>
                <input id="item_tanggal" type="date" class="w-full border rounded p-2 mb-3">

                <label class="text-sm">Uraian</label>
                <input id="item_uraian" class="w-full border rounded p-2 mb-3">

                <label class="text-sm">Jumlah</label>
                <input id="item_jumlah" type="number" class="w-full border rounded p-2 mb-3">

                <label class="text-sm">Bukti Transfer</label>
                <input id="item_bukti" type="file" class="w-full border rounded p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button onclick="closeTambahRealisasi()" class="px-3 py-2 bg-gray-200 rounded">Batal</button>
                    <button onclick="simpanItemPencairan()" class="px-3 py-2 bg-green-600 text-white rounded">
                        Tambah
                    </button>
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
                Proses penyimpanan modul kelembagaan
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
           HELPERS
        ============================================================ */
        const getEl = (id) => document.getElementById(id);
        const setText = (id, txt) => {
            const el = getEl(id);
            if (el) el.innerText = txt;
        };
        const safeObj = (o) => (o && typeof o === "object" ? o : {});
        const safeNumber = (v) => {
            const n = Number(v);
            return Number.isFinite(n) ? n : 0;
        };

        /* ============================================================
           STATE
        ============================================================ */
        let currentModul = 1;
        let step = 0;
        let nikBuffer = "";
        let nikTimer = null;
        let suppressUntil = 0; // waktu sampai kapan hasil SR diabaikan
        let postSpeakDelay = 900;
        let answersByModul = {
            1: {},
            2: {},
            3: {},
            4: {},
            5: {},
            6: {},
            7: {}
        };

        let anggaranItems = [];
        let pencairanItems = [];

        let modulStatus = {
            1: 'active',
            2: 'pending',
            3: 'pending',
            4: 'pending',
            5: 'pending',
            6: 'pending'
        };
        let stepByModul = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0
        };

        const reviewableModules = [1, 2, 3, 4, 5, 6, 7];

        /* ============================================================
           VOICE STATE
        ============================================================ */
        let recognition = null;
        let isListening = false;
        let isSpeaking = false;
        let isPaused = true;
        let isProcessingAnswer = false;
        let allowListen = true;

        let questionTimeout = null;

        // visualizer
        let audioContext = null;
        let analyser = null;
        let dataArray = null;
        let canvas = null;
        let ctx = null;
        let micStream = null;
        let resizeBound = false;

        // modul 6 helper
        let kegiatanIndex = null;
        let sisaKegiatan = null;

        /* ============================================================
           MODUL
        ============================================================ */
        const modules = [{
                id: 1,
                name: "Pengurus Kelembagaan"
            },
            {
                id: 2,
                name: "Keputusan"
            },
            {
                id: 3,
                name: "Kegiatan Kelembagaan"
            },
            {
                id: 4,
                name: "Agenda Lembaga"
            },
            {
                id: 5,
                name: "Anggaran"
            },
            {
                id: 6,
                name: "Pencairan"
            },
        ];

        /* ============================================================
           QUESTIONS
        ============================================================ */
        const questions = {
            1: [{
                    type: "text",
                    label: "Silakan sebutkan nomor induk atau NIK pengurus",
                    field: "nomor_induk",
                    validation: /^\d{16}$/,
                    error: "NIK harus 16 digit"
                },
                {
                    type: "text",
                    label: "Siapa nama lengkap pengurus",
                    field: "nama_lengkap",
                    validation: /^[A-Za-z.\s]+$/,
                    error: "Nama hanya boleh huruf dan spasi"
                },
                {
                    type: "select",
                    label: "Apa jenis kelamin pengurus, laki laki atau perempuan",
                    field: "jenis_kelamin",
                    options: {
                        L: "Laki-laki",
                        P: "Perempuan"
                    }
                },
                {
                    type: "text",
                    label: "Sebutkan nomor handphone pengurus",
                    field: "no_hp",
                    validation: /^\d{10,15}$/,
                    error: "Nomor HP minimal 10 digit"
                },
                {
                    type: "email",
                    label: "Apa alamat email pengurus",
                    field: "email"
                },
                {
                    type: "textarea",
                    label: "Di mana alamat tempat tinggal pengurus",
                    field: "alamat"
                },

                {
                    type: "select",
                    label: "Apa jabatan pengurus di dalam lembaga",
                    field: "kdjabatan",
                    options: safeObj(masters?.jabatan)
                },
                {
                    type: "select",
                    label: "Di unit kerja mana pengurus bertugas",
                    field: "kdunit",
                    options: safeObj(masters?.unit)
                },
                {
                    type: "select",
                    label: "Periode awal kepengurusan dimulai pada tahun berapa",
                    field: "kdperiode",
                    options: safeObj(masters?.periode)
                },
                {
                    type: "select",
                    label: "Periode akhir kepengurusan berakhir pada tahun berapa",
                    field: "kdperiode_akhir",
                    options: safeObj(masters?.periode_akhir)
                },
                {
                    type: "select",
                    label: "Apa status pengurus dalam lembaga",
                    field: "kdstatus",
                    options: safeObj(masters?.status)
                },
                {
                    type: "select",
                    label: "Jenis surat keputusan yang digunakan",
                    field: "kdjenissk",
                    options: safeObj(masters?.jenis_sk)
                },

                {
                    type: "text",
                    label: "Sebutkan nomor surat keputusan",
                    field: "no_sk"
                },
                {
                    type: "date",
                    label: "Kapan tanggal surat keputusan diterbitkan",
                    field: "tanggal_sk"
                },

                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan? Jika tidak ada, katakan tidak ada",
                    field: "keterangan"
                }
            ],

            2: [{
                    type: "text",
                    label: "Sebutkan nomor surat keputusan",
                    field: "nomor_sk"
                },
                {
                    type: "text",
                    label: "Apa judul dari keputusan ini",
                    field: "judul_keputusan"
                },
                {
                    type: "select",
                    label: "Apa jenis keputusan ini",
                    field: "kdjenis",
                    options: safeObj(masters?.jenis)
                },
                {
                    type: "select",
                    label: "Unit mana yang mengeluarkan keputusan ini",
                    field: "kdunit",
                    options: safeObj(masters?.unit)
                },
                {
                    type: "select",
                    label: "Keputusan ini berlaku pada periode tahun berapa",
                    field: "kdperiode",
                    options: safeObj(masters?.periode)
                },
                {
                    type: "select",
                    label: "Siapa jabatan yang menetapkan keputusan ini",
                    field: "kdjabatan",
                    options: safeObj(masters?.jabatan)
                },
                {
                    type: "date",
                    label: "Kapan tanggal keputusan ini ditetapkan",
                    field: "tanggal_keputusan"
                },
                {
                    type: "select",
                    label: "Apa status keputusan ini",
                    field: "kdstatus",
                    options: safeObj(masters?.status)
                },
                {
                    type: "select",
                    label: "Apa metode penetapan keputusan ini",
                    field: "kdmetode",
                    options: safeObj(masters?.metode)
                },
            ],

            3: [{
                    type: "text",
                    label: "Sebutkan nama kegiatan",
                    field: "nama_kegiatan"
                },
                {
                    type: "select",
                    label: "Apa jenis kegiatan ini",
                    field: "kdjenis",
                    options: safeObj(masters?.jenis_kegiatan)
                },
                {
                    type: "text",
                    label: "Di mana lokasi kegiatan ini",
                    field: "lokasi"
                },
                {
                    type: "select",
                    label: "Unit mana yang melaksanakan kegiatan ini",
                    field: "kdunit",
                    options: safeObj(masters?.unit_keputusan)
                },
                {
                    type: "select",
                    label: "Kegiatan ini pada periode tahun berapa",
                    field: "kdperiode",
                    options: safeObj(masters?.periode)
                },
                {
                    type: "select",
                    label: "Apa status kegiatan ini",
                    field: "kdstatus",
                    options: safeObj(masters?.status_kegiatan)
                },
                {
                    type: "select",
                    label: "Sumber dana kegiatan ini dari mana",
                    field: "kdsumber",
                    options: safeObj(masters?.sumber_dana)
                },
                {
                    type: "text",
                    label: "Berapa pagu anggaran kegiatan ini",
                    field: "pagu_anggaran"
                },
                {
                    type: "date",
                    label: "Kapan kegiatan ini mulai",
                    field: "tgl_mulai"
                },
                {
                    type: "select",
                    label: "Berdasarkan keputusan apa kegiatan ini",
                    field: "keputusan_id",
                    options: safeObj(masters?.keputusan)
                },
            ],

            4: [{
                    type: "text",
                    label: "Apa judul agenda ini",
                    field: "judul_agenda"
                },
                {
                    type: "select",
                    label: "Apa jenis agenda ini",
                    field: "kdjenis",
                    options: safeObj(masters?.jenis_agenda)
                },
                {
                    type: "select",
                    label: "Unit mana yang menyelenggarakan agenda ini",
                    field: "kdunit",
                    options: safeObj(masters?.unit_keputusan)
                },
                {
                    type: "select",
                    label: "Apa status agenda ini",
                    field: "kdstatus",
                    options: safeObj(masters?.status_agenda)
                },
                {
                    type: "select",
                    label: "Di mana tempat agenda ini",
                    field: "kdtempat",
                    options: safeObj(masters?.tempat_agenda)
                },
                {
                    type: "select",
                    label: "Agenda ini pada periode tahun berapa",
                    field: "kdperiode",
                    options: safeObj(masters?.periode)
                },
                {
                    type: "date",
                    label: "Kapan tanggal agenda ini",
                    field: "tanggal"
                },
                {
                    type: "text",
                    label: "Jam mulai agenda (contoh jam 9 atau 13.30)",
                    field: "jam_mulai"
                },
                {
                    type: "text",
                    label: "Jam selesai agenda",
                    field: "jam_selesai"
                },
                {
                    type: "textarea",
                    label: "Uraian agenda (jika tidak ada, katakan 'tidak ada')",
                    field: "uraian_agenda"
                }
            ],

            5: [{
                    type: "select",
                    label: "Anggaran ini untuk unit apa",
                    field: "kdunit",
                    options: safeObj(masters?.unit_keputusan)
                },
                {
                    type: "select",
                    label: "Untuk periode tahun berapa",
                    field: "kdperiode",
                    options: safeObj(masters?.periode)
                },
                {
                    type: "select",
                    label: "Sumber dana anggaran ini dari mana",
                    field: "kdsumber",
                    options: safeObj(masters?.sumber_dana)
                },
                {
                    type: "text",
                    label: "Berapa total anggaran",
                    field: "total_anggaran"
                },
                {
                    type: "textarea",
                    label: "Keterangan anggaran, jika tidak ada katakan tidak ada",
                    field: "keterangan"
                }
            ],

            6: [{
                    type: "select",
                    label: "Pencairan ini untuk kegiatan apa",
                    field: "kegiatan_id",
                    options: safeObj(masters?.kegiatan)
                },
                {
                    type: "date",
                    label: "Tanggal pencairan",
                    field: "tanggal_cair"
                },
                {
                    type: "text",
                    label: () => (sisaKegiatan !== null && sisaKegiatan !== undefined) ?
                        `Sisa anggaran kegiatan ini ${Number(sisaKegiatan).toLocaleString()} rupiah. Berapa jumlah dana dicairkan` :
                        "Jumlah dana dicairkan",
                    field: "jumlah"
                },
                {
                    type: "text",
                    label: "Nomor SP2D",
                    field: "no_sp2d"
                }
            ]
        };

        /* ============================================================
           UTIL TEXT
        ============================================================ */
        function normalize(t) {
            return String(t || "").toLowerCase().replace(/[^a-z0-9 ]/g, '').trim();
        }

        function findBestMatch(text, options) {
            options = safeObj(options);
            const spoken = normalize(text);
            let best = null,
                bestScore = 0;

            Object.entries(options).forEach(([id, label]) => {
                const opt = normalize(label);
                let score = 0;

                if (spoken === opt) score = 100;
                else if (spoken.includes(opt) || opt.includes(spoken)) score = 80;
                else {
                    spoken.split(" ").forEach(w => {
                        if (w && opt.includes(w)) score = Math.max(score, 50);
                    });
                }

                if (score > bestScore) {
                    bestScore = score;
                    best = [id, label];
                }
            });

            return bestScore >= 50 ? best : null;
        }

        function resetNikBuffer() {
            nikBuffer = "";
            if (nikTimer) clearTimeout(nikTimer);
            nikTimer = null;
        }

        function pushNikDigits(transcript) {
            const digits = String(transcript || "").replace(/\D/g, "");
            if (digits) nikBuffer += digits;

            if (nikBuffer.length > 16) nikBuffer = nikBuffer.slice(0, 16);

            setText("voice-status", nikBuffer || transcript);

            if (nikBuffer.length === 16) {
                const nik = nikBuffer;
                resetNikBuffer();
                processVoiceAnswer(nik);
                return;
            }

            if (nikTimer) clearTimeout(nikTimer);
            nikTimer = setTimeout(async () => {
                if (nikBuffer.length !== 16) {
                    await speak(`NIK masih ${nikBuffer.length} digit. Ulangi sampai 16 digit.`);
                    resetNikBuffer();
                    resetToListening();
                }
            }, 1800);
        }
        /* ============================================================
           SPEAK
        ============================================================ */
        function speak(text) {
            return new Promise(resolve => {
                if (!text) return resolve();

                isSpeaking = true;
                allowListen = false;

                // kunci hasil SR selama TTS + cooldown
                suppressUntil = Date.now() + postSpeakDelay;

                // stop SR biar gak kebaca TTS
                if (recognition) {
                    try {
                        recognition.onend = null;
                    } catch (e) {}
                    try {
                        recognition.abort();
                    } catch (e) {} // ⬅️ penting: abort lebih “bersih” dari stop
                    recognition = null;
                }

                // pastikan tidak ada TTS lama nyangkut
                try {
                    speechSynthesis.cancel();
                } catch (e) {}

                const u = new SpeechSynthesisUtterance(text);
                u.lang = "id-ID";

                u.onend = () => {
                    isSpeaking = false;

                    // cooldown setelah TTS selesai
                    setTimeout(() => {
                        allowListen = true;

                        // restart SR kalau masih mode listening
                        if (!isPaused) {
                            try {
                                startRecognitionFresh(); // kita buat helper ini di bawah
                            } catch (e) {}
                        }
                        resolve();
                    }, postSpeakDelay);
                };

                speechSynthesis.speak(u);
            });
        }

        function startRecognitionFresh() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) return;

            // kalau ada instance lama, matikan dulu
            if (recognition) {
                try {
                    recognition.onend = null;
                } catch (e) {}
                try {
                    recognition.abort();
                } catch (e) {}
                recognition = null;
            }

            recognition = new SR();
            recognition.lang = "id-ID";
            recognition.continuous = true;

            recognition.onresult = (e) => {
                // ⬅️ inti fix: kalau TTS sedang bicara / masih cooldown, abaikan hasil
                if (isSpeaking || Date.now() < suppressUntil) return;
                if (isProcessingAnswer) return;

                let text = "";
                for (let i = e.resultIndex; i < e.results.length; i++) {
                    text += " " + e.results[i][0].transcript;
                }
                text = text.trim();

                clearQuestionTimeout();

                const q = (questions[currentModul] || [])[step];

                // khusus NIK
                if (q && q.field === "nomor_induk") {
                    pushNikDigits(text);
                    return;
                }

                setText("voice-status", text);

                const last = e.results[e.results.length - 1];
                if (last.isFinal) processVoiceAnswer(text);
            };

            recognition.onend = () => {
                if (!isPaused && !isProcessingAnswer && !isSpeaking && allowListen) {
                    try {
                        recognition.start();
                    } catch (e) {}
                    setText("voice-status", "Mendengarkan...");
                }
            };

            try {
                recognition.start();
            } catch (e) {}
        }

        async function speakQuestion() {
            const q = (questions[currentModul] || [])[step];
            if (!q) return;

            let text = q.label;
            if (typeof q.label === "function") text = q.label();
            await speak(text);
        }

        /* ============================================================
           COUNTER + TITLE
        ============================================================ */
        function updateQuestionCounter() {
            const list = questions[currentModul] || [];
            const total = list.length || 0;
            setText("currentQ", total === 0 ? 0 : (step + 1));
            setText("totalQ", total);
        }

        function updateModulTitle() {
            const m = modules.find(x => x.id === currentModul);
            if (getEl("modulTitle")) getEl("modulTitle").innerText = "Input " + (m?.name || "") + " via Suara";
        }

        /* ============================================================
           TIMEOUT PERTANYAAN
        ============================================================ */
        function clearQuestionTimeout() {
            if (questionTimeout) {
                clearTimeout(questionTimeout);
                questionTimeout = null;
            }
        }

        function startQuestionTimeout() {
            clearQuestionTimeout();

            questionTimeout = setTimeout(async () => {
                if (isSpeaking || isProcessingAnswer || isPaused) return;

                await speak("Saya ulangi pertanyaannya");
                await speakQuestion();
                startQuestionTimeout();
            }, 20000); // 10 detik
        }

        /* ============================================================
           PROGRESS STEPS
        ============================================================ */
        function updateProgressSteps() {
            const c = getEl("progressSteps");
            if (!c) return;

            c.innerHTML = "";

            modules.forEach((m, i) => {
                if (i > 0) {
                    const l = document.createElement("div");
                    l.className =
                        `h-0.5 w-12 self-center rounded-full ${modulStatus[m.id]==='completed' ? 'bg-blue-600' : 'bg-gray-300'}`;
                    c.appendChild(l);
                }

                const d = document.createElement("div");
                d.className = "flex items-center cursor-pointer";
                d.onclick = () => switchModul(m.id);

                const circle = document.createElement("div");
                circle.className = `w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${
      modulStatus[m.id]==='completed' ? 'bg-blue-600'
      : modulStatus[m.id]==='active' ? 'bg-green-600'
      : 'bg-gray-300 text-gray-600'
    }`;
                circle.textContent = m.id;

                const txt = document.createElement("div");
                txt.className = "ml-2 text-left";
                txt.innerHTML = `
      <div class="font-medium text-sm">${m.name}</div>
      <div class="text-xs ${
        modulStatus[m.id]==='completed' ? 'text-blue-600'
        : modulStatus[m.id]==='active' ? 'text-green-600'
        : 'text-gray-500'
      }">${modulStatus[m.id]==='completed' ? 'Selesai' : modulStatus[m.id]==='active' ? 'Aktif' : 'Belum'}</div>
    `;

                d.appendChild(circle);
                d.appendChild(txt);
                c.appendChild(d);
            });
        }

        /* ============================================================
           SWITCH MODUL
        ============================================================ */
        function switchModul(id) {
            stopListening();
            sisaKegiatan = null;

            stepByModul[currentModul] = step;

            Object.keys(modulStatus).forEach(k => {
                if (modulStatus[k] === 'active') modulStatus[k] = 'pending';
            });

            if (modulStatus[id] !== 'completed') modulStatus[id] = 'active';

            currentModul = id;
            step = stepByModul[id] || 0;

            updateModulTitle();
            updateProgressSteps();
            updateQuestionCounter();

            getEl("reviewForm")?.classList.add("hidden");
            getEl("inputArea")?.classList.remove("hidden");

            renderQuestion();
            setText("voice-status", "Tekan mic untuk mulai merekam...");
        }

        /* ============================================================
           RENDER QUESTION
        ============================================================ */
        function renderQuestion() {
            const q = (questions[currentModul] || [])[step];
            if (!q) return finishModul();

            const qa = getEl("quizArea");
            if (!qa) return;

            let labelText = (typeof q.label === "function") ? q.label() : q.label;

            let h = `<div class="flex flex-col items-center space-y-4 mb-6">
    <h3 class="text-xl font-semibold text-gray-800 text-center">${labelText}</h3>`;

            if (q.type === "select") {
                h += `<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-3xl mx-auto">`;
                Object.entries(safeObj(q.options)).forEach(([id, n]) => {
                    h += `<div class="option-card" data-id="${id}">${n}</div>`;
                });
                h += `</div>`;
            } else {
                h += `<div class="w-full max-w-md">
      <input id="inputAnswer" readonly class="w-full border border-gray-300 rounded-xl p-4 text-center text-lg bg-white shadow-sm"
        placeholder="Jawaban muncul di sini...">
    </div>`;
            }

            h += `</div>`;
            qa.innerHTML = h;

            qa.querySelectorAll(".option-card").forEach(card => {
                card.onclick = () => processVoiceAnswer(card.innerText);
            });
        }

        function showCurrentQuestion() {
            const q = (questions[currentModul] || [])[step];
            if (!q) return;

            const qa = getEl("quizArea");
            if (!qa) return;

            let labelText = (typeof q.label === "function") ? q.label() : q.label;

            let h = `<div class="flex flex-col items-center space-y-4 mb-6">
    <h3 class="text-xl font-semibold text-gray-800 text-center">${labelText}</h3>`;

            if (q.type === "select") {
                h += `<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-3xl mx-auto">`;
                Object.entries(safeObj(q.options)).forEach(([id, n]) => {
                    h += `<div class="option-card opacity-50 pointer-events-none">${n}</div>`;
                });
                h += `</div>`;
            } else {
                h += `<div class="w-full max-w-md">
      <input readonly class="w-full border border-gray-300 rounded-xl p-4 text-center text-lg bg-white shadow-sm"
        placeholder="Jawaban muncul di sini...">
    </div>`;
            }

            h += `</div>`;
            qa.innerHTML = h;
        }

        /* ============================================================
           FINISH MODUL
        ============================================================ */
        function finishModul() {
            modulStatus[currentModul] = "completed";
            stepByModul[currentModul] = step;

            updateProgressSteps();
            stopListening();

            if (reviewableModules.includes(currentModul)) {
                openReviewForm();
                return;
            }

            const modul = modules.find(m => m.id === currentModul);
            setText("voice-status", `${modul?.name||"Modul"} selesai`);

            const qa = getEl("quizArea");
            if (qa) {
                qa.innerHTML = `
      <div class="text-center space-y-3">
        <div class="text-green-600 text-xl font-semibold">${modul?.name||"Modul"} selesai ✔</div>
        <div class="text-gray-500 text-sm">Silakan pilih modul lain di bagian atas</div>
      </div>`;
            }
        }

        /* ============================================================
           BUILD KEGIATAN INDEX (MODUL 6)
        ============================================================ */
        function buildKegiatanIndex() {
            kegiatanIndex = {};
            const keg = safeObj(masters?.kegiatan);
            Object.entries(keg).forEach(([id, name]) => {
                kegiatanIndex[normalize(name)] = id;
            });
        }

        /* ============================================================
           NORMALIZERS
        ============================================================ */
        function normalizeNullable(text) {
            if (!text) return null;
            const t = String(text).toLowerCase().trim();
            const kosong = ["tidak ada", "nggak ada", "ga ada", "gak ada", "kosong", "nihil", "tidak diisi", "tidak perlu",
                "skip", "lewati"
            ];
            return kosong.includes(t) ? null : text;
        }

        function normalizeKeterangan(text) {
            if (!text) return "";
            const t = String(text).toLowerCase().trim();
            const kosong = ["tidak ada", "nggak ada", "ga ada", "gak ada", "tidak", "kosong", "nihil",
                "tidak ada keterangan"
            ];
            return kosong.includes(t) ? "" : text;
        }

        function normalizeEmail(text) {
            if (!text) return "";
            return String(text)
                .toLowerCase()
                .replace(/\s*(at|ad|et)\s*/g, "@")
                .replace(/\s*(dot|titik)\s*/g, ".")
                .replace(/\s+/g, "")
                .trim();
        }

        function normalizeTime(text) {
            if (!text) return null;
            text = String(text).toLowerCase().replace("pukul", "").trim();

            if (/^\d{1,2}$/.test(text)) return text.padStart(2, "0") + ":00";
            if (/^\d{1,2}\s+\d{1,2}$/.test(text)) {
                const [h, m] = text.split(" ");
                return h.padStart(2, "0") + ":" + m.padStart(2, "0");
            }
            if (/^\d{1,2}[:.]\d{2}$/.test(text)) return text.replace(".", ":");
            return null;
        }

        function normalizeDate(text) {
            if (!text) return null;
            text = String(text).toLowerCase().trim();

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
            if (/^\d{8}$/.test(text)) {
                const y = text.slice(0, 4),
                    m = text.slice(4, 6),
                    d = text.slice(6, 8);
                return `${y}-${m}-${d}`;
            }

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

        function normalizeMoney(text) {
            if (!text) return "";
            let t = String(text).toLowerCase().trim();

            // angka 1.000.000 atau 1,000,000
            if (/^[\d.,]+$/.test(t)) return t.replace(/[.,]/g, "");

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
                ribu: 1000,
                juta: 1000000,
                miliar: 1000000000,
                milyar: 1000000000,
                triliun: 1000000000000
            };

            Object.entries(numbers).sort((a, b) => b[0].length - a[0].length).forEach(([k, v]) => {
                t = t.replace(new RegExp("\\b" + k + "\\b", "gi"), String(v));
            });

            let total = 0;

            Object.entries(multipliers).forEach(([k, mul]) => {
                const r = new RegExp("(\\d+)\\s*" + k, "gi");
                t = t.replace(r, (m, n) => {
                    total += parseInt(n, 10) * mul;
                    return "";
                });
            });

            const rest = t.match(/\b\d+\b/g);
            if (rest) {
                rest.forEach(n => {
                    const v = parseInt(n, 10);
                    if (v < 1000) total += v;
                });
            }

            return String(total);
        }

        function normalizeSk(text) {
            if (!text) return "";
            let t = String(text).toLowerCase().trim();

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
                t = t.replace(new RegExp("\\b" + k + "\\b", "gi"), v);
            });

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

            Object.entries(angka).sort((a, b) => b[0].length - a[0].length).forEach(([k, v]) => {
                t = t.replace(new RegExp("\\b" + k + "\\b", "gi"), String(v));
            });

            // gabung angka "20 3" -> "203" (lebih aman)
            t = t.replace(/\b(\d+)\s+(\d+)\b/g, (m, a, b) => {
                if (b.length === 1) return a + b;
                return m;
            });

            t = t.replace(/\s*([\/\.\-])\s*/g, "$1").replace(/\s+/g, " ").trim();
            return t.toUpperCase();
        }

        function toISODate(val) {
            if (!val) return "";

            // sudah ISO
            if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return val;

            // format MM/DD/YYYY (misal 01/20/2026)
            if (/^\d{2}\/\d{2}\/\d{4}$/.test(val)) {
                const [m, d, y] = val.split("/");
                return `${y}-${m}-${d}`;
            }

            // format DD/MM/YYYY (kalau suatu saat kepake)
            if (/^\d{2}-\d{2}-\d{4}$/.test(val)) {
                const [d, m, y] = val.split("-");
                return `${y}-${m}-${d}`;
            }

            return "";
        }

        /* ============================================================
           RESET LISTEN UI
        ============================================================ */
        function resetToListening() {
            const input = getEl("inputAnswer");
            if (input) input.value = "";
            setText("voice-status", "Mendengarkan...");
        }
        async function showAnswerWithTransition(rawText) {
            const input = getEl("inputAnswer");
            if (!input) return;


            input.value = ""; // placeholder muncul

            const SHOW_DELAY = 600; // delay sebelum jawaban muncul
            const HOLD_MS = 900; // jawaban stay sebentar sebelum hilang
            const HIDE_DELAY = 0; // kalau mau delay sebelum hilang (biasanya 0)

            // tunggu sebelum tampil
            await new Promise(r => setTimeout(r, SHOW_DELAY));

            // tampilkan jawaban
            const el = getEl("inputAnswer");
            if (!el) return;
            el.value = rawText;

            // tahan sebentar
            await new Promise(r => setTimeout(r, HOLD_MS));

            // hilangkan (biar placeholder balik)
            if (HIDE_DELAY) await new Promise(r => setTimeout(r, HIDE_DELAY));
            const el2 = getEl("inputAnswer");
            if (el2 && el2.value === rawText) el2.value = "";
        }

        /* ============================================================
           PROCESS ANSWER
        ============================================================ */
        async function processVoiceAnswer(text) {
            if (isProcessingAnswer) return;
            isProcessingAnswer = true;
            clearQuestionTimeout();

            try {
                const q = (questions[currentModul] || [])[step];
                if (!q) return;

                let value = normalizeNullable(text);

                // textarea -> normalisasi keterangan
                if (q.type === "textarea") value = normalizeKeterangan(text);

                if (value === null) {
                    await speak("Baik, dikosongkan");
                }

                if (typeof value === "string") value = value.trim();

                // format khusus
                if (q.field === "no_sk" || q.field === "no_sp2d") value = normalizeSk(text);

                if (q.field === "no_hp") {
                    value = normalizeSk(text).replace(/[^0-9]/g, "");
                    if (value.length < 10) {
                        await speak("Nomor handphone tidak valid");
                        resetToListening();
                        return;
                    }
                }

                if (["nama_lengkap", "judul_keputusan", "nama_kegiatan", "judul_agenda"].includes(q.field)) {
                    if (/\d/.test(String(value))) {
                        await speak("Nama tidak boleh mengandung angka");
                        resetToListening();
                        return;
                    }
                }

                if (["pagu_anggaran", "total_anggaran", "jumlah"].includes(q.field)) {
                    value = normalizeMoney(text);
                    if (!value || Number(value) <= 0) {
                        await speak("Jumlah tidak valid, ulangi");
                        resetToListening();
                        return;
                    }

                    // modul 6 batas sisa
                    if (q.field === "jumlah" && sisaKegiatan !== null && sisaKegiatan !== undefined) {
                        if (Number(value) > Number(sisaKegiatan)) {
                            await speak(
                                `Jumlah melebihi sisa anggaran. Sisa tinggal ${Number(sisaKegiatan).toLocaleString()}`
                            );
                            resetToListening();
                            return;
                        }
                    }
                }

                if (q.field === "jam_mulai" || q.field === "jam_selesai") {
                    const t = normalizeTime(text);
                    if (!t && q.field === "jam_mulai") {
                        await speak("Format jam tidak valid, ulangi");
                        resetToListening();
                        return;
                    }
                    value = t;
                }

                if (q.type === "email") {
                    value = normalizeEmail(text);
                }

                if (q.type === "date") {
                    const d = normalizeDate(text);
                    if (!d) {
                        await speak("Format tanggal tidak valid, ulangi");
                        resetToListening();
                        return;
                    }
                    value = d;
                }

                if (q.type === "select") {
                    if (q.field === "kegiatan_id") {
                        const key = normalize(text);
                        const id = kegiatanIndex?.[key];
                        if (!id) {
                            await speak("Nama kegiatan tidak ditemukan, ulangi");
                            resetToListening();
                            return;
                        }
                        value = id;

                        // ambil sisa kegiatan
                        try {
                            const res = await fetch(`/voice/kegiatan/${id}/sisa`);
                            const data = await res.json();
                            sisaKegiatan = data?.sisa ?? null;
                        } catch (e) {
                            sisaKegiatan = null;
                        }
                    } else {
                        const m = findBestMatch(text, q.options);
                        if (!m) {
                            await speak("Pilihan tidak dikenali, ulangi");
                            resetToListening();
                            return;
                        }
                        value = m[0];
                    }
                }

                if (q.validation && !q.validation.test(String(value))) {
                    await speak(q.error || "Format tidak valid");
                    resetToListening();
                    return;
                }

                // simpan
                // simpan
                answersByModul[currentModul][q.field] = value;

                // ✅ tampilkan jawaban + status secara sinkron
                if (q.type !== "select") {
                    await showAnswerWithTransition(text);
                } else {
                    // ✅ tampilkan pilihan yang dipilih di status
                    setText("voice-status", `Jawaban: ${text}`);
                    await new Promise(r => setTimeout(r, 900)); // lama tampil (sesuaikan)

                    // opsional: kosongkan lagi biar rapih sebelum lanjut
                    setText("voice-status", "Memproses...");
                    await new Promise(r => setTimeout(r, 250));
                }

                allowListen = false;

                step++;
                allowListen = false;

                updateQuestionCounter();

                if (step < (questions[currentModul] || []).length) {
                    renderQuestion();

                    // status sinkron: siap dengar setelah pertanyaan dibacakan
                    await new Promise(r => setTimeout(r, 150));
                    allowListen = true;

                    setText("voice-status", "Menyiapkan pertanyaan...");
                    await speakQuestion();

                    setText("voice-status", "Mendengarkan...");
                    startQuestionTimeout();
                } else {
                    finishModul();
                }


            } finally {
                isProcessingAnswer = false;
            }
        }

        /* ============================================================
           VISUALIZER
        ============================================================ */
        async function initVisualizer() {
            // reset dulu biar tidak dobel
            try {
                if (micStream) {
                    micStream.getTracks().forEach(t => t.stop());
                    micStream = null;
                }
                if (audioContext) {
                    await audioContext.close();
                    audioContext = null;
                }
            } catch (e) {}

            audioContext = new AudioContext();
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 256;

            canvas = getEl("visualizer");
            if (!canvas) return;
            ctx = canvas.getContext("2d");

            const resize = () => {
                canvas.width = canvas.offsetWidth * window.devicePixelRatio;
                canvas.height = canvas.offsetHeight * window.devicePixelRatio;
            };
            resize();

            if (!resizeBound) {
                resizeBound = true;
                window.addEventListener("resize", resize);
            }

            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
            micStream = stream;

            const src = audioContext.createMediaStreamSource(stream);
            src.connect(analyser);

            dataArray = new Uint8Array(analyser.frequencyBinCount);
            drawWaveVisualizer();
        }

        function drawWaveVisualizer() {
            if (!isListening || !analyser || !ctx || !canvas) return;

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
           START / STOP LISTENING
        ============================================================ */
        function startListening() {
            if (!isPaused) return;

            isPaused = false;
            isListening = true;

            getEl("recordBtn")?.classList.add("recording");
            getEl("visualizer")?.classList.add("show");
            getEl("visualizerPlaceholder")?.classList.add("hide");

            const recordIcon = getEl("recordIcon");
            if (recordIcon) {
                recordIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
            d="M10 9h4v6h-4z" fill="white"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
            d="M12 21a9 9 0 100-18 9 9 0 000 18z" stroke="white" fill="none"/>`;
            }

            // ✅ cek dukungan SR tetap di sini
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SR) {
                alert("Browser tidak mendukung SpeechRecognition");
                stopListening();
                return;
            }

            // ✅ GANTI SEMUA BLOK recognition = new SR() dkk dengan ini
            startRecognitionFresh();
            setText("voice-status", "Mendengarkan...");
            getEl("recordBtn")?.classList.add("recording");

            initVisualizer().catch(() => {});

            setTimeout(async () => {
                await speakQuestion();
                startQuestionTimeout();
            }, 600);
        }

        function stopListening() {
            isPaused = true;
            isListening = false;
            clearQuestionTimeout();

            // stop SR
            if (recognition) {
                recognition.onend = null;
                try {
                    recognition.abort();
                } catch (e) {}
                recognition = null;
            }

            // stop mic stream
            if (micStream) {
                micStream.getTracks().forEach(t => t.stop());
                micStream = null;
            }

            // stop audio context
            if (audioContext) {
                try {
                    audioContext.close();
                } catch (e) {}
                audioContext = null;
            }

            // UI reset
            getEl("recordBtn")?.classList.remove("recording");
            getEl("visualizer")?.classList.remove("show");
            getEl("visualizerPlaceholder")?.classList.remove("hide");

            const recordIcon = getEl("recordIcon");
            if (recordIcon) {
                recordIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />`;
            }
        }

        /* ============================================================
           MODAL + TABLES
        ============================================================ */
        function openTambahKegiatanAnggaran() {
            getEl("modalAnggaranItem")?.classList.remove("hidden");
        }

        function closeTambahKegiatanAnggaran() {
            getEl("modalAnggaranItem")?.classList.add("hidden");
        }

        function simpanItemAnggaran() {
            const kegiatan = getEl("item_kegiatan");
            const sumber = getEl("item_sumber");
            const nilai = getEl("item_nilai");

            if (!kegiatan?.value || !nilai?.value) {
                alert("Kegiatan dan nilai wajib diisi");
                return;
            }

            anggaranItems.push({
                kegiatan_id: kegiatan.value,
                nama_kegiatan: kegiatan.options[kegiatan.selectedIndex]?.text || "",
                kdsumber: sumber?.value || "",
                nilai: safeNumber(nilai.value)
            });

            closeTambahKegiatanAnggaran();
            renderAnggaranTable();
        }

        function renderAnggaranTable() {
            const tbody = getEl("anggaranItemsTable");
            if (!tbody) return;

            tbody.innerHTML = "";
            let total = 0;

            anggaranItems.forEach((i, idx) => {
                total += safeNumber(i.nilai);

                tbody.innerHTML += `
      <tr>
        <td class="border px-2">${i.nama_kegiatan}</td>
        <td class="border px-2">${safeObj(masters?.sumber_dana)[i.kdsumber] || ""}</td>
        <td class="border px-2 text-right">${safeNumber(i.nilai).toLocaleString()}</td>
        <td class="border px-2 text-center">
          <button type="button" onclick="hapusAnggaranItem(${idx})" class="text-red-600">✖</button>
        </td>
      </tr>`;
            });

            const totalAnggaran = safeNumber(getEl("totalAnggaran")?.value);
            const sisa = totalAnggaran - total;

            setText("totalTerpakai", total.toLocaleString());
            setText("sisaAnggaran", sisa.toLocaleString());
        }

        function hapusAnggaranItem(i) {
            anggaranItems.splice(i, 1);
            renderAnggaranTable();
        }

        function openTambahRealisasi() {
            getEl("modalRealisasi")?.classList.remove("hidden");
        }

        function closeTambahRealisasi() {
            getEl("modalRealisasi")?.classList.add("hidden");
        }

        function simpanItemPencairan() {
            const tgl = getEl("item_tanggal")?.value;
            const uraian = getEl("item_uraian")?.value;
            const jumlah = getEl("item_jumlah")?.value;
            const buktiInput = getEl("item_bukti");

            if (!tgl || !uraian || !jumlah) {
                alert("Tanggal, uraian, dan jumlah wajib diisi");
                return;
            }

            const buktiFile = buktiInput?.files?.length ? buktiInput.files[0] : null;

            pencairanItems.push({
                tanggal: tgl,
                uraian: uraian,
                jumlah: safeNumber(jumlah),
                bukti: buktiFile
            });

            // reset modal
            if (getEl("item_tanggal")) getEl("item_tanggal").value = "";
            if (getEl("item_uraian")) getEl("item_uraian").value = "";
            if (getEl("item_jumlah")) getEl("item_jumlah").value = "";
            if (buktiInput) buktiInput.value = "";

            closeTambahRealisasi();
            renderPencairanTable();
        }

        function hapusPencairanItem(index) {
            pencairanItems.splice(index, 1);
            renderPencairanTable();
        }

        function renderPencairanTable() {
            const tbody = getEl("pencairanItemsTable");
            if (!tbody) return;

            tbody.innerHTML = "";
            let total = 0;

            pencairanItems.forEach((i, idx) => {
                total += safeNumber(i.jumlah);
                tbody.innerHTML += `
      <tr>
        <td class="border px-2">${i.tanggal}</td>
        <td class="border px-2">${i.uraian}</td>
        <td class="border px-2 text-right">${safeNumber(i.jumlah).toLocaleString()}</td>
        <td class="border px-2 text-center">
          <button type="button" onclick="hapusPencairanItem(${idx})" class="text-red-600">✖</button>
        </td>
      </tr>`;
            });

            setText("totalRealisasi", total.toLocaleString());
        }

        /* ============================================================
           REVIEW FORM (INI PAKAI PUNYAMU - AKU BIARIN)
           -> kamu sudah panjang, tapi tetap jalan.
        ============================================================ */
        function buildSelect(name, options, selected) {
            options = safeObj(options);
            let h = `<select name="${name}" class="w-full rounded-lg border-gray-300">`;
            Object.entries(options).forEach(([id, label]) => {
                h += `<option value="${id}" ${String(id)===String(selected)?"selected":""}>${label}</option>`;
            });
            return h + `</select>`;
        }

        // NOTE: openReviewForm() panjang banget di code kamu.
        // Aku TIDAK ubah isi field-fieldnya supaya tidak mengganggu layout/logic yang kamu sudah punya.
        // Tapi fungsi ini tetap harus ada => pakai versi kamu yang existing.
        // Kalau kamu mau aku rapihin juga openReviewForm() biar lebih ringkas, bilang ya.
        function openReviewForm() {
            stopListening();

            document.getElementById('inputArea').classList.add('hidden');
            document.getElementById('reviewForm').classList.remove('hidden');

            const c = document.getElementById('reviewFields');
            c.innerHTML = "";

            const add = (label, input) => {
                c.innerHTML += `
            <div>
                <label class="text-sm font-medium">${label}</label>
                ${input}
            </div>`;
            };

            // =====================================================
            // ================= MODUL 1 : PENGURUS ================
            // =====================================================
            if (currentModul === 1) {

                add("Nomor Induk (NIK)",
                    `<input name="nomor_induk" value="${answersByModul[currentModul].nomor_induk || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Nama Lengkap",
                    `<input name="nama_lengkap" value="${answersByModul[currentModul].nama_lengkap || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jenis Kelamin", `
            <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300">
                <option value="L" ${answersByModul[currentModul].jenis_kelamin=="L"?"selected":""}>Laki-laki</option>
                <option value="P" ${answersByModul[currentModul].jenis_kelamin=="P"?"selected":""}>Perempuan</option>
            </select>
        `);

                add("No HP",
                    `<input name="no_hp" value="${answersByModul[currentModul].no_hp || ''}" class="w-full rounded-lg border-gray-300"/>`
                );
                add("Email",
                    `<input name="email" value="${answersByModul[currentModul].email || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                c.innerHTML += `
            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Alamat</label>
                <textarea name="alamat" class="w-full rounded-lg border-gray-300">${answersByModul[currentModul].alamat || ''}</textarea>
            </div>
        `;

                add("Jabatan", buildSelect("kdjabatan", masters.jabatan, answersByModul[currentModul].kdjabatan));
                add("Unit", buildSelect("kdunit", masters.unit, answersByModul[currentModul].kdunit));
                add("Periode Awal", buildSelect("kdperiode", masters.periode, answersByModul[currentModul].kdperiode));
                add("Periode Akhir", buildSelect("kdperiode_akhir", masters.periode_akhir, answersByModul[currentModul]
                    .kdperiode_akhir));
                add("Status", buildSelect("kdstatus", masters.status, answersByModul[currentModul].kdstatus));
                add("Jenis SK", buildSelect("kdjenissk", masters.jenis_sk, answersByModul[currentModul].kdjenissk));

                add("Nomor SK",
                    `<input name="no_sk" value="${answersByModul[currentModul].no_sk || ''}" class="w-full rounded-lg border-gray-300"/>`
                );
                add("Tanggal SK",
                    `<input type="date" name="tanggal_sk"
    value="${toISODate(answersByModul[currentModul].tanggal_sk)}"
    class="w-full rounded-lg border-gray-300"/>`
                );

                c.innerHTML += `
            <div class="sm:col-span-2">
                <label class="text-sm font-medium">Keterangan</label>
                <textarea name="keterangan" class="w-full rounded-lg border-gray-300">${answersByModul[currentModul].keterangan || ''}</textarea>
            </div>
        `;

                c.innerHTML += `
            <div>
                <label>Foto</label>
                <input type="file" name="foto" class="w-full rounded-lg border-gray-300"/>
            </div>
            <div>
                <label>Tanda Tangan</label>
                <input type="file" name="tanda_tangan" class="w-full rounded-lg border-gray-300"/>
            </div>
        `;
            }

            // =====================================================
            // ================= MODUL 2 : KEPUTUSAN ===============
            // =====================================================
            if (currentModul === 2) {
                Object.entries(answersByModul[1]).forEach(([k, v]) => {
                    c.innerHTML += `<input type="hidden" name="${k}" value="${v ?? ''}">`;
                });
                c.innerHTML += `
            <div class="sm:col-span-2">
                <h4 class="text-sm font-semibold text-blue-700 mb-3">
                    Data Keputusan
                </h4>
            </div>
        `;

                add("Nomor SK",
                    `<input name="nomor_sk" value="${answersByModul[currentModul].nomor_sk || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Judul Keputusan",
                    `<input name="judul_keputusan" value="${answersByModul[currentModul].judul_keputusan || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jenis Keputusan",
                    buildSelect("kdjenis", masters.jenis, answersByModul[currentModul].kdjenis)
                );

                add("Unit",
                    buildSelect("kdunit", masters.unit_keputusan, answersByModul[currentModul].kdunit)
                );

                add("Periode",
                    buildSelect("kdperiode", masters.periode, answersByModul[currentModul].kdperiode)
                );

                add("Jabatan Penetap",
                    buildSelect("kdjabatan", masters.jabatan, answersByModul[currentModul].kdjabatan)
                );

                add("Tanggal Keputusan",
                    `<input type="date" name="tanggal_keputusan" value="${answersByModul[currentModul].tanggal_keputusan || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Status",
                    buildSelect("kdstatus", masters.status_keputusan, answersByModul[currentModul].kdstatus)
                );

                add("Metode",
                    buildSelect("kdmetode", masters.metode, answersByModul[currentModul].kdmetode)
                );
            }
            // =====================================================
            // ================= MODUL 3 : KEGIATAN =================
            // =====================================================
            if (currentModul === 3) {

                c.innerHTML += `
        <div class="sm:col-span-2">
            <h4 class="text-sm font-semibold text-blue-700 mb-3">
                Data Kegiatan
            </h4>
        </div>
    `;

                add("Nama Kegiatan",
                    `<input name="nama_kegiatan" value="${answersByModul[3].nama_kegiatan || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jenis Kegiatan",
                    buildSelect("kdjenis", masters.jenis_kegiatan, answersByModul[3].kdjenis)
                );
                add("Lokasi",
                    `<input name="lokasi" value="${answersByModul[3].lokasi || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Unit",
                    buildSelect("kdunit", masters.unit_keputusan, answersByModul[3].kdunit)
                );

                add("Periode",
                    buildSelect("kdperiode", masters.periode, answersByModul[3].kdperiode)
                );

                add("Status",
                    buildSelect("kdstatus", masters.status_kegiatan, answersByModul[3].kdstatus)
                );

                add("Sumber Dana",
                    buildSelect("kdsumber", masters.sumber_dana, answersByModul[3].kdsumber)
                );

                add("Pagu Anggaran",
                    `<input name="pagu_anggaran" value="${answersByModul[3].pagu_anggaran || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Tanggal Mulai",
                    `<input type="date" name="tgl_mulai" value="${answersByModul[3].tgl_mulai || ''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Berdasarkan Keputusan",
                    buildSelect("keputusan_id", masters.keputusan, answersByModul[3].keputusan_id)
                );
            }
            if (currentModul === 4) {

                add("Judul Agenda",
                    `<input name="judul_agenda" value="${answersByModul[4].judul_agenda||''}" class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jenis Agenda", buildSelect("kdjenis", masters.jenis_agenda, answersByModul[4].kdjenis));
                add("Unit", buildSelect("kdunit", masters.unit_keputusan, answersByModul[4].kdunit));
                add("Status", buildSelect("kdstatus", masters.status_agenda, answersByModul[4].kdstatus));
                add("Tempat", buildSelect("kdtempat", masters.tempat_agenda, answersByModul[4].kdtempat));
                add("Periode", buildSelect("kdperiode", masters.periode, answersByModul[4].kdperiode));

                add("Tanggal",
                    `<input type="date" name="tanggal" value="${answersByModul[4].tanggal||''}" class="w-full rounded-lg"/>`
                );
                add("Jam Mulai",
                    `<input type="time" name="jam_mulai" value="${answersByModul[4].jam_mulai||''}" class="w-full rounded-lg"/>`
                );
                add("Jam Selesai",
                    `<input type="time" name="jam_selesai" value="${answersByModul[4].jam_selesai||''}" class="w-full rounded-lg"/>`
                );

                c.innerHTML += `
    <div class="sm:col-span-2">
        <label>Uraian</label>
        <textarea name="uraian_agenda" class="w-full rounded-lg">${answersByModul[4].uraian_agenda||''}</textarea>
    </div>
    `;
            }
            if (currentModul === 5) {

                add("Unit", buildSelect("kdunit", masters.unit_keputusan, answersByModul[5].kdunit));
                add("Periode", buildSelect("kdperiode", masters.periode, answersByModul[5].kdperiode));
                add("Sumber Dana", buildSelect("kdsumber", masters.sumber_dana, answersByModul[5].kdsumber));

                add("Total Anggaran",
                    `<input id="totalAnggaran" name="total_anggaran"
            value="${answersByModul[5].total_anggaran || ''}"
            class="w-full rounded-lg border-gray-300"/>`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
          <label>Keterangan</label>
          <textarea name="keterangan" class="w-full rounded-lg">${answersByModul[5].keterangan || ''}</textarea>
        </div>

        <div class="sm:col-span-2 border-t pt-4 mt-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-semibold text-blue-700">Rincian Kegiatan</h4>
                <button type="button"
                    onclick="openTambahKegiatanAnggaran()"
                    class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                    + Tambah Kegiatan
                </button>
            </div>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Kegiatan</th>
                        <th class="border px-2 py-1">Sumber</th>
                        <th class="border px-2 py-1 text-right">Nilai</th>
                        <th class="border px-2 py-1 w-16"></th>
                    </tr>
                </thead>
                <tbody id="anggaranItemsTable"></tbody>
            </table>

            <div class="text-right mt-2">
                <span class="text-sm text-gray-500">Total terpakai:</span>
                <b id="totalTerpakai">0</b>
                |
                <span class="text-sm text-gray-500">Sisa:</span>
                <b id="sisaAnggaran">0</b>
            </div>
        </div>
    `;

                renderAnggaranTable();
            }
            // =====================================================
            // ================= MODUL 6 : PENCAIRAN ================
            // =====================================================
            if (currentModul === 6) {

                add("Kegiatan", buildSelect("kegiatan_id", masters.kegiatan, answersByModul[6].kegiatan_id));

                add("Tanggal Pencairan",
                    `<input type="date" name="tanggal_cair"
            value="${answersByModul[6].tanggal_cair || ''}"
            class="w-full rounded-lg border-gray-300"/>`
                );

                add("Jumlah Dicairkan",
                    `<input name="jumlah"
            value="${answersByModul[6].jumlah || ''}"
            class="w-full rounded-lg border-gray-300"/>`
                );

                add("Nomor SP2D",
                    `<input name="no_sp2d"
            value="${answersByModul[6].no_sp2d || ''}"
            class="w-full rounded-lg border-gray-300"/>`
                );

                // 🔥 TIDAK ADA bukti di header pencairan

                c.innerHTML += `
        <div class="sm:col-span-2 border-t pt-4 mt-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-semibold text-blue-700">Realisasi Pengeluaran</h4>
                <button type="button"
                    onclick="openTambahRealisasi()"
                    class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                    + Tambah Realisasi
                </button>
            </div>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">Uraian</th>
                        <th class="border px-2 py-1 text-right">Jumlah</th>
                        <th class="border px-2 py-1 w-16"></th>
                    </tr>
                </thead>
                <tbody id="pencairanItemsTable"></tbody>
            </table>

            <div class="text-right mt-2">
                <span class="text-sm text-gray-500">Total Realisasi:</span>
                <b id="totalRealisasi">0</b>
            </div>
        </div>
    `;

                renderPencairanTable();
            }


        }
        /* ============================================================
           VALIDATION (kamu sudah punya)
        ============================================================ */
        function validateReviewForm() {

            // =====================================
            // MODUL 1 — PENGURUS
            // =====================================
            if (currentModul === 1) {
                const required = [
                    "nomor_induk",
                    "nama_lengkap",
                    "kdjabatan",
                    "kdunit",
                    "kdperiode",
                    "kdperiode_akhir",
                    "kdstatus",
                    "kdjenissk",
                    "no_sk",
                    "tanggal_sk"
                ];

                for (const name of required) {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el || !el.value || el.value.trim() === "") {
                        alert("Field wajib belum diisi: " + name.replaceAll("_", " "));
                        el?.focus();
                        return false;
                    }
                }

                // Email format
                const email = document.querySelector('[name="email"]')?.value;
                if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert("Format email tidak valid");
                    return false;
                }

                // Tanggal SK harus YYYY-MM-DD
                const tgl = document.querySelector('[name="tanggal_sk"]')?.value;
                if (!/^\d{4}-\d{2}-\d{2}$/.test(tgl)) {
                    alert("Format tanggal SK harus YYYY-MM-DD");
                    return false;
                }

                return true;
            }

            // =====================================
            // MODUL 2 — KEPUTUSAN
            // =====================================
            if (currentModul === 2) {
                const required = [
                    "nomor_sk",
                    "judul_keputusan",
                    "kdjenis",
                    "kdunit",
                    "kdperiode",
                    "kdjabatan",
                    "tanggal_keputusan",
                    "kdstatus",
                    "kdmetode"
                ];

                for (const name of required) {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el || !el.value || el.value.trim() === "") {
                        alert("Field wajib belum diisi: " + name.replaceAll("_", " "));
                        el?.focus();
                        return false;
                    }
                }

                return true;
            }
            if (currentModul === 3) {
                const required = [
                    "nama_kegiatan",
                    "kdjenis",
                    "kdunit",
                    "kdperiode",
                    "kdstatus",
                    "kdsumber",
                    "pagu_anggaran",
                    "tgl_mulai"
                ];

                for (const name of required) {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el || !el.value || el.value.trim() === "") {
                        alert("Field wajib belum diisi: " + name.replaceAll("_", " "));
                        el?.focus();
                        return false;
                    }
                }
                return true;
            }


            return true;
        }

        /* ============================================================
           INIT
        ============================================================ */
        getEl("recordBtn")?.addEventListener("click", () => {
            if (isPaused) startListening();
            else stopListening();
        });

        updateProgressSteps();
        updateModulTitle();
        updateQuestionCounter();
        buildKegiatanIndex();
        renderQuestion();

        /* ============================================================
           SUBMIT SAVE (FIXED: tidak baca jumlah kalau bukan modul 6)
        ============================================================ */
        getEl("saveModulBtn")?.addEventListener("click", async () => {
            if (!validateReviewForm()) return;

            // Modul 5: minimal 1 kegiatan
            if (currentModul === 5) {
                if (anggaranItems.length === 0) {
                    alert("Minimal satu kegiatan harus diisi");
                    return;
                }
                answersByModul[5].total_anggaran = getEl("totalAnggaran")?.value || "";
            }

            const formEl = getEl("voiceForm");
            if (!formEl) {
                alert("Form tidak ditemukan");
                return;
            }

            const form = new FormData(formEl);
            form.append("modul", currentModul);

            if (currentModul === 5) {
                form.append("items", JSON.stringify(anggaranItems));
            }

            if (currentModul === 6) {
                // validasi jumlah vs sisa hanya untuk modul 6
                const jumlahEl = document.querySelector('[name="jumlah"]');
                const jumlah = safeNumber(jumlahEl?.value);

                if (sisaKegiatan !== null && sisaKegiatan !== undefined && jumlah > Number(sisaKegiatan)) {
                    alert("Jumlah pencairan melebihi sisa anggaran kegiatan");
                    return;
                }

                if (pencairanItems.length === 0) {
                    alert("Minimal satu realisasi harus diisi");
                    return;
                }

                form.append("items", JSON.stringify(
                    pencairanItems.map(i => ({
                        tanggal: i.tanggal,
                        uraian: i.uraian,
                        jumlah: i.jumlah
                    }))
                ));

                pencairanItems.forEach((i, idx) => {
                    if (i.bukti) form.append(`bukti_${idx}`, i.bukti);
                });
            }

            getEl("loadingOverlay")?.classList.remove("hidden");

            try {
                const res = await fetch("{{ route('voice.kelembagaan.store-all') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]')?.content || ""
                    },
                    body: form
                });

                const raw = await res.text();
                let data = {};
                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    data = {
                        html: raw
                    };
                }

                if (!res.ok) {
                    const msg = data.errors ?
                        Object.entries(data.errors).flatMap(([f, errs]) => errs.map(er => `• ${f}: ${er}`))
                        .join("<br>") :
                        (data.message || data.error || "Terjadi kesalahan server");

                    getEl("errorText").innerHTML = msg;

                    // kalau yang balik HTML, tampilkan ringkas
                    if (data.html && !msg) getEl("errorText").innerText =
                        "Server mengembalikan HTML error page. Cek laravel.log.";

                    getEl("errorModal")?.classList.remove("hidden");
                    return;
                }

                if (!res.ok) {
                    let msg = "";

                    if (data?.errors) {
                        Object.entries(data.errors).forEach(([field, errors]) => {
                            (errors || []).forEach(err => {
                                msg += `<div class="error-item text-red-600">• ${err}</div>`;
                            });
                        });
                    } else {
                        msg = data?.message || "Terjadi kesalahan";
                    }

                    if (getEl("errorText")) getEl("errorText").innerHTML = msg;
                    getEl("errorModal")?.classList.remove("hidden");
                    return;
                }

                const modul = modules.find(m => m.id === currentModul);
                setText("successText", (modul?.name || "Modul") + " berhasil disimpan");
                getEl("successModal")?.classList.remove("hidden");

                // reset modul state
                step = 0;
                stepByModul[currentModul] = 0;
                answersByModul[currentModul] = {};
                anggaranItems = [];
                pencairanItems = [];
                sisaKegiatan = null;

                modulStatus[currentModul] = "active";

                updateProgressSteps();
                updateQuestionCounter();
                stopListening();

            } catch (e) {
                alert("Terjadi kesalahan jaringan");
                console.error(e);
            } finally {
                getEl("loadingOverlay")?.classList.add("hidden");
            }
        });

        getEl("successOkBtn")?.addEventListener("click", () => {
            getEl("successModal")?.classList.add("hidden");
            getEl("reviewForm")?.classList.add("hidden");
            getEl("inputArea")?.classList.remove("hidden");
            renderQuestion();
            stopListening();
            setText("voice-status", "Tekan mic untuk mulai merekam...");
        });

        getEl("errorOkBtn")?.addEventListener("click", () => {
            getEl("errorModal")?.classList.add("hidden");
        });
    </script>


</x-app-layout>
