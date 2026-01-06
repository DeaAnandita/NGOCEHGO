<x-app-layout>
    <div class="flex">
        @include('penduduk.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Tambah Data Lembaga Masyarakat</h3>

                <form action="{{ route('penduduk.lemmas.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Penduduk -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Penduduk</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             <!-- Pilih Penduduk -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penduduk</label>
                                <select name="nik" id="nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Penduduk --</option>
                                    @foreach($penduduks as $kel)
                                        <option value="{{ $kel->nik }}">{{ $kel->penduduk_namalengkap }} ({{ $kel->nik }})</option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Lembaga Masyarakat -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Keikutsertaan Lembaga Masyarakat</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($masterLembaga as $index => $lembaga)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $lembaga->lembaga }}</label>
                                    <select name="lemmas_{{ $loop->iteration }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawabLemmas as $jawab)
                                            <option value="{{ $jawab->kdjawablemmas }}">{{ $jawab->jawablemmas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol -->
                     <div class="mt-6 flex space-x-4 justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('penduduk.lembagaekonomi.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
