@extends('layouts.app')

@section('title','Tambah Perawatan')

@section('content')

<div class="bg-white shadow rounded-xl p-6">

<h2 class="text-xl font-bold mb-4">
Tambah Perawatan
</h2>

<form action="{{ route('perawatan.store') }}" method="POST">

@csrf

{{-- Barang Rusak --}}
<div class="mb-3">
<label>Barang Rusak</label>

<select name="barang_rusak_id" 
id="barang_rusak"
class="w-full border rounded-lg p-2">

<option value="">Pilih Barang</option>

@foreach($barangRusak as $item)

<option 
value="{{ $item->id }}"
data-keterangan="{{ $item->keterangan }}"
>
{{ $item->detailBarang->produk->nama ?? '-' }}
</option>

@endforeach

</select>

</div>


{{-- Keterangan Kerusakan --}}
<div class="mb-3">
<label>Keterangan Kerusakan</label>

<textarea 
id="keterangan_rusak"
class="w-full border rounded-lg p-2"
readonly
></textarea>

</div>


{{-- Tanggal --}}
<div class="mb-3">
<label>Tanggal</label>

<input 
type="date"
name="tanggal"
class="w-full border rounded-lg p-2"
required>

</div>


{{-- Biaya --}}
<div class="mb-3">
<label>Biaya</label>

<input 
type="number"
name="biaya"
class="w-full border rounded-lg p-2">

</div>


{{-- Status --}}
<div class="mb-3">
<label>Status</label>

<select 
name="status"
class="w-full border rounded-lg p-2">

<option value="proses">Proses</option>
<option value="selesai">Selesai</option>

</select>

</div>


{{-- Keterangan Tambahan --}}
<!-- <div class="mb-3">
<label>Keterangan Perawatan</label>

<textarea 
name="keterangan"
class="w-full border rounded-lg p-2">
</textarea>

</div> -->


<button 
class="bg-blue-600 text-white px-4 py-2 rounded-lg">

Simpan

</button>

</form>

</div>


{{-- Script otomatis tampil keterangan --}}
<script>

document.getElementById('barang_rusak')
.addEventListener('change', function(){

let keterangan = this.options[
this.selectedIndex
].getAttribute('data-keterangan');

document.getElementById('keterangan_rusak')
.value = keterangan ?? '';

});

</script>

@endsection