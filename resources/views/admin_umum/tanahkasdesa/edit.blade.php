<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Tanah Kas Desa</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTanahKas" method="POST"
                    action="{{ route('admin-umum.tanahkasdesa.update', $data->kdtanahkasdesa) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- DATA TANAH KAS --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Tanah Kas Desa</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- KODE --}}
                            <div>
                                <label class="text-sm font-medium">Kode Tanah Kas</label>
                                <input value="{{ $data->kdtanahkasdesa }}"
                                    class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Pencatatan</label>
                                <input type="date" name="tanggaltanahkasdesa"
                                    value="{{ old('tanggaltanahkasdesa', $data->tanggaltanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Asal Tanah</label>
                                <input type="text" name="asaltanahkasdesa"
                                    value="{{ old('asaltanahkasdesa', $data->asaltanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nomor Sertifikat</label>
                                <input type="text" name="sertifikattanahkasdesa"
                                    value="{{ old('sertifikattanahkasdesa', $data->sertifikattanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Luas Tanah (m²)</label>
                                <input type="number" step="0.01" name="luastanahkasdesa"
                                    value="{{ old('luastanahkasdesa', $data->luastanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Kelas Tanah</label>
                                <input type="text" name="kelastanahkasdesa"
                                    value="{{ old('kelastanahkasdesa', $data->kelastanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Perolehan</label>
                                <select name="kdperolehantkd" class="w-full rounded-lg border-gray-300">
                                    @foreach ($perolehan as $p)
                                        <option value="{{ $p->kdperolehantkd }}"
                                            {{ $data->kdperolehantkd == $p->kdperolehantkd ? 'selected' : '' }}>
                                            {{ $p->perolehantkd }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis TKD</label>
                                <select name="kdjenistkd" class="w-full rounded-lg border-gray-300">
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenistkd }}"
                                            {{ $data->kdjenistkd == $j->kdjenistkd ? 'selected' : '' }}>
                                            {{ $j->jenistkd }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Patok</label>
                                <select name="kdpatok" class="w-full rounded-lg border-gray-300">
                                    @foreach ($patok as $p)
                                        <option value="{{ $p->kdpatok }}"
                                            {{ $data->kdpatok == $p->kdpatok ? 'selected' : '' }}>
                                            {{ $p->patok }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Papan Nama</label>
                                <select name="kdpapannama" class="w-full rounded-lg border-gray-300">
                                    @foreach ($papan as $pn)
                                        <option value="{{ $pn->kdpapannama }}"
                                            {{ $data->kdpapannama == $pn->kdpapannama ? 'selected' : '' }}>
                                            {{ $pn->papannama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Lokasi</label>
                                <input type="text" name="lokasitanahkasdesa"
                                    value="{{ old('lokasitanahkasdesa', $data->lokasitanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Peruntukan</label>
                                <input type="text" name="peruntukantanahkasdesa"
                                    value="{{ old('peruntukantanahkasdesa', $data->peruntukantanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Mutasi</label>
                                <input type="text" name="mutasitanahkasdesa"
                                    value="{{ old('mutasitanahkasdesa', $data->mutasitanahkasdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                        </div>
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Foto Tanah</label>

                        @if ($data->fototanahkasdesa)
                            <p class="text-sm mb-2">
                                <a href="{{ asset('storage/' . $data->fototanahkasdesa) }}" target="_blank"
                                    class="text-blue-600 underline">
                                    Lihat Foto Lama
                                </a>
                            </p>
                        @endif

                        <input type="file" name="fototanahkasdesa" accept="image/png,image/jpg,image/jpeg"
                            class="w-full rounded-lg border-gray-300">
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Keterangan</label>
                        <textarea name="keterangantanahkasdesa" class="w-full rounded-lg border-gray-300">{{ old('keterangantanahkasdesa', $data->keterangantanahkasdesa) }}</textarea>
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Update
                        </button>

                        <a href="{{ route('admin-umum.tanahkasdesa.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
