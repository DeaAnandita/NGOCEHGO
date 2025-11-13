class VoiceInteractive {
    constructor() {
        this.sessionId = null;
        this.currentModule = 0;
        this.currentQuestion = 0;
        this.answers = {};
        this.recognition = null;
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isSpeaking = false;
        this.isListening = false;

        this.modules = [ /* sama seperti sebelumnya */ ];

        this.elements = { /* sama */ };

        this.init();
    }

    async init() {
        const res = await axios.post('/admin/voice/session', { type: 'keluarga' });
        this.sessionId = res.data.session_id;
        this.renderSidebar();
        this.updateProgress();

        // Langkah 1: Tunggu user klik tombol MULAI
        this.elements.systemSpeech.textContent = "Selamat datang! Klik tombol mikrofon hijau untuk memulai pengisian data keluarga.";
        this.elements.micStatus.textContent = "Klik untuk Mulai";
        this.elements.micBtn.disabled = false;

        this.elements.micBtn.onclick = () => this.startSession();
    }

    startSession() {
        if (this.isSpeaking || this.isListening) return;

        this.elements.micBtn.disabled = true;
        this.elements.micStatus.textContent = "Memulai sesi...";
        this.elements.micBtn.classList.add('animate-pulse');

        // Langkah 2: Sistem bicara dulu
        this.speakAndThenListen("Baik, kita akan memulai pengisian form data keluarga. Sebutkan nomor Kartu Keluarga Anda.");
    }

    speakAndThenListen(text) {
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
            this.elements.micBtn.onclick = () => this.startListening(); // Aktifkan mikrofon
        };

        utter.onerror = () => {
            this.isSpeaking = false;
            alert("Gagal bicara. Coba refresh halaman.");
        };

        speechSynthesis.speak(utter);
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

        // Cek mutasi datang
        if (q.field === 'kdmutasimasuk') {
            const isDatang = this.masterMutasi.some(m => 
                lowerText.includes(m.mutasimasuk.toLowerCase()) && 
                m.mutasimasuk.toLowerCase().includes('datang')
            );
            if (isDatang) {
                this.insertWilayahDatangModule();
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
                this.elements.systemSpeech.textContent = "SELESAI! Semua data telah diisi. Terima kasih.";
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
        axios.post('/admin/voice/final-save', { session_id: voice.sessionId })
            .then(() => {
                alert('Semua data berhasil disimpan!');
                window.location.href = '/admin/voice';
            });
    };
});