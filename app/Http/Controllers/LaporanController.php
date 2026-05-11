<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Pinjam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $dari = $request->dari;
    $sampai = $request->sampai;

    $role = Auth::user()->role;

    // ======================
    // PEMINJAMAN
    // ======================
    $pinjamQuery = Pinjam::with('produk');

    $pinjamQuery->when($dari, function ($q) use ($dari) {
        $q->whereDate('tanggal_pinjam', '>=', $dari);
    });

    $pinjamQuery->when($sampai, function ($q) use ($sampai) {
        $q->whereDate('tanggal_pinjam', '<=', $sampai);
    });

    if ($role != 'super_admin') {
        $pinjamQuery->whereHas('produk', function ($q) use ($role) {

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

    // ======================
    // KEUANGAN
    // ======================
    $keuanganQuery = Keuangan::with('perawatan');

    $keuanganQuery->when($dari, function ($q) use ($dari) {
        $q->whereDate('tanggal', '>=', $dari);
    });

    $keuanganQuery->when($sampai, function ($q) use ($sampai) {
        $q->whereDate('tanggal', '<=', $sampai);
    });

    if ($role != 'super_admin') {
        $keuanganQuery->whereHas('perawatan', function ($q) use ($role) {

            $q->whereHas('barangRusak.detailBarang.produk', function ($qq) use ($role) {

                if ($role == 'admin_ti') {
                    $qq->where('departemen', 'TI');
                } elseif ($role == 'admin_akuntansi') {
                    $qq->where('departemen', 'AKUNTANSI');
                } elseif ($role == 'admin_k3') {
                    $qq->where('departemen', 'K3');
                } elseif ($role == 'admin_rekayasapangan') {
                    $qq->where('departemen', 'REKAYASA_PANGAN');
                } elseif ($role == 'admin_tika') {
                    $qq->where('departemen', 'TI&AI');
                }

            });

        });
    }

    // ======================
    // EXECUTE (INI YANG BENAR)
    // ======================
    $data = $pinjamQuery->get();
    $keuangans = $keuanganQuery->get();
    $totalPengeluaran = $keuanganQuery->sum('nominal'); // PINDAH KE SINI

    return view('laporan.index', compact(
        'data',
        'keuangans',
        'totalPengeluaran'
    ));
}

    public function pdf(Request $request)
{
    $dari = $request->dari;
    $sampai = $request->sampai;

    // ======================
    // PEMINJAMAN
    // ======================
    $pinjam = Pinjam::with('produk')
        ->when($dari, fn($q) => $q->whereDate('tanggal_pinjam', '>=', $dari))
        ->when($sampai, fn($q) => $q->whereDate('tanggal_pinjam', '<=', $sampai))
        ->get();

    // ======================
    // KEUANGAN
    // ======================
    $keuangan = Keuangan::with('perawatan')
        ->when($dari, fn($q) => $q->whereDate('tanggal', '>=', $dari))
        ->when($sampai, fn($q) => $q->whereDate('tanggal', '<=', $sampai))
        ->get();

    // ======================
    // TOTAL
    // ======================
    $total = $keuangan->sum('nominal');

    $pdf = Pdf::loadView('laporan.export_pdf', compact(
        'pinjam',
        'keuangan',
        'total',
        'dari',
        'sampai'
    ));

    return $pdf->download('laporan.pdf');
}
}