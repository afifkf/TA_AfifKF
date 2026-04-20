<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pinjam;
use App\Models\Produk;
use App\Models\User;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function index()
    {
        // update status terlambat
        Pinjam::where('status','dipinjam')
            ->whereNotNull('batas_kembali')
            ->where('batas_kembali','<', Carbon::now())
            ->update(['status' => 'terlambat']);

        $query = Pinjam::with('produk','user');

        // =========================
        // ROLE FILTER
        // =========================
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

        // super_admin => tidak difilter

        $pinjam = $query->get();

        // summary tetap global atau bisa juga difilter kalau mau
        $totalDipinjam = Pinjam::where('status','dipinjam')->sum('jumlah');
        $totalDikembalikan = Pinjam::where('status','dikembalikan')->sum('jumlah');
        $totalTerlambat = Pinjam::where('status','terlambat')->sum('jumlah');

        return view('pinjam.index', compact(
            'pinjam',
            'totalDipinjam',
            'totalDikembalikan',
            'totalTerlambat'
        ));
    }

    public function create()
    {
        $produk = Produk::all();
        $user = User::all();

        return view('pinjam.create', compact('produk','user'));
    }

    public function store(Request $request)
    {
        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->jumlah) {
            return back()->with('error','Stok tidak cukup');
        }

        $produk->stok -= $request->jumlah;
        $produk->save();

        Pinjam::create([
            'produk_id' => $request->produk_id,
            'user_id' => auth()->id(),
            'nama_peminjam' => $request->nama_peminjam,
            'nim' => $request->nim,
            'no_whatsapp' => $request->no_whatsapp,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_kembali' => $request->batas_kembali,
            'status' => 'dipinjam'
        ]);

        $detail = DetailBarang::where('produk_id', $request->produk_id)
            ->where('status','tersedia')
            ->limit($request->jumlah)
            ->get();

        foreach($detail as $d) {
            $d->update(['status' => 'dipinjam']);
        }

        return redirect()->route('pinjam.index')
            ->with('success','Barang berhasil dipinjam');
    }

    public function kembali($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        $pinjam->update([
            'tanggal_dikembalikan' => now(),
            'status' => 'dikembalikan'
        ]);

        $produk = Produk::find($pinjam->produk_id);
        $produk->stok += $pinjam->jumlah;
        $produk->save();

        $detail = DetailBarang::where('produk_id', $pinjam->produk_id)
            ->where('status','dipinjam')
            ->limit($pinjam->jumlah)
            ->get();

        foreach($detail as $d) {
            $d->update(['status' => 'tersedia']);
        }

        return redirect()->route('pinjam.index')
            ->with('success','Barang dikembalikan');
    }

    public function edit(Pinjam $pinjam)
    {
        $produk = Produk::all();
        $user = User::all();

        return view('pinjam.edit', compact('pinjam','produk','user'));
    }

    public function update(Request $request, Pinjam $pinjam)
    {
        if($request->status == 'dikembalikan' && $pinjam->status != 'dikembalikan') {

            $produk = Produk::find($pinjam->produk_id);
            $produk->stok += $pinjam->jumlah;
            $produk->save();

            $detail = DetailBarang::where('produk_id', $pinjam->produk_id)
                ->where('status','dipinjam')
                ->limit($pinjam->jumlah)
                ->get();

            foreach($detail as $d) {
                $d->update(['status' => 'tersedia']);
            }

            $pinjam->tanggal_dikembalikan = now();
        }

        $pinjam->update($request->all());

        return redirect()->route('pinjam.index');
    }

    public function destroy(Pinjam $pinjam)
    {
        $pinjam->delete();

        return redirect()->route('pinjam.index');
    }
}