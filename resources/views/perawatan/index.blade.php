@extends('layouts.app')

@section('title','Perawatan Barang')

@section('content')

<div class="bg-white shadow rounded-xl p-6">

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">
            Perawatan Barang
        </h2>

        <a href="{{ route('perawatan.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Tambah Perawatan
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">

                <tr>
                    <th class="border p-3">No</th>
                    <th class="border p-3">Barang</th>
                    <th class="border p-3">Keterangan</th>
                    <th class="border p-3">Tanggal</th>
                    <th class="border p-3">Biaya</th>
                    <th class="border p-3">Status</th>
                    <th class="border p-3 text-center">Aksi</th>
                </tr>

            </thead>

            <tbody>

            @forelse($perawatans as $item)

                <tr class="hover:bg-gray-50">

                    <td class="border p-3 text-center">
                        {{ $perawatans->firstItem() + $loop->index }}
                    </td>

                    <td class="border p-3">
                        {{ $item->nama_barang }}
                    </td>

                    <td class="border p-3">
                        {{ $item->barangRusak->keterangan ?? 'Sudah selesai diperbaiki' }}
                    </td>

                    <td class="border p-3">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                    </td>

                    <td class="border p-3">
                        Rp {{ number_format($item->biaya, 0, ',', '.') }}
                    </td>

                    <td class="border p-3 text-center">

                        <span class="px-3 py-1 rounded text-white

                        @if($item->status == 'selesai')
                            bg-green-500
                        @elseif($item->status == 'proses')
                            bg-yellow-500
                        @else
                            bg-gray-500
                        @endif">

                            {{ ucfirst($item->status) }}

                        </span>

                    </td>

                    <td class="border p-3">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('perawatan.edit',$item->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </a>

                            <form action="{{ route('perawatan.destroy',$item->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin ingin menghapus data ini?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center p-6 text-gray-500">
                        Belum ada data perawatan.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $perawatans->links() }}
    </div>

</div>

@endsection