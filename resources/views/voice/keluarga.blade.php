<x-app-layout>
<div class="flex gap-6 max-w-7xl mx-auto py-8">

    {{-- ================= SIDE BAR STATUS ================= --}}
    <div id="sidebar" class="flex flex-col items-center w-20 h-fit py-6 space-y-6 bg-white rounded-2xl shadow-md p-4">
        <div id="modul1" class="flex flex-col items-center bg-green-100 text-green-800 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-user-group class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center font-semibold">Keluarga</span>
        </div>

        <div id="modul2" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-home-modern class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Prasarana</span>
        </div>

        <div id="modul3" class="flex flex-col items-center bg-gray-100 text-gray-500 rounded-lg px-2 py-1 transition-all">
            <x-heroicon-o-tv class="w-7 h-7" />
            <span class="text-[10px] mt-1 text-center">Aset Keluarga</span>
        </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">🎤 Input Data Keluarga via Suara</h2>

        {{-- PROGRESS BAR --}}
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
            <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
        </div>

        <div id="voice-status" class="p-3 bg-gray-100 rounded mb-4 text-gray-700">
            Klik tombol di bawah untuk mulai mendengarkan...
        </div>

        <form id="voiceForm">
            @csrf

            <div class="flex gap-3 mb-6">
                <button type="button" id="startBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    🎙️ Mulai Bicara
                </button>

                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    💾 Simpan
                </button>
            </div>

            <div class="space-y-3">
                <div>
                    <label>No KK</label>
                    <input type="text" name="no_kk" id="no_kk" maxlength="16" class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Jenis Mutasi</label>
                    <select name="kdmutasimasuk" id="kdmutasimasuk" class="w-full border rounded p-2">
                        <option value="">-- Pilih Mutasi --</option>
                        @foreach($mutasi as $m)
                            <option value="{{ $m->kdmutasimasuk }}">{{ strtolower($m->mutasimasuk) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Nama Kepala Keluarga</label>
                    <input type="text" name="keluarga_kepalakeluarga" id="keluarga_kepalakeluarga" class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Dusun</label>
                    <select name="kddusun" id="kddusun" class="w-full border rounded p-2">
                        <option value="">-- Pilih Dusun --</option>
                        @foreach($dusun as $d)
                            <option value="{{ $d->kddusun }}">{{ strtolower($d->dusun) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label>RW</label>
                        <input type="text" name="keluarga_rw" id="keluarga_rw" maxlength="3" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label>RT</label>
                        <input type="text" name="keluarga_rt" id="keluarga_rt" maxlength="3" class="w-full border rounded p-2">
                    </div>
                </div>

                <div>
                    <label>Alamat Lengkap</label>
                    <textarea name="keluarga_alamatlengkap" id="keluarga_alamatlengkap" class="w-full border rounded p-2"></textarea>
                </div>

                {{-- Wilayah datang hanya aktif jika mutasi = datang --}}
                <div id="wilayahDatang" class="hidden">
                    <h3 class="text-lg font-semibold mt-6 mb-2">Wilayah Datang</h3>
                    <select name="kdprovinsi" id="kdprovinsi" class="w-full border rounded p-2 mb-2">
                        <option value="">-- Provinsi --</option>
                        @foreach($provinsi as $p)
                            <option value="{{ $p->kdprovinsi }}">{{ strtolower($p->provinsi) }}</option>
                        @endforeach
                    </select>
                    <select name="kdkabupaten" id="kdkabupaten" class="w-full border rounded p-2 mb-2">
                        <option value="">-- Kabupaten --</option>
                        @foreach($kabupaten as $k)
                            <option value="{{ $k->kdkabupaten }}">{{ strtolower($k->kabupaten) }}</option>
                        @endforeach
                    </select>
                    <select name="kdkecamatan" id="kdkecamatan" class="w-full border rounded p-2 mb-2">
                        <option value="">-- Kecamatan --</option>
                        @foreach($kecamatan as $kc)
                            <option value="{{ $kc->kdkecamatan }}">{{ strtolower($kc->kecamatan) }}</option>
                        @endforeach
                    </select>
                    <select name="kddesa" id="kddesa" class="w-full border rounded p-2">
                        <option value="">-- Desa --</option>
                        @foreach($desa as $ds)
                            <option value="{{ $ds->kddesa }}">{{ strtolower($ds->desa) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Tombol lanjut modul --}}
        <div id="nextModule" class="hidden mt-6">
            <button id="nextBtn" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                ➡️ Lanjut ke Modul Selanjutnya
            </button>
        </div>
    </div>
</div>

{{-- ================= JAVASCRIPT VOICE ================= --}}
<script>
let recognition;
let step = 0;
let isSpeaking = false;
const progressBar = document.getElementById("progressBar");

const questions = [
    "Sebutkan nomor kartu keluarga, 16 digit.",
    "Jenis mutasi apa? Pilih dari: {{ implode(', ', $mutasi->pluck('mutasimasuk')->toArray()) }}.",
    "Siapa nama kepala keluarga?",
    "Dusun apa? Pilih dari: {{ implode(', ', $dusun->pluck('dusun')->toArray()) }}.",
    "RW berapa?",
    "RT berapa?",
    "Sebutkan alamat lengkapnya."
];

const fieldIds = ["no_kk","kdmutasimasuk","keluarga_kepalakeluarga","kddusun","keluarga_rw","keluarga_rt","keluarga_alamatlengkap"];

const wilayahQuestions = [
    "Provinsi mana asalnya?",
    "Kabupaten mana asalnya?",
    "Kecamatan mana asalnya?",
    "Desa mana asalnya?"
];

const wilayahFieldIds = ["kdprovinsi", "kdkabupaten", "kdkecamatan", "kddesa"];


document.getElementById('startBtn').addEventListener('click', startListening);

function updateProgress() {
    const percent = (step / questions.length) * 100;
    progressBar.style.width = percent + "%";
}

function startListening() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'id-ID';
    recognition.continuous = false;
    recognition.interimResults = false;
    step = 0;
    updateProgress();
    askQuestion();
}

function askQuestion() {
    if (step < questions.length) {
        speak(questions[step], () => startRecognition());
        updateProgress();
    } else {
        speak("Terima kasih, semua data sudah selesai diisi.");
        document.getElementById('voice-status').innerText = "✅ Selesai mendengarkan.";
    }
}

function startRecognition() {
    recognition.start();
    document.getElementById('voice-status').innerText = "🎧 Mendengarkan jawaban...";
    recognition.onresult = function (event) {
        const text = event.results[0][0].transcript.trim().toLowerCase();
        if (text && !isSpeaking) processAnswer(text);
    };
    recognition.onerror = function () {
        speak("Maaf, saya tidak mendengar dengan jelas. Ulangi jawaban Anda.", askQuestion);
    };
    recognition.onend = function () {
        if (!isSpeaking && step < questions.length) {
            speak("Saya tidak mendengar jawaban, tolong ulangi.", askQuestion);
        }
    };
}

function processAnswer(text) {
    const field = document.getElementById(fieldIds[step]);
    switch (fieldIds[step]) {
        case "no_kk":
            const digits = text.replace(/\D/g, '');
            if (digits.length === 16) {
                field.value = digits;
                nextStep();
            } else {
                speak("Nomor kartu keluarga harus 16 digit, ulangi lagi.", askQuestion);
            }
            break;
        case "kdmutasimasuk":
            const mutasiOptions = @json($mutasi->pluck('mutasimasuk', 'kdmutasimasuk'));
            const matchedMutasi = Object.entries(mutasiOptions)
                .find(([id, nama]) => text.includes(nama.toLowerCase()));
            if (matchedMutasi) {
                field.value = matchedMutasi[0];
                if (matchedMutasi[1].toLowerCase().includes('datang')) {
                    document.getElementById('wilayahDatang').classList.remove('hidden');
                    questions.push(...wilayahQuestions);
                    fieldIds.push(...wilayahFieldIds);
                } else {
                    document.getElementById('wilayahDatang').classList.add('hidden');
                }
                nextStep();
            } else {
                speak("Mutasi tidak dikenali. Ulangi jenis mutasi.", askQuestion);
            }
            break;
        case "kddusun":
            const dusunOptions = @json($dusun->pluck('dusun', 'kddusun'));
            const matchedDusun = Object.entries(dusunOptions)
                .find(([id, nama]) => text.includes(nama.toLowerCase()));
            if (matchedDusun) {
                field.value = matchedDusun[0];
                nextStep();
            } else {
                speak("Dusun tidak dikenali. Ulangi nama dusunnya.", askQuestion);
            }
            break;
        case "keluarga_rw":
        case "keluarga_rt":
            const num = text.match(/\d+/);
            if (num) {
                field.value = num[0].padStart(3, '0');
                nextStep();
            } else {
                speak("Ulangi, sebutkan angka RT atau RW.", askQuestion);
            }
            break;
        default:
            field.value = text;
            nextStep();
    }
}

function nextStep() {
    step++;
    updateProgress();
    askQuestion();
}

function speak(text, callback = null) {
    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = 'id-ID';
    isSpeaking = true;
    utter.onend = function () {
        isSpeaking = false;
        if (callback) callback();
    };
    speechSynthesis.speak(utter);
}

// === DYNAMIC WILAYAH ===
document.getElementById('kdprovinsi').addEventListener('change', async function() {
    const provId = this.value;
    const kabupatenSelect = document.getElementById('kdkabupaten');
    kabupatenSelect.innerHTML = '<option value="">-- Kabupaten --</option>';
    const response = await fetch(`/get-kabupaten/${provId}`);
    const data = await response.json();
    data.forEach(item => {
        kabupatenSelect.innerHTML += `<option value="${item.kdkabupaten}">${item.kabupaten}</option>`;
    });
});

document.getElementById('kdkabupaten').addEventListener('change', async function() {
    const kabId = this.value;
    const kecamatanSelect = document.getElementById('kdkecamatan');
    kecamatanSelect.innerHTML = '<option value="">-- Kecamatan --</option>';
    const response = await fetch(`/get-kecamatan/${kabId}`);
    const data = await response.json();
    data.forEach(item => {
        kecamatanSelect.innerHTML += `<option value="${item.kdkecamatan}">${item.kecamatan}</option>`;
    });
});

document.getElementById('kdkecamatan').addEventListener('change', async function() {
    const kecId = this.value;
    const desaSelect = document.getElementById('kddesa');
    desaSelect.innerHTML = '<option value="">-- Desa --</option>';
    const response = await fetch(`/get-desa/${kecId}`);
    const data = await response.json();
    data.forEach(item => {
        desaSelect.innerHTML += `<option value="${item.kddesa}">${item.desa}</option>`;
    });
});


document.getElementById('voiceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const response = await fetch("{{ route('voice.keluarga.store') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value },
        body: formData
    });
    const result = await response.json();
    if (result.success) {
        alert("✅ Data berhasil disimpan!");
        document.getElementById('nextModule').classList.remove('hidden');
    } else {
        alert("❌ Gagal menyimpan data: " + result.error);
    }
});

document.getElementById('nextBtn').addEventListener('click', () => {
    document.getElementById('modul1').className = 'flex flex-col items-center bg-blue-100 text-blue-800 rounded-lg px-2 py-1';
    document.getElementById('modul2').className = 'flex flex-col items-center bg-green-100 text-green-800 rounded-lg px-2 py-1';
    speak("Modul selanjutnya siap diisi.");
});
</script>
</x-app-layout>
