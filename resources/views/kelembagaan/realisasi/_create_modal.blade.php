<div id="tambahRealisasiModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">

        <h3 class="font-bold mb-4">
            Tambah Realisasi<br>
            <span class="text-sm text-gray-500">
                {{ $pencairan->kegiatan->nama_kegiatan }}
            </span>
        </h3>

        <p class="text-sm mb-4">
            Sisa dana:
            <span class="font-bold text-green-600">
                Rp {{ number_format($sisa, 0, ',', '.') }}
            </span>
        </p>

        <form method="POST" action="{{ route('kelembagaan.realisasi.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pencairan_id" value="{{ $pencairan->id }}">

            <label class="text-sm">Tanggal</label>
            <input type="date" name="tanggal" required class="w-full border rounded p-2 mb-3">

            <label class="text-sm">Uraian</label>
            <input type="text" name="uraian" required class="w-full border rounded p-2 mb-3">

            <label class="text-sm">Jumlah Uang</label>
            <input type="number" name="jumlah" max="{{ $sisa }}" required
                class="w-full border rounded p-2 mb-3">

            <label class="text-sm">Bukti</label>
            <input type="file" name="bukti" class="w-full border rounded p-2 mb-4">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTambahRealisasi()" class="px-4 py-2 bg-gray-200 rounded">
                    Batal
                </button>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>
