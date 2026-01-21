<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 p-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <h3 class="text-xl font-bold mb-6">Tambah Anggaran Kelembagaan</h3>

                <form action="{{ route('kelembagaan.anggaran.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium">Lembaga</label>
                            <select name="kdunit" class="w-full rounded-lg border-gray-300" required>
                                <option value="">-- Pilih Lembaga --</option>
                                @foreach ($unit as $u)
                                    <option value="{{ $u->kdunit }}">{{ $u->unit_keputusan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Periode / Tahun</label>
                            <select name="kdperiode" class="w-full rounded-lg border-gray-300" required>
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($periode as $p)
                                    <option value="{{ $p->kdperiode }}">{{ $p->tahun_awal }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Sumber Dana</label>
                            <select name="kdsumber" class="w-full rounded-lg border-gray-300" required>
                                <option value="">-- Pilih Sumber Dana --</option>
                                @foreach ($sumber as $s)
                                    <option value="{{ $s->kdsumber }}">{{ $s->sumber_dana }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Total Anggaran</label>
                            <input type="number" name="total_anggaran" class="w-full rounded-lg border-gray-300"
                                required>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
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
