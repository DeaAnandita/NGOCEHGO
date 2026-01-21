<x-app-layout>

    <div class="max-w-6xl mx-auto mt-8">

        <div class="flex justify-between mb-4">
            <a href="{{ route('pelayanan.surat.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>

            @if ($surat->status === 'disetujui')
                <a href="{{ route('pelayanan.surat.print', $surat->cetak_token) }}" target="_blank"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Cetak
                </a>
            @endif
        </div>

        <div class="bg-white shadow rounded p-2">
            <iframe
                src="
        @if ($surat->status === 'disetujui') {{ route('pelayanan.surat.cetak', $surat->cetak_token) }}
        @else
            {{ route('pelayanan.surat.preview', $surat->id) }} @endif
        "
                class="w-full h-[85vh] border">
            </iframe>
        </div>

    </div>

</x-app-layout>
