<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Pinjam;
use App\Models\BarangRusak;
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
    // BARANG RUSAK
    // ======================
$barangRusakQuery = BarangRusak::with([
    'detailBarang.produk',
    'pinjam.user'
]);
    $barangRusakQuery->when($dari, function ($q) use ($dari) {
        $q->whereDate('tanggal_rusak', '>=', $dari);
    });

    $barangRusakQuery->when($sampai, function ($q) use ($sampai) {
        $q->whereDate('tanggal_rusak', '<=', $sampai);
    });

    if ($role != 'super_admin') {

        $barangRusakQuery->whereHas('detailBarang.produk', function ($q) use ($role) {

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
    // PAGINATION
    // ======================

    $data = $pinjamQuery
        ->latest('tanggal_pinjam')
        ->paginate(5, ['*'], 'pinjam_page')
        ->withQueryString();

    $keuangans = $keuanganQuery
        ->latest('tanggal')
        ->paginate(5, ['*'], 'keuangan_page')
        ->withQueryString();

    $barangRusaks = $barangRusakQuery
        ->latest('tanggal_rusak')
        ->paginate(5, ['*'], 'rusak_page')
        ->withQueryString();

    $totalPengeluaran = (clone $keuanganQuery)->sum('nominal');

    return view('laporan.index', compact(
        'data',
        'keuangans',
        'barangRusaks',
        'totalPengeluaran'
    ));
}

public function pdfPeminjaman(Request $request)
{
    $role = Auth::user()->role;

    $query = Pinjam::with('produk')
        ->orderBy('tanggal_pinjam', 'desc');

    if ($role != 'super_admin') {

        $query->whereHas('produk', function ($q) use ($role) {

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

    $data = $query->get();

    $pdf = Pdf::loadView('laporan.pdf_peminjaman', compact('data'));

    return $pdf->download('laporan-peminjaman.pdf');
}


public function pdfKeuangan(Request $request)
{
    $role = Auth::user()->role;

    $query = Keuangan::with('perawatan')
        ->orderBy('tanggal', 'desc');

    if ($role != 'super_admin') {

        $query->whereHas('perawatan', function ($q) use ($role) {

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

    $keuangans = $query->get();
    $totalPengeluaran = $keuangans->sum('nominal');

    $pdf = Pdf::loadView(
        'laporan.pdf_keuangan',
        compact('keuangans', 'totalPengeluaran')
    );

    return $pdf->download('laporan-keuangan.pdf');
}


public function pdfBarangRusak(Request $request)
{
    $role = Auth::user()->role;

    $query = BarangRusak::with([
    'detailBarang.produk',
    'pinjam.user',
])
->orderBy('tanggal_rusak', 'desc');

    if ($role != 'super_admin') {

        $query->whereHas('detailBarang.produk', function ($q) use ($role) {

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

    $barangRusaks = $query->get();

    $pdf = Pdf::loadView(
        'laporan.pdf_barang_rusak',
        compact('barangRusaks')
    );

    return $pdf->download('laporan-barang-rusak.pdf');
}

public function updatePertanggungjawaban(Request $request, $id)
{
    $request->validate([
        'jenis_pertanggungjawaban' => 'required|in:ganti_barang,ganti_uang',
        'status_pertanggungjawaban' => 'required|in:menunggu,proses,selesai',
        'nominal_ganti' => 'nullable|numeric|min:0',
        'keterangan_pertanggungjawaban' => 'nullable|string',
    ]);

    $barangRusak = BarangRusak::findOrFail($id);

    // Jika jenisnya ganti uang, nominal wajib diisi
    if (
        $request->jenis_pertanggungjawaban == 'ganti_uang'
        && !$request->nominal_ganti
    ) {
        return back()
            ->withErrors([
                'nominal_ganti' => 'Nominal ganti uang wajib diisi.'
            ])
            ->withInput();
    }

    // Jika ganti barang, nominal dibuat null
    $nominal = $request->jenis_pertanggungjawaban == 'ganti_uang'
        ? $request->nominal_ganti
        : null;

    $barangRusak->update([
        'jenis_pertanggungjawaban' => $request->jenis_pertanggungjawaban,
        'status_pertanggungjawaban' => $request->status_pertanggungjawaban,
        'nominal_ganti' => $nominal,
        'keterangan_pertanggungjawaban' =>
            $request->keterangan_pertanggungjawaban,
    ]);

    return back()->with(
        'success',
        'Data pertanggungjawaban berhasil diperbarui.'
    );
}
    
}