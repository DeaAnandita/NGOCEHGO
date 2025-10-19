<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Aset Keluarga</h3>

                <form action="{{ route('keluarga.asetkeluarga.update', $asetkeluarga->no_kk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Keluarga Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Keluarga</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keluarga</label>
                                <input type="text" value="{{ $asetkeluarga->keluarga->keluarga_kepalakeluarga ?? '-' }} ({{ $asetkeluarga->no_kk }})"
                                       class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-sm text-gray-700" disabled>
                                <input type="hidden" name="no_kk" value="{{ $asetkeluarga->no_kk }}">
                            </div>
                        </div>
                    </div>

                    <!-- Aset Elektronik -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Aset Elektronik</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($masterAset->whereIn('kdasetkeluarga', [1, 2, 3, 4, 5, 36, 37, 38, 40, 41]) as $aset)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $aset->asetkeluarga }}</label>
                                    <select name="asetkeluarga_{{ $aset->kdasetkeluarga }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawab }}"
                                                    {{ old("asetkeluarga_{$aset->kdasetkeluarga}", $asetkeluarga->{"asetkeluarga_{$aset->kdasetkeluarga}"}) == $jawab->kdjawab ? 'selected' : '' }}>
                                                {{ $jawab->jawab }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("asetkeluarga_{$aset->kdasetkeluarga}")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Aset Transportasi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Aset Transportasi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($masterAset->whereIn('kdasetkeluarga', [8, 9, 10, 11, 12, 13, 14, 15]) as $aset)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $aset->asetkeluarga }}</label>
                                    <select name="asetkeluarga_{{ $aset->kdasetkeluarga }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawab }}"
                                                    {{ old("asetkeluarga_{$aset->kdasetkeluarga}", $asetkeluarga->{"asetkeluarga_{$aset->kdasetkeluarga}"}) == $jawab->kdjawab ? 'selected' : '' }}>
                                                {{ $jawab->jawab }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("asetkeluarga_{$aset->kdasetkeluarga}")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Aset Keuangan -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Aset Keuangan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($masterAset->whereIn('kdasetkeluarga', [6, 16, 17, 18, 19, 20, 21, 22, 23]) as $aset)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $aset->asetkeluarga }}</label>
                                    <select name="asetkeluarga_{{ $aset->kdasetkeluarga }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawab }}"
                                                    {{ old("asetkeluarga_{$aset->kdasetkeluarga}", $asetkeluarga->{"asetkeluarga_{$aset->kdasetkeluarga}"}) == $jawab->kdjawab ? 'selected' : '' }}>
                                                {{ $jawab->jawab }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("asetkeluarga_{$aset->kdasetkeluarga}")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Aset Usaha -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Aset Usaha</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($masterAset->whereIn('kdasetkeluarga', [24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 39]) as $aset)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $aset->asetkeluarga }}</label>
                                    <select name="asetkeluarga_{{ $aset->kdasetkeluarga }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawab }}"
                                                    {{ old("asetkeluarga_{$aset->kdasetkeluarga}", $asetkeluarga->{"asetkeluarga_{$aset->kdasetkeluarga}"}) == $jawab->kdjawab ? 'selected' : '' }}>
                                                {{ $jawab->jawab }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("asetkeluarga_{$aset->kdasetkeluarga}")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition duration-200 shadow-sm">Simpan</button>
                        <a href="{{ route('keluarga.asetkeluarga.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition duration-200 shadow-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>