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

    @if($item->status == 'menunggu')
        bg-yellow-500

    @elseif($item->status == 'dipinjam')
        bg-blue-500

    @elseif($item->status == 'terlambat')
        bg-red-500

    @elseif($item->status == 'dikembalikan')
        bg-green-500

    @elseif($item->status == 'ditolak')
        bg-red-700

    @else
        bg-gray-500

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
                        <th class="border p-3">Nama Mahasiswa</th>
                        <th class="border p-3">NIM</th>
                        <th class="border p-3">Tanggal Rusak</th>
                        <th class="border p-3">Keterangan</th>
                        <th class="border p-3">Status Perbaikan</th>
                        <th class="border p-3">Pertanggungjawaban</th>  
                        <th class="border p-3">Aksi</th>  

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
    {{ $item->pinjam->user->name ?? '-' }}
</td>

<td class="border p-3">
    {{ $item->pinjam->user->nim ?? '-' }}
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
                        <td class="border p-3">

    @if($item->jenis_pertanggungjawaban)

        <div class="text-sm">

            <p>
                <strong>Jenis:</strong>

                @if($item->jenis_pertanggungjawaban == 'ganti_barang')
                    Ganti Barang
                @else
                    Ganti Uang
                @endif
            </p>

            <p>
                <strong>Status:</strong>

                @if($item->status_pertanggungjawaban == 'menunggu')

                    <span class="text-yellow-600">
                        Menunggu
                    </span>

                @elseif($item->status_pertanggungjawaban == 'proses')

                    <span class="text-blue-600">
                        Proses
                    </span>

                @elseif($item->status_pertanggungjawaban == 'selesai')

                    <span class="text-green-600">
                        Selesai
                    </span>

                @endif

            </p>

            @if($item->nominal_ganti)

                <p>
                    <strong>Nominal:</strong>

                    Rp {{ number_format(
                        $item->nominal_ganti,
                        0,
                        ',',
                        '.'
                    ) }}

                </p>

            @endif

        </div>

    @else

        <span class="text-gray-500">
            Belum ditentukan
        </span>

    @endif

</td>

<td class="border p-3">

    <form
        action="{{ route(
            'barang-rusak.pertanggungjawaban',
            $item->id
        ) }}"
        method="POST">

        @csrf
        @method('PUT')

        <select
            name="jenis_pertanggungjawaban"
            class="border rounded p-2 w-full mb-2"
            required>

            <option value="">
                Pilih Pertanggungjawaban
            </option>

            <option
                value="ganti_barang"
                {{ $item->jenis_pertanggungjawaban
                    == 'ganti_barang'
                    ? 'selected'
                    : '' }}>

                Ganti Barang

            </option>

            <option
                value="ganti_uang"
                {{ $item->jenis_pertanggungjawaban
                    == 'ganti_uang'
                    ? 'selected'
                    : '' }}>

                Ganti Uang

            </option>

        </select>


        <select
            name="status_pertanggungjawaban"
            class="border rounded p-2 w-full mb-2"
            required>

            <option
                value="menunggu"
                {{ $item->status_pertanggungjawaban
                    == 'menunggu'
                    ? 'selected'
                    : '' }}>

                Menunggu

            </option>

            <option
                value="proses"
                {{ $item->status_pertanggungjawaban
                    == 'proses'
                    ? 'selected'
                    : '' }}>

                Proses

            </option>

            <option
                value="selesai"
                {{ $item->status_pertanggungjawaban
                    == 'selesai'
                    ? 'selected'
                    : '' }}>

                Selesai

            </option>

        </select>


        <input
            type="number"
            name="nominal_ganti"
            value="{{ $item->nominal_ganti }}"
            placeholder="Nominal ganti uang"
            class="border rounded p-2 w-full mb-2">


        <textarea
            name="keterangan_pertanggungjawaban"
            placeholder="Keterangan"
            class="border rounded p-2 w-full mb-2">{{ $item->keterangan_pertanggungjawaban }}</textarea>


        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded w-full">

            Simpan

        </button>

    </form>

</td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="10" class="text-center p-4 text-gray-500">
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const selects = document.querySelectorAll(
        'select[name="jenis_pertanggungjawaban"]'
    );

    selects.forEach(function (select) {

        select.addEventListener('change', function () {

            const form = this.closest('form');

            const nominalInput = form.querySelector(
                'input[name="nominal_ganti"]'
            );

            if (this.value === 'ganti_uang') {

                nominalInput.style.display = 'block';
                nominalInput.required = true;

            } else {

                nominalInput.style.display = 'none';
                nominalInput.required = false;
                nominalInput.value = '';

            }

        });

        // Jalankan saat halaman pertama kali dibuka
        select.dispatchEvent(new Event('change'));

    });

});
</script>

@endsection