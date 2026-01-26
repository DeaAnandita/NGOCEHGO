document.addEventListener("DOMContentLoaded", () => {
    const synth = window.speechSynthesis;
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = "id-ID";
    recognition.interimResults = false;

    const startBtn = document.getElementById("start-btn");
    const stopBtn = document.getElementById("stop-btn");
    const questionEl = document.getElementById("question");
    const answerEl = document.getElementById("answer");
    const form = document.getElementById("keluargaForm") || document.getElementById("pendudukForm");
    const wilayahDiv = document.getElementById("wilayah-datang");

    let step = 0;
    let isDatang = false;
    let currentMode = "keluarga"; // "keluarga" atau "penduduk"

    // ==================== PERTANYAAN KELUARGA ====================
    const keluargaQuestions = [
        "Jenis mutasi apa? Tetap, lahir, atau datang?",
        "Tanggal mutasi kapan?",
        "Sebutkan nomor kartu keluarga",
        "Siapa nama kepala keluarga?",
        "Dusun atau lingkungan apa?",
        "RW berapa?",
        "RT berapa?",
        "Sebutkan alamat lengkap"
    ];

    // ==================== PERTANYAAN PENDUDUK ====================
    const pendudukQuestions = [
        "Apa pekerjaannya?",
        "Sebutkan nomor NIK",
        "Sebutkan nama lengkap penduduk",
        "Di mana tempat lahirnya?",
        "Tanggal lahir? format YYYY-MM-DD",
        "Apa golongan darahnya? A, AB, B, atau O?",
        "Sebutkan nomor akta lahir",
        "Apa jenis kelaminnya? Laki-laki atau perempuan?",
        "Apa agamanya?",
        "Sebutkan nomor kartu keluarga",
        "Sebutkan nomor urut dalam KK",
        "Apa hubungan dalam keluarga?",
        "Apa hubungan dengan kepala keluarga?",
        "Apa status perkawinannya?",
        "Memiliki akta nikah?",
        "Apakah tercantum dalam KK?",
        "Apa status tinggalnya?",
        "Jenis kartu identitas apa?",
        "Jenis mutasi apa? Tetap, lahir, atau datang?",
        "Sebutkan nama ayah",
        "Sebutkan nama ibu",
        "Sebutkan nama tempat bekerja jika ada"
    ];

    const questions = currentMode === "keluarga" ? keluargaQuestions : pendudukQuestions;

    function speak(text, callback) {
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = "id-ID";
        utter.onend = callback;
        synth.speak(utter);
    }

    function listen(callback) {
        recognition.start();
        recognition.onresult = (e) => {
            const text = e.results[0][0].transcript.toLowerCase();
            callback(text);
        };
    }

    function formatRT_RW(num) {
        return num.padStart(3, '0');
    }

    function nextQuestion() {
        const currentQuestions = currentMode === "keluarga" ? keluargaQuestions : pendudukQuestions;
        
        if (step < currentQuestions.length) {
            questionEl.textContent = currentQuestions[step];
            speak(currentQuestions[step], () => listen(handleAnswer));
        } else if (isDatang) {
            speak("Karena mutasi datang, sebutkan asal wilayah Anda.", askWilayahDatang);
        } else {
            speak("Semua data sudah diisi. Silakan tekan tombol simpan.");
            questionEl.textContent = "✅ Semua pertanyaan selesai.";
        }
    }

    function handleAnswer(answer) {
        answerEl.textContent = `"${answer}"`;

        if (currentMode === "keluarga") {
            handleKeluargaAnswer(answer);
        } else if (currentMode === "penduduk") {
            handlePendudukAnswer(answer);
        }

        step++;
        nextQuestion();
    }

    // ==================== HANDLE ANSWER KELUARGA ====================
    function handleKeluargaAnswer(answer) {
        switch (step) {
            case 0:
                if (answer.includes("datang")) {
                    isDatang = true;
                    document.querySelector("#kdmutasimasuk").value = 3;
                } else if (answer.includes("lahir")) {
                    document.querySelector("#kdmutasimasuk").value = 2;
                } else {
                    document.querySelector("#kdmutasimasuk").value = 1;
                }
                break;
            case 1:
                document.querySelector("#tanggal_mutasi").value = new Date().toISOString().split("T")[0];
                break;
            case 2:
                document.querySelector("#nokk").value = answer.replace(/\D/g, "").slice(0, 16);
                break;
            case 3:
                document.querySelector("#kepala_rumah_tangga").value = answer.split(" ").pop();
                break;
            case 4:
                selectOption("dusun", answer);
                break;
            case 5:
                document.querySelector("#rw").value = formatRT_RW(answer.replace(/\D/g, ""));
                break;
            case 6:
                document.querySelector("#rt").value = formatRT_RW(answer.replace(/\D/g, ""));
                break;
            case 7:
                document.querySelector("#alamat").value = answer;
                break;
        }
    }

    // ==================== HANDLE ANSWER PENDUDUK ====================
    function handlePendudukAnswer(answer) {
        switch (step) {
            case 0:
                selectOption("kdpekerjaan", answer);
                break;
            case 1:
                document.querySelector("#nik")?.value = answer.replace(/\D/g, "").slice(0, 16);
                break;
            case 2:
                document.querySelector("#penduduk_namalengkap")?.value = answer;
                break;
            case 3:
                document.querySelector("#penduduk_tempatlahir")?.value = answer;
                break;
            case 4:
                document.querySelector("#penduduk_tanggallahir")?.value = answer;
                break;
            case 5:
                const golDarah = { "a": "a", "ab": "ab", "b": "b", "o": "o" };
                const match = Object.keys(golDarah).find(key => answer.includes(key));
                if (match) document.querySelector("#penduduk_goldarah")?.value = golDarah[match];
                break;
            case 6:
                document.querySelector("#penduduk_noaktalahir")?.value = answer.replace(/\D/g, "");
                break;
            case 7:
                if (answer.includes("perempuan") || answer.includes("wanita")) {
                    selectOption("kdjeniskelamin", "perempuan");
                } else {
                    selectOption("kdjeniskelamin", "laki");
                }
                break;
            case 8:
                selectOption("kdagama", answer);
                break;
            case 9:
                document.querySelector("#no_kk")?.value = answer.replace(/\D/g, "").slice(0, 16);
                break;
            case 10:
                document.querySelector("#penduduk_nourutkk")?.value = answer.replace(/\D/g, "").padStart(2, "0");
                break;
            case 11:
                selectOption("kdhubungankeluarga", answer);
                break;
            case 12:
                selectOption("kdhubungankepalakeluarga", answer);
                break;
            case 13:
                selectOption("kdstatuskawin", answer);
                break;
            case 14:
                selectOption("kdaktanikah", answer);
                break;
            case 15:
                selectOption("kdtercantumdalamkk", answer);
                break;
            case 16:
                selectOption("kdstatustinggal", answer);
                break;
            case 17:
                selectOption("kdkartuidentitas", answer);
                break;
            case 18:
                if (answer.includes("datang")) {
                    isDatang = true;
                    selectOption("kdmutasimasuk", "datang");
                } else if (answer.includes("lahir")) {
                    isDatang = false;
                    selectOption("kdmutasimasuk", "lahir");
                } else {
                    isDatang = false;
                    selectOption("kdmutasimasuk", "tetap");
                }
                break;
            case 19:
                document.querySelector("#penduduk_namaayah")?.value = answer;
                break;
            case 20:
                document.querySelector("#penduduk_namaibu")?.value = answer;
                break;
            case 21:
                document.querySelector("#penduduk_namatempatbekerja")?.value = answer;
                break;
        }
    }

    function selectOption(id, text) {
        const select = document.getElementById(id);
        for (let opt of select.options) {
            if (opt.text.toLowerCase().includes(text)) {
                select.value = opt.value;
                return;
            }
        }
        select.value = "";
    }

    function askWilayahDatang() {
        wilayahDiv.classList.remove("hidden");
        const wilayahFields = [
            { id: "provinsi", q: "Dari provinsi mana?" },
            { id: "kabupaten", q: "Kabupaten atau kota apa?" },
            { id: "kecamatan", q: "Kecamatan apa?" },
            { id: "desa", q: "Desa atau kelurahan apa?" },
        ];

        let idx = 0;
        function askNextWilayah() {
            if (idx < wilayahFields.length) {
                const { id, q } = wilayahFields[idx];
                questionEl.textContent = q;
                speak(q, () => listen(answer => {
                    document.getElementById(id).value = answer;
                    idx++;
                    askNextWilayah();
                }));
            } else {
                speak("Data wilayah asal sudah lengkap. Silakan simpan data.");
                questionEl.textContent = "✅ Semua pertanyaan selesai.";
            }
        }
        askNextWilayah();
    }

    // ▶️ Start & Stop
    startBtn.addEventListener("click", () => {
        // Jika belum ada mode terpilih, tampilkan menu pemilihan
        if (!currentMode || currentMode === "undefined") {
            showModeSelection();
        } else {
            startBtn.classList.add("hidden");
            stopBtn.classList.remove("hidden");
            step = 0;
            isDatang = false;
            nextQuestion();
        }
    });

    // ==================== MODE SELECTION MENU ====================
    function showModeSelection() {
        const modes = [
            { text: "Input Data Keluarga", value: "keluarga" },
            { text: "Input Data Penduduk", value: "penduduk" }
        ];

        let modeIdx = 0;
        const announceMode = () => {
            if (modeIdx < modes.length) {
                const msg = `Pilihan ${modeIdx + 1}: ${modes[modeIdx].text}`;
                speak(msg, () => {
                    listen(answer => {
                        if (answer.includes("benar") || answer.includes("iya") || answer.includes("ya")) {
                            currentMode = modes[modeIdx].value;
                            speak(`Anda memilih ${modes[modeIdx].text}. Dimulai sekarang.`, () => {
                                startBtn.classList.add("hidden");
                                stopBtn.classList.remove("hidden");
                                step = 0;
                                isDatang = false;
                                nextQuestion();
                            });
                        } else {
                            modeIdx++;
                            announceMode();
                        }
                    });
                });
            }
        };

        questionEl.textContent = "🎯 Pilih jenis input data";
        speak("Silakan pilih. Ketikkan nomor atau jawab benar jika sudah tepat.", announceMode);
    }

    stopBtn.addEventListener("click", () => {
        recognition.stop();
        startBtn.classList.remove("hidden");
        stopBtn.classList.add("hidden");
        speak("Rekaman dihentikan.");
    });

    // 📨 Submit via AJAX
    form.addEventListener("submit", e => {
        e.preventDefault();
        
        const endpoint = currentMode === "keluarga" 
            ? "/voice/keluarga/store" 
            : "/voice/penduduk/store-all";
        
        fetch(endpoint, {
            method: "POST",
            body: new FormData(form),
            headers: { "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value }
        })
        .then(res => res.json())
        .then(res => {
            alert(res.message || "Data berhasil disimpan!");
            location.reload();
        })
        .catch(() => alert("Gagal menyimpan data."));
    });
});
