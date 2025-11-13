<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Uji Coba Input Data dengan Suara</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; color: #111; padding: 40px; }
        h1 { color: #2563eb; }
        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .output {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h1>🎙️ Input Data Keluarga dengan Suara</h1>
    <p>Klik tombol di bawah, lalu ucapkan sesuatu (contoh: <i>“Nama Kepala Keluarga Dea Asti”</i>)</p>
    <button id="startBtn">Mulai Rekam</button>
    <button id="stopBtn" disabled>Berhenti</button>

    <div class="output" id="result">💬 Hasil suara akan muncul di sini...</div>

    <script>
        // === VOICE INPUT (Speech Recognition) ===
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.interimResults = false;
        recognition.continuous = false;

        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const resultDiv = document.getElementById('result');

        startBtn.onclick = () => {
            recognition.start();
            startBtn.disabled = true;
            stopBtn.disabled = false;
            resultDiv.innerText = "🎤 Mendengarkan...";
        };

        stopBtn.onclick = () => {
            recognition.stop();
            startBtn.disabled = false;
            stopBtn.disabled = true;
        };

        recognition.onresult = (event) => {
            const text = event.results[0][0].transcript;
            resultDiv.innerHTML = `<strong>Hasil:</strong> ${text}`;
            speakText(`Kamu bilang ${text}`);
        };

        recognition.onerror = (event) => {
            resultDiv.innerHTML = `❌ Error: ${event.error}`;
            startBtn.disabled = false;
            stopBtn.disabled = true;
        };

        // === VOICE OUTPUT (Text-to-Speech) ===
        function speakText(text) {
            const synth = window.speechSynthesis;
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'id-ID';

            // Pilih suara wanita Indonesia (kalau tersedia)
            const voices = synth.getVoices();
            const indoVoice = voices.find(v => v.lang === 'id-ID' && v.name.toLowerCase().includes('female'));
            if (indoVoice) utter.voice = indoVoice;

            synth.speak(utter);
        }
    </script>
</body>
</html>
