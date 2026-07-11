<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailBarang;
use App\Models\Produk;
use App\Models\BarangRusak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DetailBarangController extends Controller
{

    public function index()
{
    $query = DetailBarang::with('produk');

    // Filter berdasarkan role admin
    if (Auth::user()->role == 'admin_ti') {
        $query->whereHas('produk', function ($q) {
            $q->where('departemen', 'TI');
        });
    }

    elseif (Auth::user()->role == 'admin_akuntansi') {
        $query->whereHas('produk', function ($q) {
            $q->where('departemen', 'AKUNTANSI');
        });
    }

    elseif (Auth::user()->role == 'admin_k3') {
        $query->whereHas('produk', function ($q) {
            $q->where('departemen', 'K3');
        });
    }

    elseif (Auth::user()->role == 'admin_rekayasapangan') {
        $query->whereHas('produk', function ($q) {
            $q->where('departemen', 'REKAYASA_PANGAN');
        });
    }

    elseif (Auth::user()->role == 'admin_tika') {
        $query->whereHas('produk', function ($q) {
            $q->where('departemen', 'TI&AI');
        });
    }

    $data = $query
        ->latest()
        ->paginate(10);

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
        'produk_id' => 'required'
    ]);

    $produk = Produk::findOrFail($request->produk_id);

    // =========================
    // CEK BATAS STOK
    // =========================
    $totalDetail = DetailBarang::where('produk_id', $request->produk_id)->count();

    if ($totalDetail >= $produk->stok) {
        return back()->with('error', 'Jumlah detail barang tidak boleh melebihi stok');
    }

    // =========================
    // PREFIX
    // =========================
    if ($produk->jenis == 'Barang Habis Pakai') {
        $prefix = 'BHP';
    } elseif ($produk->jenis == 'Inventaris') {
        $prefix = 'INV';
    } else {
        $prefix = '-';
    }

    // =========================
    // FORMAT NAMA PRODUK (AMAN)
    // =========================
    $namaProduk = Str::slug($produk->nama); 
    // contoh: "Laptop Asus" → "laptop-asus"

    // =========================
    // NOMOR URUT PER PRODUK
    // =========================
    $lastNumber = DetailBarang::where('produk_id', $produk->id)->count() + 1;

    // =========================
    // FORMAT FINAL KODE
    // =========================
    $kodeBarang = strtoupper($prefix . '-' . $namaProduk . '-' . str_pad($lastNumber, 3, '0', STR_PAD_LEFT));

    // =========================
    // SIMPAN
    // =========================
    DetailBarang::create([
        'produk_id' => $produk->id,
        'kode_barang' => $kodeBarang,
        'status' => 'tersedia'
    ]);

    return redirect()->route('detail-barang.index')
        ->with('success', 'Kode barang: ' . $kodeBarang);
}


    public function show($id)
{
    $produk = Produk::query();

    // Filter berdasarkan role
    if (Auth::user()->role == 'admin_ti') {
        $produk->where('departemen', 'TI');
    }

    elseif (Auth::user()->role == 'admin_akuntansi') {
        $produk->where('departemen', 'AKUNTANSI');
    }

    elseif (Auth::user()->role == 'admin_k3') {
        $produk->where('departemen', 'K3');
    }

    elseif (Auth::user()->role == 'admin_rekayasapangan') {
        $produk->where('departemen', 'REKAYASA_PANGAN');
    }

    elseif (Auth::user()->role == 'admin_tika') {
        $produk->where('departemen', 'TI&AI');
    }

    // super_admin tidak difilter

    $produk = $produk->findOrFail($id);

    $data = DetailBarang::where('produk_id', $produk->id)
        ->latest()
        ->paginate(10);

    return view('detail_barang.show', compact('produk', 'data'));
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