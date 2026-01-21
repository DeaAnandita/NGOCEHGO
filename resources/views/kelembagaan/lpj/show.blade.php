<x-app-layout>
    <div class="flex">
        @include('kelembagaan.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- HEADER --}}
                <div class="flex justify-between mb-4">
                    <h3 class="text-xl font-bold">
                        Laporan Pertanggungjawaban (LPJ)
                    </h3>

                    <a href="{{ route('kelembagaan.lpj.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        ← Kembali
                    </a>
                </div>

                {{-- RINGKASAN --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 text-sm">Kegiatan</p>
                        <p class="font-semibold">{{ $lpj->kegiatan->nama_kegiatan }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 text-sm">Total Anggaran</p>
                        <p class="font-bold text-blue-700">
                            Rp {{ number_format($lpj->total_anggaran, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 text-sm">Total Realisasi</p>
                        <p class="font-bold text-orange-600">
                            Rp {{ number_format($lpj->total_realisasi, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-500 text-sm">Sisa</p>
                        <p class="font-bold {{ $lpj->sisa_anggaran < 0 ? 'text-red-600' : 'text-green-700' }}">
                            Rp {{ number_format($lpj->sisa_anggaran, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mb-6">
                    <span
                        class="px-3 py-1 rounded text-sm
                        @if ($lpj->status == 1) bg-yellow-100 text-yellow-800
                        @elseif ($lpj->status == 2) bg-green-100 text-green-800
                        @elseif ($lpj->status == 3) bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-700 @endif">
                        @if ($lpj->status == 1)
                            Diajukan
                        @elseif ($lpj->status == 2)
                            Disetujui
                        @elseif ($lpj->status == 3)
                            Ditolak
                        @else
                            Draft
                        @endif
                    </span>
                </div>

                {{-- ================= RINCIAN REALISASI ================= --}}
                <h4 class="font-bold mb-3">Rincian Realisasi</h4>

                <div class="overflow-x-auto mb-10">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="p-2">Tanggal</th>
                                <th class="text-left">Uraian</th>
                                <th>Jumlah</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($lpj->kegiatan->pencairanDana as $p)
                                @foreach ($p->realisasi as $r)
                                    <tr class="border-t">
                                        <td class="p-2 text-center">
                                            {{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}
                                        </td>

                                        <td class="px-2">{{ $r->uraian }}</td>

                                        <td class="px-2 text-right font-semibold">
                                            Rp {{ number_format($r->jumlah, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            @if ($r->bukti)
                                                <a href="{{ asset('storage/' . $r->bukti) }}" target="_blank"
                                                    class="text-blue-600 underline">
                                                    Lihat
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-gray-500">
                                        Belum ada data realisasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ================= PDF PREVIEW (PINDAH KE BAWAH) ================= --}}
                @if ($lpj->file_lpj)
                    <div>
                        <h4 class="font-bold mb-3">File LPJ (PDF)</h4>
                        <div class="mt-4 mb-3">
                            <a href="{{ asset('storage/' . $lpj->file_lpj) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                                ⬇ Download PDF
                            </a>
                        </div>
                        <div class="border rounded-lg overflow-hidden">
                            <iframe src="{{ asset('storage/' . $lpj->file_lpj) }}" class="w-full h-[600px]"
                                frameborder="0">
                            </iframe>
                        </div>


                    </div>
                @else
                    <div class="text-gray-500 italic mt-6">
                        Tidak ada file LPJ diunggah
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
