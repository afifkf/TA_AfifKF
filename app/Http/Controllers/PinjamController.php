<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pinjam;
use App\Models\Produk;
use App\Models\User;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\BarangRusak;

class PinjamController extends Controller
{
        public function index(Request $request)
    {
        // update status terlambat
        Pinjam::where('status', 'dipinjam')
            ->whereNotNull('batas_kembali')
            ->where('batas_kembali', '<', Carbon::now())
            ->update(['status' => 'terlambat']);

        $query = Pinjam::with('produk', 'user','admin');

        // =========================
        // MAHASISWA HANYA MELIHAT MILIKNYA
        // =========================
        if (Auth::user()->role == 'mahasiswa') {
            $query->where('user_id', Auth::id());
        }

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
        elseif (Auth::user()->role == 'mahasiswa') {

    $query->whereHas('produk', function ($q) {

        $q->where(
            'departemen',
            Auth::user()->departemen
        );

    });

}


        // summary
$summary = Pinjam::query();

if (Auth::user()->role == 'mahasiswa') {
    $summary->where('user_id', Auth::id());
}

$totalDipinjam = (clone $summary)
    ->where('status', 'dipinjam')
    ->sum('jumlah');

$totalDikembalikan = (clone $summary)
    ->where('status', 'dikembalikan')
    ->sum('jumlah');

$totalTerlambat = (clone $summary)
    ->where('status', 'terlambat')
    ->sum('jumlah');

            // pagination
        $pinjam = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('pinjam.index', compact(
            'pinjam',
            'totalDipinjam',
            'totalDikembalikan',
            'totalTerlambat'
        ));
    }

public function create()
{

    $role = Auth::user()->role;

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
    elseif ($role == 'mahasiswa') {

    $produk->where(
        'departemen',
        Auth::user()->departemen
    );

}
    // super_admin melihat semua produk

    $produk = $produk->get();

    // Admin yang sedang login
    $user = User::where('id', Auth::id())->get();

    return view('pinjam.create', compact('produk', 'user'));
}

public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|exists:produks,id',
        'jumlah' => 'required|integer|min:1',
        'tanggal_pinjam' => 'required|date',
        'batas_kembali' => 'required|date|after:tanggal_pinjam',
    ]);

// ===========================
// JIKA MAHASISWA
// ===========================
if (Auth::user()->role == 'mahasiswa') {

$produk = Produk::findOrFail($request->produk_id);

if ($produk->departemen != Auth::user()->departemen) {

    abort(403);

}
            $last = Pinjam::max('id') + 1;           

        $pinjam = Pinjam::create([
        'nomor_surat' => 'LAB-' . date('Y') . '-' . str_pad(
            $last,
            5,
            '0',
            STR_PAD_LEFT
        ),
        'produk_id' => $request->produk_id,
        'user_id' => Auth::id(),
        'nama_peminjam' => Auth::user()->name,
        'nim' => Auth::user()->nim,
        'no_whatsapp' => Auth::user()->no_whatsapp,
        'jumlah' => $request->jumlah,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'batas_kembali' => $request->batas_kembali,
        'status' => 'menunggu'
    ]);

    // ===========================
    // KIRIM WHATSAPP KE ADMIN
    // ===========================

    $produk = Produk::find($request->produk_id);

    $pesan = "📢 PENGAJUAN PEMINJAMAN BARU

Nama : {$pinjam->nama_peminjam}
NIM : {$pinjam->nim}

Barang : {$produk->nama}

Jumlah : {$pinjam->jumlah}

Tanggal Pinjam : {$pinjam->tanggal_pinjam}

http://127.0.0.1:8000/
Silakan login ke Sistem Informasi Laboratorium
untuk melakukan persetujuan.";

    Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send', [
        'target' => env('FONNTE_ADMIN'),
        'message' => $pesan,
    ]);


    return redirect()->route('pinjam.index')
        ->with('success', 'Pengajuan berhasil dikirim.');
}
    // ===========================
// JIKA ADMIN
// ===========================

$produk = Produk::findOrFail($request->produk_id);

if ($produk->stok < $request->jumlah) {
    return back()->with('error', 'Stok tidak cukup');
}

$detailBarang = DetailBarang::where('produk_id', $request->produk_id)
    ->where('status', 'tersedia')
    ->limit($request->jumlah)
    ->get();

if ($detailBarang->count() != $request->jumlah) {
    return back()->with('error', 'Detail barang tidak mencukupi.');
}

$pinjam = Pinjam::create([
    'produk_id' => $request->produk_id,
    'user_id' => Auth::id(),
    'admin_id' => Auth::id(),
    'nama_peminjam' => $request->nama_peminjam,
    'nim' => $request->nim,
    'no_whatsapp' => $request->no_whatsapp,
    'jumlah' => $request->jumlah,
    'tanggal_pinjam' => $request->tanggal_pinjam,
    'batas_kembali' => $request->batas_kembali,
    'tanggal_disetujui' => now(),
    'status' => 'dipinjam'
]);

foreach ($detailBarang as $barang) {

    $barang->update([
        'status' => 'dipinjam'
    ]);

}

$pinjam->detailBarangs()->sync(
    $detailBarang->pluck('id')->toArray()
);

$produk->decrement('stok', $request->jumlah);

return redirect()
    ->route('pinjam.index')
    ->with('success', 'Barang berhasil dipinjam.');
}


public function pilihBarang($id)
{
    // Hanya admin
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $pinjam = Pinjam::with('produk')->findOrFail($id);

    // Pastikan status masih menunggu
    if ($pinjam->status != 'menunggu') {
        return redirect()->route('pinjam.index')
            ->with('error', 'Pengajuan sudah diproses.');
    }

    // Mahasiswa wajib upload bukti TTD
    if (!$pinjam->bukti_ttd) {
        return redirect()->route('pinjam.index')
            ->with('error', 'Mahasiswa belum mengunggah bukti yang telah ditandatangani.');
    }

    $detailBarang = DetailBarang::where('produk_id', $pinjam->produk_id)
    ->orderBy('kode_barang')
    ->get();

    return view('pinjam.pilih_barang', compact(
        'pinjam',
        'detailBarang'
    ));
}


public function setujui(Request $request, $id)
{
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $request->validate([
        'detail_barang_id' => 'required|array',
        'detail_barang_id.*' => 'exists:detail_barangs,id',
    ]);

    $pinjam = Pinjam::findOrFail($id);
    if (!$pinjam->bukti_ttd) {

    return back()->with(
        'error',
        'Mahasiswa belum mengupload surat yang telah ditandatangani.'
    );

}

    if ($pinjam->status != 'menunggu') {
        return back()->with('error', 'Pengajuan sudah diproses.');
    }

    if (count($request->detail_barang_id) != $pinjam->jumlah) {
        return back()->with(
            'error',
            'Jumlah barang yang dipilih harus '.$pinjam->jumlah
        );
    }

    $produk = Produk::findOrFail($pinjam->produk_id);

    if ($produk->stok < $pinjam->jumlah) {
        return back()->with('error','Stok tidak mencukupi.');
    }

    foreach ($request->detail_barang_id as $idBarang) {

        $barang = DetailBarang::findOrFail($idBarang);

        if ($barang->status != 'tersedia') {
            return back()->with(
                'error',
                'Ada barang yang sudah dipinjam.'
            );
        }

        $barang->update([
            'status' => 'dipinjam'
        ]);
    }

    $pinjam->detailBarangs()->sync(
        $request->detail_barang_id
    );

    $produk->decrement('stok', $pinjam->jumlah);

    $pinjam->update([
        'status' => 'dipinjam',
        'admin_id' => Auth::id(),
        'tanggal_disetujui' => now(),
    ]);

    try {

    $pesan = "✅ PENGAJUAN DISETUJUI

Halo {$pinjam->nama_peminjam},

Pengajuan peminjaman Anda telah disetujui.

Barang :
{$pinjam->produk->nama}

Jumlah :
{$pinjam->jumlah}

Tanggal Pinjam :
{$pinjam->tanggal_pinjam}

Batas Pengembalian :
{$pinjam->batas_kembali}

Silakan datang ke Laboratorium untuk mengambil barang.

Terima kasih.";

    Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send',[
        'target'=>$pinjam->no_whatsapp,
        'message'=>$pesan
    ]);

}catch(\Exception $e){
    \Log::error($e->getMessage());
}

    return redirect()
        ->route('pinjam.index')
        ->with('success','Peminjaman berhasil disetujui.');
}
public function tolak(Request $request, $id)
{
    // Hanya admin
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $request->validate([
        'alasan_penolakan' => 'required|string|max:500'
    ]);

    $pinjam = Pinjam::findOrFail($id);

    if ($pinjam->status != 'menunggu') {
        return back()->with('error', 'Pengajuan sudah diproses.');
    }

    $pinjam->update([
        'status' => 'ditolak',
        'admin_id' => Auth::id(),
        'alasan_penolakan' => $request->alasan_penolakan
    ]);

    // ==========================
    // Kirim WhatsApp
    // ==========================

    try {

        $pesan = "Halo {$pinjam->nama_peminjam},

Mohon maaf, pengajuan peminjaman Anda ditolak.

Barang :
{$pinjam->produk->nama}

Alasan :
{$request->alasan_penolakan}

Silakan melakukan pengajuan kembali apabila sudah memenuhi persyaratan.

Terima kasih.";

        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post('https://api.fonnte.com/send', [
            'target' => $pinjam->no_whatsapp,
            'message' => $pesan,
        ]);

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }

    return back()->with('success', 'Pengajuan berhasil ditolak.');
}

public function kembali(Request $request, $id)
{
    $pinjam = Pinjam::with('detailBarangs')->findOrFail($id);

    foreach ($pinjam->detailBarangs as $barang) {

    if ($request->kondisi[$barang->id] == 'baik') {

        $barang->update([
            'status' => 'tersedia'
        ]);

    } else {

        $barang->update([
            'status' => 'rusak'
        ]);

        BarangRusak::create([
            'detail_barang_id' => $barang->id,
            'tanggal_rusak' => now(),
            'keterangan' => $request->keterangan[$barang->id] ?? '',
            'status' => 'rusak'
        ]);

    }

}

    $pinjam->update([
        'status' => 'dikembalikan',
        'tanggal_dikembalikan' => now()
    ]);

    try {

    $pesan = "📦 PENGEMBALIAN BERHASIL

Halo {$pinjam->nama_peminjam},

Terima kasih.

Pengembalian barang laboratorium telah berhasil dicatat.

Barang :
{$pinjam->produk->nama}

Tanggal Pengembalian :
".now()->format('d-m-Y H:i')."

Terima kasih telah menjaga fasilitas laboratorium.";

    Http::withHeaders([
        'Authorization'=>env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send',[
        'target'=>$pinjam->no_whatsapp,
        'message'=>$pesan
    ]);

}catch(\Exception $e){
    \Log::error($e->getMessage());
}

    $produk = Produk::findOrFail($pinjam->produk_id);

    $produk->increment('stok', $pinjam->jumlah);

    $pinjam->detailBarangs()->detach();

    return redirect()
        ->route('pinjam.index')
        ->with('success','Barang berhasil dikembalikan.');
}

public function edit(Pinjam $pinjam)
{
    // Mahasiswa tidak boleh mengakses halaman edit
    if (Auth::user()->role == 'mahasiswa') {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $role = Auth::user()->role;

    $produk = Produk::query();

    if ($role == 'admin_ti') {
        $produk->where('departemen', 'TI');

        if ($pinjam->produk->departemen != 'TI') {
            abort(403);
        }

    } elseif ($role == 'admin_akuntansi') {
        $produk->where('departemen', 'AKUNTANSI');

        if ($pinjam->produk->departemen != 'AKUNTANSI') {
            abort(403);
        }

    } elseif ($role == 'admin_k3') {
        $produk->where('departemen', 'K3');

        if ($pinjam->produk->departemen != 'K3') {
            abort(403);
        }

    } elseif ($role == 'admin_rekayasapangan') {
        $produk->where('departemen', 'REKAYASA_PANGAN');

        if ($pinjam->produk->departemen != 'REKAYASA_PANGAN') {
            abort(403);
        }

    } elseif ($role == 'admin_tika') {
        $produk->where('departemen', 'TI&AI');

        if ($pinjam->produk->departemen != 'TI&AI') {
            abort(403);
        }
    }
    // super_admin bebas mengakses semua

    $produk = $produk->get();

    $user = User::where('id', Auth::id())->get();

    return view('pinjam.edit', compact('pinjam', 'produk', 'user'));
}

public function update(Request $request, Pinjam $pinjam)
{
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $request->validate([
        'status' => 'required'
    ]);

    if (
        $request->status == 'dikembalikan' &&
        in_array($pinjam->status, ['dipinjam','terlambat'])
    ) {

        $produk = Produk::findOrFail($pinjam->produk_id);

        $produk->increment('stok', $pinjam->jumlah);

        foreach ($pinjam->detailBarangs as $barang) {

            $barang->update([
                'status'=>'tersedia'
            ]);

        }

        $pinjam->detailBarangs()->detach();

        $pinjam->tanggal_dikembalikan = now();

    }

    $pinjam->status = $request->status;

    $pinjam->save();

    return redirect()
        ->route('pinjam.index')
        ->with('success','Status berhasil diperbarui.');
}

public function suratPdf(Pinjam $pinjam)
{
    if (
        Auth::id() != $pinjam->user_id &&
        Auth::user()->role == 'mahasiswa'
    ) {
        abort(403);
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'pinjam.surat_pdf',
        compact('pinjam')
    );

    return $pdf->stream(
        'Surat-Peminjaman-'.$pinjam->nomor_surat.'.pdf'
    );
}

public function formUploadBukti($id)
{
    $pinjam = Pinjam::findOrFail($id);

    if (Auth::id() != $pinjam->user_id) {
        abort(403);
    }

    return view('pinjam.upload_bukti', compact('pinjam'));
}

public function uploadBukti(Request $request, $id)
{
    $request->validate([
    'bukti_ttd' => 'required|mimes:pdf,jpg,jpeg,png|max:4096'
]);

    $pinjam = Pinjam::findOrFail($id);

    if (Auth::id() != $pinjam->user_id) {
        abort(403);
    }

    $file = $request->file('bukti_ttd')
        ->store('bukti_ttd', 'public');

    $pinjam->update([
        'bukti_ttd' => $file
    ]);

    return redirect()
        ->route('pinjam.index')
        ->with('success', 'Bukti berhasil diupload.');
}

public function detailBarang($id)
{
    // hanya admin
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $pinjam = Pinjam::with('produk')->findOrFail($id);

    $detailBarang = DetailBarang::where(
            'produk_id',
            $pinjam->produk_id
        )
        ->orderBy('kode_barang')
        ->get();

    return response()->json([
        'pinjam' => $pinjam,
        'detail_barang' => $detailBarang
    ]);
}

public function detailPeminjaman($id)
{
    if (Auth::user()->role == 'mahasiswa') {

        $pinjam = Pinjam::where('user_id', Auth::id())
            ->with('detailBarangs')
            ->findOrFail($id);

    } else {

        $pinjam = Pinjam::with('detailBarangs')
            ->findOrFail($id);

    }

    return response()->json([
        'detail_barang' => $pinjam->detailBarangs
    ]);
}

public function downloadBukti(Pinjam $pinjam)
{
    // Mahasiswa hanya boleh mengunduh bukti miliknya sendiri
    if (
        Auth::user()->role == 'mahasiswa' &&
        Auth::id() != $pinjam->user_id
    ) {
        abort(403);
    }

    // Pastikan file tersedia
    if (!$pinjam->bukti_ttd) {
        return back()->with(
            'error',
            'Bukti tanda tangan belum tersedia.'
        );
    }

    if (!Storage::disk('public')->exists($pinjam->bukti_ttd)) {
        return back()->with(
            'error',
            'File bukti tanda tangan tidak ditemukan.'
        );
    }

    return Storage::disk('public')->download(
        $pinjam->bukti_ttd,
        'Bukti-Tanda-Tangan-' . $pinjam->nomor_surat . '.' .
        pathinfo($pinjam->bukti_ttd, PATHINFO_EXTENSION)
    );
}

public function destroy(Pinjam $pinjam)
{
    
    // Tidak boleh menghapus peminjaman yang masih aktif
    if (in_array($pinjam->status, ['dipinjam', 'terlambat'])) {
        return back()->with(
            'error',
            'Data tidak dapat dihapus karena barang belum dikembalikan.'
        );
    }

    if ($pinjam->bukti_ttd) {

    Storage::disk('public')->delete(
        $pinjam->bukti_ttd
    );

}

    $pinjam->delete();

    return redirect()
        ->route('pinjam.index')
        ->with('success', 'Data peminjaman berhasil dihapus.');
}
}