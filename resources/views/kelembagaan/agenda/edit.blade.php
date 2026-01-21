<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- TOMBOL KEMBALI --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('kelembagaan.agenda.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    Edit Agenda
                </h3>

                {{-- ERROR SERVER --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kelembagaan.agenda.update', $agenda->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- DATA AGENDA --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">
                            Data Agenda
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Judul --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Judul Agenda</label>
                                <input type="text" name="judul_agenda"
                                    value="{{ old('judul_agenda', $agenda->judul_agenda) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            {{-- Uraian --}}
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Uraian Agenda</label>
                                <textarea name="uraian_agenda" rows="3" class="w-full rounded-lg border-gray-300">{{ old('uraian_agenda', $agenda->uraian_agenda) }}</textarea>
                            </div>

                            {{-- Jenis --}}
                            <div>
                                <label class="text-sm font-medium">Jenis Agenda</label>
                                <select name="kdjenis" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($jenis as $j)
                                        <option value="{{ $j->kdjenis }}"
                                            {{ old('kdjenis', $agenda->kdjenis) == $j->kdjenis ? 'selected' : '' }}>
                                            {{ $j->jenis_agenda }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="text-sm font-medium">Unit Lembaga</label>
                                <select name="kdunit" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($unit as $u)
                                        <option value="{{ $u->kdunit }}"
                                            {{ old('kdunit', $agenda->kdunit) == $u->kdunit ? 'selected' : '' }}>
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
                                            {{ old('kdperiode', $agenda->kdperiode) == $p->kdperiode ? 'selected' : '' }}>
                                            {{ $p->tahun_awal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tempat --}}
                            <div>
                                <label class="text-sm font-medium">Tempat</label>
                                <select name="kdtempat" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($tempat as $t)
                                        <option value="{{ $t->kdtempat }}"
                                            {{ old('kdtempat', $agenda->kdtempat) == $t->kdtempat ? 'selected' : '' }}>
                                            {{ $t->tempat_agenda }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-sm font-medium">Status</label>
                                <select name="kdstatus" class="w-full rounded-lg border-gray-300" required>
                                    @foreach ($status as $s)
                                        <option value="{{ $s->kdstatus }}"
                                            {{ old('kdstatus', $agenda->kdstatus) == $s->kdstatus ? 'selected' : '' }}>
                                            {{ $s->status_agenda }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="text-sm font-medium">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', $agenda->tanggal) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            {{-- Jam Mulai --}}
                            <div>
                                <label class="text-sm font-medium">Jam Mulai</label>
                                <input type="time" name="jam_mulai"
                                    value="{{ old('jam_mulai', $agenda->jam_mulai) }}"
                                    class="w-full rounded-lg border-gray-300" required>
                            </div>

                            {{-- Jam Selesai --}}
                            <div>
                                <label class="text-sm font-medium">Jam Selesai</label>
                                <input type="time" name="jam_selesai"
                                    value="{{ old('jam_selesai', $agenda->jam_selesai) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('kelembagaan.agenda.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
