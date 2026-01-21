<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.keputusan.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Edit Keputusan Lembaga
                </h3>

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('kelembagaan.keputusan.update', $keputusan->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Keputusan</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nomor SK --}}
                            <div>
                                <label class="text-sm font-medium">Nomor SK</label>
                                <input type="text" name="nomor_sk"
                                    value="{{ old('nomor_sk', $keputusan->nomor_sk) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            {{-- Judul --}}
                            <div>
                                <label class="text-sm font-medium">Judul Keputusan</label>
                                <input type="text" name="judul_keputusan"
                                    value="{{ old('judul_keputusan', $keputusan->judul_keputusan) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            {{-- Jenis --}}
                            <div>
                                <label class="text-sm font-medium">Jenis Keputusan</label>
                                <select name="kdjenis" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenis }}"
                                            {{ old('kdjenis', $keputusan->kdjenis) == $j->kdjenis ? 'selected' : '' }}>
                                            {{ $j->jenis_keputusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="text-sm font-medium">Unit</label>
                                <select name="kdunit" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($unit as $u)
                                        <option value="{{ $u->kdunit }}"
                                            {{ old('kdunit', $keputusan->kdunit) == $u->kdunit ? 'selected' : '' }}>
                                            {{ $u->unit_keputusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Periode --}}
                            <div>
                                <label class="text-sm font-medium">Periode</label>
                                <select name="kdperiode" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($periode as $p)
                                        <option value="{{ $p->kdperiode }}"
                                            {{ old('kdperiode', $keputusan->kdperiode) == $p->kdperiode ? 'selected' : '' }}>
                                            {{ $p->tahun_awal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <label class="text-sm font-medium">Jabatan Penetap</label>
                                <select name="kdjabatan" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($jabatan as $j)
                                        <option value="{{ $j->kdjabatan }}"
                                            {{ old('kdjabatan', $keputusan->kdjabatan) == $j->kdjabatan ? 'selected' : '' }}>
                                            {{ $j->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="text-sm font-medium">Tanggal Keputusan</label>
                                <input type="date" name="tanggal_keputusan"
                                    value="{{ old('tanggal_keputusan', $keputusan->tanggal_keputusan) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-sm font-medium">Status</label>
                                <select name="kdstatus" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($status as $s)
                                        <option value="{{ $s->kdstatus }}"
                                            {{ old('kdstatus', $keputusan->kdstatus) == $s->kdstatus ? 'selected' : '' }}>
                                            {{ $s->status_keputusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Metode --}}
                            <div>
                                <label class="text-sm font-medium">Metode</label>
                                <select name="kdmetode" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($metode as $m)
                                        <option value="{{ $m->kdmetode }}"
                                            {{ old('kdmetode', $keputusan->kdmetode) == $m->kdmetode ? 'selected' : '' }}>
                                            {{ $m->metode }}
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
                        <a href="{{ route('kelembagaan.keputusan.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
