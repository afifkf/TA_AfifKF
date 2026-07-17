@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">
            Detail Barang : {{ $produk->nama }}
        </h2>

        <div class="flex gap-2">

            <a href="{{ route('detail-barang.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Tambah Detail
            </a>

            <a href="{{ route('produk.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Kembali
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">

                <tr>
                    <th class="border p-3">No</th>
                    <th class="border p-3">Kode Barang</th>
                    <th class="border p-3">Foto</th>
                    <th class="border p-3">Status</th>                    
                    <th class="border p-3 text-center">Aksi</th>
                </tr>

            </thead>

            <tbody>

            @forelse($data as $d)

                <tr class="hover:bg-gray-50">

                    <td class="border p-3 text-center">
                        {{ $data->firstItem() + $loop->index }}
                    </td>

                    <td class="border p-3 text-center">
                        {{ $d->kode_barang }}
                    </td>

                    <td class="border p-3 text-center">

@if($d->gambar)

<img
src="{{ asset('storage/'.$d->gambar) }}"
class="w-20 h-20 object-cover rounded mx-auto border">

@else

<span class="text-gray-400">
Tidak ada foto
</span>

@endif

</td>

                    <td class="border p-3 text-center">

                        @if($d->status == 'tersedia')

                            <span class="bg-green-200 text-green-700 px-3 py-1 rounded">
                                Tersedia
                            </span>

                        @elseif($d->status == 'dipinjam')

                            <span class="bg-yellow-200 text-yellow-700 px-3 py-1 rounded">
                                Dipinjam
                            </span>

                        @elseif($d->status == 'rusak')

                            <span class="bg-red-200 text-red-700 px-3 py-1 rounded">
                                Rusak
                            </span>

                        @endif

                    </td>

                    <td class="border p-3">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('detail-barang.edit', $d->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </a>

                            <form action="{{ route('detail-barang.destroy', $d->id) }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin hapus data ini?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-500">
                            Belum ada detail barang.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $data->links() }}
    </div>

</div>

@endsection