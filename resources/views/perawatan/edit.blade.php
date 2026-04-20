@extends('layouts.app')

@section('title','Edit Perawatan')

@section('content')

<div class="bg-white shadow rounded-xl p-6">

<h2 class="text-xl font-bold mb-4">
Edit Perawatan
</h2>

<form 
action="{{ route('perawatan.update',$perawatan->id) }}"
method="POST">

@csrf
@method('PUT')


<div class="mb-3">
<label>Barang Rusak</label>

<select 
name="barang_rusak_id"
id="barang_rusak"
class="w-full border rounded-lg p-2">

@foreach($barangRusak as $item)

<option 
value="{{ $item->id }}"
data-keterangan="{{ $item->keterangan }}"
{{ $perawatan->barang_rusak_id == $item->id ? 'selected' : '' }}
>

{{ $item->detailBarang->produk->nama ?? '-' }}

</option>

@endforeach

</select>

</div>


<div class="mb-3">
<label>Keterangan Kerusakan</label>

<textarea 
id="keterangan_rusak"
class="w-full border rounded-lg p-2"
readonly>

{{ $perawatan->barangRusak->keterangan ?? '' }}

</textarea>

</div>


<div class="mb-3">
<label>Tanggal</label>

<input 
type="date"
name="tanggal"
value="{{ $perawatan->tanggal }}"
class="w-full border rounded-lg p-2">

</div>


<div class="mb-3">
<label>Biaya</label>

<input 
type="number"
name="biaya"
value="{{ $perawatan->biaya }}"
class="w-full border rounded-lg p-2">

</div>


<div class="mb-3">
<label>Status</label>

<select 
name="status"
class="w-full border rounded-lg p-2">

<option value="proses"
{{ $perawatan->status == 'proses' ? 'selected' : '' }}>
Proses
</option>

<option value="selesai"
{{ $perawatan->status == 'selesai' ? 'selected' : '' }}>
Selesai
</option>

</select>

</div>


<!-- <div class="mb-3">
<label>Keterangan Perawatan</label>

<textarea 
name="keterangan"
class="w-full border rounded-lg p-2">

{{ $perawatan->keterangan }}

</textarea>

</div> -->


<button 
class="bg-blue-600 text-white px-4 py-2 rounded">

Update

</button>

</form>

</div>


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