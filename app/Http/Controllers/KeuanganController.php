<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    public function index()
{
    $role = Auth::user()->role;

    $query = Keuangan::with('perawatan.barangRusak.detailBarang.produk');

    // =========================
    // ROLE FILTER
    // =========================
    if ($role != 'super_admin') {

        $query->whereHas('perawatan.barangRusak.detailBarang.produk', function ($q) use ($role) {

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

    // =========================
    // DATA
    // =========================
    $keuangans = (clone $query)->latest()->paginate(5);

    $totalPengeluaran = (clone $query)->sum('nominal');

    return view('keuangan.index', compact('keuangans', 'totalPengeluaran'));
}

    public function create()
    {
        $perawatans = Perawatan::all();

        return view('keuangan.create', compact('perawatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perawatan_id' => 'required',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable'
        ]);

        Keuangan::create([
            'perawatan_id' => $request->perawatan_id,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
    }

    public function edit(Keuangan $keuangan)
    {
        $perawatans = Perawatan::all();

        return view('keuangan.edit', compact('keuangan', 'perawatans'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $request->validate([
            'perawatan_id' => 'required',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable'
        ]);

        $keuangan->update($request->all());

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil diupdate');
    }

    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus');
    }
}