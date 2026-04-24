@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">
Tambah Detail Barang
</h2>

<form action="{{ route('detail-barang.store') }}" method="POST">
@csrf

{{-- Pilih Produk --}}
<div class="mb-3">
<label class="block mb-1">Produk</label>

<select name="produk_id" 
class="w-full border p-2 rounded" required>

<option value="">-- Pilih Produk --</option>

@foreach($produk as $p)

<option value="{{ $p->id }}">
{{ $p->nama }}
</option>

@endforeach

</select>
</div>


{{-- Kode Barang --}}
<!-- <div class="mb-3">
<label class="block mb-1">
Kode Barang
</label>

<input type="text"
name="kode_barang"
class="w-full border p-2 rounded"
required>

@error('kode_barang')
<div class="text-red-500 text-sm">
{{ $message }}
</div>
@enderror

</div> -->


<div class="flex gap-2 mt-4">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Simpan
</button>

<a href="{{ route('detail-barang.index') }}"
class="bg-gray-500 text-white px-4 py-2 rounded">
Kembali
</a>

</div>

</form>

</div>

@endsection