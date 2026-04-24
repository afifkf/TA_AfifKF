@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

<!-- Header -->
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Daftar Barang</h2>
</div>

<!-- Toolbar -->
<div class="flex justify-between items-center mb-4">

    <!-- Kiri -->
    <div class="flex gap-2">
        <a href="{{ route('produk.create') }}" 
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Tambah Barang
        </a>
    </div>

    <!-- Tengah (Search) -->
    <form action="{{ route('produk.index') }}" method="GET" class="flex gap-2">
        <input type="text" 
        name="search" 
        placeholder="Cari barang..." 
        value="{{ request('search') }}"
        class="border rounded-lg px-3 py-2">

        <button class="bg-gray-500 text-white px-4 py-2 rounded-lg">
            Cari
        </button>
    </form>

    <!-- Kanan -->
    <div>
        <a href="{{ route('produk.exportPdf') }}" 
        class="bg-red-500 text-white px-4 py-2 rounded-lg">
        Cetak Tabel
        </a>
    </div>
    <a href="{{ route('detail-barang.index') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Seluruh Detail Barang
    </a>


</div>


@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-3">
    {{ session('success') }}
</div>
@endif


<div class="overflow-x-auto">
<table class="w-full border">

<thead class="bg-gray-100">
<tr>
    <th class="p-3 border">Nama</th>
    <th class="p-3 border">Harga</th>
    <th class="p-3 border">Stok</th>
    <th class="p-3 border">Jenis</th>   
    <th class="p-3 border">Dibuat</th>
    <th class="p-3 border">Diperbarui</th>
    <th class="p-3 border text-center">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($produks as $produk)

<tr class="border hover:bg-gray-50">
    
    <td class="p-3 border">
        {{ $produk->nama }}
    </td>

    <td class="p-3 border">
        Rp {{ number_format($produk->harga, 0, ',', '.') }}
    </td>

    <td class="border p-2">

<div class="grid grid-cols-3 gap-2">

<div class="bg-green-100 p-2 rounded-lg text-center">
<div class="text-xs text-green-700">
Tersedia
</div>
<div class="text-lg font-bold text-green-600">
{{ $produk->stok_tersedia }}
</div>
</div>

<div class="bg-yellow-100 p-2 rounded-lg text-center">
<div class="text-xs text-yellow-700">
Dipinjam
</div>
<div class="text-lg font-bold text-yellow-600">
{{ $produk->stok_dipinjam }}
</div>
</div>

<div class="bg-red-100 p-2 rounded-lg text-center">
<div class="text-xs text-red-700">
Rusak
</div>
<div class="text-lg font-bold text-red-600">
{{ $produk->stok_rusak }}
</div>
</div>

</div></td>

    <td class="p-3 border">
        {{ $produk->jenis }}
    </td>


    <td class="p-3 border">
        {{ \Carbon\Carbon::parse($produk->created_at)->translatedFormat('d F Y') }}

    </td>

    <td class="p-3 border">
       {{ \Carbon\Carbon::parse($produk->updated_at)->translatedFormat('d F Y') }}

    </td>

    <td class="p-3 border">

        <div class="flex justify-center gap-2">

            <!-- Detail -->
            <a href="{{ route('detail-barang.show', $produk->id) }}" 
            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
            Detail
            </a>

            <!-- Edit -->
            <a href="{{ route('produk.edit', $produk->id) }}" 
            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
            Edit
            </a>

            <!-- Hapus -->
            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button 
                onclick="return confirm('Yakin hapus produk ini?')" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                Hapus
                </button>

            </form>

        </div>

    </td>

</tr>

@endforeach

</tbody>

</table>
</div>


<!-- Pagination -->
<div class="mt-4">
    {{ $produks->links() }}
</div>

</div>

@endsection