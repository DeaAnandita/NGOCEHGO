<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 p-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <h3 class="text-xl font-bold mb-6">
                    Edit Anggaran Kelembagaan
                </h3>

                <form action="{{ route('kelembagaan.anggaran.update', $anggaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- LEMBAGA --}}
                        <div>
                            <label class="text-sm font-medium">Lembaga</label>
                            <select name="kdunit" class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                                @foreach ($unit as $u)
                                    <option value="{{ $u->kdunit }}"
                                        {{ $u->kdunit == $anggaran->kdunit ? 'selected' : '' }}>
                                        {{ $u->unit_keputusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PERIODE --}}
                        <div>
                            <label class="text-sm font-medium">Periode / Tahun</label>
                            <select name="kdperiode" class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                                @foreach ($periode as $p)
                                    <option value="{{ $p->kdperiode }}"
                                        {{ $p->kdperiode == $anggaran->kdperiode ? 'selected' : '' }}>
                                        {{ $p->tahun_awal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SUMBER DANA --}}
                        <div>
                            <label class="text-sm font-medium">Sumber Dana</label>
                            <select name="kdsumber" class="w-full rounded-lg border-gray-300" required>
                                @foreach ($sumber as $s)
                                    <option value="{{ $s->kdsumber }}"
                                        {{ $s->kdsumber == $anggaran->kdsumber ? 'selected' : '' }}>
                                        {{ $s->sumber_dana }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TOTAL --}}
                        <div>
                            <label class="text-sm font-medium">Total Anggaran</label>
                            <input type="number" name="total_anggaran"
                                value="{{ old('total_anggaran', intval($anggaran->total_anggaran)) }}"
                                class="w-full rounded-lg border-gray-300" required>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full rounded-lg border-gray-300">{{ old('keterangan', $anggaran->keterangan) }}</textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('kelembagaan.anggaran.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
