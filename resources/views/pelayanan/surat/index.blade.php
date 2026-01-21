<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">
                Daftar Pengajuan Surat
            </h2>

            <a href="{{ route('pelayanan.surat.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-semibold shadow">
                + Buat Pengajuan
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">

                <table class="min-w-full table-auto border-collapse text-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="border px-4 py-3">No</th>
                            <th class="border px-4 py-3">NIK</th>
                            <th class="border px-4 py-3">Nama</th>
                            <th class="border px-4 py-3">Keperluan</th>
                            <th class="border px-4 py-3">Status</th>
                            <th class="border px-4 py-3">Tanggal</th>
                            <th class="border px-4 py-3 text-center">QR</th>
                            <th class="border px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($surats as $index => $s)
                            <tr class="hover:bg-gray-50">

                                <td class="border px-4 py-3">{{ $index + 1 }}</td>
                                <td class="border px-4 py-3">{{ $s->nik }}</td>
                                <td class="border px-4 py-3">{{ $s->nama }}</td>
                                <td class="border px-4 py-3">{{ Str::limit($s->keperluan, 40) }}</td>

                                {{-- STATUS --}}
                                <td class="border px-4 py-3">
                                    @if ($s->status == 'menunggu')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Menunggu</span>
                                    @elseif($s->status == 'disetujui')
                                        <span
                                            class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                                    @endif
                                </td>

                                <td class="border px-4 py-3">
                                    {{ $s->created_at->format('d-m-Y H:i') }}
                                </td>

                                {{-- QR --}}
                                <td class="border px-4 py-3 text-center">
                                    @if ($s->status === 'disetujui' && $s->cetak_token)
                                        @php
                                            $qr =
                                                'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
                                                urlencode(route('pelayanan.surat.print', $s->cetak_token));
                                        @endphp

                                        <img src="{{ $qr }}"
                                            class="w-14 h-14 mx-auto cursor-pointer hover:scale-105 transition"
                                            onclick="showQR('{{ $qr }}')" title="Klik untuk perbesar QR">
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="border px-4 py-3 text-center">
                                    <a href="{{ route('pelayanan.surat.show', $s->id) }}"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Detail
                                    </a>

                                    {{-- APPROVE / REJECT hanya untuk admin --}}
                                    @if (in_array(Auth::user()->role_id, [1, 3]) && $s->status === 'menunggu')
                                        <form action="{{ route('pelayanan.surat.approve', $s->id) }}" method="POST"
                                            class="inline-block ml-1">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('pelayanan.surat.reject', $s->id) }}" method="POST"
                                            class="inline-block ml-1"
                                            onsubmit="return confirm('Tolak pengajuan surat ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    @if ($s->status === 'disetujui')
                                        <a href="{{ route('pelayanan.surat.print', $s->cetak_token) }}" target="_blank"
                                            class="bg-purple-600 text-white px-3 py-1 rounded text-xs ml-1">
                                            Cetak
                                        </a>

                                        <a href="{{ route('pelayanan.surat.download', $s->cetak_token) }}"
                                            class="bg-green-600 text-white px-3 py-1 rounded text-xs ml-1">
                                            Download
                                        </a>
                                    @endif
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-400">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- MODAL QR --}}
    <div id="qrModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 shadow-xl text-center w-80">

            <h3 class="font-bold text-lg mb-4">Scan untuk Cetak Surat</h3>

            <img id="qrBig" src="" class="mx-auto w-64 h-64">

            <p class="mt-4 text-sm text-gray-600">
                Arahkan kamera ke QR ini untuk membuka halaman cetak surat.
            </p>

            <button onclick="closeQR()" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Tutup
            </button>
        </div>
    </div>

    <script>
        function showQR(src) {
            document.getElementById('qrBig').src = src;
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQR() {
            document.getElementById('qrModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
