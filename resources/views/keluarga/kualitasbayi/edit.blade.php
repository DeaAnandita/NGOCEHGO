<x-app-layout>
    <div class="flex">
        @include('keluarga.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Kualitas Bayi</h3>

                <form action="{{ route('keluarga.kualitasbayi.update', $kualitasbayi->no_kk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Keluarga Section -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Keluarga</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keluarga</label>
                                <input type="text"
                                       value="{{ $kualitasbayi->keluarga->keluarga_kepalakeluarga ?? '-' }} ({{ $kualitasbayi->no_kk }})"
                                       class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-sm text-gray-700" disabled>
                                <input type="hidden" name="no_kk" value="{{ $kualitasbayi->no_kk }}">
                            </div>
                        </div>
                    </div>

                    <!-- Kualitas Bayi -->
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Kualitas Kesehatan Bayi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($masterKualitas as $kualitas)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $kualitas->kualitasbayi }}</label>
                                    <select name="kualitasbayi_{{ $kualitas->kdkualitasbayi }}"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach($masterJawab as $jawab)
                                            <option value="{{ $jawab->kdjawabkualitasbayi }}"
                                                {{ old("kualitasbayi_{$kualitas->kdkualitasbayi}", $kualitasbayi->{"kualitasbayi_{$kualitas->kdkualitasbayi}"} ?? '') == $jawab->kdjawabkualitasbayi ? 'selected' : '' }}>
                                                {{ $jawab->jawabkualitasbayi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("kualitasbayi_{$kualitas->kdkualitasbayi}")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex space-x-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition duration-200 shadow-sm">
                            Simpan
                        </button>
                        <a href="{{ route('keluarga.kualitasbayi.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-300 transition duration-200 shadow-sm">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
