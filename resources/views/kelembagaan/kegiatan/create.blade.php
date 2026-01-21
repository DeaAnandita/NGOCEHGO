<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- TOMBOL KEMBALI --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.kegiatan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Tambah Kegiatan
                </h3>

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kelembagaan.kegiatan.store') }}" method="POST">
                    @csrf

                    {{-- DATA KEGIATAN --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Kegiatan</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Kegiatan</label>
                                <select name="kdjenis" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenis }}"
                                            {{ old('kdjenis') == $j->kdjenis ? 'selected' : '' }}>
                                            {{ $j->jenis_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Unit</label>
                                <select name="kdunit" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($unit as $u)
                                        <option value="{{ $u->kdunit }}"
                                            {{ old('kdunit') == $u->kdunit ? 'selected' : '' }}>
                                            {{ $u->unit_keputusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Periode</label>
                                <select name="kdperiode" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($periode as $p)
                                        <option value="{{ $p->kdperiode }}"
                                            {{ old('kdperiode') == $p->kdperiode ? 'selected' : '' }}>
                                            {{ $p->tahun_awal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Status</label>
                                <select name="kdstatus" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($status as $s)
                                        <option value="{{ $s->kdstatus }}"
                                            {{ old('kdstatus') == $s->kdstatus ? 'selected' : '' }}>
                                            {{ $s->status_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Sumber Dana</label>
                                <select name="kdsumber" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($sumber as $s)
                                        <option value="{{ $s->kdsumber }}"
                                            {{ old('kdsumber') == $s->kdsumber ? 'selected' : '' }}>
                                            {{ $s->sumber_dana }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Pagu Anggaran</label>
                                <input type="number" name="pagu_anggaran" value="{{ old('pagu_anggaran') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Lokasi Kegiatan</label>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Dasar Keputusan (Opsional)</label>
                                <select name="keputusan_id" class="w-full rounded-lg border-gray-300">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach ($keputusan as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('keputusan_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nomor_sk }} – {{ $k->judul_keputusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                            Simpan
                        </button>
                        <a href="{{ route('kelembagaan.kegiatan.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
