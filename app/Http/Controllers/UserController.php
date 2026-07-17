<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function index()
{
    $users = User::latest()->paginate(5);

    return view('user.index', compact('users'));
}

public function createAdmin()
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    return view('user.create-admin');
}

public function storeAdmin(Request $request)
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:admin_ti,admin_akuntansi,admin_k3,admin_rekayasapangan,admin_tika',
    ]);

    $departemenMap = [
        'admin_ti' => 'TI',
        'admin_akuntansi' => 'AKUNTANSI',
        'admin_k3' => 'K3',
        'admin_rekayasapangan' => 'REKAYASA PANGAN',
        'admin_tika' => 'TIKA',
    ];

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'departemen' => $departemenMap[$request->role],
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'Admin berhasil ditambahkan.');
}

public function editAdmin(User $user)
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    return view('user.edit-admin', compact('user'));
}
public function updateAdmin(Request $request, User $user)
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required'
    ]);

    $departemenMap = [
        'admin_ti' => 'TI',
        'admin_akuntansi' => 'AKUNTANSI',
        'admin_k3' => 'K3',
        'admin_rekayasapangan' => 'REKAYASA PANGAN',
        'admin_tika' => 'TIKA',
    ];

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'departemen' => $departemenMap[$request->role] ?? null,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'Data admin berhasil diperbarui.');
}

public function updateRole(Request $request, User $user)
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    $request->validate([
        'role' => 'required'
    ]);

    $user->update([
        'role' => $request->role
    ]);

    return back()->with('success','Role berhasil diubah.');
}
public function destroy(User $user)
{
    if (Auth::user()->role != 'super_admin') {
        abort(403);
    }

    if ($user->id == Auth::id()) {
        return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
    }

    $user->delete();

    return back()->with('success', 'Admin berhasil dihapus.');
}
}