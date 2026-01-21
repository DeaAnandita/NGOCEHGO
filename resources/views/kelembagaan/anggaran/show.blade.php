<x-app-layout>
    @php
        $totalAnggaran = $anggaran->total_anggaran;
        $totalTerpakai = $anggaran->kegiatanAnggaran->sum('nilai_anggaran');
        $sisaAnggaran = $totalAnggaran - $totalTerpakai;
    @endphp

    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 p-6">

            {{-- RINGKASAN --}}
            <div class="bg-white p-6 rounded-2xl shadow mb-6">
                <h3 class="text-xl font-bold mb-4">
                    {{ $anggaran->unit->unit_keputusan }} - {{ $anggaran->periode->tahun_awal }}
                </h3>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Total Anggaran</p>
                        <p class="font-bold text-blue-700 text-lg">
                            Rp {{ number_format($totalAnggaran, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Terpakai</p>
                        <p class="font-bold text-orange-600 text-lg">
                            Rp {{ number_format($totalTerpakai, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Sisa</p>
                        <p class="font-bold text-lg {{ $sisaAnggaran < 0 ? 'text-red-600' : 'text-green-700' }}">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white p-6 rounded-2xl shadow">

                <div class="flex justify-between mb-4">
                    <h4 class="font-bold">Anggaran per Kegiatan</h4>
                    <button onclick="openTambah()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                        + Tambah
                    </button>
                </div>

                <table class="w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-2">Kegiatan</th>
                            <th class="border px-2 py-2">Sumber</th>
                            <th class="border px-2 py-2">Nilai</th>
                            <th class="border px-2 py-2 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggaran->kegiatanAnggaran as $k)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="border px-2 py-2">
                                    {{ $k->kegiatan->nama_kegiatan }}
                                </td>

                                <td class="border px-2 py-2 text-center">
                                    {{ $k->sumber->sumber_dana }}
                                </td>

                                <td class="border px-2 py-2 text-right font-semibold">
                                    Rp {{ number_format($k->nilai_anggaran, 0, ',', '.') }}
                                </td>

                                <td class="border px-2 py-2">
                                    <div class="flex justify-center gap-2">

                                        {{-- EDIT --}}
                                        <button
                                            onclick="openEdit(
                            {{ $k->id }},
                            @js($k->kegiatan->nama_kegiatan),
                            {{ $k->kdsumber }},
                            {{ $k->nilai_anggaran }}
                        )"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                            Edit
                                        </button>

                                        {{-- DELETE --}}
                                        <form method="POST"
                                            action="{{ route('kelembagaan.kegiatan_anggaran.destroy', $k->id) }}"
                                            x-data
                                            @submit.prevent="$dispatch('open-delete-modal', {
                            form: $el,
                            name: @js($k->kegiatan->nama_kegiatan)
                        })">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center space-y-2">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 0v10m-6 4h12" />
                                        </svg>

                                        <p class="font-medium text-gray-600">
                                            Belum ada anggaran kegiatan
                                        </p>

                                        <p class="text-sm text-gray-400">
                                            Klik tombol <b>+ Tambah</b> untuk memasukkan anggaran kegiatan.
                                        </p>

                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    @include('kelembagaan.kegiatan_anggaran.form')

    {{-- MODAL EDIT --}}
    @include('kelembagaan.kegiatan_anggaran.edit')

    <script>
        function openTambah() {
            document.getElementById('tambahModal').classList.remove('hidden');
        }

        function closeTambah() {
            document.getElementById('tambahModal').classList.add('hidden');
        }

        function openEdit(id, kegiatan, kdsumber, nilai) {
            edit_id.value = id;
            edit_kegiatan.value = kegiatan;
            edit_kdsumber.value = kdsumber;
            edit_nilai.value = nilai;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEdit() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
