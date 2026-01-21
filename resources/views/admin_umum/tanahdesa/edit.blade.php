<x-app-layout>
    <div class="flex">
        @include('admin_umum.sidebar')

        <div class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin-umum.tanahdesa.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                        ← Kembali
                    </a>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Tanah Desa</h3>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTanah" method="POST" action="{{ route('admin-umum.tanahdesa.update', $data->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- DATA TANAH --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-semibold text-blue-700 mb-4">Data Tanah</h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- KODE --}}
                            <div>
                                <label class="text-sm font-medium">Kode Tanah</label>
                                <input value="{{ $data->kdtanahdesa }}"
                                    class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Pencatatan</label>
                                <input type="date" name="tanggaltanahdesa"
                                    value="{{ old('tanggaltanahdesa', $data->tanggaltanahdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Pemilik</label>
                                <input type="text" name="pemiliktanahdesa"
                                    value="{{ old('pemiliktanahdesa', $data->pemiliktanahdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Kode Pemilik</label>
                                <input type="text" name="kdpemilik" value="{{ old('kdpemilik', $data->kdpemilik) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Jenis Pemilik</label>
                                <select name="kdjenispemilik" class="w-full rounded-lg border-gray-300">
                                    @foreach ($jenisPemilik as $j)
                                        <option value="{{ $j->kdjenispemilik }}"
                                            {{ $data->kdjenispemilik == $j->kdjenispemilik ? 'selected' : '' }}>
                                            {{ $j->jenispemilik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Status Hak</label>
                                <select name="kdstatushaktanah" class="w-full rounded-lg border-gray-300">
                                    @foreach ($statusHak as $s)
                                        <option value="{{ $s->kdstatushaktanah }}"
                                            {{ $data->kdstatushaktanah == $s->kdstatushaktanah ? 'selected' : '' }}>
                                            {{ $s->statushaktanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Penggunaan</label>
                                <select name="kdpenggunaantanah" class="w-full rounded-lg border-gray-300">
                                    @foreach ($penggunaan as $p)
                                        <option value="{{ $p->kdpenggunaantanah }}"
                                            {{ $data->kdpenggunaantanah == $p->kdpenggunaantanah ? 'selected' : '' }}>
                                            {{ $p->penggunaantanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Mutasi</label>
                                <select name="kdmutasitanah" class="w-full rounded-lg border-gray-300">
                                    @foreach ($mutasi as $m)
                                        <option value="{{ $m->kdmutasitanah }}"
                                            {{ $data->kdmutasitanah == $m->kdmutasitanah ? 'selected' : '' }}>
                                            {{ $m->mutasitanah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Tanggal Mutasi</label>
                                <input type="date" name="tanggalmutasitanahdesa"
                                    value="{{ old('tanggalmutasitanahdesa', $data->tanggalmutasitanahdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Luas Tanah (m²)</label>
                                <input type="number" step="0.01" name="luastanahdesa"
                                    value="{{ old('luastanahdesa', $data->luastanahdesa) }}"
                                    class="w-full rounded-lg border-gray-300">
                            </div>

                        </div>
                    </div>

                    {{-- FILE --}}
                    <div class="mb-8">
                        <label class="text-sm font-medium">Foto Tanah</label>

                        @if ($data->fototanahdesa)
                            <p class="text-sm mb-2">
                                <a href="{{ asset('storage/' . $data->fototanahdesa) }}" target="_blank"
                                    class="text-blue-600 underline">
                                    Lihat Foto Lama
                                </a>
                            </p>
                        @endif

                        <input type="file" name="fototanahdesa" accept="image/png,image/jpg,image/jpeg"
                            class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="mt-10 flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Update
                        </button>

                        <a href="{{ route('admin-umum.tanahdesa.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
