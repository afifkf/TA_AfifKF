@extends('layouts.app')

@section('title', 'Dashboard Inventaris')

@section('content')

<!-- Hero -->
<div class="bg-blue-600 text-white py-12 rounded-2xl mb-8">
    <div class="text-center">
        <h1 class="text-3xl font-bold">Dashboard Sistem Inventaris</h1>
        <p class="mt-2">Kelola Laboratorium dan Peminjaman Barang</p>
    </div>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <!-- Total Produk -->
    <a href="{{ route('produk.index') }}">
        <div class="bg-white shadow rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
            <h3 class="text-gray-500 text-center">Tipe Barang</h3>
            <h2 class="text-3xl font-bold text-blue-600 text-center">{{ $totalProduk ?? 0 }}</h2>
        </div>
    </a>

    <!-- Barang Dipinjam -->
    <a href="{{ route('pinjam.index') }}">
        <div class="bg-white shadow rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
            <h3 class="text-gray-500 text-center">Barang Dipinjam</h3>
            <h2 class="text-3xl font-bold text-yellow-600 text-center">{{ $barangDipinjam ?? 0 }}</h2>
        </div>
    </a>

    <!-- Barang Rusak -->
    <a href="{{ route('barang-rusak.index') }}">
        <div class="bg-white shadow rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
            <h3 class="text-gray-500 text-center">Barang Rusak</h3>

            <h2 class="text-3xl font-bold text-red-600 text-center">
            {{ $barangRusak ?? 0 }}
            </h2>

        </div>
    </a>

    <!-- Total User -->
    <a href="{{ route('user.index') }}">
        <div class="bg-white shadow rounded-2xl p-6 hover:shadow-lg transition cursor-pointer">
            <h3 class="text-gray-500 text-center">Total User</h3>
            <h2 class="text-3xl font-bold text-green-600 text-center">{{ $totalUser ?? 0 }}</h2>
        </div>
    </a>

</div>

<!-- Produk -->
<!-- <div class="bg-white shadow rounded-2xl p-6 mb-8">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Produk</h2>
        <a href="{{ route('produk.index') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
           Lihat Semua
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($produk as $item)
        <div class="border rounded-xl p-4 hover:shadow-md transition">
            <h3 class="font-bold text-lg">{{ $item->nama }}</h3>
            <p class="text-gray-500">{{ $item->deskripsi }}</p>
            <p class="font-semibold text-blue-600">
                Rp {{ number_format($item->harga) }}
            </p>
            <p class="text-gray-500">
                Stok : {{ $item->stok }}
            </p>
        </div>
        @empty
        <p>Tidak ada produk</p>
        @endforelse
    </div>
</div> -->

<!-- Barang Dipinjam -->
<div class="bg-white shadow rounded-2xl p-6 mb-8">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Barang Dipinjam</h2>

        <a href="{{ route('pinjam.index') }}" 
           class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
           Lihat Semua
        </a>
    </div>

    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Barang</th>
                <th class="p-3 text-left">Peminjam</th>
                <th class="p-3 text-left">Tanggal Pinjam</th>
                <th class="p-3 text-left">Batas Pengembalian</th>
                <th class="p-3 text-left">Tanggal Kembali</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pinjam as $p)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $p->produk->nama }}</td>
                <td class="p-3">{{ $p->nama_peminjam }}</td>
                <td class="p-3">
                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}
                </td>
                <td class="p-3">
                    {{ \Carbon\Carbon::parse($p->batas_kembali)->translatedFormat('d F Y') }}
                <td class="p-3">
                    {{ $p->tanggal_dikembalikan 
                        ? \Carbon\Carbon::parse($p->tanggal_dikembalikan)->translatedFormat('d F Y') 
                        : '-' 
                    }}
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-white
                @if($p->status == 'terlambat')
                    bg-red-500
                @elseif($p->status == 'dipinjam')
                    bg-yellow-500
                @else
                    bg-green-500
                @endif
                ">
                    {{ $p->status }}
                </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center p-3">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Profil -->
<div class="bg-white shadow rounded-2xl p-6">
    <h2 class="text-xl font-bold mb-4">Profil Sistem</h2>

    <div class="space-y-2">
        <p>
            <strong>Nama Sistem :</strong> 
            Sistem Inventaris Barang
        </p>

        <p>
            <strong>Deskripsi :</strong> 
            Sistem untuk mengelola peminjaman dan produk
        </p>

        <p>
            <strong>Dibuat Oleh :</strong> 
            {{ auth()->user()->name ?? 'Admin' }}
        </p>
    </div>
</div>

@endsection