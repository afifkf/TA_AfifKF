@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">
Tambah Peminjaman
</h2>

<form action="{{ route('pinjam.store') }}" method="POST">
@csrf

{{-- Produk --}}
<div class="mb-3">
<label class="block mb-1">Produk</label>

<select name="produk_id" class="w-full border p-2 rounded">
@foreach($produk as $p)
<option value="{{ $p->id }}">
{{ $p->nama }}
</option>
@endforeach
</select>
</div>


{{-- User Admin --}}
<div class="mb-3">
<label class="block mb-1">Admin Pencatat</label>


    <input
        type="text"
        class="w-full border p-2 rounded bg-gray-100"
        value="{{ Auth::user()->name }}"
        readonly>

</div>


{{-- Nama Peminjam --}}
<div class="mb-3">
<label class="block mb-1">Nama Peminjam</label>

<input 
type="text"
name="nama_peminjam"
class="w-full border p-2 rounded"
required>
</div>


{{-- NIM --}}
<div class="mb-3">
<label class="block mb-1">NIM</label>

<input 
type="text"
name="nim"
class="w-full border p-2 rounded"
required>
</div>


{{-- No Whatsapp --}}
<div class="mb-3">
<label class="block mb-1">No WhatsApp</label>

<input 
type="text"
name="no_whatsapp"
class="w-full border p-2 rounded"
placeholder="08xxxxxxxxxx">
</div>


{{-- Jumlah Pinjam --}}
<div class="mb-3">
<label class="block mb-1">Jumlah Pinjam</label>

<input 
type="number"
name="jumlah"
min="1"
value="1"
class="w-full border p-2 rounded"
required>
</div>


{{-- Tanggal Pinjam --}}
<div class="mb-3">
<label class="block mb-1">Tanggal Pinjam</label>

<input 
type="date" 
name="tanggal_pinjam"
class="w-full border p-2 rounded">
</div>


{{-- Batas Kembali --}}
<div class="mb-3">
<label class="block mb-1">Batas Pengembalian</label>

<input 
type="date"
name="batas_kembali"
class="w-full border p-2 rounded"
required>
</div>


<div class="flex gap-2 mt-4">
<button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
Simpan
</button>

<a href="{{ route('pinjam.index') }}"
class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
Kembali
</a>
</div>

</form>

</div>

@endsection