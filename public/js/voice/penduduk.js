document.addEventListener("DOMContentLoaded", () => {
    // 🧠 Setup Speech API
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    const synth = window.speechSynthesis;

    // ⚙️ Konfigurasi yang membuat pendengaran lebih maksimal
    recognition.lang = "id-ID";
    recognition.continuous = false;               // satu pertanyaan, satu dengar
    recognition.interimResults = true;            // tangkap hasil parsial
    recognition.maxAlternatives = 3;              // ambil sampai 3 alternatif pengenalan
    recognition.onspeechend = () => recognition.stop();

    const startBtn = document.getElementById("start-btn");
    const stopBtn = document.getElementById("stop-btn");
    const qEl = document.getElementById("question");
    const aEl = document.getElementById("answer");
    const form = document.getElementById("pendudukForm");

    let step = 0;
    let isMutasiDatang = false;
    let listening = false;

    const baseQuestions = [
        { label: "Apa pekerjaannya?", field: "kdpekerjaan" },
        { label: "Sebutkan nomor NIK", field: "nik" },
        { label: "Sebutkan nama lengkap penduduk", field: "penduduk_namalengkap" },
        { label: "Di mana tempat lahirnya?", field: "penduduk_tempatlahir" },
        { label: "Tanggal lahir? (format YYYY-MM-DD)", field: "penduduk_tanggallahir" },
        { label: "Apa golongan darahnya? A, AB, B, atau O?", field: "penduduk_goldarah" },
        { label: "Sebutkan nomor akta lahir", field: "penduduk_noaktalahir" },
        { label: "Apa jenis kelaminnya? Laki-laki atau perempuan?", field: "kdjeniskelamin" },
        { label: "Apa agamanya?", field: "kdagama" },
        { label: "Sebutkan nomor kartu keluarga", field: "no_kk" },
        { label: "Sebutkan nomor urut dalam KK", field: "penduduk_nourutkk" },
        { label: "Apa hubungan dalam keluarga?", field: "kdhubungankeluarga" },
        { label: "Apa hubungan dengan kepala keluarga?", field: "kdhubungankepalakeluarga" },
        { label: "Apa status perkawinannya?", field: "kdstatuskawin" },
        { label: "Memiliki akta nikah?", field: "kdaktanikah" },
        { label: "Apakah tercantum dalam KK?", field: "kdtercantumdalamkk" },
        { label: "Apa status tinggalnya?", field: "kdstatustinggal" },
        { label: "Jenis kartu identitas apa?", field: "kdkartuidentitas" },
        { label: "Jenis mutasi apa? Tetap, lahir, atau datang?", field: "kdmutasimasuk" },
        { label: "Sebutkan nama ayah", field: "penduduk_namaayah" },
        { label: "Sebutkan nama ibu", field: "penduduk_namaibu" },
        { label: "Sebutkan nama tempat bekerja jika ada", field: "penduduk_namatempatbekerja" }
    ];

    const wilayahQuestions = [
        { label: "Dari provinsi mana?", field: "kdprovinsi" },
        { label: "Kabupaten atau kota apa?", field: "kdkabupaten" },
        { label: "Kecamatan apa?", field: "kdkecamatan" },
        { label: "Desa atau kelurahan apa?", field: "kddesa" }
    ];

    let questions = [...baseQuestions];

    // 🔊 Fungsi bicara
    function speak(text, after) {
        const u = new SpeechSynthesisUtterance(text);
        u.lang = "id-ID";
        u.rate = 0.95;
        u.pitch = 1;
        u.volume = 1;
        u.onend = () => setTimeout(after, 500); // beri jeda 0.5 detik
        synth.cancel();
        synth.speak(u);
    }

    // 🎧 Dengarkan dengan retry otomatis
    function listen(callback, retry = 0) {
        if (listening) return;
        listening = true;

        let finalTranscript = "";
        recognition.start();

        recognition.onresult = e => {
            for (let i = e.resultIndex; i < e.results.length; ++i) {
                const transcript = e.results[i][0].transcript.trim().toLowerCase();
                if (e.results[i].isFinal) finalTranscript += transcript;
            }
            aEl.textContent = finalTranscript;
        };

        recognition.onerror = evt => {
            listening = false;
            if (retry < 2) {
                speak("Maaf, saya tidak mendengar dengan jelas. Tolong ulangi lagi.", () =>
                    listen(callback, retry + 1)
                );
            } else {
                speak("Jawaban tidak terdeteksi. Kita lanjut ke pertanyaan berikutnya.", () => {
                    callback("");
                });
            }
        };

        recognition.onend = () => {
            listening = false;
            if (finalTranscript) callback(finalTranscript);
        };
    }

    // 🧭 Isi dropdown sesuai text
    function selectOption(id, text) {
        const sel = document.getElementById(id);
        if (!sel) return;
        for (const opt of sel.options) {
            if (opt.text.toLowerCase().includes(text)) {
                sel.value = opt.value;
                return;
            }
        }
    }

    // 🌍 Pertanyaan wilayah datang
    function askWilayahDatang() {
        let i = 0;
        function ask() {
            if (i < wilayahQuestions.length) {
                const q = wilayahQuestions[i];
                qEl.textContent = q.label;
                speak(q.label, () => listen(ans => {
                    const input = document.getElementById(q.field);
                    if (input) {
                        if (input.tagName === "SELECT") {
                            selectOption(q.field, ans);
                        } else {
                            input.value = ans;
                        }
                    }
                    i++;
                    ask();
                }));
            } else {
                finish();
            }
        }
        ask();
    }

    // 🧩 Logic pertanyaan berurutan
    function nextQuestion() {
        if (step < questions.length) {
            const q = questions[step];
            const questionText = typeof q === 'string' ? q : q.label;
            qEl.textContent = questionText;
            speak(questionText, () => listen(handleAnswer));
        } else if (isMutasiDatang) {
            speak("Karena mutasi datang, sebutkan asal wilayah Anda.", askWilayahDatang);
        } else {
            finish();
        }
    }

    // 🧠 Proses jawaban dan isi field
    function handleAnswer(ans) {
        ans = ans.toLowerCase();
        const currentQuestion = questions[step];
        const fieldName = typeof currentQuestion === 'string' ? null : currentQuestion.field;

        if (!fieldName) return;

        // Mapping jawaban ke field input
        switch (fieldName) {
            case "kdpekerjaan":
                selectOption("kdpekerjaan", ans);
                break;
            case "nik":
            case "penduduk_namaayah":
            case "penduduk_namaibu":
            case "penduduk_namatempatbekerja":
                document.querySelector(`#${fieldName}`)?.value = ans;
                break;
            case "penduduk_namalengkap":
                document.querySelector(`#${fieldName}`)?.value = ans;
                break;
            case "penduduk_tempatlahir":
                document.querySelector(`#${fieldName}`)?.value = ans;
                break;
            case "penduduk_tanggallahir":
                document.querySelector(`#${fieldName}`)?.value = new Date().toISOString().split("T")[0];
                break;
            case "penduduk_goldarah":
                const golDarah = { "a": "a", "ab": "ab", "b": "b", "o": "o" };
                const match = Object.keys(golDarah).find(key => ans.includes(key));
                if (match) document.querySelector(`#${fieldName}`)?.value = golDarah[match];
                break;
            case "penduduk_noaktalahir":
            case "penduduk_nourutkk":
                document.querySelector(`#${fieldName}`)?.value = ans.replace(/\D/g, "");
                break;
            case "kdjeniskelamin":
                if (ans.includes("perempuan") || ans.includes("wanita")) {
                    selectOption("kdjeniskelamin", "perempuan");
                } else {
                    selectOption("kdjeniskelamin", "laki");
                }
                break;
            case "kdagama":
                selectOption("kdagama", ans);
                break;
            case "no_kk":
                document.querySelector(`#${fieldName}`)?.value = ans.replace(/\D/g, "").slice(0, 16);
                break;
            case "kdhubungankeluarga":
            case "kdhubungankepalakeluarga":
            case "kdstatuskawin":
            case "kdaktanikah":
            case "kdtercantumdalamkk":
            case "kdstatustinggal":
            case "kdkartuidentitas":
                selectOption(fieldName, ans);
                break;
            case "kdmutasimasuk":
                if (ans.includes("datang")) {
                    isMutasiDatang = true;
                    selectOption("kdmutasimasuk", "datang");
                } else if (ans.includes("lahir")) {
                    isMutasiDatang = false;
                    selectOption("kdmutasimasuk", "lahir");
                } else {
                    isMutasiDatang = false;
                    selectOption("kdmutasimasuk", "tetap");
                }
                // Update pertanyaan jika mutasi datang
                if (isMutasiDatang && !questions.some(q => q.field === "kdprovinsi")) {
                    questions.splice(step + 1, 0, ...wilayahQuestions);
                }
                break;
            case "kdprovinsi":
            case "kdkabupaten":
            case "kdkecamatan":
            case "kddesa":
                selectOption(fieldName, ans);
                break;
        }

        step++;
        nextQuestion();
    }

    function finish() {
        qEl.textContent = "✅ Semua pertanyaan selesai.";
        speak("Semua data sudah diisi. Silakan tekan tombol simpan data.");
    }

    // ▶️ Tombol Mulai & Stop
    startBtn?.addEventListener("click", () => {
        step = 0;
        isMutasiDatang = false;
        questions = [...baseQuestions];  // Reset pertanyaan
        startBtn.classList.add("hidden");
        stopBtn?.classList.remove("hidden");
        nextQuestion();
    });

    stopBtn?.addEventListener("click", () => {
        recognition.stop();
        listening = false;
        startBtn?.classList.remove("hidden");
        stopBtn.classList.add("hidden");
        speak("Rekaman dihentikan.");
    });

    // 💾 Kirim form via AJAX
    form?.addEventListener("submit", e => {
        e.preventDefault();
        fetch("/voice/penduduk/store", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": form.querySelector("[name=_token]")?.value },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(r => alert(r.message))
        .catch(() => alert("Terjadi kesalahan saat menyimpan data."));
    });
});
