document.addEventListener("DOMContentLoaded", () => {
    const synth = window.speechSynthesis;
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = "id-ID";
    recognition.interimResults = false;

    const startBtn = document.getElementById("start-btn");
    const stopBtn = document.getElementById("stop-btn");
    const questionEl = document.getElementById("question");
    const answerEl = document.getElementById("answer");
    const form = document.getElementById("keluargaForm");
    const wilayahDiv = document.getElementById("wilayah-datang");

    let step = 0;
    let isDatang = false;

    const questions = [
        "Jenis mutasi apa? Tetap, lahir, atau datang?",
        "Tanggal mutasi kapan?",
        "Sebutkan nomor kartu keluarga",
        "Siapa nama kepala keluarga?",
        "Dusun atau lingkungan apa?",
        "RW berapa?",
        "RT berapa?",
        "Sebutkan alamat lengkap"
    ];

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
        if (step < questions.length) {
            questionEl.textContent = questions[step];
            speak(questions[step], () => listen(handleAnswer));
        } else if (isDatang) {
            speak("Karena mutasi datang, sebutkan asal wilayah Anda.", askWilayahDatang);
        } else {
            speak("Semua data sudah diisi. Silakan tekan tombol simpan.");
            questionEl.textContent = "✅ Semua pertanyaan selesai.";
        }
    }

    function handleAnswer(answer) {
        answerEl.textContent = `"${answer}"`;

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

        step++;
        nextQuestion();
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
        startBtn.classList.add("hidden");
        stopBtn.classList.remove("hidden");
        step = 0;
        nextQuestion();
    });

    stopBtn.addEventListener("click", () => {
        recognition.stop();
        startBtn.classList.remove("hidden");
        stopBtn.classList.add("hidden");
        speak("Rekaman dihentikan.");
    });

    // 📨 Submit via AJAX
    form.addEventListener("submit", e => {
        e.preventDefault();
        fetch("/voice/keluarga/store", {
            method: "POST",
            body: new FormData(form),
            headers: { "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value }
        })
        .then(res => res.json())
        .then(res => {
            alert(res.message);
            location.reload();
        })
        .catch(() => alert("Gagal menyimpan data."));
    });
});
