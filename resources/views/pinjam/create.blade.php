@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-8 py-6">

            @if(auth()->user()->role == 'mahasiswa')

                <h2 class="text-3xl font-bold text-white">
                    📦 Ajukan Peminjaman Barang
                </h2>

                <p class="text-blue-100 mt-2">
                    Silakan lengkapi formulir berikut untuk mengajukan peminjaman barang laboratorium.
                </p>

            @else

                <h2 class="text-3xl font-bold text-white">
                    📦 Tambah Peminjaman
                </h2>

                <p class="text-blue-100 mt-2">
                    Input data peminjaman barang laboratorium.
                </p>

            @endif

        </div>

        <div class="p-8">

            <form action="{{ route('pinjam.store') }}" method="POST">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<!-- Jenis Barang -->
<div>

    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Jenis Barang
    </label>

    <select
        id="jenisBarang"
        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

        <option value="">
            -- Pilih Jenis Barang --
        </option>

        <option value="Inventaris">
            Inventaris
        </option>

        <option value="Barang Habis Pakai">
            Barang Habis Pakai
        </option>

    </select>

</div>


<!-- Produk -->
<div>

    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Pilih Barang
    </label>

    <select
        name="produk_id"
        id="produk"
        required
        disabled
        class="w-full border border-gray-300 rounded-xl px-4 py-3
        focus:ring-2 focus:ring-blue-500">

        <option value="">
            -- Pilih Jenis Barang Terlebih Dahulu --
        </option>

        @foreach($produk as $p)

            <option
                value="{{ $p->id }}"
                data-jenis="{{ $p->jenis }}">

                {{ $p->nama }}

            </option>

        @endforeach

    </select>

</div>
                    @if(auth()->user()->role != 'mahasiswa')

                    <!-- Admin -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Admin Pencatat
                        </label>

                        <input
                            type="text"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100"
                            value="{{ Auth::user()->name }}"
                            readonly>

                    </div>

                    @endif

                    <!-- Nama -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Peminjam
                        </label>

                        @if(auth()->user()->role == 'mahasiswa')

                        <input
                            type="text"
                            name="nama_peminjam"
                            value="{{ auth()->user()->name }}"
                            readonly
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100">

                        @else

                        <input
                            type="text"
                            name="nama_peminjam"
                            required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                        @endif

                    </div>

                    <!-- NIM -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            NIM
                        </label>

                        @if(auth()->user()->role == 'mahasiswa')

                        <input
                            type="text"
                            name="nim"
                            value="{{ auth()->user()->nim }}"
                            readonly
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100">

                        @else

                        <input
                            type="text"
                            name="nim"
                            required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                        @endif

                    </div>
                    <!-- No WhatsApp -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            No. WhatsApp
                        </label>

                        @if(auth()->user()->role == 'mahasiswa')

                        <input
                            type="text"
                            name="no_whatsapp"
                            value="{{ auth()->user()->no_whatsapp }}"
                            readonly
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100">

                        @else

                        <input
                            type="text"
                            name="no_whatsapp"
                            placeholder="08xxxxxxxxxx"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                        @endif

                    </div>

                    <!-- Jumlah Pinjam -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Pinjam
                        </label>

                        <input
                            type="number"
                            name="jumlah"
                            min="1"
                            value="1"
                            required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- Keterangan -->
<div>

    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Keterangan
    </label>

    <textarea
        name="keterangan"
        rows="3"
        placeholder="Masukkan keterangan peminjaman..."
        class="w-full border border-gray-300 rounded-xl px-4 py-3
        focus:ring-2 focus:ring-blue-500"></textarea>

</div>

                    <!-- Tanggal Pinjam -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Pinjam
                        </label>

                        <input type="datetime-local"
                                name="tanggal_pinjam"
                                class="border rounded w-full">
                    </div>

                    <!-- Batas Pengembalian -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Batas Pengembalian
                        </label>

                        <input type="datetime-local"
                                name="batas_kembali"
                                class="border rounded w-full">
                    </div>

                </div>

                {{-- Error Validasi --}}
                @if ($errors->any())

                <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4">

                    <h4 class="font-semibold text-red-700 mb-2">
                        Terjadi Kesalahan
                    </h4>

                    <ul class="list-disc ml-5 text-red-600">

                        @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

                @endif
                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">

                    <a href="{{ route('pinjam.index') }}"
                        class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition duration-200">

                        ← Kembali

                    </a>

                    <button
                        type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition duration-300">

                        💾 Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- Informasi -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-5">

        <h3 class="font-bold text-blue-700 text-lg mb-2">
            ℹ️ Informasi
        </h3>

        @if(auth()->user()->role == 'mahasiswa')

        <ul class="list-disc ml-5 text-gray-700 space-y-2">

            <li>
                Pengajuan akan diperiksa oleh admin laboratorium.
            </li>

            <li>
                Status awal pengajuan adalah
                <span class="font-semibold text-yellow-600">
                    Menunggu
                </span>.
            </li>

            <li>
                Setelah disetujui, Anda akan menerima notifikasi WhatsApp.
            </li>

            <li>
                Pastikan nomor WhatsApp yang tersimpan pada akun sudah benar.
            </li>

        </ul>

        @else

        <ul class="list-disc ml-5 text-gray-700 space-y-2">

            <li>
                Admin dapat langsung membuat data peminjaman.
            </li>

            <li>
                Stok barang akan otomatis berkurang setelah data disimpan.
            </li>

            <li>
                Detail barang akan berubah menjadi
                <span class="font-semibold text-blue-600">
                    Dipinjam
                </span>.
            </li>

            <li>
                Pastikan data peminjam telah sesuai sebelum menyimpan.
            </li>

        </ul>

        @endif

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const jenisBarang = document.getElementById('jenisBarang');
    const produk = document.getElementById('produk');

    const semuaProduk = Array.from(
        produk.querySelectorAll('option[data-jenis]')
    );

    jenisBarang.addEventListener('change', function () {

        const jenisDipilih = this.value;

        // Hapus semua pilihan barang
        produk.innerHTML = '';

        if (!jenisDipilih) {

            produk.disabled = true;

            produk.innerHTML = `
                <option value="">
                    -- Pilih Jenis Barang Terlebih Dahulu --
                </option>
            `;

            return;

        }

        // Aktifkan dropdown barang
        produk.disabled = false;

        produk.innerHTML = `
            <option value="">
                -- Pilih Barang --
            </option>
        `;

        // Filter barang berdasarkan jenis
        semuaProduk.forEach(function (option) {

            if (option.dataset.jenis === jenisDipilih) {

                produk.appendChild(option);

            }

        });

    });

});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Stok Tidak Cukup',
        text: @json(session('error')),
        confirmButtonText: 'OK'
    });
</script>
@endif

@endsection