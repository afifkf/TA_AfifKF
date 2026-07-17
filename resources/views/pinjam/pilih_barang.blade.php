@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-xl p-6">

    <h2 class="text-2xl font-bold mb-6">
        Pilih Detail Barang
    </h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <hr class="my-6">

<h3 class="text-lg font-semibold mb-3">
Bukti Peminjaman yang Sudah Ditandatangani
</h3>

@if($pinjam->bukti_ttd)

    <a href="{{ asset('storage/'.$pinjam->bukti_ttd) }}"
       target="_blank">

        <img
            src="{{ asset('storage/'.$pinjam->bukti_ttd) }}"
            class="w-96 border rounded shadow">

    </a>

@else

<div class="text-red-600">
Mahasiswa belum mengupload bukti.
</div>

@endif

<hr class="my-6">

    <div class="mb-6">

        <table class="w-full">

            <tr>
                <td class="font-semibold w-52">Nama Mahasiswa</td>
                <td>: {{ $pinjam->nama_peminjam }}</td>
            </tr>

            <tr>
                <td class="font-semibold">NIM</td>
                <td>: {{ $pinjam->nim }}</td>
            </tr>

            <tr>
                <td class="font-semibold">Barang</td>
                <td>: {{ $pinjam->produk->nama }}</td>
            </tr>

            <tr>
                <td class="font-semibold">Jumlah Dipinjam</td>
                <td>: {{ $pinjam->jumlah }}</td>
            </tr>

        </table>

    </div>

    <form
        action="{{ route('pinjam.setujui',$pinjam->id) }}"
        method="POST">

        @csrf

        <div class="border rounded-lg">

            <table class="w-full">

                <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 border w-20">
                        Pilih
                    </th>

                    <th class="p-3 border">
                        Kode Barang
                    </th>

                    <th class="p-3 border">
                        Status
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($detailBarang as $barang)

                <tr>

                    <td class="border text-center">
                        @if($barang->status == 'tersedia')

                        <input
                            type="checkbox"
                            class="pilih-barang"
                            name="detail_barang_id[]"
                            value="{{ $barang->id }}">

                        @else

                        <span class="text-gray-500">
                        —
                        </span>

                        @endif

                    </td>

                    <td class="border p-3">

                        {{ $barang->kode_barang }}

                    </td>

                    <td class="border p-3">

                    @if($barang->status == 'tersedia')

                    <span class="bg-green-500 text-white px-3 py-1 rounded">
                        Tersedia
                    </span>

                    @elseif($barang->status == 'dipinjam')

                    <span class="bg-yellow-500 text-white px-3 py-1 rounded">
                        Dipinjam
                    </span>

                    @elseif($barang->status == 'rusak')

                    <span class="bg-red-500 text-white px-3 py-1 rounded">
                        Rusak
                    </span>

                    @else

                    <span class="bg-gray-500 text-white px-3 py-1 rounded">
                        {{ ucfirst($barang->status) }}
                    </span>

                    @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="3"
                        class="text-center p-5">

                        Tidak ada detail barang tersedia.

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <a href="{{ route('pinjam.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">

                Batal

            </a>

            <button
                class="bg-green-600 text-white px-5 py-2 rounded">

                Setujui Peminjaman

            </button>

        </div>

    </form>

</div>
<script>

const maksimal = {{ $pinjam->jumlah }};

const checkbox = document.querySelectorAll('.pilih-barang');

checkbox.forEach(function(item){

    item.addEventListener('change', function(){

        let terpilih = document.querySelectorAll('.pilih-barang:checked');

        if(terpilih.length > maksimal){

            alert('Maksimal memilih ' + maksimal + ' barang.');

            this.checked = false;

        }

    });

});

</script>
@endsection