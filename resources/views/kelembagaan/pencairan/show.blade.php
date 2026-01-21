<x-app-layout>
    @php
        $total = $pencairan->jumlah;
        $terpakai = $totalRealisasi;
        $sisaDana = $sisa;
    @endphp

    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 p-6">

            {{-- ================= RINGKASAN ================= --}}
            <div class="bg-white p-6 rounded-2xl shadow mb-6">
                <h3 class="text-xl font-bold mb-4">
                    {{ $pencairan->kegiatan->nama_kegiatan }}
                </h3>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Dana Dicairkan</p>
                        <p class="font-bold text-blue-700 text-lg">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Sudah Dipakai</p>
                        <p class="font-bold text-orange-600 text-lg">
                            Rp {{ number_format($terpakai, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Sisa Dana</p>
                        <p class="font-bold text-lg {{ $sisaDana < 0 ? 'text-red-600' : 'text-green-700' }}">
                            Rp {{ number_format($sisaDana, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ================= TABEL REALISASI ================= --}}
            <div class="bg-white p-6 rounded-2xl shadow">

                <div class="flex justify-between mb-4">
                    <h4 class="font-bold">Realisasi Pengeluaran</h4>

                    <button onclick="openTambahRealisasi()" @if ($sisaDana <= 0) disabled @endif
                        class="px-4 py-1 rounded text-white
                        {{ $sisaDana <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }}">
                        + Tambah
                    </button>
                </div>

                <table class="w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-2">Tanggal</th>
                            <th class="border px-2 py-2">Uraian</th>
                            <th class="border px-2 py-2">Jumlah</th>
                            <th class="border px-2 py-2">Bukti</th>
                            <th class="border px-2 py-2 w-32">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pencairan->realisasi as $r)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="border px-2 py-2 text-center">
                                    {{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}
                                </td>

                                <td class="border px-2 py-2 text-left">
                                    {{ $r->uraian }}
                                </td>

                                <td class="border px-2 py-2 text-right font-semibold">
                                    Rp {{ number_format($r->jumlah, 0, ',', '.') }}
                                </td>

                                <td class="border px-2 py-2 text-center">
                                    @if ($r->bukti)
                                        <a href="{{ asset('storage/' . $r->bukti) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="border px-2 py-2">
                                    <div class="flex justify-center gap-2">

                                        {{-- EDIT --}}
                                        <button
                                            onclick="openEditRealisasi(
                            {{ $r->id }},
                            '{{ $r->tanggal }}',
                            @js($r->uraian),
                            {{ $r->jumlah }},
                            @js($r->bukti)
                        )"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                            Edit
                                        </button>

                                        {{-- DELETE --}}
                                        <form action="{{ route('kelembagaan.realisasi.destroy', $r->id) }}"
                                            method="POST" x-data
                                            @submit.prevent="$dispatch('open-delete-modal', {
                              form: $el,
                              name: @js($r->uraian)
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
                                <td colspan="5" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                        <p class="font-medium text-gray-600">
                                            Belum ada realisasi pengeluaran
                                        </p>

                                        <p class="text-sm text-gray-400">
                                            Klik tombol <b>+ Tambah</b> untuk menambahkan realisasi dana.
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

    {{-- ================= MODAL CREATE ================= --}}
    @include('kelembagaan.realisasi._create_modal')

    {{-- ================= MODAL EDIT ================= --}}
    @include('kelembagaan.realisasi._edit_modal')

    {{-- ================= MODAL DELETE ================= --}}
    <div x-data="{ open: false, form: null, name: '' }"
        x-on:open-delete-modal.window="
        open=true;
        form=$event.detail.form;
        name=$event.detail.name;
     "
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Hapus</h3>

            <p class="text-gray-600 mb-6">
                Yakin ingin menghapus realisasi
                <span class="font-semibold text-red-600" x-text="name"></span>?
            </p>

            <div class="flex justify-end gap-3">
                <button @click="open=false" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Batal
                </button>

                <button @click="form.submit()" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        function openTambahRealisasi() {
            document.getElementById('tambahRealisasiModal').classList.remove('hidden');
        }

        function closeTambahRealisasi() {
            document.getElementById('tambahRealisasiModal').classList.add('hidden');
        }

        function openEditRealisasi(id, tanggal, uraian, jumlah, bukti) {
            edit_realisasi_id.value = id;
            edit_tanggal.value = tanggal;
            edit_uraian.value = uraian;
            edit_jumlah.value = jumlah;

            const previewBox = document.getElementById('buktiPreview');
            const img = document.getElementById('buktiImage');
            const link = document.getElementById('buktiLink');

            if (bukti) {
                const url = "/storage/" + bukti;
                previewBox.classList.remove('hidden');

                if (bukti.endsWith('.jpg') || bukti.endsWith('.jpeg') || bukti.endsWith('.png')) {
                    img.src = url;
                    img.classList.remove('hidden');
                    link.classList.add('hidden');
                } else {
                    link.href = url;
                    link.classList.remove('hidden');
                    img.classList.add('hidden');
                }
            } else {
                previewBox.classList.add('hidden');
                img.classList.add('hidden');
                link.classList.add('hidden');
            }

            document.getElementById('editRealisasiModal').classList.remove('hidden');
        }


        function closeEditRealisasi() {
            document.getElementById('editRealisasiModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
