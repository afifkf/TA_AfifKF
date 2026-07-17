@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Tambah Admin
        </h2>

        <a href="{{ route('users.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">

            Kembali

        </a>

    </div>

    @if ($errors->any())

        <div class="bg-red-100 text-red-700 rounded p-3 mb-5">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('users.storeAdmin') }}"
        method="POST">

        @csrf

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Nama
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="border rounded w-full p-2"
                required>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="border rounded w-full p-2"
                required>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Role Admin
            </label>

            <select
                name="role"
                class="border rounded w-full p-2"
                required>

                <option value="">Pilih Role</option>

                <option value="admin_ti">Admin TI</option>

                <option value="admin_akuntansi">Admin Akuntansi</option>

                <option value="admin_k3">Admin K3</option>

                <option value="admin_rekayasapangan">Admin Rekayasa Pangan</option>

                <option value="admin_tika">Admin TIKA</option>

            </select>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="border rounded w-full p-2"
                required>

        </div>

        <div class="mb-6">

            <label class="block mb-2 font-semibold">
                Konfirmasi Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                class="border rounded w-full p-2"
                required>

        </div>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

            Simpan

        </button>

    </form>

</div>

@endsection