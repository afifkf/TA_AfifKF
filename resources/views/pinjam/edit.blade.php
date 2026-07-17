@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">
Edit Peminjaman
</h2>

<form action="{{ route('pinjam.update',$pinjam->id) }}" method="POST">
@csrf
@method('PUT')

<label>Status</label>

<select name="status" class="w-full border p-2 rounded mb-3">

    <option value="dipinjam"
        {{ $pinjam->status == 'dipinjam' ? 'selected' : '' }}>
        Dipinjam
    </option>

    <option value="terlambat"
        {{ $pinjam->status == 'terlambat' ? 'selected' : '' }}>
        Terlambat
    </option>

    <option value="dikembalikan"
        {{ $pinjam->status == 'dikembalikan' ? 'selected' : '' }}>
        Dikembalikan
    </option>

</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Update
</button>

</form>

</div>

@endsection