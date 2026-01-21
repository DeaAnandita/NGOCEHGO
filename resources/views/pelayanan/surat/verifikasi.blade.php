<x-app-layout>

    <div class="max-w-4xl mx-auto mt-10 bg-white shadow-xl rounded-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-blue-800 text-white p-6 text-center">
            <h1 class="text-2xl font-bold uppercase">
                Sistem Verifikasi Dokumen Desa Kaliwungu
            </h1>
            <p class="text-sm mt-1">
                Layanan resmi Pemerintah Desa Kaliwungu – Kabupaten Kudus
            </p>
        </div>

        {{-- ISI --}}
        <div class="p-8">

            @if (!$valid)
                <div class="text-center">
                    <h2 class="text-xl font-bold text-red-600 mb-2">
                        ❌ Dokumen Tidak Valid
                    </h2>

                    <p class="text-gray-700 max-w-xl mx-auto">
                        Kode verifikasi yang Anda masukkan tidak ditemukan di dalam sistem kami atau telah
                        dinonaktifkan.
                        Dokumen ini <b>tidak dapat dipastikan keasliannya</b>.
                    </p>

                    <a href="/"
                        class="mt-6 inline-block bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded">
                        Kembali
                    </a>
                </div>
            @else
                <div class="text-center">
                    <h2 class="text-xl font-bold text-green-700 mb-2">
                        ✔ Dokumen Asli dan Terverifikasi
                    </h2>

                    <p class="text-gray-700 max-w-2xl mx-auto">
                        Berdasarkan hasil pengecekan sistem verifikasi Pemerintah Desa Kaliwungu, dokumen surat ini
                        dinyatakan <b>asli</b> dan <b>terdaftar secara resmi</b>.
                        Berikut adalah data ringkas surat yang bersangkutan:
                    </p>
                </div>

                {{-- RINGKASAN --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 p-5 rounded border">

                    <div><b>Nomor Surat</b><br>{{ $surat->nomor_surat }}</div>
                    <div><b>Tanggal Surat</b><br>{{ $surat->tanggal_surat?->format('d-m-Y') }}</div>

                    <div><b>Nama Pemohon</b><br>{{ $surat->nama }}</div>
                    <div><b>NIK</b><br>{{ $surat->nik }}</div>

                    <div class="md:col-span-2">
                        <b>Keperluan</b><br>{{ $surat->keperluan }}
                    </div>

                    <div>
                        <b>Status Dokumen</b><br>
                        <span class="text-green-700 font-semibold">
                            Disetujui & Sah
                        </span>
                    </div>

                </div>

                {{-- PREVIEW SURAT --}}
                <div class="mt-10">

                    <h3 class="text-lg font-semibold text-gray-800 mb-3 text-center">
                        Pratinjau Dokumen Surat
                    </h3>

                    <div class="border rounded shadow bg-gray-100 p-3">
                        <iframe src="{{ route('pelayanan.surat.cetak', $surat->cetak_token) }}"
                            class="w-full h-[700px] bg-white border">
                        </iframe>
                    </div>

                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Dokumen ini ditampilkan langsung dari sistem resmi Pemerintah Desa Kaliwungu.
                        QR Code pada surat dapat digunakan untuk verifikasi ulang.
                    </p>

                </div>

                <div class="mt-8 text-center">
                    <a href="/" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded">
                        Kembali ke Beranda
                    </a>
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
    