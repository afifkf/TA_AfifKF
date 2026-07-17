@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Edit Pengguna
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
        action="{{ route('users.updateAdmin',$user->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Nama
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name',$user->name) }}"
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
                value="{{ old('email',$user->email) }}"
                class="border rounded w-full p-2"
                required>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Role
            </label>

            <select
                name="role"
                class="border rounded w-full p-2"
                required>

                <option value="mahasiswa" {{ $user->role=='mahasiswa'?'selected':'' }}>
                    Mahasiswa
                </option>

                <option value="admin_ti" {{ $user->role=='admin_ti'?'selected':'' }}>
                    Admin TI
                </option>

                <option value="admin_akuntansi" {{ $user->role=='admin_akuntansi'?'selected':'' }}>
                    Admin Akuntansi
                </option>

                <option value="admin_k3" {{ $user->role=='admin_k3'?'selected':'' }}>
                    Admin K3
                </option>

                <option value="admin_rekayasapangan" {{ $user->role=='admin_rekayasapangan'?'selected':'' }}>
                    Admin Rekayasa Pangan
                </option>

                <option value="admin_tika" {{ $user->role=='admin_tika'?'selected':'' }}>
                    Admin TIKA
                </option>

                <option value="super_admin" {{ $user->role=='super_admin'?'selected':'' }}>
                    Super Admin
                </option>

            </select>

        </div>

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Password Baru
            </label>

            <input
                type="password"
                name="password"
                class="border rounded w-full p-2">

            <small class="text-gray-500">
                Kosongkan jika password tidak ingin diubah.
            </small>

        </div>

        <div class="mb-6">

            <label class="block mb-2 font-semibold">
                Konfirmasi Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                class="border rounded w-full p-2">

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('users.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">

                Batal

            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@endsection