@extends('layouts.app')

@section('content')
<!-- <pre>{{ get_class($pinjam) }}</pre> -->
<div class="bg-white shadow rounded-2xl p-6">

    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Riwayat Peminjaman</h1>

        <a href="{{ route('pinjam.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Tambah Peminjaman
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">No</th>
                    <th class="p-3 border text-left">Barang</th>
                    <th class="p-3 border text-left">Admin</th>
                    <th class="p-3 border text-left">Nama Peminjam</th>
                    <th class="p-3 border text-left">NIM</th>
                    <th class="p-3 border text-left">No WhatsApp</th>
                    <th class="p-3 border text-left">Jumlah</th>
                    <th class="p-3 border text-left">Tanggal Peminjaman</th>
                    <th class="p-3 border text-left">Status</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($pinjam as $p)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 border text center">
                        {{ $pinjam->firstItem() + $loop->index }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->produk->nama }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->user->name }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->nama_peminjam }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->nim }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->no_whatsapp }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->jumlah }}
                    </td>

                    <td class="p-3 border">
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
                        @endif">

                            {{ ucfirst($p->status) }}

                        </span>

                    </td>

                    <td class="p-3 border">

                        <div class="flex gap-2">

                            <a href="{{ route('pinjam.edit',$p->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </a>

                            <form action="{{ route('pinjam.destroy',$p->id) }}" method="POST">

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
                    <td colspan="10" class="text-center p-6">
                        Belum ada data peminjaman.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $pinjam->links() }}
    </div>

</div>

@endsection