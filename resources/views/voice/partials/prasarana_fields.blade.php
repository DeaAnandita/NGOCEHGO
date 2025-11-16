@php
    // Pastikan di controller: $masters = [...] dengan pluck('nama', 'kd...')
    $fields = [
        'kdstatuspemilikbangunan' => ['label' => 'Status Pemilik Bangunan', 'master' => 'status_pemilik_bangunan'],
        'kdstatuspemiliklahan'    => ['label' => 'Status Pemilik Lahan',    'master' => 'status_pemilik_lahan'],
        'kdjenisfisikbangunan'    => ['label' => 'Jenis Fisik Bangunan',   'master' => 'jenis_fisik_bangunan'],
        'kdjenislantaibangunan'   => ['label' => 'Jenis Lantai Bangunan',  'master' => 'jenis_lantai'],
        'kdkondisilantaibangunan' => ['label' => 'Kondisi Lantai Bangunan','master' => 'kondisi_lantai'],
        'kdjenisdindingbangunan'  => ['label' => 'Jenis Dinding Bangunan', 'master' => 'jenis_dinding'],
        'kdkondisidindingbangunan'=> ['label' => 'Kondisi Dinding Bangunan','master' => 'kondisi_dinding'],
        'kdjenisatapbangunan'     => ['label' => 'Jenis Atap Bangunan',    'master' => 'jenis_atap'],
        'kdkondisiatapbangunan'   => ['label' => 'Kondisi Atap Bangunan',  'master' => 'kondisi_atap'],
        'kdsumberairminum'        => ['label' => 'Sumber Air Minum',       'master' => 'sumber_air_minum'],
        'kdkondisisumberair'      => ['label' => 'Kondisi Sumber Air',     'master' => 'kondisi_sumber_air'],
        'kdcaraperolehanair'      => ['label' => 'Cara Perolehan Air',     'master' => 'cara_perolehan_air'],
        'kdsumberpeneranganutama' => ['label' => 'Sumber Penerangan Utama','master' => 'sumber_penerangan'],
        'kdsumberdayaterpasang'   => ['label' => 'Daya Terpasang',         'master' => 'daya_terpasang'],
        'kdbahanbakarmemasak'     => ['label' => 'Bahan Bakar Memasak',    'master' => 'bahan_bakar'],
        'kdfasilitastempatbab'    => ['label' => 'Fasilitas Tempat BAB',   'master' => 'fasilitas_bab'],
        'kdpembuanganakhirtinja'  => ['label' => 'Pembuangan Akhir Tinja',  'master' => 'pembuangan_tinja'],
        'kdcarapembuangansampah'  => ['label' => 'Cara Pembuangan Sampah', 'master' => 'pembuangan_sampah'],
        'kdmanfaatmataair'        => ['label' => 'Manfaat Mata Air',       'master' => 'manfaat_mataair'],
    ];
@endphp

@foreach($fields as $field => $info)
    <div>
        <label class="block font-medium mb-2 text-gray-700">{{ $info['label'] }}</label>
        <select name="{{ $field }}" id="{{ $field }}" class="w-full border rounded-lg p-4 text-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
            <option value="">-- Silahkan Pilih --</option>
            @foreach($masters[$info['master']] as $kd => $nama)
                <option value="{{ $kd }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>
@endforeach

<div>
    <label class="block font-medium mb-2 text-gray-700">Luas Lantai (m²)</label>
    <input type="number" step="0.01" min="0" name="prasdas_luaslantai" id="prasdas_luaslantai" 
           class="w-full border rounded-lg p-4 text-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
           placeholder="Contoh: 60.5" required>
</div>

<div>
    <label class="block font-medium mb-2 text-gray-700">Jumlah Kamar</label>
    <input type="number" min="0" name="prasdas_jumlahkamar" id="prasdas_jumlahkamar" 
           class="w-full border rounded-lg p-4 text-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
           placeholder="Contoh: 3" required>
</div>