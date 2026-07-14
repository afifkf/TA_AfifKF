@extends('layouts.app')

@section('content')

<div class="space-y-8">

    {{-- HERO --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">

        <div class="flex flex-col lg:flex-row justify-between items-center">

            <div>

                <h1 class="text-4xl font-bold mb-2">
                    👋 Selamat Datang,
                </h1>

                <h2 class="text-2xl font-semibold">
                    {{ Auth::user()->name }}
                </h2>

                <p class="mt-4 text-blue-100">
                    Sistem Informasi Laboratorium
                </p>

                <p class="text-blue-100">
                    Teknik Informatika PSDKU Madiun
                </p>

                <p class="mt-5">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>

            </div>

            <div class="mt-8 lg:mt-0">

                <a href="{{ route('pinjam.create') }}"
                    class="bg-white text-blue-700 px-6 py-4 rounded-xl shadow-lg font-bold hover:bg-blue-100 transition">

                    ➕ Ajukan Peminjaman

                </a>

            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        {{-- Menunggu --}}
        <div class="bg-yellow-100 rounded-2xl p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">

            <div class="text-5xl mb-3">
                ⏳
            </div>

            <h2 class="font-semibold text-gray-700">
                Pengajuan Menunggu
            </h2>

            <div class="text-4xl font-bold text-yellow-700 mt-2">
                {{ $menunggu }}
            </div>

            <p class="text-gray-500 mt-2">
                Pengajuan
            </p>

        </div>

        {{-- Dipinjam --}}
        <div class="bg-blue-100 rounded-2xl p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">

            <div class="text-5xl mb-3">
                📦
            </div>

            <h2 class="font-semibold text-gray-700">
                Sedang Dipinjam
            </h2>

            <div class="text-4xl font-bold text-blue-700 mt-2">
                {{ $dipinjam }}
            </div>

            <p class="text-gray-500 mt-2">
                Barang
            </p>

        </div>

        {{-- Dikembalikan --}}
        <div class="bg-green-100 rounded-2xl p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">

            <div class="text-5xl mb-3">
                ✅
            </div>

            <h2 class="font-semibold text-gray-700">
                Sudah Dikembalikan
            </h2>

            <div class="text-4xl font-bold text-green-700 mt-2">
                {{ $dikembalikan }}
            </div>

            <p class="text-gray-500 mt-2">
                Barang
            </p>

        </div>

        {{-- Ditolak --}}
        <div class="bg-red-100 rounded-2xl p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">

            <div class="text-5xl mb-3">
                ❌
            </div>

            <h2 class="font-semibold text-gray-700">
                Pengajuan Ditolak
            </h2>

            <div class="text-4xl font-bold text-red-700 mt-2">
                {{ $ditolak }}
            </div>

            <p class="text-gray-500 mt-2">
                Pengajuan
            </p>

        </div>

    </div>

    {{-- INFORMASI --}}
    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Info --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-xl font-bold mb-4">
                📢 Informasi
            </h2>

            <div class="space-y-3 text-gray-700">

                <div class="border-l-4 border-blue-500 pl-4">
                    Pastikan barang dikembalikan tepat waktu.
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    Pengajuan akan diproses oleh admin laboratorium.
                </div>

                <div class="border-l-4 border-yellow-500 pl-4">
                    Silakan cek status pengajuan secara berkala.
                </div>

            </div>

        </div>

        {{-- Panduan --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-xl font-bold mb-4">
                📖 Cara Melakukan Peminjaman
            </h2>

            <ol class="space-y-3 list-decimal list-inside text-gray-700">

                <li>Klik tombol <b>Ajukan Peminjaman</b>.</li>

                <li>Pilih barang yang ingin dipinjam.</li>

                <li>Isi tanggal peminjaman.</li>

                <li>Tunggu persetujuan admin laboratorium.</li>

                <li>Datang ke laboratorium untuk mengambil barang.</li>

            </ol>

        </div>

    </div>

</div>

@endsection