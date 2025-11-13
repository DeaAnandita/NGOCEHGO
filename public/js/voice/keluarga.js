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
    const form = document.getElementById("keluargaForm");
    const wilayahDiv = document.getElementById("wilayah-datang");

    let step = 0;
    let isDatang = false;
    let listening = false;

    const questions = [
        "Jenis mutasi apa? Tetap, lahir, atau datang?",
        "Tanggal mutasi kapan?",
        "Sebutkan nomor kartu keluarga.",
        "Siapa nama kepala keluarga?",
        "Dusun atau lingkungan apa?",
        "RW berapa?",
        "RT berapa?",
        "Sebutkan alamat lengkap."
    ];

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

    // 🧩 Logic pertanyaan berurutan
    function nextQuestion() {
        if (step < questions.length) {
            const q = questions[step];
            qEl.textContent = q;
            speak(q, () => listen(handleAnswer));
        } else if (isDatang) {
            speak("Karena mutasi datang, sebutkan asal wilayah Anda.", askWilayahDatang);
        } else {
            finish();
        }
    }

    // 🧠 Proses jawaban dan isi field
    function handleAnswer(ans) {
        ans = ans.toLowerCase();
        switch (step) {
            case 0:
                if (ans.includes("datang")) {
                    isDatang = true;
                    document.querySelector("#kdmutasimasuk").value = 3;
                } else if (ans.includes("lahir")) {
                    document.querySelector("#kdmutasimasuk").value = 2;
                } else {
                    document.querySelector("#kdmutasimasuk").value = 1;
                }
                break;
            case 1:
                document.querySelector("#tanggal_mutasi").value = new Date().toISOString().split("T")[0];
                break;
            case 2:
                document.querySelector("#nokk").value = ans.replace(/\D/g, "").slice(0, 16);
                break;
            case 3:
                const nama = ans.split(" ").pop();
                document.querySelector("#kepala_rumah_tangga").value = nama;
                break;
            case 4:
                selectOption("dusun", ans);
                break;
            case 5:
                document.querySelector("#rw").value = ans.replace(/\D/g, "").padStart(3, "0");
                break;
            case 6:
                document.querySelector("#rt").value = ans.replace(/\D/g, "").padStart(3, "0");
                break;
            case 7:
                document.querySelector("#alamat").value = ans;
                break;
        }

        step++;
        nextQuestion();
    }

    // 🧭 Isi dropdown sesuai text
    function selectOption(id, text) {
        const sel = document.getElementById(id);
        for (const opt of sel.options) {
            if (opt.text.toLowerCase().includes(text)) {
                sel.value = opt.value;
                return;
            }
        }
    }

    // 🌍 Pertanyaan wilayah datang
    function askWilayahDatang() {
        wilayahDiv.classList.remove("hidden");
        const wilayah = [
            { id: "provinsi", q: "Dari provinsi mana?" },
            { id: "kabupaten", q: "Kabupaten atau kota apa?" },
            { id: "kecamatan", q: "Kecamatan apa?" },
            { id: "desa", q: "Desa atau kelurahan apa?" },
        ];
        let i = 0;
        function ask() {
            if (i < wilayah.length) {
                const { id, q } = wilayah[i];
                qEl.textContent = q;
                speak(q, () => listen(ans => {
                    document.getElementById(id).value = ans;
                    i++;
                    ask();
                }));
            } else {
                finish();
            }
        }
        ask();
    }

    function finish() {
        qEl.textContent = "✅ Semua pertanyaan selesai.";
        speak("Semua data sudah diisi. Silakan tekan tombol simpan data.");
    }

    // ▶️ Tombol Mulai & Stop
    startBtn.addEventListener("click", () => {
        step = 0;
        isDatang = false;
        startBtn.classList.add("hidden");
        stopBtn.classList.remove("hidden");
        nextQuestion();
    });

    stopBtn.addEventListener("click", () => {
        recognition.stop();
        startBtn.classList.remove("hidden");
        stopBtn.classList.add("hidden");
        speak("Rekaman dihentikan.");
    });

    // 💾 Kirim form via AJAX
    form.addEventListener("submit", e => {
        e.preventDefault();
        fetch("/voice/keluarga/store", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": form.querySelector("[name=_token]").value },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(r => alert(r.message))
        .catch(() => alert("Terjadi kesalahan saat menyimpan data."));
    });
});
