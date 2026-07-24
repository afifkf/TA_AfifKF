<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangRusak;



class BarangRusakController extends Controller
{
    public function index()
    {
        $data = BarangRusak::with('detailBarang.produk')->latest()->get();

        return view('barang_rusak.index',compact('data'));
    }

    public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required',
        'keterangan' => 'required'
    ]);

    BarangRusak::create($request->all());

    return redirect()->route('barang-rusak.index');
}

public function pertanggungjawaban(Request $request, BarangRusak $barangRusak)
{
    $request->validate([
        'jenis_pertanggungjawaban' => 'required|in:ganti_barang,ganti_uang',
        'status_pertanggungjawaban' => 'required|in:menunggu,proses,selesai',
        'nominal_ganti' => 'nullable|numeric|min:0',
        'keterangan_pertanggungjawaban' => 'nullable|string',
    ]);

    if (
        $request->jenis_pertanggungjawaban == 'ganti_uang'
        && !$request->nominal_ganti
    ) {
        return back()->withErrors([
            'nominal_ganti' =>
                'Nominal wajib diisi jika memilih ganti uang.'
        ]);
    }

    $barangRusak->update([
        'jenis_pertanggungjawaban' =>
            $request->jenis_pertanggungjawaban,

        'status_pertanggungjawaban' =>
            $request->status_pertanggungjawaban,

        'nominal_ganti' =>
            $request->jenis_pertanggungjawaban == 'ganti_uang'
                ? $request->nominal_ganti
                : null,

        'keterangan_pertanggungjawaban' =>
            $request->keterangan_pertanggungjawaban,
    ]);

    return back()->with(
        'success',
        'Data pertanggungjawaban berhasil diperbarui.'
    );
}
}
