@extends('layouts.app')

@section('title', 'Dashboard Inventaris')

@section('content')

@if(auth()->user()->role == 'mahasiswa')

<!-- ===========================
DASHBOARD MAHASISWA
=========================== -->

<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 rounded-3xl shadow-xl p-10 text-white mb-8">

    <h1 class="text-4xl font-bold">
        Selamat Datang 👋
    </h1>

    <p class="mt-3 text-lg">
        {{ Auth::user()->name }}
    </p>

    <p class="text-blue-100">
        NIM :
        {{ Auth::user()->nim }}
    </p>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

    <a href="{{ route('pinjam.create') }}"
       class="bg-blue-600 hover:bg-blue-700 transition rounded-2xl shadow-lg p-8 text-center text-white">

        <div class="text-6xl mb-4">
            📦
        </div>

        <h2 class="text-2xl font-bold">
            Ajukan Peminjaman
        </h2>

        <p class="mt-2 text-blue-100">
            Ajukan peminjaman barang laboratorium
        </p>

    </a>

    <a href="{{ route('pinjam.index') }}"
       class="bg-green-600 hover:bg-green-700 transition rounded-2xl shadow-lg p-8 text-center text-white">

        <div class="text-6xl mb-4">
            📋
        </div>

        <h2 class="text-2xl font-bold">
            Riwayat Pengajuan
        </h2>

        <p class="mt-2 text-green-100">
            Lihat status pengajuan peminjaman
        </p>

    </a>

</div>

@else

<!-- ===========================
DASHBOARD ADMIN
=========================== -->

<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 rounded-3xl shadow-xl overflow-hidden mb-8">

    <div class="flex justify-between items-center px-10 py-10">

        <div>

            <h1 class="text-4xl font-bold text-white">
                Dashboard Inventaris
            </h1>

            <p class="mt-3 text-blue-100">
                Selamat datang,
                <strong>{{ Auth::user()->name }}</strong>
            </p>

            <p class="text-blue-100">
                Kelola seluruh inventaris laboratorium dengan mudah.
            </p>

        </div>

        <div class="hidden lg:block">

            <div class="text-[120px] opacity-20 text-white">
                📦
            </div>

        </div>

    </div>

</div>

<!-- ===========================
STATISTIK
=========================== -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <a href="{{ route('produk.index') }}">

        <div class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Tipe Barang
                    </p>

                    <h2 class="text-5xl font-bold text-blue-600 mt-2">
                        {{ $totalProduk }}
                    </h2>

                </div>

                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center text-3xl">
                    📦
                </div>

            </div>

        </div>

    </a>

    <a href="{{ route('pinjam.index') }}">

        <div class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Barang Dipinjam
                    </p>

                    <h2 class="text-5xl font-bold text-yellow-500 mt-2">
                        {{ $barangDipinjam }}
                    </h2>

                </div>

                <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center text-3xl">
                    📋
                </div>

            </div>

        </div>

    </a>

    <a href="{{ route('user.index') }}">

        <div class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total User
                    </p>

                    <h2 class="text-5xl font-bold text-green-600 mt-2">
                        {{ $totalUser }}
                    </h2>

                </div>

                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center text-3xl">
                    👥
                </div>

            </div>

        </div>

    </a>

</div>

<!-- ===========================
QUICK MENU
=========================== -->

<!-- <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">

    <a href="{{ route('produk.index') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white rounded-3xl p-6 text-center shadow-lg transition">

        <div class="text-5xl mb-3">
            📦
        </div>

        <div class="font-bold">
            Kelola Barang
        </div>

    </a>

    <a href="{{ route('pinjam.index') }}"
       class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-3xl p-6 text-center shadow-lg transition">

        <div class="text-5xl mb-3">
            📋
        </div>

        <div class="font-bold">
            Data Pinjam
        </div>

    </a>

    <a href="{{ route('laporan.index') }}"
       class="bg-green-600 hover:bg-green-700 text-white rounded-3xl p-6 text-center shadow-lg transition">

        <div class="text-5xl mb-3">
            📊
        </div>

        <div class="font-bold">
            Laporan
        </div>

    </a>

    <a href="{{ route('user.index') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white rounded-3xl p-6 text-center shadow-lg transition">

        <div class="text-5xl mb-3">
            👤
        </div>

        <div class="font-bold">
            Pengguna
        </div>

    </a>

</div> -->

<!-- ===========================
RIWAYAT PEMINJAMAN
=========================== -->

<div class="bg-white rounded-3xl shadow-xl overflow-hidden">

    <div class="flex justify-between items-center border-b px-8 py-5">

        <div>

            <h2 class="text-2xl font-bold">
                Riwayat Peminjaman Terbaru
            </h2>

            <p class="text-gray-500">
                Aktivitas peminjaman terbaru laboratorium
            </p>

        </div>

        <a href="{{ route('pinjam.index') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl">

            Lihat Semua

        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

<thead class="bg-gray-100">

    <tr>

        <th class="p-4 text-left font-semibold">
            Barang
        </th>

        <th class="p-4 text-left font-semibold">
            Peminjam
        </th>

        <th class="p-4 text-left font-semibold">
            Tanggal Pinjam
        </th>

        <th class="p-4 text-left font-semibold">
            Batas Pengembalian
        </th>

        <th class="p-4 text-left font-semibold">
            Tanggal Kembali
        </th>

        <th class="p-4 text-center font-semibold">
            Status
        </th>

    </tr>

</thead>

<tbody>

@forelse($pinjam as $p)

<tr class="border-b hover:bg-blue-50 transition">

    <td class="p-4 font-semibold">
        {{ $p->produk->nama }}
    </td>

    <td class="p-4">
        {{ $p->nama_peminjam }}
    </td>

    <td class="p-4">
        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y H:i') }}
    </td>

    <td class="p-4">

        {{ $p->batas_kembali
            ? \Carbon\Carbon::parse($p->batas_kembali)->translatedFormat('d F Y H:i')
            : '-'
        }}

    </td>

    <td class="p-4">

        {{ $p->tanggal_dikembalikan
            ? \Carbon\Carbon::parse($p->tanggal_dikembalikan)->translatedFormat('d F Y')
            : '-'
        }}

    </td>

    <td class="p-4 text-center">

        @if($p->status=='menunggu')

            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                ⏳ Menunggu

            </span>

        @elseif($p->status=='dipinjam')

            <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">

                📦 Dipinjam

            </span>

        @elseif($p->status=='dikembalikan')

            <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">

                ✔ Dikembalikan

            </span>

        @elseif($p->status=='ditolak')

            <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">

                ✖ Ditolak

            </span>

        @else

            <span class="px-4 py-2 rounded-full bg-red-600 text-white font-semibold">

                ⚠ Terlambat

            </span>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="6" class="text-center py-12 text-gray-500">

        <div class="text-7xl mb-3">
            📭
        </div>

        <div class="text-xl font-semibold">

            Belum ada data peminjaman

        </div>

        <p class="text-sm mt-2">

            Data peminjaman akan muncul di sini.

        </p>

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

<!-- ===========================
FOOTER DASHBOARD
=========================== -->

<div class="mt-8 bg-gradient-to-r from-gray-800 to-gray-900 rounded-3xl p-8 text-white shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-center">

        <div>

            <h3 class="text-2xl font-bold">

                Sistem Informasi Laboratorium

            </h3>

            <p class="text-gray-300 mt-2">

                Teknik Informatika PSDKU Madiun

            </p>

        </div>

        <div class="mt-6 md:mt-0 text-right">

            <p class="text-gray-400">

                Login sebagai

            </p>

            <h3 class="text-xl font-bold">

                {{ Auth::user()->name }}

            </h3>

            <p class="text-blue-300">

                {{ strtoupper(str_replace('_',' ',Auth::user()->role)) }}

            </p>

        </div>

    </div>

</div>

@endif

@endsection