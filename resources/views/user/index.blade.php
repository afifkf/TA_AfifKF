@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

<!-- Header -->
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Manajemen Pengguna</h2>

</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-3">
    {{ session('success') }}
</div>
@endif

<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full border">

<thead class="bg-gray-100">
<tr>
    <th class="p-3 border">No</th>
    <th class="p-3 border">Nama</th>
    <th class="p-3 border">Email</th>
    <th class="p-3 border">Dibuat</th>
    <!-- <th class="p-3 border text-center">Aksi</th> -->
</tr>
</thead>

<tbody>

@forelse($users as $key => $user)

<tr class="border hover:bg-gray-50">

    <td class="p-3 border">
        {{ $key + 1 }}
    </td>

    <td class="p-3 border">
        {{ $user->name }}
    </td>

    <td class="p-3 border">
        {{ $user->email }}
    </td>


    <td class="p-3 border">
        {{ $user->created_at->format('d-m-Y') }}
    </td>

    <!-- <td class="p-3 border">

    <div class="flex justify-center gap-2">

        <a href="{{ route('user.edit', $user->id) }}"
        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
        Edit
        </a>

        <form action="{{ route('user.destroy', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button
            onclick="return confirm('Yakin hapus user?')"
            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            Hapus
            </button>
        </form>

    </div>

    </td> -->

</tr>

@empty

<tr>
<td colspan="6" class="text-center p-4 text-gray-500">
    Tidak ada data user
</td>
</tr>

@endforelse

</tbody>

</table>
</div>

</div>

@endsection