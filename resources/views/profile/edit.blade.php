@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-8 w-full">
    <h2 class="text-3xl font-bold mb-6">
        Profil
    </h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 rounded p-3 mb-5">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">

        @csrf
        @method('PATCH')

        <div class="grid grid-cols-2 gap-6">

            <div>
                <label class="font-semibold">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name) }}"
                    class="w-full border rounded-lg p-3 mt-2">
            </div>

            <div>
                <label class="font-semibold">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email',$user->email) }}"
                    class="w-full border rounded-lg p-3 mt-2">
            </div>

            <div>
                <label class="font-semibold">
                    Role
                </label>

                <input
                    type="text"
                    value="{{ strtoupper(str_replace('_',' ', $user->role)) }}"
                    readonly
                    class="w-full border rounded-lg p-3 mt-2 bg-gray-100">
            </div>

            <div>
                <label class="font-semibold">
                    Bergabung Sejak
                </label>

                <input
                    type="text"
                    value="{{ $user->created_at->translatedFormat('d F Y') }}"
                    readonly
                    class="w-full border rounded-lg p-3 mt-2 bg-gray-100">
            </div>

            <div>
                <label class="font-semibold">
                    Terakhir Diupdate
                </label>

                <input
                    type="text"
                    value="{{ $user->updated_at->translatedFormat('d F Y H:i') }}"
                    readonly
                    class="w-full border rounded-lg p-3 mt-2 bg-gray-100">
            </div>

        </div>

        <hr class="my-8">

        <h3 class="text-xl font-bold mb-5">
            Ubah Password
        </h3>

        <div class="grid grid-cols-2 gap-6">

            <div>
                <label>Password Baru</label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg p-3 mt-2">
            </div>

            <div>
                <label>Konfirmasi Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded-lg p-3 mt-2">
            </div>

        </div>

        <button
            class="mt-8 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
            Simpan Perubahan
        </button>

    </form>

</div>

@endsection