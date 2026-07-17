@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">
Edit Detail Barang
</h2>

@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 mb-3 rounded">
{{ session('error') }}
</div>
@endif

<form
action="{{ route('detail-barang.update',$data->id) }}"
method="POST"
enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
<label>Produk</label>

<select name="produk_id" 
class="w-full border p-2 rounded">

@foreach($produk as $p)

<option value="{{ $p->id }}"
{{ $data->produk_id == $p->id ? 'selected' : '' }}>
{{ $p->nama }}
</option>

@endforeach

</select>

</div>


<div class="mb-3">
<label>Kode Barang</label>

<input type="text"
name="kode_barang"
value="{{ $data->kode_barang }}"
class="w-full border p-2 rounded"
required>

@error('kode_barang')
<div class="text-red-500 text-sm">
{{ $message }}
</div>
@enderror

</div>

<div class="mb-3">

<label>Foto Barang</label>

@if($data->gambar)

<div class="mb-2">
    <img
        src="{{ asset('storage/'.$data->gambar) }}"
        class="w-40 rounded border">
</div>

@endif

<input
type="file"
name="gambar"
class="w-full border p-2 rounded">

@error('gambar')
<div class="text-red-500 text-sm">
{{ $message }}
</div>
@enderror

</div>


<div class="mb-3">
<label>Status</label>

<select name="status"class="w-full border p-2 rounded">

<option value="tersedia"
{{ $data->status == 'tersedia' ? 'selected' : '' }}>
Tersedia
</option>

<option value="rusak"
{{ $data->status == 'rusak' ? 'selected' : '' }}>
Rusak
</option>

</select>

</div>

<div class="mb-3">
<label>Keterangan Rusak</label>

<textarea
name="keterangan"
class="w-full border p-2 rounded">
</textarea>

</div>


<div class="flex gap-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Update
</button>

<a href="{{ route('detail-barang.index') }}"
class="bg-gray-500 text-white px-4 py-2 rounded">
Kembali
</a>

</div>

</form>

</div>

@endsection