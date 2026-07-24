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

    {{-- Nama --}}
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

    {{-- Email --}}
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

    {{-- Nomor WhatsApp --}}
    <div class="mb-5">

        <label class="block mb-2 font-semibold">
            Nomor WhatsApp
        </label>

        <input
            type="text"
            name="no_whatsapp"
            value="{{ old('no_whatsapp') }}"
            placeholder="08xxxxxxxxxx"
            class="border rounded w-full p-2"
            required>

    </div>

    {{-- Role Admin --}}
    <div class="mb-5">

        <label class="block mb-2 font-semibold">
            Role Admin
        </label>

        <select
            name="role"
            class="border rounded w-full p-2"
            required>

            <option value="">Pilih Role</option>

            <option value="admin_ti"
                {{ old('role') == 'admin_ti' ? 'selected' : '' }}>
                Admin TI
            </option>

            <option value="admin_akuntansi"
                {{ old('role') == 'admin_akuntansi' ? 'selected' : '' }}>
                Admin Akuntansi
            </option>

            <option value="admin_k3"
                {{ old('role') == 'admin_k3' ? 'selected' : '' }}>
                Admin K3
            </option>

            <option value="admin_rekayasapangan"
                {{ old('role') == 'admin_rekayasapangan' ? 'selected' : '' }}>
                Admin Rekayasa Pangan
            </option>

            <option value="admin_tika"
                {{ old('role') == 'admin_tika' ? 'selected' : '' }}>
                Admin TIKA
            </option>

        </select>

    </div>

    {{-- Password --}}
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

    {{-- Konfirmasi Password --}}
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
