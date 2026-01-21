<x-app-layout>
    <div class="max-w-md mx-auto mt-20 bg-white p-8 shadow text-center rounded-lg">

        <h1 class="text-xl font-bold mb-4">Scan untuk Mencetak Surat</h1>

        @if ($surat->barcode_cetak_path)
            <img src="{{ asset($surat->barcode_cetak_path) }}" class="mx-auto w-64 h-64 mb-4" alt="QR Cetak Surat">
        @else
            <p class="text-gray-600">Barcode cetak belum dibuat.</p>
        @endif

        <p class="text-sm text-gray-500 mt-4">
            Arahkan kamera HP atau scanner ke QR di atas<br>
            untuk membuka halaman cetak surat secara otomatis.
        </p>

        <a href="{{ route('pelayanan.surat.index') }}"
            class="inline-block mt-6 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Kembali
        </a>

    </div>
</x-app-layout>
