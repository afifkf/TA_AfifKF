@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Laporan</h2>
    </div>

    <!-- FILTER -->
    <form method="GET" class="flex gap-2 mb-4">

        <input type="date"
            name="dari"
            value="{{ request('dari') }}"
            class="border rounded-lg px-3 py-2">

        <input type="date"
            name="sampai"
            value="{{ request('sampai') }}"
            class="border rounded-lg px-3 py-2">

        <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Filter
        </button>

        <a href="{{ route('laporan.pdf', request()->all()) }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Cetak PDF
        </a>

    </form>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
        {{ session('success') }}
    </div>
    @endif

    <!-- ===================== -->
    <!-- TABEL PEMINJAMAN -->
    <!-- ===================== -->
    <div class="overflow-x-auto">
        <table class="w-full border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Tanggal Pinjam</th>
                    <th class="p-3 border">Batas Pengembalian</th>
                    <th class="p-3 border">Nama Produk</th>
                    <th class="p-3 border">Nama Peminjam</th>
                    <th class="p-3 border">No WhatsApp</th>
                    <th class="p-3 border">Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $key => $item)

                <tr class="border hover:bg-gray-50">

                    <td class="p-3 border">{{ $key + 1 }}</td>

                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-3 border">{{ $item->produk->nama ?? '-' }}</td>

                    <td class="p-3 border">{{ $item->nama_peminjam }}</td>

                    <td class="p-3 border">{{ $item->no_whatsapp }}</td>

                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded text-white
                        @if($item->status == 'terlambat')
                            bg-red-500
                        @elseif($item->status == 'dipinjam')
                            bg-yellow-500
                        @else
                            bg-green-500
                        @endif
                        ">
                            {{ $item->status }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">
                        Data tidak ditemukan
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <!-- ===================== -->
    <!-- TABEL KEUANGAN -->
    <!-- ===================== -->
    <div class="mt-10">

        <h2 class="text-2xl font-bold mb-4">Laporan Keuangan (Perawatan)</h2>

        <div class="overflow-x-auto">
            <table class="w-full border">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Tanggal</th>
                        <th class="p-3 border">Barang</th>
                        <th class="p-3 border">Keterangan</th>
                        <th class="p-3 border">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($keuangans as $key => $item)

                    <tr class="border hover:bg-gray-50">

                        <td class="p-3 border">{{ $key + 1 }}</td>

                        <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                        </td>
                        <td class="p-3 border">
                            {{ $item->perawatan->nama_barang ?? '-' }}
                        </td>

                        <td class="p-3 border">
                            Perawatan {{ $item->perawatan->nama_barang ?? '-' }}
                        </td>

                        <td class="p-3 border">
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">
                            Data keuangan tidak ditemukan
                        </td>
                    </tr>

                    @endforelse

                </tbody>
<tfoot>
    <tr class="bg-gray-100 font-bold">
        <td colspan="4" class="p-3 border text-center">
            Total Pengeluaran
        </td>
        <td class="p-3 border">
            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
        </td>
    </tr>
</tfoot>
            </table>
        </div>

    </div>

</div>

@endsection