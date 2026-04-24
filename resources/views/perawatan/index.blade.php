@extends('layouts.app')

@section('title','Perawatan Barang')

@section('content')

<div class="bg-white shadow rounded-xl p-6">

<div class="flex justify-between mb-4">
<h2 class="text-xl font-bold">Perawatan Barang</h2>

<a href="{{ route('perawatan.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded-lg">
Tambah Perawatan
</a>

</div>

<table class="w-full">
<thead class="bg-gray-100">
<tr>
<th class="p-3">Barang</th>
<th class="p-3">Keterangan</th>
<th class="p-3">Tanggal</th>
<th class="p-3">Biaya</th>
<th class="p-3">Status</th>
<th class="p-3">Aksi</th>
</tr>
</thead>

<tbody>
@foreach($perawatans as $item)

<tr class="border-b">
<td class="p-3">
{{ $item->nama_barang }}
</td>

<td class="p-3">
{{ $item->barangRusak->keterangan ?? 'sudah selesai diperbaiki' }}
</td>

<td class="p-3">
{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
</td>

<td class="p-3">
Rp {{ number_format($item->biaya) }}
</td>

<td class="p-3">
    <span class="px-2 py-1 rounded text-white
        @if($item->status == 'selesai')
            bg-green-500
        @elseif($item->status == 'proses')
            bg-yellow-500
        @else
            bg-gray-500
        @endif
    ">
        {{ $item->status }}
    </span>
</td>

<td class="p-3 flex gap-2">

<a href="{{ route('perawatan.edit',$item->id) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">
Edit
</a>

<form action="{{ route('perawatan.destroy',$item->id) }}"
method="POST">

@csrf
@method('DELETE')

<button 
class="bg-red-500 text-white px-3 py-1 rounded">
Hapus
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-4">
{{ $perawatans->links() }}
</div>

</div>

@endsection