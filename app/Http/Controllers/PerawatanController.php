<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\BarangRusak;
use App\Models\DetailBarang;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerawatanController extends Controller
{
    public function index()
    {
    $query = Perawatan::with('barangRusak.detailBarang.produk');
    if (Auth::user()->role != 'super_admin') {

        $query->whereHas('barangRusak.detailBarang.produk', function ($q) {
            $role = Auth::user()->role;

            if ($role == 'admin_ti') {
                $q->where('departemen', 'TI');
            }

            elseif ($role == 'admin_akuntansi') {
                $q->where('departemen', 'AKUNTANSI');
            }

            elseif ($role == 'admin_k3') {
                $q->where('departemen', 'K3');
            }

            elseif ($role == 'admin_rekayasapangan') {
                $q->where('departemen', 'REKAYASA_PANGAN');
            }

            elseif ($role == 'admin_tika') {
                $q->where('departemen', 'TI&AI');
            }

    });

}

$perawatans = $query->latest()->paginate(10);

    return view('perawatan.index', compact('perawatans'));
}
        

public function create()
{
    $barangRusak = BarangRusak::with('detailBarang.produk')
        ->where('status', 'rusak');

    $role = Auth::user()->role;

    if ($role != 'super_admin') {

        $barangRusak->whereHas('detailBarang.produk', function ($q) use ($role) {

            if ($role == 'admin_ti') {
                $q->where('departemen', 'TI');
            } elseif ($role == 'admin_akuntansi') {
                $q->where('departemen', 'AKUNTANSI');
            } elseif ($role == 'admin_k3') {
                $q->where('departemen', 'K3');
            } elseif ($role == 'admin_rekayasapangan') {
                $q->where('departemen', 'REKAYASA_PANGAN');
            } elseif ($role == 'admin_tika') {
                $q->where('departemen', 'TI&AI');
            }

        });

    }

    $barangRusak = $barangRusak->get();

    return view('perawatan.create', compact('barangRusak'));
}

public function store(Request $request)
{
    $request->validate([
        'barang_rusak_id' => 'required',
        'tanggal' => 'required',
        'biaya' => 'required',
        'status' => 'required',
        'keterangan' => 'nullable',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $gambar = null;

    if ($request->hasFile('gambar')) {
        $gambar = $request->file('gambar')
            ->store('perawatan', 'public');
    }

    DB::transaction(function () use ($request, $gambar) {

        $barangRusak = BarangRusak::with('detailBarang.produk')
            ->findOrFail($request->barang_rusak_id);

        $perawatan = Perawatan::create([
            'barang_rusak_id' => $request->barang_rusak_id,
            'nama_barang' => $barangRusak->detailBarang->produk->nama ?? '-',
            'tanggal' => $request->tanggal,
            'biaya' => $request->biaya,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        Keuangan::create([
            'perawatan_id' => $perawatan->id,
            'tanggal' => $request->tanggal,
            'keterangan' => 'Perawatan: ' . ($barangRusak->detailBarang->produk->nama ?? '-'),
            'nominal' => $request->biaya
        ]);
    });

    return redirect()->route('perawatan.index')
        ->with('success', 'Perawatan & Keuangan berhasil dicatat');
}



public function edit(Perawatan $perawatan)
{
    $barangRusak = BarangRusak::with('detailBarang.produk');

    $role = Auth::user()->role;

    if ($role != 'super_admin') {

        $barangRusak->whereHas('detailBarang.produk', function ($q) use ($role) {

            if ($role == 'admin_ti') {
                $q->where('departemen', 'TI');
            } elseif ($role == 'admin_akuntansi') {
                $q->where('departemen', 'AKUNTANSI');
            } elseif ($role == 'admin_k3') {
                $q->where('departemen', 'K3');
            } elseif ($role == 'admin_rekayasapangan') {
                $q->where('departemen', 'REKAYASA_PANGAN');
            } elseif ($role == 'admin_tika') {
                $q->where('departemen', 'TI&AI');
            }

        });

    }

    $barangRusak = $barangRusak->get();

    return view('perawatan.edit', compact('perawatan', 'barangRusak'));
}


public function update(Request $request, Perawatan $perawatan)
{
    $request->validate([
        'barang_rusak_id' => 'required',
        'tanggal' => 'required|date',
        'biaya' => 'required|numeric',
        'status' => 'required',
        'keterangan' => 'nullable',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $gambar = $perawatan->gambar;

    if ($request->hasFile('gambar')) {

        if ($gambar && Storage::disk('public')->exists($gambar)) {
            Storage::disk('public')->delete($gambar);
        }

        $gambar = $request->file('gambar')
            ->store('perawatan', 'public');
    }

    DB::transaction(function () use ($request, $perawatan, $gambar) {

        $perawatan->update([
            'barang_rusak_id' => $request->barang_rusak_id,
            'tanggal' => $request->tanggal,
            'biaya' => $request->biaya,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        $keuangan = Keuangan::where('perawatan_id', $perawatan->id)->first();

        if ($keuangan) {

            $keuangan->update([
                'tanggal' => $request->tanggal,
                'nominal' => $request->biaya,
                'keterangan' => 'Perawatan'
            ]);

        }

        if ($request->status == 'selesai') {

            $barangRusak = BarangRusak::find($request->barang_rusak_id);

            if ($barangRusak) {

                $barangRusak->update([
                    'status' => 'selesai'
                ]);

                DetailBarang::where('id', $barangRusak->detail_barang_id)
                    ->update([
                        'status' => 'tersedia'
                    ]);
            }

        }

    });

    return redirect()
        ->route('perawatan.index')
        ->with('success', 'Perawatan berhasil diperbarui');
}

    public function destroy(Perawatan $perawatan)
{
    if (
        $perawatan->gambar &&
        Storage::disk('public')->exists($perawatan->gambar)
    ) {
        Storage::disk('public')->delete($perawatan->gambar);
    }

    $perawatan->delete();

    return redirect()
        ->route('perawatan.index')
        ->with('success', 'Data berhasil dihapus');
}
}