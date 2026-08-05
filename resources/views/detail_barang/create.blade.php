@extends('layouts.app')

@section('content')

<div class="bg-white shadow-lg rounded-2xl p-8">

    <div class="border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Tambah Detail Barang
        </h2>

        <p class="text-sm text-gray-500">
            Tambahkan detail barang beserta foto barang.
        </p>
    </div>

    <form
        action="{{ route('detail-barang.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Produk --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-semibold">
                    Produk
                </label>

                <select
                    name="produk_id"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required>

                    <option value="">
                        -- Pilih Produk --
                    </option>

                    @foreach ($produk as $p)

                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Foto barang --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-semibold">
                    Foto Barang
                </label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                    class="w-full border rounded-lg px-4 py-2">

                @error('gambar')

                    <div class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

        <div class="flex gap-3 mt-8">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                Simpan

            </button>

            <a
                href="{{ route('detail-barang.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection