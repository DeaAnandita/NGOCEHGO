<div id="editRealisasiModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">

        <h3 class="font-bold mb-4">Edit Realisasi</h3>

        <form method="POST" action="{{ route('kelembagaan.realisasi.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="edit_realisasi_id">

            <label class="text-sm">Tanggal</label>
            <input type="date" name="tanggal" id="edit_tanggal" class="w-full border rounded p-2 mb-3" required>

            <label class="text-sm">Uraian</label>
            <input type="text" name="uraian" id="edit_uraian" class="w-full border rounded p-2 mb-3" required>

            <label class="text-sm">Jumlah Uang</label>
            <input type="number" name="jumlah" id="edit_jumlah" class="w-full border rounded p-2 mb-3" required>

            {{-- PREVIEW BUKTI --}}
            <div class="mb-3">
                <label class="text-sm">Bukti Saat Ini</label>

                <div id="buktiPreview" class="mt-2 hidden">
                    <img id="buktiImage" class="max-h-40 rounded border mx-auto hidden" />

                    <a id="buktiLink" href="#" target="_blank"
                        class="block text-center text-blue-600 underline hidden">
                        Lihat File
                    </a>
                </div>
            </div>

            <label class="text-sm">Ganti Bukti (opsional)</label>
            <input type="file" name="bukti" class="w-full border rounded p-2 mb-4" accept="image/*,.pdf">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditRealisasi()" class="px-4 py-2 bg-gray-200 rounded">
                    Batal
                </button>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>
