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

        // FILTER ROLE PINJAM
        if ($role != 'super_admin') {

            $pinjamQuery->whereHas('produk', function ($q) use ($role) {

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

        // ======================
        // KEUANGAN
        // ======================
        $keuanganQuery = Keuangan::with('perawatan.barangRusak.detailBarang.produk');

        $keuanganQuery->when($dari, function ($q) use ($dari) {
            $q->whereDate('tanggal', '>=', $dari);
        });

        $keuanganQuery->when($sampai, function ($q) use ($sampai) {
            $q->whereDate('tanggal', '<=', $sampai);
        });

        if ($role != 'super_admin') {

    $keuanganQuery->whereHas('perawatan.barangRusak.detailBarang.produk', function ($q) use ($role) {

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
        

        // ======================
        // EXECUTE
        // ======================
        $data = $pinjamQuery->get();
        $keuangans = $keuanganQuery->get();

        return view('laporan.index', compact('data', 'keuangans'));
    }

    public function pdf(Request $request)
    {
        $query = Pinjam::with('produk');

        if ($request->dari && $request->sampai) {
            $query->whereBetween('tanggal_pinjam', [
                $request->dari,
                $request->sampai
            ]);
        }

        $data = $query->latest()->get();

        $pdf = Pdf::loadView('laporan.export_pdf', compact('data'));

        return $pdf->download('laporan-peminjaman.pdf');
    }
}