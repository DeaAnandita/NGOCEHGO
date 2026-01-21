    {{-- MODAL TAMBAH --}}
    <div id="tambahModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white rounded-xl p-6 w-96">

            <h3 class="font-bold mb-4">Tambah Anggaran Kegiatan</h3>

            <form method="POST" action="{{ route('kelembagaan.kegiatan_anggaran.store') }}">
                @csrf

                <input type="hidden" name="anggaran_id" value="{{ $anggaran->id }}">

                <label class="text-sm">Kegiatan</label>
                <select name="kegiatan_id" required class="w-full border rounded p-2 mb-3">
                    <option value="">-- Pilih --</option>
                    @foreach ($kegiatan as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                    @endforeach
                </select>

                <label class="text-sm">Sumber Dana</label>
                <select name="kdsumber" required class="w-full border rounded p-2 mb-3">
                    @foreach ($sumber as $s)
                        <option value="{{ $s->kdsumber }}">{{ $s->sumber_dana }}</option>
                    @endforeach
                </select>

                <label class="text-sm">Nilai</label>
                <input type="number" name="nilai_anggaran" class="w-full border rounded p-2 mb-4" required>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeTambah()" class="px-4 py-2 bg-gray-200 rounded">
                        Batal
                    </button>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
