<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailBarang;
use App\Models\Produk;
use App\Models\BarangRusak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
    $produk = Produk::query();

    if (Auth::user()->role == 'admin_ti') {
        $produk->where('departemen', 'TI');
    } elseif (Auth::user()->role == 'admin_akuntansi') {
        $produk->where('departemen', 'AKUNTANSI');
    } elseif (Auth::user()->role == 'admin_k3') {
        $produk->where('departemen', 'K3');
    } elseif (Auth::user()->role == 'admin_rekayasapangan') {
        $produk->where('departemen', 'REKAYASA_PANGAN');
    } elseif (Auth::user()->role == 'admin_tika') {
        $produk->where('departemen', 'TI&AI');
    }

    $produk = $produk->get();

    return view('detail_barang.create', compact('produk'));
}


    public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|exists:produks,id',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
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

    $gambar = null;

    if ($request->hasFile('gambar')) {

        $gambar = $request->file('gambar')
            ->store('detail-barang','public');

    }
    // =========================
    // SIMPAN
    // =========================
    DetailBarang::create([
        'produk_id' => $produk->id,
        'kode_barang' => $kodeBarang,
        'status' => 'tersedia',
        'gambar' => $gambar,
    ]);

    return redirect()->route('detail-barang.index')
        ->with('success', 'Kode barang: ' . $kodeBarang);
}


    public function show($id)
{
    $produk = Produk::query();

    if (Auth::user()->role == 'admin_ti') {
        $produk->where('departemen', 'TI');
    } elseif (Auth::user()->role == 'admin_akuntansi') {
        $produk->where('departemen', 'AKUNTANSI');
    } elseif (Auth::user()->role == 'admin_k3') {
        $produk->where('departemen', 'K3');
    } elseif (Auth::user()->role == 'admin_rekayasapangan') {
        $produk->where('departemen', 'REKAYASA_PANGAN');
    } elseif (Auth::user()->role == 'admin_tika') {
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
    $data = DetailBarang::with('produk')->findOrFail($id);

    $role = Auth::user()->role;

    // Batasi akses berdasarkan departemen
    if (
        ($role == 'admin_ti' && $data->produk->departemen != 'TI') ||
        ($role == 'admin_akuntansi' && $data->produk->departemen != 'AKUNTANSI') ||
        ($role == 'admin_k3' && $data->produk->departemen != 'K3') ||
        ($role == 'admin_rekayasapangan' && $data->produk->departemen != 'REKAYASA_PANGAN') ||
        ($role == 'admin_tika' && $data->produk->departemen != 'TI&AI')
    ) {
        abort(403);
    }

    $produk = Produk::query();

    if ($role == 'admin_ti') {
        $produk->where('departemen', 'TI');
    } elseif ($role == 'admin_akuntansi') {
        $produk->where('departemen', 'AKUNTANSI');
    } elseif ($role == 'admin_k3') {
        $produk->where('departemen', 'K3');
    } elseif ($role == 'admin_rekayasapangan') {
        $produk->where('departemen', 'REKAYASA_PANGAN');
    } elseif ($role == 'admin_tika') {
        $produk->where('departemen', 'TI&AI');
    }

    $produk = $produk->get();

    return view('detail_barang.edit', compact('data', 'produk'));
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'kode_barang' => 'required|string|max:255',
            'status' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $detailBarang = DetailBarang::findOrFail($id);

        // Tidak boleh mengubah menjadi rusak jika sedang dipinjam
        if ($request->status == 'rusak' && $detailBarang->status == 'dipinjam') {
            return back()->with('error', 'Barang sedang dipinjam.');
        }

        // Upload gambar baru
        $gambar = $detailBarang->gambar;

        if ($request->hasFile('gambar')) {

            if ($gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }

            $gambar = $request->file('gambar')
                ->store('detail-barang', 'public');
        }

        $detailBarang->update([
            'produk_id'   => $request->produk_id,
            'kode_barang' => $request->kode_barang,
            'status'      => $request->status,
            'gambar'      => $gambar,
        ]);

        // Tambahkan ke tabel barang rusak jika baru diubah menjadi rusak
        if (
            $request->status == 'rusak' &&
            !BarangRusak::where('detail_barang_id', $detailBarang->id)->exists()
        ) {

            BarangRusak::create([
                'detail_barang_id' => $detailBarang->id,
                'keterangan'       => $request->keterangan,
                'tanggal_rusak'    => now()
            ]);
        }

        return redirect()
            ->route('detail-barang.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
{
    $detailBarang = DetailBarang::findOrFail($id);

    // Tidak boleh menghapus barang yang sedang dipinjam
    if ($detailBarang->status == 'dipinjam') {
        return back()->with(
            'error',
            'Barang sedang dipinjam sehingga tidak dapat dihapus.'
        );
    }

    // Hapus gambar jika ada
    if (
        $detailBarang->gambar &&
        Storage::disk('public')->exists($detailBarang->gambar)
    ) {
        Storage::disk('public')->delete($detailBarang->gambar);
    }

    $detailBarang->delete();

    return back()->with('success', 'Data berhasil dihapus.');
}

}