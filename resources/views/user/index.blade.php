@extends('layouts.app')

@section('content')

<div class="bg-white shadow rounded-2xl p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Manajemen Pengguna</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">Role</th>
                    <th class="p-3 border">Dibuat</th>
                    @if(Auth::user()->role == 'super_admin')
                        <th class="p-3 border text-center">Aksi</th>
                    @endif                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                <tr class="border hover:bg-gray-50">

                    <td class="p-3 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-3 border">
                        {{ $user->name }}
                    </td>

                    <td class="p-3 border">
                        {{ $user->email }}
                    </td>

                    <td class="p-3 border">
                        {{ $user->role }}
                    </td>

                    <td class="p-3 border">
                        {{ $user->created_at->format('d-m-Y') }}
                    </td>

                    @if(Auth::user()->role == 'super_admin')

                    <td class="p-3 border">

                        <form action="{{ route('users.updateRole', $user->id) }}"
                            method="POST"
                            class="flex items-center gap-2">

                            @csrf
                            @method('PUT')

                            <select name="role" class="border rounded px-2 py-1">

                                <option value="user" {{ $user->role=='user'?'selected':'' }}>
                                    User
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
                                    Admin TI&AI
                                </option>

                                <option value="super_admin" {{ $user->role=='super_admin'?'selected':'' }}>
                                    Super Admin
                                </option>

                            </select>

                            <button type="submit"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Simpan
                            </button>

                        </form>

                    </td>

                    @endif

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center p-6 text-gray-500">
                        Tidak ada data user.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>

    

</div>

@endsection