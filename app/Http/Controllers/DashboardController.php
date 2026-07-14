<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pinjam;
use App\Models\User;
use App\Models\DetailBarang;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role == 'mahasiswa') {

    $menunggu = Pinjam::where('user_id', Auth::id())
        ->where('status', 'menunggu')
        ->count();

    $dipinjam = Pinjam::where('user_id', Auth::id())
        ->whereIn('status', ['dipinjam', 'terlambat'])
        ->count();

    $dikembalikan = Pinjam::where('user_id', Auth::id())
        ->where('status', 'dikembalikan')
        ->count();

    $ditolak = Pinjam::where('user_id', Auth::id())
        ->where('status', 'ditolak')
        ->count();

    return view('dashboard-mahasiswa', compact(
        'menunggu',
        'dipinjam',
        'dikembalikan',
        'ditolak'
    ));
}

        $departemenMap = [
            'admin_ti' => 'TI',
            'admin_akuntansi' => 'AKUNTANSI',
            'admin_k3' => 'K3',
            'admin_rekayasapangan' => 'REKAYASA_PANGAN',
            'admin_tika' => 'TI&AI',
        ];

        $departemen = $departemenMap[$role] ?? null;

        // =========================
        // PRODUK
        // =========================
        $produkQuery = Produk::query();

        if ($role != 'super_admin' && $departemen) {
            $produkQuery->where('departemen', $departemen);
        }

        $totalProduk = $produkQuery->count();

        // =========================
        // BARANG TERSEDIA
        // =========================
        $barangTersediaQuery = DetailBarang::where('status', 'tersedia');

        if ($role != 'super_admin' && $departemen) {
            $barangTersediaQuery->whereHas('produk', function ($q) use ($departemen) {
                $q->where('departemen', $departemen);
            });
        }

        $barangTersedia = $barangTersediaQuery->count();

        // =========================
        // BARANG DIPINJAM
        // =========================
        $barangDipinjamQuery = DetailBarang::where('status', 'dipinjam');

        if ($role != 'super_admin' && $departemen) {
            $barangDipinjamQuery->whereHas('produk', function ($q) use ($departemen) {
                $q->where('departemen', $departemen);
            });
        }

        $barangDipinjam = $barangDipinjamQuery->count();

        // =========================
        // BARANG RUSAK
        // =========================
        $barangRusakQuery = DetailBarang::where('status', 'rusak');

        if ($role != 'super_admin' && $departemen) {
            $barangRusakQuery->whereHas('produk', function ($q) use ($departemen) {
                $q->where('departemen', $departemen);
            });
        }

        $barangRusak = $barangRusakQuery->count();

        // =========================
        // USER (global saja)
        // =========================
        $totalUser = User::count();

        // =========================
        // LIST PINJAM (dashboard recent)
        // =========================
        $pinjamQuery = Pinjam::with('produk', 'user','admin');

        if ($role != 'super_admin' && $departemen) {
            $pinjamQuery->whereHas('produk', function ($q) use ($departemen) {
                $q->where('departemen', $departemen);
            });
        }

        $pinjam = $pinjamQuery->latest()->take(4)->get();

        // =========================
        // LIST PRODUK (dashboard)
        // =========================
        $produkListQuery = Produk::query();

        if ($role != 'super_admin' && $departemen) {
            $produkListQuery->where('departemen', $departemen);
        }

        $produk = $produkListQuery->latest()->take(6)->get();

        return view('dashboard', compact(
            'totalProduk',
            'barangTersedia',
            'barangDipinjam',
            'barangRusak',
            'totalUser',
            'pinjam',
            'produk'
        ));
    }
}