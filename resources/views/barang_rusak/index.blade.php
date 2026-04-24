@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">
Barang Rusak
</h2>

<table class="w-full border">

<thead class="bg-gray-100">
<tr>
<th class="border p-2">No</th>
<th class="border p-2">Nama Barang</th>
<th class="border p-2">Kode Barang</th>
<th class="border p-2">Tanggal Rusak</th>
<th class="border p-2">Keterangan</th>
<th class="border p-2">Status Perbaikan</th>

</tr>
</thead>

<tbody>

@forelse($data as $item)

<tr>
<td class="border p-2 text-center">
{{ $loop->iteration }}
</td>

<td class="border p-2">
{{ $item->detailBarang->produk->nama ?? '-' }}
</td>

<td class="border p-2">
{{ $item->detailBarang->kode_barang ?? '-' }}
</td>



<td class="border p-2">
    {{ \Carbon\Carbon::parse($item->tanggal_rusak)->translatedFormat('d F Y') }}

</td>

<td class="border p-2">
{{ $item->keterangan }}
</td>

<td class="border p-2">
    @if($item->status == 'rusak')
        Masih Rusak
    @elseif($item->status == 'selesai')
        Perbaikan Selesai
    @else
        {{ $item->status }}
    @endif
</td>
</tr>

@empty

<tr>
<td colspan="5" class="text-center p-4">
Belum ada barang rusak
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

@endsection