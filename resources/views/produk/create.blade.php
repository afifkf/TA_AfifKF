@extends('layouts.app')

@section('content')

<div class="bg-white shadow-lg rounded-2xl p-8 w-full">

    <div class="border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Tambah Barang
        </h2>

        <p class="text-sm text-gray-500">
            Silakan isi data barang dengan lengkap.
        </p>
    </div>

    <form action="{{ route('produk.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block mb-2 font-semibold">
                    Nama Barang
                </label>

                <input
                    type="text"
                    name="nama"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Jenis Barang
                </label>

                <select
                    name="jenis"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>

                    <option value="">-- Pilih Jenis Barang --</option>
                    <option value="Inventaris">Inventaris</option>
                    <option value="Barang Habis Pakai">Barang Habis Pakai</option>

                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Stok
                </label>

                <input
                    type="number"
                    name="stok"
                    min="1"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            @if(auth()->user()->role == 'super_admin')
                <div class="md:col-span-2">
                    <label class="block mb-2 font-semibold">
                        Prodi
                    </label>

                    <select
                        name="departemen"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

                        <option value="TI">D3 TI</option>
                        <option value="AKUNTANSI">Akuntansi</option>
                        <option value="K3">K3</option>
                        <option value="REKAYASA_PANGAN">Rekayasa Pangan</option>
                        <option value="TI&AI">D4 TI & AI</option>

                    </select>
                </div>
            @endif

            <div class="md:col-span-2">
                <label class="block mb-2 font-semibold">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

        </div>

        <div class="flex gap-3 mt-8">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">

                Simpan

            </button>

            <a
                href="{{ route('produk.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection