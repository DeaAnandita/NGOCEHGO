{{-- KELUARGA FIELDS --}}
<div class="space-y-6">
    <h4 class="col-span-2 text-xl font-bold text-teal-700 mb-4">Data Keluarga</h4>

    <div>
        <label class="block font-medium">Jenis Mutasi</label>
        <select name="kdmutasimasuk" id="kdmutasimasuk" class="w-full border rounded-lg p-3" required>
            <option value="">-- Pilih Mutasi --</option>
            @foreach($mutasi as $kd => $nama)
                <option value="{{ $kd }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-medium">Tanggal Mutasi</label>
        <input type="date" name="keluarga_tanggalmutasi" id="keluarga_tanggalmutasi" class="w-full border rounded-lg p-3" required>
    </div>

    <div class="relative">
        <label class="block font-medium">No KK</label>
        <input type="text" name="no_kk" id="no_kk" maxlength="16" class="w-full border rounded-lg p-3" required>
        <p id="noKkError" class="text-red-600 text-sm mt-1 hidden">No KK sudah terdaftar!</p>
    </div>

    <div>
        <label class="block font-medium">Kepala Rumah Tangga</label>
        <input type="text" name="keluarga_kepalakeluarga" id="keluarga_kepalakeluarga" class="w-full border rounded-lg p-3" required>
    </div>

    <div>
        <label class="block font-medium">Dusun/Lingkungan</label>
        <select name="kddusun" id="kddusun" class="w-full border rounded-lg p-3" required>
            <option value="">-- Silahkan Pilih --</option>
            @foreach($dusun as $kd => $nama)
                <option value="{{ $kd }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-medium">RW</label>
        <input type="text" name="keluarga_rw" id="keluarga_rw" maxlength="3" class="w-full border rounded-lg p-3" required>
    </div>

    <div>
        <label class="block font-medium">RT</label>
        <input type="text" name="keluarga_rt" id="keluarga_rt" maxlength="3" class="w-full border rounded-lg p-3" required>
    </div>

    <div>
        <label class="block font-medium">Alamat Lengkap</label>
        <textarea name="keluarga_alamatlengkap" id="keluarga_alamatlengkap" class="w-full border rounded-lg p-3 h-32" required></textarea>
    </div>
</div>