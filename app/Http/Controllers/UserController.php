<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
public function index()
{
    $users = User::latest()->paginate(5);

    return view('user.index', compact('users'));
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
}