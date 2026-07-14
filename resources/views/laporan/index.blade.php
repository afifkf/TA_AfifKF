@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

    <!-- ===================== -->
    <!-- HEADER PEMINJAMAN -->
    <!-- ===================== -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Laporan Peminjaman</h2>

        <a href="{{ route('laporan.peminjaman.pdf', request()->all()) }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Cetak PDF
        </a>
    </div>

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

                    <th class="p-3 border">Nama Produk</th>
                    <th class="p-3 border">Nama Peminjam</th>


                    <th class="p-3 border">Tanggal Pinjam</th>
                    <th class="p-3 border">Batas Pengembalian</th>
                    <th class="p-3 border">No WhatsApp</th>
                    <th class="p-3 border">Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $item)

                <tr class="border hover:bg-gray-50">

                    <td class="p-3 border text center">
                        {{ $data->firstItem() + $loop->index }}</td>


                    <td class="p-3 border">
                        {{ $item->produk->nama ?? '-' }}
                    </td>
                    
                    <td class="p-3 border">
                        {{ $item->nama_peminjam }}
                    </td>


                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item->batas_kembali)->translatedFormat('d F Y') }}
                    </td>


                   

                    <td class="p-3 border">
                        {{ $item->no_whatsapp }}
                    </td>

                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded text-white
                        @if($item->status == 'terlambat')
                            bg-red-500
                        @elseif($item->status == 'dipinjam')
                            bg-yellow-500
                        @else
                            bg-green-500
                        @endif">
                            {{ ucfirst($item->status) }}
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
        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>


    <!-- ===================== -->
    <!-- LAPORAN KEUANGAN -->
    <!-- ===================== -->
    <div class="mt-10">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-2xl font-bold">
                Laporan Keuangan Perawatan Barang
            </h2>

            <a href="{{ route('laporan.keuangan.pdf', request()->all()) }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Cetak PDF
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full border">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">No</th>
                                                <th class="p-3 border">Nama Barang</th>

                        <th class="p-3 border">Tanggal</th>
                        <th class="p-3 border">Keterangan</th>
                        <th class="p-3 border">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($keuangans as $item)

                    <tr class="border hover:bg-gray-50">

                        <td class="p-3 border text center">
                            {{ $keuangans->firstItem() + $loop->index }}
                        </td>

                        <td class="p-3 border">
                            {{ $item->perawatan->nama_barang ?? '-' }}
                        </td>

                        <td class="p-3 border">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
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
            <div class="mt-4">
                {{ $keuangans->links() }}
            </div>

        </div>

    </div>

    <!-- ===================== -->
    <!-- BARANG RUSAK -->
    <!-- ===================== -->
    <div class="mt-10">
        <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold mb-4">
                    Laporan Barang Rusak
                </h2>

                <a href="{{ route('laporan.barangrusak.pdf', request()->all()) }}"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Cetak PDF
                </a>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full border">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="border p-3">No</th>
                        <th class="border p-3">Nama Barang</th>
                        <th class="border p-3">Kode Barang</th>
                        <th class="border p-3">Tanggal Rusak</th>
                        <th class="border p-3">Keterangan</th>
                        <th class="border p-3">Status Perbaikan</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($barangRusaks as $item)

                    <tr>

                        <td class="border p-3 text-center">
                            {{ $barangRusaks->firstItem() + $loop->index }}
                        </td>

                        <td class="border p-3">
                            {{ $item->detailBarang->produk->nama ?? '-' }}
                        </td>

                        <td class="border p-3">
                            {{ $item->detailBarang->kode_barang ?? '-' }}
                        </td>

                        <td class="border p-3">
                            {{ \Carbon\Carbon::parse($item->tanggal_rusak)->translatedFormat('d F Y') }}
                        </td>

                        <td class="border p-3">
                            {{ $item->keterangan }}
                        </td>

                        <td class="border p-3">

                            @if($item->status == 'rusak')
                                <span class="bg-red-500 text-white px-2 py-1 rounded">
                                    Masih Rusak
                                </span>

                            @elseif($item->status == 'selesai')
                                <span class="bg-green-500 text-white px-2 py-1 rounded">
                                    Perbaikan Selesai
                                </span>

                            @else
                                {{ $item->status }}
                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center p-4 text-gray-500">
                            Belum ada barang rusak
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
            <div class="mt-4">
                {{ $barangRusaks->links() }}
            </div>

        </div>

    </div>

</div>

@endsection