@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

<div class="flex justify-between mb-4">
<h1 class="text-2xl font-bold">Riwayat Peminjaman</h1>

<a href="{{ route('pinjam.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded-lg">
Tambah Peminjaman
</a>

</div>

<table class="w-full">

<thead class="bg-gray-100">
<tr>
<th class="p-3 text-left">No</th>
<th class="p-3 text-left">Barang</th>
<th class="p-3 text-left">Admin</th>
<th class="p-3 text-left">Nama Peminjam</th>
<th class="p-3 text-left">NIM</th>
<th class="p-3 text-left">No WhatsApp</th>
<th class="p-3 text-left">Jumlah</th>
<th class="p-3 text-left">Tanggal Peminjaman</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($pinjam as $p)

<tr class="border-b">

<td class="p-3">{{ $loop->iteration }}</td>
<td class="p-3">{{ $p->produk->nama }}</td>
<td class="p-3">{{ $p->user->name }}</td>
<td class="p-3">{{ $p->nama_peminjam }}</td>
<td class="p-3">{{ $p->nim }}</td>
<td class="p-3">{{ $p->no_whatsapp }}</td>
<td class="p-3">{{ $p->jumlah }}</td>
<td class="p-3">
    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}

</td>
<td class="p-3 border">
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
<td class="p-3 flex gap-2">

<a href="{{ route('pinjam.edit',$p->id) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">
Edit
</a>

<form action="{{ route('pinjam.destroy',$p->id) }}" method="POST">
@csrf
@method('DELETE')

<button class="bg-red-500 text-white px-3 py-1 rounded">
Hapus
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection