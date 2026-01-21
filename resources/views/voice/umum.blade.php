<x-app-layout>
    @slot('progressumum')
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
                Input Data Adminitrasi Umum via Suara
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

            <form id="voiceForm" enctype="multipart/form-data">
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
                Proses penyimpanan modul Adminitrasi Umum
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
        const requiredFieldsByModul = {
            1: ["kdperaturan", "kdjenisperaturandesa", "nomorperaturan", "judulpengaturan"],
            2: ["kd_keputusan", "nomor_keputusan", "tanggal_keputusan", "judul_keputusan", "kdjeniskeputusan_umum"],
            3: ["kdaparat", "namaaparat", "statusaparatdesa"],
            4: ["kdtanahkasdesa", "luastanahkasdesa", "tanggaltanahkasdesa"],
            5: ["kdtanahdesa", "tanggaltanahdesa", "luastanahdesa"],
            6: ["kdagendalembaga", "kdjenisagenda_umum", "agendalembaga_tanggal"],
            7: ["kdekspedisi", "ekspedisi_tanggal"],
            8: ["kdinventaris", "inventaris_tanggal", "inventaris_identitas"],
        };

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
            3: {},
            4: {},
            5: {},
            6: {},
            7: {},
            8: {},

        };

        let allowListen = true;

        let modulStatus = {
            1: 'active',
            2: 'pending',
            3: 'pending',
            4: 'pending',
            5: 'pending',
            6: 'pending',
            7: 'pending',
            8: 'pending',
        };
        let stepByModul = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0,
            7: 0,
            8: 0,
        };



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
        let isPaused = true
        let questionTimeout = null;
        let isProcessingAnswer = false;
        let micStream = null;
        const reviewableModules = [1, 2, 3, 4, 5, 6, 7, 8];

        /* ============================================================
           MODUL
        ============================================================ */
        const modules = [{
                id: 1,
                name: "Buku Peraturan"
            },
            {
                id: 2,
                name: "Buku Keputusan"
            },
            {
                id: 3,
                name: "Buku Aparat"
            },
            {
                id: 4,
                name: "Kas Tanah Desa"
            },
            {
                id: 5,
                name: "Tanah Desa"
            },
            {
                id: 6,
                name: "Agenda Lembaga"
            },
            {
                id: 7,
                name: "Ekspedisi"
            },
            {
                id: 8,
                name: "Inventaris"
            }
        ];


        /* ============================================================
           QUESTIONS
        ============================================================ */
        const questions = {

            /* =====================================================
               1️⃣ BUKU PERATURAN
            ===================================================== */
            1: [{
                    type: "text",
                    label: "Sebutkan kode peraturan",
                    field: "kdperaturan"
                },
                {
                    type: "select",
                    label: "Apa jenis peraturan desa",
                    field: "kdjenisperaturandesa",
                    options: masters.jenis_peraturan
                },
                {
                    type: "text",
                    label: "Sebutkan nomor peraturan",
                    field: "nomorperaturan"
                },
                {
                    type: "text",
                    label: "Apa judul pengaturan peraturan ini",
                    field: "judulpengaturan"
                },
                {
                    type: "textarea",
                    label: "Apa uraian peraturan ini, jika tidak ada katakan tidak ada",
                    field: "uraianperaturan"
                },
                {
                    type: "textarea",
                    label: "Apa kesepakatan peraturan ini, jika tidak ada katakan tidak ada",
                    field: "kesepakatanperaturan"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "keteranganperaturan"
                }
            ],

            /* =====================================================
               2️⃣ BUKU KEPUTUSAN
            ===================================================== */
            2: [{
                    type: "text",
                    label: "Sebutkan kode keputusan",
                    field: "kd_keputusan"
                },
                {
                    type: "text",
                    label: "Sebutkan nomor keputusan",
                    field: "nomor_keputusan"
                },
                {
                    type: "date",
                    label: "Kapan tanggal keputusan ditetapkan",
                    field: "tanggal_keputusan"
                },
                {
                    type: "text",
                    label: "Apa judul keputusan",
                    field: "judul_keputusan"
                },
                {
                    type: "select",
                    label: "Apa jenis keputusan umum",
                    field: "kdjeniskeputusan_umum",
                    options: masters.jenis_keputusan
                },
                {
                    type: "textarea",
                    label: "Apa uraian keputusan, jika tidak ada katakan tidak ada",
                    field: "uraian_keputusan"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "keterangan_keputusan"
                }
            ],

            /* =====================================================
               3️⃣ BUKU APARAT DESA
            ===================================================== */
            3: [{
                    type: "select",
                    label: "Apa Jenis aparat desa",
                    field: "kdaparat",
                    options: masters.aparat
                },
                {
                    type: "text",
                    label: "Sebutkan nama aparat desa",
                    field: "namaaparat"
                },
                {
                    type: "text",
                    label: "Sebutkan NIP aparat, jika tidak ada katakan tidak ada",
                    field: "nipaparat"
                },
                {
                    type: "text",
                    label: "Sebutkan NIK aparat, jika tidak ada katakan tidak ada",
                    field: "nik"
                },
                {
                    type: "text",
                    label: "Apa pangkat aparat desa",
                    field: "pangkataparat"
                },
                {
                    type: "text",
                    label: "Sebutkan nomor pengangkatan aparat",
                    field: "nomorpengangkatan"
                },
                {
                    type: "date",
                    label: "Kapan tanggal pengangkatan aparat",
                    field: "tanggalpengangkatan"
                },
                {
                    type: "select",
                    label: "Apa status aparat desa",
                    field: "statusaparatdesa",
                    options: {
                        Aktif: "Aktif",
                        Nonaktif: "Nonaktif"
                    }
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan aparat desa",
                    field: "keteranganaparatdesa"
                }
            ],

            /* =====================================================
               4️⃣ TANAH KAS DESA (🔥 MASTER LENGKAP)
            ===================================================== */
            4: [{
                    type: "text",
                    label: "Sebutkan kode tanah kas desa",
                    field: "kdtanahkasdesa"
                },
                {
                    type: "text",
                    label: "Apa asal tanah kas desa",
                    field: "asaltanahkasdesa"
                },
                {
                    type: "text",
                    label: "Apa nomor sertifikat tanah kas desa",
                    field: "sertifikattanahkasdesa"
                },
                {
                    type: "text",
                    label: "Berapa luas tanah kas desa",
                    field: "luastanahkasdesa"
                },
                {
                    type: "text",
                    label: "Apa kelas tanah kas desa",
                    field: "kelastanahkasdesa"
                },
                {
                    type: "text",
                    label: "Apa status mutasi tanah kas desa",
                    field: "mutasitanahkasdesa"
                },
                {
                    type: "date",
                    label: "Kapan tanggal tanah kas desa dicatat",
                    field: "tanggaltanahkasdesa"
                },

                {
                    type: "select",
                    label: "Bagaimana cara perolehan tanah kas desa",
                    field: "kdperolehantkd",
                    options: masters.perolehantkd
                },
                {
                    type: "select",
                    label: "Apa jenis tanah kas desa",
                    field: "kdjenistkd",
                    options: masters.jenistkd
                },
                {
                    type: "select",
                    label: "Apakah tanah kas desa memiliki patok batas",
                    field: "kdpatok",
                    options: masters.patok
                },
                {
                    type: "select",
                    label: "Apakah tanah kas desa memiliki papan nama",
                    field: "kdpapannama",
                    options: masters.papannama
                },

                {
                    type: "text",
                    label: "Di mana lokasi tanah kas desa",
                    field: "lokasitanahkasdesa"
                },
                {
                    type: "textarea",
                    label: "Apa peruntukan tanah kas desa",
                    field: "peruntukantanahkasdesa"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "keterangantanahkasdesa"
                }
            ],

            /* =====================================================
               5️⃣ TANAH DESA (🔥 MASTER LENGKAP)
            ===================================================== */
            5: [{
                    type: "text",
                    label: "Sebutkan kode tanah desa",
                    field: "kdtanahdesa"
                },
                {
                    type: "date",
                    label: "Kapan tanggal tanah desa dicatat",
                    field: "tanggaltanahdesa"
                },
                {
                    type: "select",
                    label: "Apa jenis pemilik tanah desa",
                    field: "kdjenispemilik",
                    options: masters.jenispemilik
                },
                {
                    type: "text",
                    label: "Siapa pemilik tanah desa",
                    field: "pemiliktanahdesa"
                },
                {
                    type: "select",
                    label: "Apa status hak tanah",
                    field: "kdstatushaktanah",
                    options: masters.statushak_tanah
                },
                {
                    type: "select",
                    label: "Apa penggunaan tanah desa",
                    field: "kdpenggunaantanah",
                    options: masters.penggunaan_tanah
                },
                {
                    type: "select",
                    label: "Apa status mutasi tanah",
                    field: "kdmutasitanah",
                    options: masters.mutasi_tanah
                },

                {
                    type: "text",
                    label: "Berapa luas tanah desa",
                    field: "luastanahdesa"
                },
                {
                    type: "date",
                    label: "Kapan tanggal mutasi tanah desa",
                    field: "tanggalmutasitanahdesa"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tanah desa",
                    field: "keterangantanahdesa"
                }
            ],

            /* =====================================================
               6️⃣ AGENDA LEMBAGA
            ===================================================== */
            6: [{
                    type: "text",
                    label: "Sebutkan kode agenda lembaga",
                    field: "kdagendalembaga"
                },
                {
                    type: "select",
                    label: "Apa jenis agenda lembaga",
                    field: "kdjenisagenda_umum",
                    options: masters.jenis_agenda
                },
                {
                    type: "date",
                    label: "Kapan tanggal agenda lembaga",
                    field: "agendalembaga_tanggal"
                },
                {
                    type: "text",
                    label: "Apa identitas surat agenda lembaga",
                    field: "agendalembaga_identitassurat"
                },
                {
                    type: "text",
                    label: "Sebutkan nomor surat agenda, jika ada",
                    field: "agendalembaga_nomorsurat"
                },
                {
                    type: "date",
                    label: "Kapan tanggal surat agenda, jika ada",
                    field: "agendalembaga_tanggalsurat"
                },
                {
                    type: "textarea",
                    label: "Apa isi surat agenda",
                    field: "agendalembaga_isisurat"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "agendalembaga_keterangan"
                }
            ],

            /* =====================================================
               7️⃣ EKSPEDISI
            ===================================================== */
            7: [{
                    type: "text",
                    label: "Sebutkan kode ekspedisi",
                    field: "kdekspedisi"
                },
                {
                    type: "date",
                    label: "Kapan tanggal ekspedisi",
                    field: "ekspedisi_tanggal"
                },
                {
                    type: "text",
                    label: "Apa identitas surat ekspedisi",
                    field: "ekspedisi_identitassurat"
                },
                {
                    type: "text",
                    label: "Sebutkan nomor surat ekspedisi",
                    field: "ekspedisi_nomorsurat"
                },
                {
                    type: "date",
                    label: "Kapan tanggal surat ekspedisi",
                    field: "ekspedisi_tanggalsurat"
                },
                {
                    type: "textarea",
                    label: "Apa isi surat ekspedisi",
                    field: "ekspedisi_isisurat"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "ekspedisi_keterangan"
                }
            ],

            /* =====================================================
               8️⃣ INVENTARIS (🔥 MASTER LENGKAP)
            ===================================================== */
            8: [{
                    type: "text",
                    label: "Sebutkan kode inventaris",
                    field: "kdinventaris"
                },
                {
                    type: "date",
                    label: "Kapan tanggal inventaris dicatat",
                    field: "inventaris_tanggal"
                },

                {
                    type: "select",
                    label: "Siapa pengguna barang inventaris",
                    field: "kdpengguna",
                    options: masters.pengguna
                },
                {
                    type: "select",
                    label: "Apa satuan barang inventaris",
                    field: "kdsatuanbarang",
                    options: masters.satuanbarang
                },
                {
                    type: "select",
                    label: "Apa asal barang inventaris",
                    field: "kdasalbarang",
                    options: masters.asalbarang
                },

                {
                    type: "text",
                    label: "Apa identitas barang inventaris",
                    field: "inventaris_identitas"
                },
                {
                    type: "text",
                    label: "Berapa volume barang inventaris",
                    field: "inventaris_volume"
                },
                {
                    type: "text",
                    label: "Berapa harga barang inventaris",
                    field: "inventaris_harga"
                },
                {
                    type: "textarea",
                    label: "Apakah ada keterangan tambahan",
                    field: "inventaris_keterangan"
                }
            ]
        };


        /* ============================================================
           UTIL
        ============================================================ */
        function normalize(t) {
            return t.toLowerCase().replace(/[^a-z0-9 ]/g, '').trim();
        }

        function normalizeSlash(text) {
            return text
                .toLowerCase()
                .replace(/\s*miring\s*/g, "/")
                .replace(/\s+/g, "");
        }

        function setVoiceStatus(msg) {
            const el = document.getElementById("voice-status");
            if (el) el.innerText = msg;
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

                // pastikan mic berhenti saat TTS
                pauseMic();

                const u = new SpeechSynthesisUtterance(text);
                u.lang = "id-ID";

                u.onend = () => {
                    isSpeaking = false;

                    // mic jangan auto nyala di sini kalau sedang paused
                    // nanti yang nyalakan adalah flow pertanyaan
                    resolve();
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

        async function askQuestionWithMicOff() {
            pauseMic(); // ✅ mic mati
            await speakQuestion(); // ✅ TTS jalan
            resumeMic(); // ✅ mic nyala lagi
            startQuestionTimeout(); // lanjut timeout setelah mic nyala
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




        function switchModul(id) {
            stopListening();
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
            renderQuestion();
            updateProgressBar();
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
            setVoiceStatus(isPaused ? "Tekan mic untuk mulai merekam..." : "Mendengarkan...");

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
                c.onclick = () => processManualAnswer(c.innerText);
            });


            // 🔥 pastikan browser commit DOM
            requestAnimationFrame(() => {
                isRendering = false;
            });
        }

        function hardStopInteraction() {
            // stop timeout ulang pertanyaan
            clearQuestionTimeout();

            // stop TTS kalau lagi ngomong
            try {
                speechSynthesis.cancel();
            } catch (e) {}
            isSpeaking = false;

            // ❌ JANGAN matikan mic permanen
            // allowListen = false;

            // ❌ JANGAN abort recognition di sini (biar mic tetap nyala)
            // if (recognition) { ...abort... }
        }

        async function processManualAnswer(text) {
            // ❌ JANGAN reset mic
            // ❌ JANGAN ubah isPaused / isListening
            // ❌ JANGAN remove class recording

            // cukup hentikan TTS & timeout
            clearQuestionTimeout();
            try {
                speechSynthesis.cancel();
            } catch (e) {}
            isSpeaking = false;

            // proses jawaban
            await processVoiceAnswer(text);

            // mic tetap dianggap aktif → UI tetap merah
            if (!isPaused) {
                resumeMic();
            }
        }


        function startQuestionTimeout() {
            clearQuestionTimeout();

            questionTimeout = setTimeout(async () => {
                if (isSpeaking || isProcessingAnswer || isPaused) return;

                pauseMic();
                await speak("Saya ulangi pertanyaannya");
                await speakQuestion();
                resumeMic();
                startQuestionTimeout();
            }, 9000);

        }

        function clearQuestionTimeout() {
            if (questionTimeout) {
                clearTimeout(questionTimeout);
                questionTimeout = null;
            }
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

            /* =====================================================
               1️⃣ BUKU PERATURAN
            ===================================================== */
            if (currentModul === 1) {
                add("Kode Peraturan",
                    `<input name="kdperaturan" value="${data.kdperaturan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Jenis Peraturan", buildSelect("kdjenisperaturandesa", masters.jenis_peraturan, data
                    .kdjenisperaturandesa));
                add("Nomor Peraturan",
                    `<input name="nomorperaturan" value="${data.nomorperaturan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Judul Peraturan",
                    `<input name="judulpengaturan" value="${data.judulpengaturan||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Uraian</label>
            <textarea name="uraianperaturan" class="w-full rounded-lg border-gray-300">${data.uraianperaturan||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Kesepakatan</label>
            <textarea name="kesepakatanperaturan" class="w-full rounded-lg border-gray-300">${data.kesepakatanperaturan||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="keteranganperaturan" class="w-full rounded-lg border-gray-300">${data.keteranganperaturan||''}</textarea>
        </div>`;
                add("File Peraturan",
                    `<input type="file" name="filepengaturan"
        class="w-full rounded-lg border-gray-300">`
                );

            }

            /* =====================================================
               2️⃣ BUKU KEPUTUSAN
            ===================================================== */
            if (currentModul === 2) {
                add("Kode Keputusan",
                    `<input name="kd_keputusan" value="${data.kd_keputusan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Nomor Keputusan",
                    `<input name="nomor_keputusan" value="${data.nomor_keputusan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Keputusan",
                    `<input type="date" name="tanggal_keputusan" value="${data.tanggal_keputusan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Judul Keputusan",
                    `<input name="judul_keputusan" value="${data.judul_keputusan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Jenis Keputusan", buildSelect("kdjeniskeputusan_umum", masters.jenis_keputusan, data
                    .kdjeniskeputusan_umum));

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Uraian</label>
            <textarea name="uraian_keputusan" class="w-full rounded-lg border-gray-300">${data.uraian_keputusan||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="keterangan_keputusan" class="w-full rounded-lg border-gray-300">${data.keterangan_keputusan||''}</textarea>
        </div>`;
                add("File Keputusan",
                    `<input type="file" name="file_keputusan"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               3️⃣ BUKU APARAT
            ===================================================== */
            if (currentModul === 3) {
                add("Jenis Aparat", buildSelect("kdaparat", masters.aparat, data.kdaparat));

                add("Nama Aparat",
                    `<input name="namaaparat" value="${data.namaaparat||''}" class="w-full rounded-lg border-gray-300">`
                );

                add("NIP",
                    `<input name="nipaparat" value="${data.nipaparat||''}" class="w-full rounded-lg border-gray-300">`);
                add("NIK", `<input name="nik" value="${data.nik||''}" class="w-full rounded-lg border-gray-300">`);
                add("Pangkat",
                    `<input name="pangkataparat" value="${data.pangkataparat||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Nomor Pengangkatan",
                    `<input name="nomorpengangkatan" value="${data.nomorpengangkatan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Pengangkatan",
                    `<input type="date" name="tanggalpengangkatan" value="${data.tanggalpengangkatan||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Status", buildSelect("statusaparatdesa", {
                    Aktif: "Aktif",
                    Nonaktif: "Nonaktif"
                }, data.statusaparatdesa));

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="keteranganaparatdesa" class="w-full rounded-lg border-gray-300">${data.keteranganaparatdesa||''}</textarea>
        </div>`;
                add("Foto Pengangkatan Aparat",
                    `<input type="file"
        name="fotopengangkatan"
        accept="image/*"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               4️⃣ TANAH KAS DESA (LENGKAP)
            ===================================================== */
            if (currentModul === 4) {
                add("Kode Tanah Kas Desa",
                    `<input name="kdtanahkasdesa" value="${data.kdtanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Asal Tanah",
                    `<input name="asaltanahkasdesa" value="${data.asaltanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Sertifikat",
                    `<input name="sertifikattanahkasdesa" value="${data.sertifikattanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Luas Tanah",
                    `<input name="luastanahkasdesa" value="${data.luastanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Kelas Tanah",
                    `<input name="kelastanahkasdesa" value="${data.kelastanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );

                add("Mutasi Tanah",
                    `<input name="mutasitanahkasdesa" value="${data.mutasitanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Pencatatan",
                    `<input type="date" name="tanggaltanahkasdesa" value="${data.tanggaltanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );

                add("Perolehan Tanah", buildSelect("kdperolehantkd", masters.perolehantkd, data.kdperolehantkd));
                add("Jenis TKD", buildSelect("kdjenistkd", masters.jenistkd, data.kdjenistkd));
                add("Patok Batas", buildSelect("kdpatok", masters.patok, data.kdpatok));
                add("Papan Nama", buildSelect("kdpapannama", masters.papannama, data.kdpapannama));

                add("Lokasi",
                    `<input name="lokasitanahkasdesa" value="${data.lokasitanahkasdesa||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Peruntukan</label>
            <textarea name="peruntukantanahkasdesa" class="w-full rounded-lg border-gray-300">${data.peruntukantanahkasdesa||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="keterangantanahkasdesa" class="w-full rounded-lg border-gray-300">${data.keterangantanahkasdesa||''}</textarea>
        </div>`;
                add("Foto Tanah Kas Desa",
                    `<input type="file" name="fototanahkasdesa"
        accept="image/*"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               5️⃣ TANAH DESA (LENGKAP)
            ===================================================== */
            if (currentModul === 5) {
                add("Kode Tanah Desa",
                    `<input name="kdtanahdesa" value="${data.kdtanahdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Pencatatan",
                    `<input type="date" name="tanggaltanahdesa" value="${data.tanggaltanahdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Pemilik",
                    `<input name="pemiliktanahdesa" value="${data.pemiliktanahdesa||''}" class="w-full rounded-lg border-gray-300">`
                );

                add("Jenis Pemilik", buildSelect("kdjenispemilik", masters.jenispemilik, data.kdjenispemilik));
                add("Status Hak Tanah", buildSelect("kdstatushaktanah", masters.statushak_tanah, data.kdstatushaktanah));
                add("Penggunaan Tanah", buildSelect("kdpenggunaantanah", masters.penggunaan_tanah, data.kdpenggunaantanah));
                add("Mutasi Tanah", buildSelect("kdmutasitanah", masters.mutasi_tanah, data.kdmutasitanah));

                add("Luas Tanah",
                    `<input name="luastanahdesa" value="${data.luastanahdesa||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Mutasi",
                    `<input type="date" name="tanggalmutasitanahdesa" value="${data.tanggalmutasitanahdesa||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="keterangantanahdesa" class="w-full rounded-lg border-gray-300">${data.keterangantanahdesa||''}</textarea>
        </div>`;
                add("Foto Tanah Desa",
                    `<input type="file" name="fototanahdesa"
        accept="image/*"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               6️⃣ AGENDA LEMBAGA
            ===================================================== */
            if (currentModul === 6) {
                add("Kode Agenda",
                    `<input name="kdagendalembaga" value="${data.kdagendalembaga||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Jenis Agenda", buildSelect("kdjenisagenda_umum", masters.jenis_agenda, data.kdjenisagenda_umum));
                add("Tanggal Agenda",
                    `<input type="date" name="agendalembaga_tanggal" value="${data.agendalembaga_tanggal||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Identitas Surat",
                    `<input name="agendalembaga_identitassurat" value="${data.agendalembaga_identitassurat||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Nomor Surat",
                    `<input name="agendalembaga_nomorsurat" value="${data.agendalembaga_nomorsurat||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Surat",
                    `<input type="date" name="agendalembaga_tanggalsurat" value="${data.agendalembaga_tanggalsurat||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Isi Surat</label>
            <textarea name="agendalembaga_isisurat" class="w-full rounded-lg border-gray-300">${data.agendalembaga_isisurat||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="agendalembaga_keterangan" class="w-full rounded-lg border-gray-300">${data.agendalembaga_keterangan||''}</textarea>
        </div>`;
                add("File Agenda",
                    `<input type="file" name="agendalembaga_file"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               7️⃣ EKSPEDISI
            ===================================================== */
            if (currentModul === 7) {
                add("Kode Ekspedisi",
                    `<input name="kdekspedisi" value="${data.kdekspedisi||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Ekspedisi",
                    `<input type="date" name="ekspedisi_tanggal" value="${data.ekspedisi_tanggal||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Identitas Surat",
                    `<input name="ekspedisi_identitassurat" value="${data.ekspedisi_identitassurat||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Nomor Surat",
                    `<input name="ekspedisi_nomorsurat" value="${data.ekspedisi_nomorsurat||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Surat",
                    `<input type="date" name="ekspedisi_tanggalsurat" value="${data.ekspedisi_tanggalsurat||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Isi Surat</label>
            <textarea name="ekspedisi_isisurat" class="w-full rounded-lg border-gray-300">${data.ekspedisi_isisurat||''}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="ekspedisi_keterangan" class="w-full rounded-lg border-gray-300">${data.ekspedisi_keterangan||''}</textarea>
        </div>`;
                add("File Ekspedisi",
                    `<input type="file" name="ekspedisi_file"
        class="w-full rounded-lg border-gray-300">`
                );
            }

            /* =====================================================
               8️⃣ INVENTARIS (LENGKAP)
            ===================================================== */
            if (currentModul === 8) {
                add("Kode Inventaris",
                    `<input name="kdinventaris" value="${data.kdinventaris||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Tanggal Inventaris",
                    `<input type="date" name="inventaris_tanggal" value="${data.inventaris_tanggal||''}" class="w-full rounded-lg border-gray-300">`
                );

                add("Pengguna Barang", buildSelect("kdpengguna", masters.pengguna, data.kdpengguna));
                add("Satuan Barang", buildSelect("kdsatuanbarang", masters.satuanbarang, data.kdsatuanbarang));
                add("Asal Barang", buildSelect("kdasalbarang", masters.asalbarang, data.kdasalbarang));

                add("Identitas Barang",
                    `<input name="inventaris_identitas" value="${data.inventaris_identitas||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Volume",
                    `<input name="inventaris_volume" value="${data.inventaris_volume||''}" class="w-full rounded-lg border-gray-300">`
                );
                add("Harga",
                    `<input name="inventaris_harga" value="${data.inventaris_harga||''}" class="w-full rounded-lg border-gray-300">`
                );

                c.innerHTML += `
        <div class="sm:col-span-2">
            <label>Keterangan</label>
            <textarea name="inventaris_keterangan" class="w-full rounded-lg border-gray-300">${data.inventaris_keterangan||''}</textarea>
        </div>`;
                add("Foto Inventaris",
                    `<input type="file" name="inventaris_foto"
        accept="image/*"
        class="w-full rounded-lg border-gray-300">`
                );
            }
        }

        /* ============================================================
           VOICE
        ============================================================ */
        function startListening() {
            if (!isPaused) return;

            isPaused = false;
            setVoiceStatus("Mendengarkan...");

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

                document.getElementById("voice-status").innerText = text;
                clearQuestionTimeout();

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

        function stopListening() {
            isPaused = true;
            setVoiceStatus("Tekan mic untuk mulai merekam...");

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
            const form = document.getElementById("voiceForm");
            const data = new FormData(form);

            const required = requiredFieldsByModul[currentModul] || [];
            let errors = [];

            // reset style error lama
            form.querySelectorAll("input, select, textarea").forEach(el => {
                el.classList.remove("border-red-500");
            });

            required.forEach(field => {
                const el = form.querySelector(`[name="${field}"]`);
                if (!el) return;

                const value = data.get(field);

                if (value === null || value === "") {
                    errors.push(`Field <b>${field}</b> wajib diisi`);
                    el.classList.add("border-red-500");
                }
            });

            if (errors.length > 0) {
                document.getElementById("errorText").innerHTML =
                    errors.map(e => `<div>• ${e}</div>`).join("");

                document.getElementById("errorModal").classList.remove("hidden");
                return false;
            }

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

        function normalizeNomor(text) {
            if (!text) return "";
            return text
                .toLowerCase()
                .replace(/\b(garis miring|slash|miring)\b/g, "/")
                .replace(/\b(titik)\b/g, ".")
                .replace(/\b(strip|dash|minus)\b/g, "-")
                .replace(/\s+/g, "") // buang spasi
                .replace(/\/{2,}/g, "/") // rapikan kalau double //
                .trim();
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
            document.getElementById("voice-status").innerText = "Mendengarkan...";
        }
        async function processVoiceAnswer(text) {
            if (isProcessingAnswer) return;
            isProcessingAnswer = true;
            clearQuestionTimeout();

            try {
                const q = questions[currentModul][step];
                if (!q) throw "retry";

                let value = text;

                /* ===============================
                   NULLABLE (tidak ada / skip)
                =============================== */
                value = normalizeNullable(value);
                if (value === null) {
                    await speak("Baik, dikosongkan");
                    answersByModul[currentModul][q.field] = null;
                    step++;
                    updateQuestionCounter();
                    finishOrNext();
                    return;
                }
                if (q.field.startsWith("kd")) {
                    value = normalizeSk(text);
                }
                /* ===============================
                   TEXTAREA / KETERANGAN
                =============================== */
                if (q.type === "textarea") {
                    value = normalizeKeterangan(text);
                }
                // ===============================
                // NOMOR / KODE SURAT / SK (pakai slash)
                // ===============================
                if ([
                        "nomorperaturan",
                        "nomor_keputusan",
                        "nomorpengangkatan",
                        "agendalembaga_nomorsurat",
                        "ekspedisi_nomorsurat",
                        "sertifikattanahkasdesa" // ✅ TAMBAHKAN INI

                    ].includes(q.field)) {
                    value = normalizeNomor(text); // <- hasilkan 12/45/75
                }

                /* ===============================
                   FIELD SELECT (MASTER DATA)
                =============================== */
                if (q.type === "select") {
                    const match = findBestMatch(text, q.options);
                    if (!match) {
                        await speak("Pilihan tidak dikenali, silakan ulangi");
                        resetToListening();
                        throw "retry";
                    }
                    value = match[0]; // simpan ID
                }

                /* ===============================
                   FIELD KODE (bebas tapi rapih)
                =============================== */
                if (q.field.startsWith("kd")) {
                    value = normalizeSk(text);
                }

                /* ===============================
                   TANGGAL
                =============================== */
                if (q.type === "date") {
                    const d = normalizeDate(text);
                    if (!d) {
                        await speak("Format tanggal tidak valid, silakan ulangi");
                        resetToListening();
                        throw "retry";
                    }
                    value = d;
                }

                /* ===============================
                   ANGKA / LUAS / VOLUME
                =============================== */
                if ([
                        "luastanahdesa",
                        "luastanahkasdesa",
                        "inventaris_volume"
                    ].includes(q.field)) {
                    value = text.replace(/[^\d]/g, "");
                    if (!value) {
                        await speak("Nilai angka tidak valid");
                        resetToListening();
                        throw "retry";
                    }
                }

                /* ===============================
                   HARGA / UANG
                =============================== */
                if (q.field === "inventaris_harga") {
                    value = normalizeMoney(text);
                    if (!value || value === "0") {
                        await speak("Harga tidak valid, silakan ulangi");
                        resetToListening();
                        throw "retry";
                    }
                }

                /* ===============================
                   NAMA / JUDUL (TIDAK BOLEH ANGKA)
                =============================== */
                if ([
                        "namaaparat", // ✅ tambah
                        "judulpengaturan",
                        "judul_keputusan",
                        "pemiliktanahdesa",
                        "inventaris_identitas"
                    ].includes(q.field)) {
                    if (/\d/.test(value)) {
                        await speak("Isian tidak boleh mengandung angka");
                        resetToListening();
                        throw "retry";
                    }
                }

                /* ===============================
                   SIMPAN JAWABAN
                =============================== */
                answersByModul[currentModul][q.field] = value;

                const input = document.getElementById("inputAnswer");
                if (input) input.value = text;

                await new Promise(r => setTimeout(r, 300));

                step++;
                updateQuestionCounter();
                updateProgressBar();
                finishOrNext();

            } catch (e) {
                // retry handled
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
                }, 300);

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
        updateProgressBar();

        document.getElementById("saveModulBtn").onclick = async () => {
            if (!validateReviewForm()) return;

            // === KHUSUS MODUL 5 ===

            const form = new FormData(document.getElementById("voiceForm"));
            form.append("modul", currentModul);

            document.getElementById("loadingOverlay").classList.remove("hidden");

            try {
                const res = await fetch("{{ route('voice.admin-umum.store') }}", {

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
        document.getElementById("skipBtn").onclick = async () => {
            hardStopInteraction(); // ✅ stop TTS + mic + timeout

            // Anggap jawaban NULL / kosong sesuai sistem kamu
            const q = questions[currentModul][step];
            if (!q) return;

            answersByModul[currentModul][q.field] = null;

            step++;
            updateQuestionCounter();
            updateProgressBar();
            finishOrNext();
        };
    </script>


</x-app-layout>
