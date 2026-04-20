<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailBarang;
use App\Models\Produk;
use App\Models\BarangRusak;
use Illuminate\Support\Facades\Auth;

class DetailBarangController extends Controller
{

    public function index()
    {
        $data = DetailBarang::with('produk')->latest()->get();

        return view('detail_barang.index', compact('data'));
        
    }


    public function create()
    {
        $produk = Produk::all();

        return view('detail_barang.create', compact('produk'));
    }


    public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required',
        'kode_barang' => 'required|unique:detail_barangs,kode_barang'
    ],[
        'kode_barang.unique' => 'Kode barang sudah digunakan'
    ]);

    $produk = Produk::find($request->produk_id);

    $totalDetail = DetailBarang::where('produk_id',$request->produk_id)->count();

    if($totalDetail >= $produk->stok)
    {
        return back()->with('error','Jumlah detail barang tidak boleh melebihi stok');
    }

    DetailBarang::create([
        'produk_id' => $request->produk_id,
        'kode_barang' => $request->kode_barang,
        'status' => 'tersedia'
    ]);

    return redirect()->route('detail-barang.index')
    ->with('success','Detail barang berhasil ditambahkan');
}


    public function show($id)
    {
        $produk = Produk::findOrFail($id);

        $data = DetailBarang::where('produk_id',$id)->get();

        return view('detail_barang.show',
        compact('produk','data'));
    }


    public function edit($id)
    {
        $data = DetailBarang::findOrFail($id);

        $produk = Produk::all();

        return view('detail_barang.edit',
        compact('data','produk'));
    }


    public function update(Request $request, $id)
    {
    $data = DetailBarang::find($id);

    if($request->status == 'rusak' && $data->status == 'dipinjam'){
    return back()->with('error','Barang sedang dipinjam');
    }

    $data->update([
    'produk_id' => $request->produk_id,
    'kode_barang' => $request->kode_barang,
    'status' => $request->status
    ]);

    if($request->status == 'rusak'){

    BarangRusak::create([
    'detail_barang_id' => $data->id,
    'keterangan' => $request->keterangan,
    'tanggal_rusak' => now()
    ]);

    }

    return redirect()->route('detail-barang.index')
    ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = DetailBarang::findOrFail($id);

        // Tidak boleh hapus jika sedang dipinjam
        if($data->status == 'dipinjam')
        {
            return back()->with('error','Barang sedang dipinjam, tidak bisa dihapus');
        }

        $data->delete();

        return back()->with('success','Data berhasil dihapus');
    }

}