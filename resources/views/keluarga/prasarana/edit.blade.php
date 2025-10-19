<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Prasarana Dasar</h3>

                <form action="{{ route('keluarga.prasarana.update', $prasarana->no_kk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Keluarga Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Keluarga</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keluarga</label>
                                <select name="no_kk" id="no_kk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Keluarga --</option>
                                    @foreach($keluargas as $kel)
                                        <option value="{{ $kel->no_kk }}" {{ old('no_kk', $prasarana->no_kk) == $kel->no_kk ? 'selected' : '' }}>
                                            {{ $kel->keluarga_kepalakeluarga ?? '-' }} ({{ $kel->no_kk }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('no_kk')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bangunan Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Bangunan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Pemilik Bangunan</label>
                                <select name="kdstatuspemilikbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($statuspemilikbangunan as $item)
                                        <option value="{{ $item->kdstatuspemilikbangunan }}" {{ old('kdstatuspemilikbangunan', $prasarana->kdstatuspemilikbangunan) == $item->kdstatuspemilikbangunan ? 'selected' : '' }}>{{ $item->statuspemilikbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdstatuspemilikbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Pemilik Lahan</label>
                                <select name="kdstatuspemiliklahan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($statuspemiliklahan as $item)
                                        <option value="{{ $item->kdstatuspemiliklahan }}" {{ old('kdstatuspemiliklahan', $prasarana->kdstatuspemiliklahan) == $item->kdstatuspemiliklahan ? 'selected' : '' }}>{{ $item->statuspemiliklahan }}</option>
                                    @endforeach
                                </select>
                                @error('kdstatuspemiliklahan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Fisik Bangunan</label>
                                <select name="kdjenisfisikbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisfisikbangunan as $item)
                                        <option value="{{ $item->kdjenisfisikbangunan }}" {{ old('kdjenisfisikbangunan', $prasarana->kdjenisfisikbangunan) == $item->kdjenisfisikbangunan ? 'selected' : '' }}>{{ $item->jenisfisikbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdjenisfisikbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Lantai Bangunan</label>
                                <select name="kdjenislantaibangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenislantaibangunan as $item)
                                        <option value="{{ $item->kdjenislantaibangunan }}" {{ old('kdjenislantaibangunan', $prasarana->kdjenislantaibangunan) == $item->kdjenislantaibangunan ? 'selected' : '' }}>{{ $item->jenislantaibangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdjenislantaibangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Lantai Bangunan</label>
                                <select name="kdkondisilantaibangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kondisi --</option>
                                    @foreach($kondisilantaibangunan as $item)
                                        <option value="{{ $item->kdkondisilantaibangunan }}" {{ old('kdkondisilantaibangunan', $prasarana->kdkondisilantaibangunan) == $item->kdkondisilantaibangunan ? 'selected' : '' }}>{{ $item->kondisilantaibangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdkondisilantaibangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Dinding Bangunan</label>
                                <select name="kdjenisdindingbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisdindingbangunan as $item)
                                        <option value="{{ $item->kdjenisdindingbangunan }}" {{ old('kdjenisdindingbangunan', $prasarana->kdjenisdindingbangunan) == $item->kdjenisdindingbangunan ? 'selected' : '' }}>{{ $item->jenisdindingbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdjenisdindingbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Dinding Bangunan</label>
                                <select name="kdkondisidindingbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kondisi --</option>
                                    @foreach($kondisidindingbangunan as $item)
                                        <option value="{{ $item->kdkondisidindingbangunan }}" {{ old('kdkondisidindingbangunan', $prasarana->kdkondisidindingbangunan) == $item->kdkondisidindingbangunan ? 'selected' : '' }}>{{ $item->kondisidindingbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdkondisidindingbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Atap Bangunan</label>
                                <select name="kdjenisatapbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisatapbangunan as $item)
                                        <option value="{{ $item->kdjenisatapbangunan }}" {{ old('kdjenisatapbangunan', $prasarana->kdjenisatapbangunan) == $item->kdjenisatapbangunan ? 'selected' : '' }}>{{ $item->jenisatapbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdjenisatapbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Atap Bangunan</label>
                                <select name="kdkondisiatapbangunan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kondisi --</option>
                                    @foreach($kondisiatapbangunan as $item)
                                        <option value="{{ $item->kdkondisiatapbangunan }}" {{ old('kdkondisiatapbangunan', $prasarana->kdkondisiatapbangunan) == $item->kdkondisiatapbangunan ? 'selected' : '' }}>{{ $item->kondisiatapbangunan }}</option>
                                    @endforeach
                                </select>
                                @error('kdkondisiatapbangunan')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Air Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Air</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sumber Air Minum</label>
                                <select name="kdsumberairminum" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($sumberairminum as $item)
                                        <option value="{{ $item->kdsumberairminum }}" {{ old('kdsumberairminum', $prasarana->kdsumberairminum) == $item->kdsumberairminum ? 'selected' : '' }}>{{ $item->sumberairminum }}</option>
                                    @endforeach
                                </select>
                                @error('kdsumberairminum')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi Sumber Air</label>
                                <select name="kdkondisisumberair" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kondisi --</option>
                                    @foreach($kondisisumberair as $item)
                                        <option value="{{ $item->kdkondisisumberair }}" {{ old('kdkondisisumberair', $prasarana->kdkondisisumberair) == $item->kdkondisisumberair ? 'selected' : '' }}>{{ $item->kondisisumberair }}</option>
                                    @endforeach
                                </select>
                                @error('kdkondisisumberair')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cara Perolehan Air</label>
                                <select name="kdcaraperolehanair" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Cara --</option>
                                    @foreach($caraperolehanair as $item)
                                        <option value="{{ $item->kdcaraperolehanair }}" {{ old('kdcaraperolehanair', $prasarana->kdcaraperolehanair) == $item->kdcaraperolehanair ? 'selected' : '' }}>{{ $item->caraperolehanair }}</option>
                                    @endforeach
                                </select>
                                @error('kdcaraperolehanair')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Penerangan & Daya Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Penerangan & Daya</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sumber Penerangan Utama</label>
                                <select name="kdsumberpeneranganutama" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($sumberpeneranganutama as $item)
                                        <option value="{{ $item->kdsumberpeneranganutama }}" {{ old('kdsumberpeneranganutama', $prasarana->kdsumberpeneranganutama) == $item->kdsumberpeneranganutama ? 'selected' : '' }}>{{ $item->sumberpeneranganutama }}</option>
                                    @endforeach
                                </select>
                                @error('kdsumberpeneranganutama')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sumber Daya Terpasang</label>
                                <select name="kdsumberdayaterpasang" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($sumberdayaterpasang as $item)
                                        <option value="{{ $item->kdsumberdayaterpasang }}" {{ old('kdsumberdayaterpasang', $prasarana->kdsumberdayaterpasang) == $item->kdsumberdayaterpasang ? 'selected' : '' }}>{{ $item->sumberdayaterpasang }}</option>
                                    @endforeach
                                </select>
                                @error('kdsumberdayaterpasang')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sanitasi Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Fasilitas Sanitasi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fasilitas Tempat BAB</label>
                                <select name="kdfasilitastempatbab" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach($fasilitastempatbab as $item)
                                        <option value="{{ $item->kdfasilitastempatbab }}" {{ old('kdfasilitastempatbab', $prasarana->kdfasilitastempatbab) == $item->kdfasilitastempatbab ? 'selected' : '' }}>{{ $item->fasilitastempatbab }}</option>
                                    @endforeach
                                </select>
                                @error('kdfasilitastempatbab')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pembuangan Akhir Tinja</label>
                                <select name="kdpembuanganakhirtinja" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Pembuangan --</option>
                                    @foreach($pembuanganakhirtinja as $item)
                                        <option value="{{ $item->kdpembuanganakhirtinja }}" {{ old('kdpembuanganakhirtinja', $prasarana->kdpembuanganakhirtinja) == $item->kdpembuanganakhirtinja ? 'selected' : '' }}>{{ $item->pembuanganakhirtinja }}</option>
                                    @endforeach
                                </select>
                                @error('kdpembuanganakhirtinja')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cara Pembuangan Sampah</label>
                                <select name="kdcarapembuangansampah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Cara --</option>
                                    @foreach($carapembuangansampah as $item)
                                        <option value="{{ $item->kdcarapembuangansampah }}" {{ old('kdcarapembuangansampah', $prasarana->kdcarapembuangansampah) == $item->kdcarapembuangansampah ? 'selected' : '' }}>{{ $item->carapembuangansampah }}</option>
                                    @endforeach
                                </select>
                                @error('kdcarapembuangansampah')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bahan Bakar & Mata Air Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Bahan Bakar & Mata Air</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bahan Bakar Memasak</label>
                                <select name="kdbahanbakarmemasak" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Bahan Bakar --</option>
                                    @foreach($bahanbakarmemasak as $item)
                                        <option value="{{ $item->kdbahanbakarmemasak }}" {{ old('kdbahanbakarmemasak', $prasarana->kdbahanbakarmemasak) == $item->kdbahanbakarmemasak ? 'selected' : '' }}>{{ $item->bahanbakarmemasak }}</option>
                                    @endforeach
                                </select>
                                @error('kdbahanbakarmemasak')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Manfaat Mata Air</label>
                                <select name="kdmanfaatmataair" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Manfaat --</option>
                                    @foreach($manfaatmataair as $item)
                                        <option value="{{ $item->kdmanfaatmataair }}" {{ old('kdmanfaatmataair', $prasarana->kdmanfaatmataair) == $item->kdmanfaatmataair ? 'selected' : '' }}>{{ $item->manfaatmataair }}</option>
                                    @endforeach
                                </select>
                                @error('kdmanfaatmataair')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dimensi Rumah Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Dimensi Rumah</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Luas Lantai (m²)</label>
                                <input type="number" step="0.01" name="prasdas_luaslantai" value="{{ old('prasdas_luaslantai', $prasarana->prasdas_luaslantai) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('prasdas_luaslantai')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Kamar</label>
                                <input type="number" name="prasdas_jumlahkamar" value="{{ old('prasdas_jumlahkamar', $prasarana->prasdas_jumlahkamar) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('prasdas_jumlahkamar')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('keluarga.prasarana.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#no_kk').select2({
                    placeholder: "-- Pilih Keluarga --",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                height: 38px;
                padding: 0.5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 28px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }
            .select2-container--default .select2-selection--single:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
            }
        </style>
    @endpush
</x-app-layout>