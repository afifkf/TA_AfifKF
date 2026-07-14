<?php

namespace App\Http\Controllers;

use App\Models\Pinjam;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = Pinjam::with('produk')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $produk = Produk::where('stok', '>', 0)->get();

        return view('pengajuan.create', compact('produk'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Pinjam $pengajuan)
    {
        //
    }

    public function edit(Pinjam $pengajuan)
    {
        //
    }

    public function update(Request $request, Pinjam $pengajuan)
    {
        //
    }

    public function destroy(Pinjam $pengajuan)
    {
        //
    }
}