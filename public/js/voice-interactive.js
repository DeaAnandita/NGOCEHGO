class VoiceInteractive {
    constructor() {
        this.sessionId = null;
        this.currentMode = null; // "keluarga" atau "penduduk"
        this.currentModule = 0;
        this.currentQuestion = 0;
        this.answers = {};
        this.recognition = null;
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isSpeaking = false;
        this.isListening = false;

        // ==================== MODULES KELUARGA ====================
        this.keluargaModules = [ /* sama seperti sebelumnya */ ];

        // ==================== MODULES PENDUDUK ====================
        this.pendudukModules = [
            {
                name: "Data Penduduk",
                questions: [
                    { field: 'kdpekerjaan', text: 'Apa pekerjaannya?' },
                    { field: 'nik', text: 'Sebutkan nomor NIK 16 digit' },
                    { field: 'penduduk_namalengkap', text: 'Sebutkan nama lengkap penduduk' },
                    { field: 'penduduk_tempatlahir', text: 'Di mana tempat lahirnya?' },
                    { field: 'penduduk_tanggallahir', text: 'Tanggal lahir? format YYYY-MM-DD' },
                    { field: 'penduduk_goldarah', text: 'Apa golongan darahnya? A, AB, B, atau O?' },
                    { field: 'penduduk_noaktalahir', text: 'Sebutkan nomor akta lahir' },
                    { field: 'kdjeniskelamin', text: 'Apa jenis kelaminnya? Laki-laki atau perempuan?' },
                    { field: 'kdagama', text: 'Apa agamanya?' },
                    { field: 'no_kk', text: 'Sebutkan nomor kartu keluarga' },
                    { field: 'penduduk_nourutkk', text: 'Sebutkan nomor urut dalam KK' },
                    { field: 'kdhubungankeluarga', text: 'Apa hubungan dalam keluarga?' },
                    { field: 'kdhubungankepalakeluarga', text: 'Apa hubungan dengan kepala keluarga?' },
                    { field: 'kdstatuskawin', text: 'Apa status perkawinannya?' },
                    { field: 'kdaktanikah', text: 'Memiliki akta nikah?' },
                    { field: 'kdtercantumdalamkk', text: 'Apakah tercantum dalam KK?' },
                    { field: 'kdstatustinggal', text: 'Apa status tinggalnya?' },
                    { field: 'kdkartuidentitas', text: 'Jenis kartu identitas apa?' },
                    { field: 'kdmutasimasuk', text: 'Jenis mutasi apa? Tetap, lahir, atau datang?' },
                    { field: 'penduduk_namaayah', text: 'Sebutkan nama ayah' },
                    { field: 'penduduk_namaibu', text: 'Sebutkan nama ibu' },
                    { field: 'penduduk_namatempatbekerja', text: 'Sebutkan nama tempat bekerja jika ada' }
                ]
            }
        ];

        this.modules = []; // Akan diisi sesuai mode

        this.elements = { /* sama */ };

        this.init();
    }

    async init() {
        // Langkah 1: Tampilkan menu pemilihan mode
        await this.showModeSelection();
    }

    async showModeSelection() {
        this.elements.systemSpeech.textContent = "🎯 Pilih jenis input data";
        this.elements.micStatus.textContent = "Klik untuk memilih";
        this.elements.micBtn.disabled = false;

        const modes = [
            { name: "Keluarga", value: "keluarga" },
            { name: "Penduduk", value: "penduduk" }
        ];

        let selectedMode = null;

        // Create mode buttons for visual selection
        const modeContainer = document.createElement('div');
        modeContainer.className = 'flex gap-4 justify-center mb-4';
        
        modes.forEach((mode, idx) => {
            const btn = document.createElement('button');
            btn.textContent = `${idx + 1}. ${mode.name}`;
            btn.className = 'px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600';
            btn.onclick = () => {
                selectedMode = mode.value;
                this.setMode(mode.value);
            };
            modeContainer.appendChild(btn);
        });

        document.body.insertBefore(modeContainer, document.body.firstChild);

        // Voice selection
        this.elements.micBtn.onclick = async () => {
            this.speakAndThenListen("Silakan pilih. Nomor 1 untuk Keluarga, atau Nomor 2 untuk Penduduk.", async (answer) => {
                const lowerAnswer = answer.toLowerCase();
                if (lowerAnswer.includes('1') || lowerAnswer.includes('keluarga')) {
                    selectedMode = 'keluarga';
                } else if (lowerAnswer.includes('2') || lowerAnswer.includes('penduduk')) {
                    selectedMode = 'penduduk';
                }

                if (selectedMode) {
                    this.setMode(selectedMode);
                } else {
                    alert('Pilihan tidak jelas. Silakan pilih ulang.');
                    await this.showModeSelection();
                }
            });
        };
    }

    async setMode(mode) {
        this.currentMode = mode;
        this.modules = mode === "keluarga" ? this.keluargaModules : this.pendudukModules;

        // Create session
        const res = await axios.post('/admin/voice/session', { type: mode });
        this.sessionId = res.data.session_id;

        this.renderSidebar();
        this.updateProgress();

        const welcome = mode === "keluarga"
            ? "Selamat datang! Klik tombol mikrofon hijau untuk memulai pengisian data keluarga."
            : "Selamat datang! Klik tombol mikrofon hijau untuk memulai pengisian data penduduk.";

        this.elements.systemSpeech.textContent = welcome;
        this.elements.micStatus.textContent = "Klik untuk Mulai";
        this.elements.micBtn.disabled = false;

        this.elements.micBtn.onclick = () => this.startSession();
    }

    startSession() {
        if (this.isSpeaking || this.isListening) return;

        this.elements.micBtn.disabled = true;
        this.elements.micStatus.textContent = "Memulai sesi...";
        this.elements.micBtn.classList.add('animate-pulse');

        const welcomeMsg = this.currentMode === "keluarga"
            ? "Baik, kita akan memulai pengisian form data keluarga. Sebutkan nomor Kartu Keluarga Anda."
            : "Baik, kita akan memulai pengisian form data penduduk. Sebutkan nomor NIK Anda.";

        this.speakAndThenListen(welcomeMsg);
    }

    speakAndThenListen(text, callback = null) {
        this.isSpeaking = true;
        this.elements.systemSpeech.textContent = text;
        this.elements.userAnswer.classList.add('hidden');

        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = 'id-ID';
        utter.rate = 0.9;

        utter.onend = () => {
            this.isSpeaking = false;
            this.elements.micStatus.textContent = "Silakan jawab sekarang";
            this.elements.micBtn.disabled = false;
            this.elements.micBtn.classList.remove('animate-pulse');
            
            if (callback) {
                this.elements.micBtn.onclick = () => this.startListeningWithCallback(callback);
            } else {
                this.elements.micBtn.onclick = () => this.startListening();
            }
        };

        utter.onerror = () => {
            this.isSpeaking = false;
            alert("Gagal bicara. Coba refresh halaman.");
        };

        speechSynthesis.speak(utter);
    }

    startListeningWithCallback(callback) {
        if (this.isListening || this.isSpeaking) return;

        this.isListening = true;
        this.elements.micBtn.disabled = true;
        this.elements.stopBtn.classList.remove('hidden');
        this.elements.micStatus.textContent = "Mendengarkan...";

        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];
                this.mediaRecorder.ondataavailable = e => this.audioChunks.push(e.data);
                this.mediaRecorder.start();
            })
            .catch(err => {
                alert("Mikrofon tidak diizinkan.");
                this.stopListening();
            });

        this.recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        this.recognition.lang = 'id-ID';
        this.recognition.continuous = false;

        this.recognition.onresult = (e) => {
            const text = e.results[0][0].transcript.trim();
            this.showUserAnswer(text);
            callback(text);
        };

        this.recognition.onerror = (e) => {
            console.error(e);
            this.stopListening();
            alert("Gagal mendengar. Coba lagi.");
        };

        this.recognition.onend = () => {
            this.stopListening();
        };

        this.recognition.start();
    }

    startListening() {
        if (this.isListening || this.isSpeaking) return;

        this.isListening = true;
        this.elements.micBtn.disabled = true;
        this.elements.stopBtn.classList.remove('hidden');
        this.elements.micStatus.textContent = "Mendengarkan...";

        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];
                this.mediaRecorder.ondataavailable = e => this.audioChunks.push(e.data);
                this.mediaRecorder.start();
            })
            .catch(err => {
                alert("Mikrofon tidak diizinkan. Izinkan akses mikrofon di browser.");
                this.stopListening();
            });

        this.recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        this.recognition.lang = 'id-ID';
        this.recognition.continuous = false;

        this.recognition.onresult = async (e) => {
            const text = e.results[0][0].transcript.trim();
            this.showUserAnswer(text);
            await this.saveAnswer(text);
            this.processAnswer(text);
        };

        this.recognition.onerror = (e) => {
            console.error(e);
            this.stopListening();
            alert("Gagal mendengar. Coba lagi.");
        };

        this.recognition.onend = () => {
            this.stopListening();
        };

        this.recognition.start();
    }

    stopListening() {
        this.isListening = false;
        if (this.recognition) this.recognition.stop();
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
        }
        this.elements.stopBtn.classList.add('hidden');
        this.elements.micStatus.textContent = "Silakan jawab sekarang";
        this.elements.micBtn.disabled = false;
        this.elements.micBtn.onclick = () => this.startListening();
    }

    showUserAnswer(text) {
        this.elements.userText.textContent = text;
        this.elements.userAnswer.classList.remove('hidden');
    }

    async saveAnswer(text) {
        const q = this.modules[this.currentModule].questions[this.currentQuestion];
        this.answers[q.field] = text;

        if (this.audioChunks.length > 0) {
            const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
            const formData = new FormData();
            formData.append('session_id', this.sessionId);
            formData.append('mode', this.currentMode);
            formData.append('module', this.modules[this.currentModule].name);
            formData.append('field', q.field);
            formData.append('answer_text', text);
            formData.append('audio', blob, 'jawaban.webm');

            try {
                await axios.post('/admin/voice/answer', formData);
            } catch (err) {
                console.error("Gagal simpan audio:", err);
            }
        }
    }

    processAnswer(text) {
        const q = this.modules[this.currentModule].questions[this.currentQuestion];
        const lowerText = text.toLowerCase();

        // Cek mutasi datang (untuk keluarga dan penduduk)
        if (q.field === 'kdmutasimasuk') {
            const isDatang = lowerText.includes('datang');
            if (isDatang) {
                this.insertWilayahDatangModule();
            }
        }

        // Special handling untuk penduduk jenis kelamin
        if (q.field === 'kdjeniskelamin' && this.currentMode === 'penduduk') {
            if (lowerText.includes('perempuan') || lowerText.includes('wanita')) {
                this.answers[q.field] = 'P';
            } else {
                this.answers[q.field] = 'L';
            }
        }

        // Special handling untuk golongan darah
        if (q.field === 'penduduk_goldarah') {
            const match = ['a', 'b', 'o'].find(g => lowerText.includes(g));
            if (match) {
                this.answers[q.field] = match.toUpperCase();
            }
        }

        this.nextQuestion();
    }

    nextQuestion() {
        this.currentQuestion++;
        if (this.currentQuestion >= this.modules[this.currentModule].questions.length) {
            this.currentModule++;
            this.currentQuestion = 0;
            if (this.currentModule >= this.modules.length) {
                const finishMsg = this.currentMode === "keluarga"
                    ? "SELESAI! Semua data keluarga telah diisi. Terima kasih."
                    : "SELESAI! Semua data penduduk telah diisi. Terima kasih.";
                
                this.elements.systemSpeech.textContent = finishMsg;
                this.elements.saveBtn.classList.remove('hidden');
                return;
            }
        }

        this.renderSidebar();
        this.updateProgress();

        const nextText = this.getCurrentQuestionText();
        setTimeout(() => this.speakAndThenListen(nextText), 1500);
    }

    getCurrentQuestionText() {
        const q = this.modules[this.currentModule].questions[this.currentQuestion];
        if (typeof q.text === 'function') {
            return q.text(this.answers);
        }
        return q.text;
    }

    // ... renderSidebar, updateProgress, insertWilayahDatangModule sama seperti sebelumnya
}

// START
document.addEventListener('DOMContentLoaded', () => {
    const voice = new VoiceInteractive();

    document.getElementById('btn-stop').onclick = () => voice.stopListening();
    document.getElementById('btn-save').onclick = () => {
        const endpoint = voice.currentMode === "keluarga"
            ? '/admin/voice/keluarga/final-save'
            : '/admin/voice/penduduk/final-save';

        axios.post(endpoint, { session_id: voice.sessionId })
            .then(() => {
                const msg = voice.currentMode === "keluarga"
                    ? 'Semua data keluarga berhasil disimpan!'
                    : 'Semua data penduduk berhasil disimpan!';
                alert(msg);
                window.location.href = '/admin/voice';
            })
            .catch(err => {
                alert('Gagal menyimpan: ' + (err.response?.data?.message || err.message));
            });
    };
});