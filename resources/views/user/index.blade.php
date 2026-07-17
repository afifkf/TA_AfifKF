@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

    <div class="flex justify-between items-center mb-4">

        <h2 class="text-2xl font-bold">
            Manajemen Pengguna
        </h2>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">Role</th>
                    <th class="p-3 border">Dibuat</th>

                    @if(Auth::user()->role=='super_admin')

                    <th class="p-3 border text-center">

                        <div class="flex justify-between items-center">

                            <span>Aksi</span>

                            <a href="{{ route('users.createAdmin') }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                                + Admin

                            </a>

                        </div>

                    </th>

                    @endif

                </tr>

            </thead>

            <tbody>

            @forelse($users as $user)

            <tr>

                <td class="border p-3 text-center">
                    {{ $users->firstItem() + $loop->index }}
                </td>

                <td class="border p-3">
                    {{ $user->name }}
                </td>

                <td class="border p-3">
                    {{ $user->email }}
                </td>

                <td class="border p-3">
                    {{ $user->role }}
                </td>

                <td class="border p-3">
                    {{ $user->created_at->format('d-m-Y') }}
                </td>

                @if(Auth::user()->role=='super_admin')

                <td class="border p-3">

                    <div class="flex flex-wrap gap-2">

                        {{-- UPDATE ROLE --}}
                        <form
                            action="{{ route('users.updateRole',$user->id) }}"
                            method="POST"
                            class="flex gap-2">

                            @csrf
                            @method('PUT')

                            <select
                                name="role"
                                class="border rounded px-2">

                                <option value="mahasiswa"
                                {{ $user->role=='mahasiswa'?'selected':'' }}>
                                    Mahasiswa
                                </option>

                                <option value="admin_ti"
                                {{ $user->role=='admin_ti'?'selected':'' }}>
                                    Admin TI
                                </option>

                                <option value="admin_akuntansi"
                                {{ $user->role=='admin_akuntansi'?'selected':'' }}>
                                    Admin Akuntansi
                                </option>

                                <option value="admin_k3"
                                {{ $user->role=='admin_k3'?'selected':'' }}>
                                    Admin K3
                                </option>

                                <option value="admin_rekayasapangan"
                                {{ $user->role=='admin_rekayasapangan'?'selected':'' }}>
                                    Admin Rekayasa Pangan
                                </option>

                                <option value="admin_tika"
                                {{ $user->role=='admin_tika'?'selected':'' }}>
                                    Admin TIKA
                                </option>

                                <option value="super_admin"
                                {{ $user->role=='super_admin'?'selected':'' }}>
                                    Super Admin
                                </option>

                            </select>

                            <button
                                class="bg-blue-600 text-white px-3 rounded">

                                Simpan

                            </button>

                        </form>

                        {{-- EDIT --}}
                        <a href="{{ route('users.editAdmin',$user->id) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">

                            Edit

                        </a>

                        {{-- DELETE --}}
                        @if(Auth::id() != $user->id)

                        <form
                            action="{{ route('users.destroy',$user->id) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Yakin menghapus user ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">

                                Hapus

                            </button>

                        </form>

                        @endif

                    </div>

                </td>

                @endif

            </tr>

            @empty

            <tr>

                <td colspan="6" class="text-center p-6">

                    Tidak ada data pengguna.

                </td>

            </tr>

            @endforelse

            </tbody>

        </table>

        <div class="mt-5">
            {{ $users->links() }}
        </div>

    </div>

</div>

@endsection