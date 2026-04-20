<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\BarangRusak;
use App\Models\DetailBarang;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerawatanController extends Controller
{
    // =========================
    // INDEX
    // =========================
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
        

    // =========================
    // CREATE
    // =========================
    public function create()
    {
$barangRusak = BarangRusak::with('detailBarang.produk')
    ->where('status', 'rusak')
    ->get();
        return view('perawatan.create', compact('barangRusak'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
{
    $request->validate([
        'barang_rusak_id' => 'required',
        'tanggal' => 'required',
        'biaya' => 'required',
        'status' => 'required',
        'keterangan' => 'nullable'
    ]);

    DB::transaction(function () use ($request) {

        $barangRusak = BarangRusak::with('detailBarang.produk')
            ->findOrFail($request->barang_rusak_id);

        // 1. SIMPAN PERAWATAN
        $perawatan = Perawatan::create([
            'barang_rusak_id' => $request->barang_rusak_id,
            'nama_barang' => $barangRusak->detailBarang->produk->nama ?? '-',
            'tanggal' => $request->tanggal,
            'biaya' => $request->biaya,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        // 2. SIMPAN KEUANGAN (PENGELUARAN)
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

    // =========================
    // EDIT
    // =========================
    public function edit(Perawatan $perawatan)
    {
        $barangRusak = BarangRusak::with('detailBarang.produk')->get();

        return view('perawatan.edit', compact('perawatan', 'barangRusak'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, Perawatan $perawatan)
{
    $request->validate([
        'barang_rusak_id' => 'required',
        'tanggal' => 'required|date',
        'biaya' => 'required|numeric',
        'status' => 'required',
        'keterangan' => 'nullable'
    ]);

    DB::transaction(function () use ($request, $perawatan) {

        // =========================
        // UPDATE PERAWATAN
        // =========================
        $perawatan->update([
            'barang_rusak_id' => $request->barang_rusak_id,
            'tanggal' => $request->tanggal,
            'biaya' => $request->biaya,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // =========================
        // UPDATE KEUANGAN
        // =========================
        $keuangan = Keuangan::where('perawatan_id', $perawatan->id)->first();

        $keteranganKeuangan = 'Perawatan';

        if ($keuangan) {
            $keuangan->update([
                'tanggal' => $request->tanggal,
                'nominal' => $request->biaya,
                'keterangan' => $keteranganKeuangan,
            ]);
        }

        // =========================
        // JIKA SELESAI
        // =========================
        if ($request->status == 'selesai') {

            $barangRusak = BarangRusak::find($request->barang_rusak_id);

            if ($barangRusak) {

                // update status barang rusak
                $barangRusak->update([
                    'status' => 'selesai'
                ]);

                // kembalikan barang
                if ($barangRusak->detail_barang_id) {
                    DetailBarang::where('id', $barangRusak->detail_barang_id)
                        ->update([
                            'status' => 'tersedia'
                        ]);
                }
            }
        }

    });

    return redirect()
        ->route('perawatan.index')
        ->with('success', 'Perawatan & Keuangan berhasil diperbarui');
}

    // =========================
    // DELETE
    // =========================
    public function destroy(Perawatan $perawatan)
    {
        $perawatan->delete();

        return redirect()
            ->route('perawatan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}