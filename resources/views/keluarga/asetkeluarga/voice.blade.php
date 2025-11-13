<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Aset Keluarga (Voice Mode)</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; padding: 30px; color: #111; }
        h1 { color: #2563eb; margin-bottom: 10px; }
        select, button {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 10px;
            font-size: 16px;
        }
        .question-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        .status {
            margin-top: 15px;
            font-weight: bold;
            color: #374151;
        }
    </style>
</head>
<body>
    <h1>🎙️ Input Aset Keluarga (Voice)</h1>

    <label for="no_kk">Pilih Keluarga:</label>
    <select id="no_kk">
        <option value="">-- Pilih Keluarga --</option>
        @foreach($keluargas as $kel)
            <option value="{{ $kel->no_kk }}">{{ $kel->keluarga_kepalakeluarga }} ({{ $kel->no_kk }})</option>
        @endforeach
    </select>

    <div class="question-box" id="questionBox">
        Klik tombol di bawah untuk mulai tanya jawab.
    </div>

    <button id="startBtn">Mulai Tanya Jawab</button>
    <button id="stopBtn" disabled>Berhenti</button>

    <div class="status" id="status"></div>

    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const synth = window.speechSynthesis;

        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.interimResults = false;
        recognition.continuous = false;

        const questions = @json($masterAset->pluck('asetkeluarga')->toArray());
        let currentIndex = 0;
        let answers = {};

        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const questionBox = document.getElementById('questionBox');
        const statusDiv = document.getElementById('status');

        function speak(text, callback) {
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'id-ID';
            utter.onend = callback;
            synth.speak(utter);
        }

        function askQuestion() {
            if (currentIndex >= questions.length) {
                finishInterview();
                return;
            }

            const q = questions[currentIndex];
            questionBox.innerHTML = `<strong>Pertanyaan ${currentIndex+1}:</strong> ${q}`;
            speak(q, () => {
                recognition.start();
                statusDiv.innerText = "🎧 Mendengarkan jawaban...";
            });
        }

        recognition.onresult = (event) => {
            const text = event.results[0][0].transcript.toLowerCase();
            statusDiv.innerText = `Jawaban: ${text}`;
            answers[`asetkeluarga_${currentIndex+1}`] = (text.includes("ya")) ? 1 : 2;

            recognition.stop();
            currentIndex++;
            setTimeout(askQuestion, 1000);
        };

        recognition.onerror = (event) => {
            statusDiv.innerText = "❌ Terjadi kesalahan: " + event.error;
            recognition.stop();
        };

        function finishInterview() {
            speak("Terima kasih. Semua pertanyaan sudah selesai.", () => {
                questionBox.innerHTML = "<b>✅ Semua pertanyaan sudah selesai!</b>";
                statusDiv.innerHTML = "Data siap dikirim ke sistem.";
                console.log("Data akhir:", answers);

                // 👉 kirim hasil ke Laravel (optional)
                const noKK = document.getElementById('no_kk').value;
                fetch("{{ route('keluarga.asetkeluarga.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ no_kk: noKK, ...answers })
                }).then(res => {
                    if (res.ok) speak("Data berhasil disimpan ke sistem.");
                });
            });
        }

        startBtn.onclick = () => {
            if (!document.getElementById('no_kk').value) {
                alert("Pilih keluarga terlebih dahulu!");
                return;
            }
            startBtn.disabled = true;
            stopBtn.disabled = false;
            currentIndex = 0;
            answers = {};
            askQuestion();
        };

        stopBtn.onclick = () => {
            recognition.stop();
            synth.cancel();
            startBtn.disabled = false;
            stopBtn.disabled = true;
            statusDiv.innerText = "🛑 Proses dihentikan.";
        };
    </script>
</body>
</html>
