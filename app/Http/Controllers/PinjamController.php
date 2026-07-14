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
        'batas_kembali' => 'nullable|date',
    ]);

// ===========================
// JIKA MAHASISWA
// ===========================
if (Auth::user()->role == 'mahasiswa') {

    $pinjam = Pinjam::create([
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

Silakan login ke Sistem Informasi Laboratorium
untuk melakukan persetujuan.";

    Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send', [
        'target' => env('FONNTE_ADMIN'),
        'message' => $pesan,
    ]);

    try {
    Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN')
    ])->post('https://api.fonnte.com/send', [
        'target' => env('FONNTE_ADMIN'),
        'message' => $pesan,
    ]);
    } catch (\Exception $e) {
        \Log::error('Gagal kirim WA: ' . $e->getMessage());
    }

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

    $produk->stok -= $request->jumlah;
    $produk->save();

    Pinjam::create([
        'produk_id' => $request->produk_id,
        'user_id' => Auth::id(),
        'nama_peminjam' => $request->nama_peminjam,
        'nim' => $request->nim,
        'no_whatsapp' => $request->no_whatsapp,
        'jumlah' => $request->jumlah,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'batas_kembali' => $request->batas_kembali,
        'status' => 'dipinjam'
    ]);

    $detail = DetailBarang::where('produk_id', $request->produk_id)
        ->where('status', 'tersedia')
        ->limit($request->jumlah)
        ->get();

    foreach ($detail as $d) {
        $d->update([
            'status' => 'dipinjam'
        ]);
    }

    return redirect()->route('pinjam.index')
        ->with('success', 'Barang berhasil dipinjam');
}


public function setujui($id)
{
    // Hanya admin
    if (Auth::user()->role == 'mahasiswa') {
        abort(403);
    }

    $pinjam = Pinjam::findOrFail($id);

    if ($pinjam->status != 'menunggu') {
        return back()->with('error', 'Pengajuan sudah diproses.');
    }

    $produk = Produk::findOrFail($pinjam->produk_id);

    if ($produk->stok < $pinjam->jumlah) {
        return back()->with('error', 'Stok barang tidak mencukupi.');
    }

    $detailBarang = DetailBarang::where('produk_id', $pinjam->produk_id)
        ->where('status', 'tersedia')
        ->take($pinjam->jumlah)
        ->get();

    if ($detailBarang->count() < $pinjam->jumlah) {
        return back()->with('error', 'Detail barang tersedia tidak mencukupi.');
    }

    // Kurangi stok
    $produk->stok -= $pinjam->jumlah;
    $produk->save();

    // Ubah status detail barang
    foreach ($detailBarang as $barang) {
        $barang->update([
            'status' => 'dipinjam'
        ]);
    }

    // Update peminjaman
    $pinjam->update([
        'status' => 'dipinjam',
        'admin_id' => Auth::id(),
        'tanggal_disetujui' => now()
    ]);

    try {

    $pesan = "Halo {$pinjam->nama_peminjam},

Pengajuan peminjaman Anda telah DISETUJUI.

Barang :
{$pinjam->produk->nama}

Jumlah :
{$pinjam->jumlah}

Silakan datang ke laboratorium untuk mengambil barang.

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

    // ==========================
    // KIRIM WHATSAPP KE MAHASISWA
    // ==========================
    $pesan = "*PENGAJUAN DISETUJUI* ✅\n\n"
        ."Halo {$pinjam->nama_peminjam},\n\n"
        ."Pengajuan peminjaman Anda telah disetujui.\n\n"
        ."Barang : {$produk->nama}\n"
        ."Jumlah : {$pinjam->jumlah}\n"
        ."Tanggal Pinjam : {$pinjam->tanggal_pinjam}\n"
        ."Batas Kembali : {$pinjam->batas_kembali}\n\n"
        ."Silakan datang ke laboratorium untuk mengambil barang.\n\n"
        ."Terima kasih.";

    try {
        \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post('https://api.fonnte.com/send', [
            'target' => $pinjam->no_whatsapp,
            'message' => $pesan,
        ]);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }

    return back()->with('success', 'Pengajuan berhasil disetujui.');
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
    // Mahasiswa tidak boleh mengedit
    if (Auth::user()->role == 'mahasiswa') {
        abort(403, 'Anda tidak memiliki akses.');
    }

    // Validasi
    $request->validate([
        'produk_id' => 'required|exists:produks,id',
        'nama_peminjam' => 'required|string|max:255',
        'nim' => 'required|string|max:30',
        'no_whatsapp' => 'nullable|string|max:20',
        'jumlah' => 'required|integer|min:1',
        'tanggal_pinjam' => 'required|date',
        'batas_kembali' => 'nullable|date',
        'status' => 'required'
    ]);

    // ==========================
    // Cek hak akses berdasarkan departemen
    // ==========================

    $departemen = $pinjam->produk->departemen;

    if (
        (Auth::user()->role == 'admin_ti' && $departemen != 'TI') ||
        (Auth::user()->role == 'admin_akuntansi' && $departemen != 'AKUNTANSI') ||
        (Auth::user()->role == 'admin_k3' && $departemen != 'K3') ||
        (Auth::user()->role == 'admin_rekayasapangan' && $departemen != 'REKAYASA_PANGAN') ||
        (Auth::user()->role == 'admin_tika' && $departemen != 'TI&AI')
    ) {
        abort(403);
    }

    // ==========================
    // Jika barang dikembalikan
    // ==========================

    if (
        $request->status == 'dikembalikan' &&
        $pinjam->status != 'dikembalikan'
    ) {

        $produk = Produk::findOrFail($pinjam->produk_id);

        $produk->stok += $pinjam->jumlah;
        $produk->save();

        $detail = DetailBarang::where('produk_id', $pinjam->produk_id)
            ->where('status', 'dipinjam')
            ->limit($pinjam->jumlah)
            ->get();

        foreach ($detail as $d) {
            $d->update([
                'status' => 'tersedia'
            ]);
        }

        $pinjam->tanggal_dikembalikan = now();
    }

    // ==========================
    // Update data
    // ==========================

    $pinjam->fill([
        'produk_id' => $request->produk_id,
        'nama_peminjam' => $request->nama_peminjam,
        'nim' => $request->nim,
        'no_whatsapp' => $request->no_whatsapp,
        'jumlah' => $request->jumlah,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'batas_kembali' => $request->batas_kembali,
        'status' => $request->status,
    ]);

    $pinjam->save();

    return redirect()
        ->route('pinjam.index')
        ->with('success', 'Data peminjaman berhasil diperbarui.');
}


public function destroy(Pinjam $pinjam)
    {
        $pinjam->delete();

        return redirect()->route('pinjam.index');
    }
}