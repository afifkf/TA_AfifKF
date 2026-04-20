<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;use App\Models\Produk;
use App\Models\Pinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{

    public function index(Request $request)
{
$keyword = $request->input('search');

$query = Produk::withCount([
'detailBarang as stok_tersedia' => function($q){
$q->where('status','tersedia');
},

'detailBarang as stok_dipinjam' => function($q){
$q->where('status','dipinjam');
},

'detailBarang as stok_rusak' => function($q){
$q->where('status','rusak');
}

]);

// FILTER BERDASARKAN ROLE
if(Auth::user()->role == 'admin_ti'){
    $query->where('departemen','TI');
}

elseif(Auth::user()->role == 'admin_akuntansi'){
$query->where('departemen','AKUNTANSI');
}

elseif (Auth::user()->role == 'admin_k3') {
    $query->where('departemen', 'K3');
}

elseif (Auth::user()->role == 'admin_rekayasapangan') {
    $query->where('departemen', 'REKAYASA_PANGAN');
}

elseif (Auth::user()->role == 'admin_tika') {
    $query->where('departemen', 'TI&AI');
}


// SEARCH
if ($keyword) {
$query->where(function($q) use ($keyword){
$q->where('nama', 'like', "%{$keyword}%")
->orWhere('deskripsi', 'like', "%{$keyword}%");
});
}

$produks = $query->orderBy('created_at', 'desc')->paginate(10);

$produks->appends(['search' => $keyword]);

return view('produk.index', compact('produks', 'keyword'));
}


public function exportPdf()
{
    $produks = Produk::all();
    $pdf = PDF::loadView('produk.export_pdf', compact('produks'))->setPaper('a4', 'landscape');
    return $pdf->download('produk.pdf');
}




    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:1',
    ]);

    $data = $request->all();

    // otomatis berdasarkan role user
if(Auth::user()->role == 'admin_ti'){
            $data['departemen'] = 'TI';
    }

    elseif(Auth::user()->role == 'admin_akuntansi'){
        $data['departemen'] = 'AKUNTANSI';
    }

    // jika super admin pakai input dari form
    elseif(Auth::user()->role == 'super_admin'){
        $data['departemen'] = $request->departemen;
    }

    Produk::create($data);

    return redirect()->route('produk.index')
    ->with('success', 'Produk berhasil ditambahkan');
}

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }


}
