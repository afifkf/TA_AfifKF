@extends('layouts.app')

@section('title', 'Tambah Perawatan')

@section('content')

<div class="bg-white shadow-lg rounded-2xl p-8">

    <div class="border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Tambah Perawatan
        </h2>

        <p class="text-sm text-gray-500">
            Lengkapi data perawatan barang di bawah ini.
        </p>
    </div>

    <form
        action="{{ route('perawatan.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Barang Rusak --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-semibold">
                    Barang Rusak
                </label>

                <select
                    name="barang_rusak_id"
                    id="barang_rusak"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                    <option value="">Pilih Barang</option>

                    @foreach ($barangRusak as $item)

                        <option
                            value="{{ $item->id }}"
                            data-keterangan="{{ $item->keterangan }}">

                            {{ $item->detailBarang->produk->nama ?? '-' }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Keterangan kerusakan --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-semibold">
                    Keterangan Kerusakan
                </label>

                <textarea
                    id="keterangan_rusak"
                    rows="4"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                    readonly></textarea>

            </div>

            {{-- Tanggal --}}
            <div>

                <label class="block mb-2 font-semibold">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>

            </div>

            {{-- Biaya --}}
            <div>

                <label class="block mb-2 font-semibold">
                    Biaya
                </label>

                <input
                    type="number"
                    name="biaya"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

            </div>

            {{-- Gambar --}}
            <div>

                <label class="block mb-2 font-semibold">
                    Foto Barang
                </label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                    class="w-full border rounded-lg px-4 py-2">

                @error('gambar')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Status --}}
            <div>

                <label class="block mb-2 font-semibold">
                    Status
                </label>

                <input
                    type="text"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                    value="Proses"
                    readonly>

                <input
                    type="hidden"
                    name="status"
                    value="proses">

            </div>

        </div>

        <div class="flex gap-3 mt-8">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                Simpan

            </button>

            <a
                href="{{ route('perawatan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Kembali

            </a>

        </div>

    </form>

</div>

<script>

document
    .getElementById('barang_rusak')
    .addEventListener('change', function () {

        let keterangan = this.options[
            this.selectedIndex
        ].getAttribute('data-keterangan');

        document.getElementById(
            'keterangan_rusak'
        ).value = keterangan ?? '';

    });

</script>

@endsection