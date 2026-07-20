@extends('layouts.app')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center px-4 py-8">

```
<div class="w-full max-w-2xl">

    <!-- Header -->
    <div class="mb-6">

        <h1 class="text-3xl font-bold text-gray-800">
            Upload Bukti Tanda Tangan
        </h1>

        <p class="text-gray-500 mt-2">
            Unggah surat peminjaman yang telah ditandatangani untuk melanjutkan proses persetujuan.
        </p>

    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-2xl">
                    📄
                </div>

                <div>

                    <h2 class="text-xl font-semibold">
                        Bukti Tanda Tangan
                    </h2>

                    <p class="text-blue-100 text-sm">
                        Pastikan dokumen yang diunggah dapat dibaca dengan jelas.
                    </p>

                </div>

            </div>

        </div>

        <!-- Form -->
        <form
            action="{{ route('pinjam.upload',$pinjam->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6">

            @csrf

            <!-- Informasi Peminjaman -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama Peminjam
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $pinjam->nama_peminjam }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Nomor Surat
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $pinjam->nomor_surat ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

            <!-- Upload Area -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">

                    Upload Foto / Scan Bukti

                </label>

                <label
                    for="bukti_ttd"
                    class="group flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition">

                    <div class="flex flex-col items-center justify-center text-center px-4">

                        <div class="text-4xl mb-3">
                            📤
                        </div>

                        <p class="mb-1 text-sm text-gray-700">

                            <span class="font-semibold text-blue-600">
                                Klik untuk memilih file
                            </span>

                            atau seret file ke sini

                        </p>

                        <p class="text-xs text-gray-500">
                            PDF, JPG, JPEG, atau PNG — Maksimal 4 MB
                        </p>

                    </div>

                    <input
                        id="bukti_ttd"
                        type="file"
                        name="bukti_ttd"
                        class="hidden"
                        accept=".pdf,.jpg,.jpeg,.png"
                        required>

                </label>

                <!-- Nama File -->
                <p
                    id="namaFile"
                    class="text-sm text-gray-600 mt-3 hidden">
                </p>

            </div>

            <!-- Warning -->
            <div class="flex gap-3 bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">

                <div class="text-xl">
                    ⚠️
                </div>

                <p class="text-sm text-yellow-800">
                    Pastikan surat peminjaman sudah ditandatangani dan file yang diunggah dapat dibaca dengan jelas.
                </p>

            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('pinjam.index') }}"
                    class="px-5 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-medium hover:bg-gray-300 transition">

                    Batal

                </a>

                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow">

                    ⬆ Upload Bukti

                </button>

            </div>

        </form>

    </div>

</div>
```

</div>

<script>

document.getElementById('bukti_ttd').addEventListener('change', function() {

    const namaFile = document.getElementById('namaFile');

    if (this.files.length > 0) {

        namaFile.innerHTML =
            '📎 File dipilih: <strong>' + this.files[0].name + '</strong>';

        namaFile.classList.remove('hidden');

    } else {

        namaFile.classList.add('hidden');

    }

});

</script>

@endsection
