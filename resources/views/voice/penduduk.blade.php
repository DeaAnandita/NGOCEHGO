<x-app-layout>

   @slot('progresspenduduk')
        <div class="sticky top-16 left-0 right-0 z-40 bg-white shadow-md border-b">
            <div class="max-w-7xl mx-auto overflow-x-auto scrollbar-hide">
                <div id="progressSteps" class="flex items-center space-x-6 px-6 py-4 min-w-max">
                    <!-- JS generate -->
                </div>
            </div>
        </div>
    @endslot

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="max-w-7xl mx-auto py-8 px-6 space-y-6">

        <div class="flex justify-center">
            <button type="submit" id="simpanBtn" form="voiceForm"
                class="px-8 py-3 text-white text-lg font-medium rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                style="background-color: #9ca3af;" disabled>
                Simpan Semua Data
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 id="modulTitle" class="text-2xl font-bold text-center mb-6 text-green-800">Input Data Keluarga via Suara</h2>

            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div id="progressBar" class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div class="text-center text-sm text-gray-600 mb-4">
                Pertanyaan <span id="currentQ">1</span> dari <span id="totalQ">7</span>
            </div>

            <div id="voice-status" class="text-center text-lg font-medium text-gray-700 mb-6">
                Tekan mic untuk mulai merekam...
            </div>

            <div id="quizArea" class="space-y-6"></div>

            <div class="flex flex-col items-center mt-10">
                <div class="relative">
                    <button id="startBtn" class="relative w-28 h-28 bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4" />
                        </svg>
                    </button>
                    <canvas id="visualizer" class="absolute inset-0 w-full h-full pointer-events-none opacity-0 transition-opacity rounded-full" width="112" height="112"></canvas>
                </div>
                <p class="mt-3 text-sm text-gray-500">Tekan untuk mulai merekam</p>
            </div>
        </div>

        <div id="reviewForm" class="hidden bg-white rounded-2xl shadow-lg p-6 mt-6">
            <h3 class="text-xl font-bold text-center mb-6 text-green-700">Review & Edit Data Penduduk</h3>
            <form id="voiceForm" class="space-y-5">
                @csrf
                <div id="reviewFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"></div>

                <!-- PERBAIKI: Ganti $provinsi → $provinsis (atau nama variabel yang benar di controller) -->
                <div id="wilayahDatangReview" class="hidden bg-teal-50 p-4 rounded-xl md:col-span-2">
                    <h4 class="font-bold text-sm mb-3">Wilayah Datang</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <label>Provinsi</label>
                            <select name="kdprovinsi" id="kdprovinsi" class="w-full border rounded-lg p-2">
                                <option value="">-- Pilih --</option>
                                @foreach($provinsis ?? [] as $k => $v)   <!-- GANTI DI SINI -->
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label>Kabupaten</label><select name="kdkabupaten" id="kdkabupaten" class="w-full border rounded-lg p-2"><option>-- Pilih Provinsi --</option></select></div>
                        <div><label>Kecamatan</label><select name="kdkecamatan" id="kdkecamatan" class="w-full border rounded-lg p-2"><option>-- Pilih Kabupaten --</option></select></div>
                        <div><label>Desa</label><select name="kddesa" id="kddesa" class="w-full border rounded-lg p-2"><option>-- Pilih Kecamatan --</option></select></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .option-card {
        transition: all .3s; border: 2px solid #e5e7eb; cursor: pointer;
        padding: 1rem; border-radius: 1rem; text-align: center;
    }
    .option-card:hover { background-color: #f0fdfa; border-color: #14b8a6; }
    .option-card.selected { background-color: #ccfbf1 !important; border-color: #14b8a6 !important; box-shadow: 0 0 0 3px rgba(20,184,166,.2); }
    #startBtn.listening { background: linear-gradient(to bottom right, #ef4444, #dc2626) !important; transform: scale(1.15); }
</style>

<script>
const mutasiOptions   = @json($mutasi ?? []);
const provinsiOptions = @json($provinsis ?? []);
const kabupatenData = @json($kabupatens ?? []);     // [kdprovinsi => [kdkab => nama]]
const kecamatanData = @json($kecamatans ?? []);     // [kdkab => [kdkec => nama]]
const desaData = @json($desas ?? []);               // [kdkec => [kddesa => nama]]           
const masters         = @json($masters ?? []);

const jenisKelaminOptions = masters.jenis_kelamin ?? {};
const agamaOptions = masters.agama ?? {};
const hubunganOptions = masters.hubungan_keluarga ?? {};
const hubunganKepalaOptions = masters.hubungan_kepala_keluarga ?? {};
const statusKawinOptions = masters.status_kawin ?? {};
const aktaNikahOptions = masters.akta_nikah ?? {};
const tercantumOptions = masters.tercantum_kk ?? {};
const statusTinggalOptions = masters.status_tinggal ?? {};
const kartuIdOptions = masters.kartu_identitas ?? {};
const pekerjaanOptions = masters.pekerjaan ?? {};
const getKabupatenOptions = (kdprovinsi) => kabupatenData[kdprovinsi] ?? {};
const getKecamatanOptions = (kdkabupaten) => kecamatanData[kdkabupaten] ?? {};
const getDesaOptions = (kdkecamatan) => desaData[kdkecamatan] ?? {};
const partisipasiSekolahOptions = masters.partisipasi_sekolah ?? {};
const tingkatSulitDisabilitasOptions = masters.tingkat_sulit_disabilitas ?? {};
const statusKedudukanKerjaOptions = masters.status_kedudukan_kerja ?? {};
const ijasahTerakhirOptions = masters.ijasah_terakhir ?? {};
const penyakitKronisOptions = masters.penyakit_kronis ?? {};
const pendapatanPerBulanOptions = masters.pendapatan_perbulan ?? {};
const jenisDisabilitasOptions = masters.jenis_disabilitas ?? {};
const lapanganUsahaOptions = masters.lapangan_usaha ?? {};
const imunisasiOptions = masters.imunisasi ?? {};
const tempatUsahaOptions = masters.tempat_usaha ?? {};
const omsetUsahaOptions = masters.omset_usaha ?? {};
const jawabProgramOptions = masters.jawab_program_serta ?? {};
const jawabLemdesOptions = masters.jawab_lemdes ?? {};
const jawabLemmasOptions = masters.jawab_lemmas ?? {};
const jawabLemekOptions = masters.jawab_lemek ?? {};

let currentModul = 1;
let step = 0;
let answers = { penduduk_tanggalmutasi: new Date().toISOString().split('T')[0] };
let modulStatus = {1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending'};
let recognition = null;
let isListening = false;
let audioContext = null, analyser = null, dataArray = null, canvas = null, ctx = null;
let isSpeaking = false;
let isReviewMode = false;

const modules = [
    {id: 1, name: "Data Penduduk"},
    {id: 2, name: "Kelahiran"},
    {id: 3, name: "Sosial Ekonomi"},
    {id: 4, name: "Usaha ART"},
    {id: 5, name: "Program Serta"},
    {id: 6, name: "Lembaga Desa"},
    {id: 7, name: "Lembaga Masyarakat"},
    {id: 8, name: "Lembaga Ekonomi"}
];

const questions = {
    1: [ 
        { type: "text", label: "Sebutkan nomor NIK", field: "nik" },
        { type: "text", label: "Sebutkan nama lengkap penduduk", field: "penduduk_namalengkap" },
        { type: "text", label: "Dimana tempat lahirnya?", field: "penduduk_tempatlahir" },
        { type: "date", label: "Tanggal lahir?", field: "penduduk_tanggallahir" },
        { type: "select", label: "Apa golongan darahnya?", field: "penduduk_goldarah", options: {"A":"A","B":"B","AB":"AB","O":"O"} },
        { type: "text", label: "Sebutkan nomor akta lahir", field: "penduduk_noaktalahir" },
        { type: "skip",   label: "Kewarganegaraan", field: "penduduk_kewarganegaraan", default: "INDONESIA" },
        { type: "select", label: "Apa jenis kelaminnya?", field: "kdjeniskelamin", options: jenisKelaminOptions },
        { type: "select", label: "Apa agamanya?", field: "kdagama", options: agamaOptions },
        { type: "text", label: "Sebutkan nomor kartu keluarga", field: "no_kk" },
        { type: "text", label: "Sebutkan nomor urut dalam KK", field: "penduduk_nourutkk" },
        { type: "select", label: "Apa hubungan dalam keluarga?", field: "kdhubungankeluarga", options: hubunganOptions },
        { type: "select", label: "Apa hubungan dengan kepala keluarga?", field: "kdhubungankepalakeluarga", options: hubunganKepalaOptions },
        { type: "select", label: "Apa status perkawinannya?", field: "kdstatuskawin", options: statusKawinOptions },
        { type: "select", label: "Memiliki akta nikah?", field: "kdaktanikah", options: aktaNikahOptions },
        { type: "select", label: "Apakah tercantum dalam KK?", field: "kdtercantumdalamkk", options: tercantumOptions },
        { type: "select", label: "Apa status tinggalnya?", field: "kdstatustinggal", options: statusTinggalOptions },
        { type: "select", label: "Jenis kartu identitas apa?", field: "kdkartuidentitas", options: kartuIdOptions },
        { type: "select", label: "Jenis mutasi apa?", field: "kdmutasimasuk", options: mutasiOptions },
        { type: "date", label: "Tanggal mutasi", field: "penduduk_tanggalmutasi" },
        { type: "text", label: "Sebutkan nama ayah", field: "penduduk_namaayah" },
        { type: "text", label: "Sebutkan nama ibu", field: "penduduk_namaibu" },
        { type: "text", label: "Sebutkan nama tempat bekerja (jika ada)", field: "penduduk_namatempatbekerja" },
        { type: "select", label: "Apa pekerjaannya?", field: "kdpekerjaan", options: pekerjaanOptions },
    ],
   2: [ // MODUL KELAHIRAN — TOTAL 16 pertanyaan khusus
        { type: "text",   label: "Sebutkan NIK bayi yang baru lahir", field: "nik_bayi" },
        { type: "text",   label: "Sebutkan nama lengkap bayi", field: "nama_bayi" },
        { type: "date",   label: "Tanggal lahir bayi?", field: "tanggal_lahir_bayi" },
        { type: "select", label: "Jenis kelamin bayi?", field: "kdjeniskelamin_bayi", options: masters.jenis_kelamin },
        { type: "select", label: "Tempat persalinan bayi?", field: "kdtempatpersalinan", options: masters.tempat_persalinan },
        { type: "select", label: "Jenis kelahiran apa?", field: "kdjeniskelahiran", options: masters.jenis_kelahiran },
        { type: "select", label: "Siapa yang menolong persalinan?", field: "kdpertolonganpersalinan", options: masters.pertolongan_persalinan },
        { type: "time",   label: "Jam berapa bayi lahir?", field: "jam_kelahiran" },
        { type: "number", label: "Ini kelahiran ke berapa bagi ibunya?", field: "kelahiran_ke" },
        { type: "number", label: "Berat lahir bayi dalam gram?", field: "berat_lahir" },
        { type: "number", label: "Panjang lahir bayi dalam cm?", field: "panjang_lahir" },
        { type: "text",   label: "Sebutkan NIK ibu kandung", field: "nik_ibu" },
        { type: "text",   label: "Sebutkan NIK ayah", field: "nik_ayah", required: false },
    ],
    3: [
        { type: "select", label: "Partisipasi sekolah saat ini?", field: "kdpartisipasisekolah", options: partisipasiSekolahOptions },
        { type: "select", label: "Ijasah tertinggi yang dimiliki?", field: "kdijasahterakhir", options: ijasahTerakhirOptions },
        { type: "select", label: "Status kedudukan dalam pekerjaan utama?", field: "kdstatuskedudukankerja", options: statusKedudukanKerjaOptions },
        { type: "select", label: "Lapangan usaha pekerjaan utama?", field: "kdlapanganusaha", options: lapanganUsahaOptions },
        { type: "select", label: "Pendapatan per bulan?", field: "kdpendapatanperbulan", options: pendapatanPerBulanOptions },
        { type: "select", label: "Penyakit kronis yang diderita?", field: "kdpenyakitkronis", options: penyakitKronisOptions },
        { type: "select", label: "Jenis disabilitas yang dialami?", field: "kdjenisdisabilitas", options: jenisDisabilitasOptions },
        { type: "select", label: "Tingkat kesulitan disabilitas?", field: "kdtingkatsulitdisabilitas", options: tingkatSulitDisabilitasOptions },
        { type: "select", label: "Imunisasi apa saja yang sudah diberikan?", field: "kdimunisasi", options: imunisasiOptions }
    ],
    4: [
        { type: "text", label: "Nama usaha ART?", field: "usahaart_namausaha" },
        { type: "select", label: "Ada tempat usaha tetap?", field: "kdtempatusaha", options: tempatUsahaOptions },
        { type: "select", label: "Lapangan usaha ART?", field: "kdlapanganusaha_art", options: lapanganUsahaOptions },
        { type: "select", label: "Omset usaha per bulan?", field: "kdomsetusaha", options: omsetUsahaOptions },
        { type: "number", label: "Jumlah pekerja di usaha ini?", field: "usahaart_jumlahpekerja" },
    ],
    5: [
    { type: "select", label: "Apakah memiliki Kartu Keluarga Sejahtera (KKS) atau Kartu Perlindungan Sosial (KPS)?", field: "kks_kps", options: jawabProgramOptions },
    { type: "select", label: "Apakah memiliki Kartu Indonesia Pintar (KIP)?", field: "kip", options: jawabProgramOptions },
    { type: "select", label: "Apakah memiliki Kartu Indonesia Sehat (KIS)?", field: "kis", options: jawabProgramOptions },
    { type: "select", label: "Apakah terdaftar sebagai peserta BPJS Kesehatan mandiri (bukan PBI/bantuan pemerintah)?", field: "bpjs_non_pbi", options: jawabProgramOptions },
    { type: "select", label: "Apakah pernah atau sedang terdaftar di BPJS Ketenagakerjaan (Jamsostek)?", field: "jamsostek", options: jawabProgramOptions },
    { type: "select", label: "Apakah memiliki asuransi kesehatan lain selain BPJS?", field: "asuransi_lain", options: jawabProgramOptions },
    { type: "select", label: "Apakah saat ini menerima bantuan Program Keluarga Harapan (PKH)?", field: "pkh", options: jawabProgramOptions },
    { type: "select", label: "Apakah pernah atau sedang menerima bantuan beras miskin (Raskin)?", field: "raskin", options: jawabProgramOptions },
    ],
   6: [
    { type: "select", label: "Apakah saat ini menjabat sebagai Kepala Desa atau Lurah?", field: "kepala_desa", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Sekretaris Desa?", field: "sekretaris_desa", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Kepala Urusan di kantor desa?", field: "kepala_urusan", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Kepala Dusun (Kadus)?", field: "kepala_dusun", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai staf atau pegawai kantor desa?", field: "staf_desa", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Ketua BPD?", field: "ketua_bpd", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Wakil Ketua BPD?", field: "wakil_ketua_bpd", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Sekretaris BPD?", field: "sekretaris_bpd", options: jawabLemdesOptions },
    { type: "select", label: "Apakah saat ini menjabat sebagai Anggota BPD?", field: "anggota_bpd", options: jawabLemdesOptions },
    ],
   7: [
        // === LEMBAGA MASYARAKAT (OPTIMIZED UNTUK VOICE) ===
    { type: "select", label: "Apakah bapak/ibu pengurus RT?", field: "lembaga_pengurus_rt", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota pengurus RT?", field: "lembaga_anggota_pengurus_rt", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus RW?", field: "lembaga_pengurus_rw", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota pengurus RW?", field: "lembaga_anggota_pengurus_rw", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus LPM atau LKMD?", field: "lembaga_pengurus_lkmd", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota LPM atau LKMD?", field: "lembaga_anggota_lkmd", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus PKK?", field: "lembaga_pengurus_pkk", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota PKK?", field: "lembaga_anggota_pkk", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus lembaga adat?", field: "lembaga_pengurus_lembaga_adat", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus Karang Taruna?", field: "lembaga_pengurus_karang_taruna", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota Karang Taruna?", field: "lembaga_anggota_karang_taruna", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pernah jadi Hansip atau Linmas?", field: "lembaga_pengurus_hansip", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus poskamling?", field: "lembaga_pengurus_poskamling", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi perempuan?", field: "lembaga_pengurus_org_perempuan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi perempuan?", field: "lembaga_anggota_org_perempuan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi bapak-bapak?", field: "lembaga_pengurus_org_bapak", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi bapak-bapak?", field: "lembaga_anggota_org_bapak", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi keagamaan?", field: "lembaga_pengurus_org_keagamaan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi keagamaan?", field: "lembaga_anggota_org_keagamaan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi profesi wartawan?", field: "lembaga_pengurus_org_wartawan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi wartawan?", field: "lembaga_anggota_org_wartawan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus Posyandu?", field: "lembaga_pengurus_posyandu", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus Posyantekdes?", field: "lembaga_pengurus_posyantekdes", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus kelompok tani atau nelayan?", field: "lembaga_pengurus_kel_tani", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota kelompok tani atau nelayan?", field: "lembaga_anggota_kel_tani", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus lembaga gotong royong?", field: "lembaga_pengurus_gotong_royong", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota lembaga gotong royong?", field: "lembaga_anggota_gotong_royong", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi profesi guru?", field: "lembaga_pengurus_org_guru", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi guru?", field: "lembaga_anggota_org_guru", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi dokter atau tenaga medis?", field: "lembaga_pengurus_org_dokter", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi tenaga medis?", field: "lembaga_anggota_org_dokter", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi pensiunan?", field: "lembaga_pengurus_org_pensiunan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi pensiunan?", field: "lembaga_anggota_org_pensiunan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi pemirsa atau pendengar?", field: "lembaga_pengurus_org_pemirsa", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi pemirsa?", field: "lembaga_anggota_org_pemirsa", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus kelompok pencinta alam?", field: "lembaga_pengurus_pencinta_alam", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota kelompok pencinta alam?", field: "lembaga_anggota_pencinta_alam", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus organisasi ilmu pengetahuan?", field: "lembaga_pengurus_ilmu_pengetahuan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota organisasi ilmu pengetahuan?", field: "lembaga_anggota_ilmu_pengetahuan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu memiliki atau mendirikan yayasan?", field: "lembaga_pemilik_yayasan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus yayasan?", field: "lembaga_pengurus_yayasan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota pengurus yayasan?", field: "lembaga_anggota_yayasan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus satgas kebersihan?", field: "lembaga_pengurus_satgas_kebersihan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota satgas kebersihan?", field: "lembaga_anggota_satgas_kebersihan", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus satgas kebakaran?", field: "lembaga_pengurus_satgas_kebakaran", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota satgas kebakaran?", field: "lembaga_anggota_satgas_kebakaran", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu pengurus posko bencana?", field: "lembaga_pengurus_posko_bencana", options: jawabLemmasOptions },
    { type: "select", label: "Apakah bapak/ibu anggota tim penanggulangan bencana?", field: "lembaga_anggota_tim_bencana", options: jawabLemmasOptions },
    ],

   8: [
    { type: "select", label: "Apakah memiliki atau terlibat dalam Koperasi?", field: "90", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki Unit Usaha Simpan Pinjam?", field: "91", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha industri kerajinan tangan?", field: "92", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha industri pakaian/jahit menjahit?", field: "93", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha industri pengolahan makanan/minuman?", field: "94", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha industri alat rumah tangga?", field: "95", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perdagangan bahan bangunan?", field: "96", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perdagangan alat pertanian/perkebunan?", field: "97", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha restoran/rumah makan/warung makan?", field: "98", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki toko kelontong/swalayan/minimarket?", field: "99", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki warung kelontong/kios/toko kecil?", field: "100", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha angkutan darat (mobil/bus/truk)?", field: "101", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha angkutan sungai/perahu motor?", field: "102", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha angkutan laut/kapal?", field: "103", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha angkutan udara?", field: "104", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha jasa ekspedisi/pengiriman barang?", field: "105", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang sumur/bor air?", field: "106", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki atau mengelola pasar harian?", field: "107", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki atau mengelola pasar mingguan?", field: "108", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki atau mengelola pasar ternak?", field: "109", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perdagangan hasil bumi atau tambang?", field: "110", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perdagangan antar pulau?", field: "111", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai pengijon (pemberi modal hasil bumi)?", field: "112", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tengkulak/pedagang pengumpul?", field: "113", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha peternakan (sapi, kambing, ayam, dll)?", field: "114", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perikanan (tambak, budidaya ikan)?", field: "115", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perkebunan (kelapa sawit, karet, dll)?", field: "116", options: jawabLemekOptions },
    { type: "select", label: "Apakah tergabung dalam kelompok simpan pinjam wanita/arisan?", field: "117", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha produksi minuman (sirup, kopi, dll)?", field: "118", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha apotek/toko obat/farmasi?", field: "119", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha karoseri/modifikasi kendaraan?", field: "120", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha penitipan/garasi kendaraan bermotor?", field: "121", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perakitan elektronik/reparasi?", field: "122", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha pengolahan kayu/mebel?", field: "123", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki atau mengelola bioskop?", field: "124", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha film keliling/bioskop berjalan?", field: "125", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup sandiwara/drama/teater?", field: "126", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup lawak/komedi?", field: "127", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup tari Jaipongan?", field: "128", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup wayang orang/golek?", field: "129", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup musik/band/orkes?", field: "130", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki grup vokal/paduan suara?", field: "131", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha persewaan genset/tenaga listrik?", field: "132", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha pengecer gas elpiji/BBM eceran?", field: "133", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha air minum isi ulang/AMDK?", field: "134", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang kayu/mebel?", field: "135", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang batu/bangunan?", field: "136", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang jahit/bordir/konveksi?", field: "137", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang cukur/pangkas rambut?", field: "138", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang service elektronik?", field: "139", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang las/besi?", field: "140", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai tukang pijat/urut/refleksi?", field: "141", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai Notaris?", field: "143", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai Pengacara/Advokat?", field: "144", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai Konsultan Manajemen?", field: "145", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai Konsultan Teknik?", field: "146", options: jawabLemekOptions },
    { type: "select", label: "Apakah berprofesi sebagai Pejabat Pembuat Akta Tanah (PPAT)?", field: "147", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha losmen/pondok wisata?", field: "148", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha wisma/penginapan?", field: "149", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha asrama/kos-kosan?", field: "150", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha persewaan kamar?", field: "151", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki rumah kontrakan?", field: "152", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki mess/pondok pekerja?", field: "153", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha hotel?", field: "154", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha home stay?", field: "155", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha villa?", field: "156", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha town house/apartemen sewa?", field: "157", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha perusahaan asuransi?", field: "158", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki lembaga keuangan mikro/bukan bank?", field: "159", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki Lembaga Perkreditan Rakyat (LPR)?", field: "160", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha pegadaian?", field: "161", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki Bank Perkreditan Rakyat (BPR)?", field: "162", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha penyewaan alat pesta/tenda?", field: "163", options: jawabLemekOptions },
    { type: "select", label: "Apakah memiliki usaha pengolahan dan penjualan hasil hutan?", field: "164", options: jawabLemekOptions }
],
};

const wilayahQuestions = [
    { type: "select", label: "Provinsi asalnya apa?", field: "kdprovinsi", options: provinsiOptions },
    { type: "select", label: "Kabupaten atau kota asalnya apa?", field: "kdkabupaten", dynamic: true, dynamicUrl: "/get-kabupaten/" },
    { type: "select", label: "Kecamatan asalnya apa?", field: "kdkecamatan", dynamic: true, dynamicUrl: "/get-kecamatan/" },
    { type: "select", label: "Desa atau kelurahan asalnya apa?", field: "kddesa", dynamic: true, dynamicUrl: "/get-desa/" }
];

/* ==================== SPEECH ==================== */
function speak(text) {
    return new Promise(resolve => {
        if (isSpeaking) return resolve();
        isSpeaking = true;
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = 'id-ID';
        utter.rate = 1.05;
        utter.onend = () => { isSpeaking = false; resolve(); };
        utter.onerror = () => { isSpeaking = false; resolve(); };
        speechSynthesis.speak(utter);
    });
}

async function speakQuestionAndOptions() {
    const q = questions[currentModul][step];

    if (currentModul === 3 && step === 0) await speak("Modul Sosial Ekonomi. Jawab sesuai pilihan.");
    if (currentModul === 4 && step === 0) await speak("Modul Usaha ART dimulai.");

    await speak(q.label);

    if (q.type === "select" && q.options) {
        const opts = Object.values(q.options).slice(0, 25);
        for (let i = 0; i < opts.length; i++) {
            const clean = opts[i].replace(/\//g, ' atau ').replace(/_/g, ' ');
            await speak(`${i + 1}. ${clean}`);
            if (i < opts.length - 1) await new Promise(r => setTimeout(r, 100));
        }
    }

    document.getElementById('voice-status').innerText = 'Mendengarkan...';
}

async function nextQuestion() {
    step++;
    if (step >= questions[currentModul].length) {
        modulStatus[currentModul] = 'completed';
        if (currentModul === 5 || currentModul === 6) {
            await submitModul(currentModul);
        }
        isReviewMode = true;
        saveData();
        updateProgressSteps();
        checkAllCompletedAndShowSimpanBtn();
        showReviewForm();
    } else {
        renderQuestion();
        if (isListening) speakQuestionAndOptions();
    }
}


/* ==================== UTILITIES ==================== */
function capitalize(text) { return text.replace(/\b\w/g, l => l.toUpperCase()); }
function cleanOptionText(text) { return text.replace(/\//g, ' atau ').replace(/_/g, ' '); }

function findBestMatch(text, options) {
    const norm = text.toLowerCase().trim();
    for (const [id, label] of Object.entries(options)) {
        const cleanLabel = label.toLowerCase().replace(/[^a-z0-9]/g, '');
        if (label.toLowerCase().includes(norm) || norm.includes(cleanLabel)) {
            return [id, label];
        }
    }
    return null;
}

function mapOmsetToCode(omset) {
    let input = (omset ?? '').toString().toLowerCase().trim();
    let angka = parseFloat(input.replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;
    if (/juta|jutaan/i.test(input)) angka *= 1000000;

    if (angka <= 1000000) return '1';
    if (angka <= 5000000) return '2';
    if (angka <= 10000000) return '3';
    return '4';
}
function mapPendapatanToCode(pendapatan) {
    if (!pendapatan) return '0';

    let input = pendapatan.toString().toLowerCase().trim();

    // Ambil angka saja
    let angkaStr = input.replace(/[^\d.,]/g, '').replace(/\./g, '').replace(/,/g, '.');
    let nilai = parseFloat(angkaStr) || 0;

    // Deteksi "juta"
    if (/juta|jutaan|milyar/i.test(input)) {
        nilai *= 1000000;
    }
    // Deteksi "ribu" (biar 800 ribu masuk)
    else if (/ribu/i.test(input) && nilai < 10000) {
        nilai *= 1000;
    }

    if (nilai <= 1000000)  return '1';  // ≤ 1 juta
    if (nilai <= 1500000)  return '2';  // ≤ 1,5 juta
    if (nilai <= 2000000)  return '3';  // ≤ 2 juta
    if (nilai <= 3000000)  return '4';  // ≤ 3 juta
    return '5';                                 // > 3 juta
}

/* ==================== PROCESS ANSWER ==================== */
async function processVoiceAnswer(text) {
    if (isSpeaking) return;
    const q = questions[currentModul][step];
    let value = text.trim();

    // Omset khusus
    if (q.field === "kdomsetusaha") {
        const kode = mapOmsetToCode(text);
        answers[q.field] = kode;
        const konfirmasi = { '1': 'kurang dari atau sama dengan satu juta', '2': 'satu sampai lima juta', '3': 'lima sampai sepuluh juta', '4': 'lebih dari sepuluh juta' };
        await speak(`Omset ${konfirmasi[kode]} rupiah. Lanjut.`);
        saveData(); renderQuestion(); setTimeout(nextQuestion, 1500);
        return;
    }
        else if (q.field === "kdpendapatanperbulan") {
        const kode = mapPendapatanToCode(text);
        if (kode === '0') {
            await speak("Maaf, pendapatan tidak terdeteksi. Ulangi dengan bilang angka, contoh: satu juta, satu setengah juta, dua juta, tiga setengah juta.");
            return;
        }
        answers[q.field] = kode;
        const konfirmasi = {
            '1': 'kurang dari atau sama dengan satu juta rupiah',
            '2': 'satu juta sampai satu setengah juta rupiah',
            '3': 'satu setengah juta sampai dua juta rupiah',
            '4': 'dua juta sampai tiga juta rupiah',
            '5': 'lebih dari tiga juta rupiah'
        };
        await speak(`Pendapatan per bulan ${konfirmasi[kode]}. Lanjut ya.`);
        const inputEl = document.getElementById('inputAnswer');
        if (inputEl) inputEl.value = konfirmasi[kode];
        saveData();
        renderQuestion();
        setTimeout(nextQuestion, 2000);
        return;
    }

    if (q.type === "skip") {
        answers[q.field] = q.default;
        setTimeout(nextQuestion, 800);
        return;
    }

    if (q.type === "select") {
        const match = findBestMatch(text, q.options);
        if (!match) { await speak("Maaf, tidak dikenali. Mohon ulangi."); return; }
        value = match[0];
    } 
    else if (q.type === "text") 
        // === VALIDASI KHUSUS ANGKA ===
    if (["nik", "no_kk"].includes(q.field)) {
        const angka = text.replace(/\D/g, ''); // hanya ambil angka
        if (angka.length !== 16) {
            await speak("Harus 16 digit. Mohon ulangi dengan jelas.");
            return;
        }
        value = angka;
    }
    else if (q.field === "penduduk_noaktalahir") {
        const angka = text.replace(/\D/g, '');
        if (angka.length === 0) {
            await speak("Nomor akta lahir harus berupa angka. Ulangi.");
            return;
        }
        value = angka;
    }
    else if (q.field === "penduduk_nourutkk") {
        const angka = text.replace(/\D/g, '');
        if (!angka || angka === "0" || parseInt(angka) > 99) {
            await speak("Nomor urut KK harus dari 1 sampai 99. Ulangi.");
            return;
        }
        value = angka.padStart(2, '0'); // otomatis jadi 02, 05, dst
    }
    // === KEWARGANEGARAAN OTOMATIS ===
    else if (q.type === "skip") {
        answers[q.field] = q.default;
        saveData();
        setTimeout(nextQuestion, 600);
        return;
    }
    // === SELECT BIASA ===
    else if (q.type === "select") {
        const match = findBestMatch(text, q.options);
        if (!match) {
            await speak("Pilihan tidak dikenali. Mohon ulangi.");
            return;
        }
        value = match[0];
    }
    // === TEXT BIASA ===
    else {
        value = capitalize(text);
    }

    answers[q.field] = value;

    // Insert wilayah datang jika mutasi "Datang"
    if (currentModul === 1 && q.field === "kdmutasimasuk" && text.toLowerCase().includes("datang")) {
        const idx = questions[1].findIndex(i => i.field === "penduduk_tanggalmutasi") + 1;
        if (!questions[1].some(x => x.field === "kdprovinsi")) {
            questions[1].splice(idx, 0, ...wilayahQuestions);
            document.getElementById('wilayahDatangReview').classList.remove('hidden');
        }
    }

    saveData();
    renderQuestion();
    setTimeout(nextQuestion, 1200);
}

function nextQuestion() {
    step++;
    if (step >= questions[currentModul].length) {
        modulStatus[currentModul] = 'completed';
        isReviewMode = true;
        saveData();
        updateProgressSteps();
        checkAllCompletedAndShowSimpanBtn();
        showReviewForm();
    } else {
        renderQuestion();
        if (isListening) speakQuestionAndOptions();
    }
}

/* ==================== RENDER ==================== */
function renderQuestion() {
    const q = questions[currentModul][step];
    let html = `<h3 class="text-lg font-medium text-center mb-6 text-gray-800">${q.label}</h3>`;

    if (q.type === "select") {
        const optionCount = Object.keys(q.options).length;

        // KHUSUS 2 PILIHAN → pakai layout spesial supaya pas di tengah
        if (optionCount === 2) {
            html += `<div class="flex justify-center items-center gap-8 px-6 max-w-3xl mx-auto">`;

            Object.entries(q.options).forEach(([id, nama]) => {
                const selected = answers[q.field] === id ? 'selected' : '';
                html += `
                    <div class="option-card ${selected} flex-1 max-w-sm py-8 cursor-pointer select-none transition-all duration-200 text-center text-lg font-semibold rounded-2xl shadow-sm hover:shadow-md"
                         data-value="${id}"
                         data-text="${nama}">
                        ${nama}
                    </div>
                `;
            });

            html += `</div>`;
        }
        // LEBIH DARI 2 PILIHAN → tetap pakai grid seperti biasa (sudah rata tengah)
        else {
            html += `
                <div class="flex justify-center px-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4 w-full max-w-5xl">
            `;

            Object.entries(q.options).forEach(([id, nama]) => {
                const selected = answers[q.field] === id ? 'selected' : '';
                html += `
                    <div class="option-card ${selected} cursor-pointer select-none text-center py-4"
                         data-value="${id}"
                         data-text="${nama}">
                        <span class="text-sm font-medium block">${nama}</span>
                    </div>
                `;
            });

            html += `
                    </div>
                </div>
            `;
        }
    } 
    else {
        // Tipe input text biasa
        const val = answers[q.field] || '';
        html += `
            <div class="max-w-md mx-auto">
                <input type="text" id="inputAnswer"
                       class="w-full border border-gray-300 rounded-xl p-3 text-center text-lg"
                       readonly value="${val}" placeholder="Jawaban akan muncul di sini...">
            </div>
        `;
    }

    document.getElementById('quizArea').innerHTML = html;
    updateProgress();
    attachCardListeners();
}

function updateProgress() {
    const total = questions[currentModul].length;
    const percent = ((step + 1) / total) * 100;
    document.getElementById('progressBar').style.width = percent + "%";
    document.getElementById('currentQ').textContent = step + 1;
    document.getElementById('totalQ').textContent = total;
}

function attachCardListeners() {
    document.querySelectorAll('.option-card').forEach(card => {
        card.onclick = () => {
            document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            processVoiceAnswer(card.dataset.text);
        };
    });
}

/* ==================== STORAGE & NAVIGASI ==================== */
function saveData() {
    localStorage.setItem('voiceAnswers', JSON.stringify(answers));
    localStorage.setItem('modulStatus', JSON.stringify(modulStatus));
    localStorage.setItem(`step_${currentModul}`, step);
    localStorage.setItem(`review_${currentModul}`, isReviewMode);
    localStorage.setItem('currentModul', currentModul);
}

function loadModulData(modId) {
    const savedAnswers = localStorage.getItem('voiceAnswers');
    const savedStatus = localStorage.getItem('modulStatus');
    const savedStep = localStorage.getItem(`step_${modId}`);
    const savedReview = localStorage.getItem(`review_${modId}`);

    if (savedAnswers) answers = JSON.parse(savedAnswers);
    if (savedStatus) modulStatus = JSON.parse(savedStatus);
    step = savedStep ? parseInt(savedStep) : 0;
    isReviewMode = savedReview === 'true';
    currentModul = modId;

    Object.keys(modulStatus).forEach(k => {
        modulStatus[k] = (k == modId) ? 'active' : (modulStatus[k] === 'completed' ? 'completed' : 'pending');
    });
}

function updateProgressSteps() {
    const container = document.getElementById('progressSteps');
    container.innerHTML = '';

    modules.forEach((mod, i) => {
        if (i > 0) {
            const line = document.createElement('div');
            line.className = `h-0.5 w-12 self-center rounded-full ${modulStatus[mod.id] === 'completed' ? 'bg-blue-600' : 'bg-gray-300'}`;
            container.appendChild(line);
        }

        const div = document.createElement('div');
        div.className = 'flex items-center cursor-pointer';
        div.onclick = () => switchModul(mod.id);

        const status = modulStatus[mod.id] || 'pending';
        const circle = document.createElement('div');
        circle.className = `w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white ${
            status === 'completed' ? 'bg-blue-600' : status === 'active' ? 'bg-green-600' : 'bg-gray-300 text-gray-600'
        }`;
        circle.textContent = mod.id;

        const text = document.createElement('div');
        text.className = 'ml-2 text-left';
        text.innerHTML = `<div class="font-medium text-sm">${mod.name}</div>
                         <div class="text-xs ${status === 'completed' ? 'text-blue-600' : status === 'active' ? 'text-green-600' : 'text-gray-500'}">
                             ${status === 'completed' ? 'Selesai' : status === 'active' ? 'Aktif' : 'Belum'}
                         </div>`;

        div.appendChild(circle);
        div.appendChild(text);
        container.appendChild(div);
    });
}

function switchModul(modId) {
    stopListening();
    saveData();
    loadModulData(modId);

    document.getElementById('reviewForm').classList.add('hidden');
    document.getElementById('quizArea').innerHTML = '';
    document.getElementById('voice-status').innerText = 'Tekan mic untuk mulai merekam...';
    document.getElementById('modulTitle').textContent = modules.find(m => m.id === modId).name;

    updateProgressSteps();
    checkAllCompletedAndShowSimpanBtn();

    if (modulStatus[modId] === 'completed' && isReviewMode) {
        showReviewForm();
    } else {
        renderQuestion();
    }
}

function stopListening() {
    if (recognition) recognition.stop();
    if (audioContext) audioContext.close().catch(() => {});
    isListening = false;
    document.getElementById('startBtn').classList.remove('listening');
    document.getElementById('visualizer').style.opacity = 0;
}

function checkAllCompletedAndShowSimpanBtn() {
    const allDone = Object.values(modulStatus).every(s => s === 'completed');
    const btn = document.getElementById('simpanBtn');
    if (allDone) {
        btn.style.backgroundColor = '#2563eb';
        btn.disabled = false;
    } else {
        btn.style.backgroundColor = '#9ca3af';
        btn.disabled = true;
    }
}

function showReviewForm() {
    stopListening();
    document.getElementById('quizArea').innerHTML = '';
    document.getElementById('voice-status').innerText = 'Review data. Selesaikan semua modul untuk simpan.';
    document.getElementById('reviewForm').classList.remove('hidden');

    const container = document.getElementById('reviewFields');
    container.innerHTML = '';

    questions[currentModul].forEach(q => {
        if (!answers[q.field]) return;

        let input = '';
        if (q.type === "select") {
            input = `<select name="${q.field}" class="w-full border rounded-lg p-2 text-sm"><option value="">-- Pilih --</option>`;
            Object.entries(q.options || {}).forEach(([k, v]) => {
                input += `<option value="${k}" ${answers[q.field] == k ? 'selected' : ''}>${v}</option>`;
            });
            input += `</select>`;
        } else {
            input = `<input type="${q.type === 'number' ? 'number' : 'text'}" name="${q.field}" value="${answers[q.field] || ''}" class="w-full border rounded-lg p-2 text-sm">`;
        }

        container.innerHTML += `
            <div>
                <label class="block text-xs font-medium mb-1">${q.label.replace('?', '')}</label>
                ${input}
            </div>`;
    });
}

/* ==================== MIC & VISUALIZER ==================== */
document.getElementById('startBtn').addEventListener('click', async () => {
    if (isListening || isReviewMode) return;

    isListening = true;
    document.getElementById('startBtn').classList.add('listening');
    document.getElementById('visualizer').style.opacity = 1;

    try {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        analyser = audioContext.createAnalyser();
        canvas = document.getElementById('visualizer');
        ctx = canvas.getContext('2d');
        dataArray = new Uint8Array(analyser.frequencyBinCount);

        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const source = audioContext.createMediaStreamSource(stream);
        source.connect(analyser);
        drawVisualizer();
    } catch (err) {
        alert("Gagal akses mikrofon! Pastikan izin diberikan.");
        stopListening();
        return;
    }

    await speakQuestionAndOptions();

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) { alert("Browser tidak mendukung Speech Recognition"); stopListening(); return; }

    recognition = new SpeechRecognition();
    recognition.lang = 'id-ID';
    recognition.continuous = true;
    recognition.interimResults = true;

    recognition.onresult = e => {
        const result = e.results[e.results.length - 1];
        if (result.isFinal) {
            const text = result[0].transcript.trim();
            document.getElementById('voice-status').innerText = `Dengar: "${text}"`;
            processVoiceAnswer(text);
        }
    };

    recognition.onerror = () => setTimeout(() => recognition?.start(), 500);
    recognition.onend = () => { if (isListening) setTimeout(() => recognition?.start(), 500); };

    recognition.start();
});

function drawVisualizer() {
    if (!isListening) return;
    requestAnimationFrame(drawVisualizer);
    analyser.getByteFrequencyData(dataArray);
    ctx.fillStyle = 'rgba(255,255,255,0.1)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    const barWidth = (canvas.width / dataArray.length) * 2.5;
    let x = 0;
    for (let i = 0; i < dataArray.length; i++) {
        const h = dataArray[i] / 2;
        ctx.fillStyle = `rgb(${Math.min(h + 120, 255)}, 80, 80)`;
        ctx.fillRect(x, canvas.height - h, barWidth, h);
        x += barWidth + 1;
    }
}

/* ==================== SUBMIT ==================== */
document.getElementById('voiceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('simpanBtn');
    btn.disabled = true;
    btn.innerText = "Menyimpan...";

    Object.keys(answers).forEach(key => {
        let el = this.querySelector(`[name="${key}"]`);
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = key;
            this.appendChild(el);
        }
        el.value = answers[key];
    });

    const formData = new FormData(this);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const res = await fetch("{{ route('voice.store-all') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": token, "Accept": "application/json" },
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            localStorage.clear();
            alert("SEMUA DATA BERHASIL DISIMPAN!");
            location.reload();
        } else {
            alert("Gagal: " + (data.error || JSON.stringify(data)));
        }
    } catch (err) {
        alert("Error: " + err.message);
    } finally {
        btn.disabled = false;
        btn.innerText = "Simpan Semua Data";
    }
});

/* ==================== CHAINED SELECT WILAYAH ==================== */
['kdprovinsi', 'kdkabupaten', 'kdkecamatan'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', async function() {
        const val = this.value;
        const nextMap = { kdprovinsi: 'kdkabupaten', kdkabupaten: 'kdkecamatan', kdkecamatan: 'kddesa' };
        const nextId = nextMap[id];
        if (!nextId || !val) return;

        const nextSelect = document.getElementById(nextId);
        nextSelect.innerHTML = '<option>-- Pilih --</option>';
        const url = `/get-${nextId.replace('kd', '').toLowerCase()}/${val}`;
        const res = await fetch(url);
        const data = await res.json();
        Object.entries(data).forEach(([k, v]) => {
            nextSelect.innerHTML += `<option value="${k}">${v}</option>`;
        });
    });
});

/* ==================== INIT ==================== */
function initFresh() {
    localStorage.clear();
    currentModul = 1; step = 0; isReviewMode = false;
    answers = { penduduk_tanggalmutasi: new Date().toISOString().split('T')[0] };
    modulStatus = {1:'active',2:'pending',3:'pending',4:'pending',5:'pending',6:'pending',7:'pending',8:'pending'};
    document.getElementById('reviewForm').classList.add('hidden');
    document.getElementById('simpanBtn').disabled = true;
    document.getElementById('simpanBtn').style.backgroundColor = '#9ca3af';
    updateProgressSteps();
    renderQuestion();
    checkAllCompletedAndShowSimpanBtn();
}
initFresh();
</script>
</x-app-layout>