<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">
<title>Laporan Produk</title>

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
    margin:25px;
    line-height:1.4;
}

table{
    width:100%;
    border-collapse:collapse;
}

.kop td{
    border:none;
    vertical-align:middle;
}

.logo{
    width:65px;
}

.center{
    text-align:center;
}

.judul{
    text-align:center;
    margin:15px 0;
}

.judul h3{
    margin:0;
    font-size:16px;
}

.judul p{
    margin-top:4px;
    font-size:11px;
}

.data th,
.data td{
    border:1px solid black;
    padding:6px;
}

.data th{
    background:#f2f2f2;
    text-align:center;
}

</style>

</head>

@php

$kop = match ($departemen) {

    'TI' => public_path('images/kop_surat_D3TI.jpg'),

    'AKUNTANSI' => public_path('images/kop_surat_akn.jpg'),

    'K3' => public_path('images/kop_surat_k3.jpg'),

    'REKAYASA_PANGAN' => public_path('images/kop_surat_rp.jpg'),

    'TI&AI' => public_path('images/kop_surat_D4TI.jpg'),

    default => public_path('images/kop_surat_D3TI.jpg'),
};

@endphp
<body>

<div style="text-align:center; margin-bottom:15px;">

     <img src="{{ $kop }}" style="width:100%;">

</div>

<hr style="border:1px solid black;margin-bottom:2px;">
<hr style="border:0.5px solid black;">

<!-- ======================== -->
<!-- JUDUL -->
<!-- ======================== -->

<div class="judul">

<h3>
LAPORAN DATA PRODUK
</h3>

<p>

Tanggal Cetak :
{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

</p>

</div>

<!-- ======================== -->
<!-- TABEL -->
<!-- ======================== -->

<table class="data">

<thead>

<tr>

<th width="5%">No</th>
<th>Nama Produk</th>
<th>Deskripsi</th>
<th width="12%">Harga</th>
<th width="8%">Stok</th>
<th width="12%">Prodi</th>
<th width="12%">Jenis</th>

</tr>

</thead>

<tbody>

@forelse($produks as $produk)

<tr>

<td align="center">
{{ $loop->iteration }}
</td>

<td>
{{ $produk->nama }}
</td>

<td>
{{ $produk->deskripsi }}
</td>

<td align="right">
Rp {{ number_format($produk->harga,0,',','.') }}
</td>

<td align="center">
{{ $produk->stok }}
</td>

<td align="center">
{{ $produk->departemen }}
</td>

<td align="center">
{{ $produk->jenis }}
</td>

</tr>

@empty

<tr>

<td colspan="7" align="center">
Tidak ada data produk.
</td>

</tr>

@endforelse

</tbody>

</table>

<br><br>

<table style="border:none;">

<tr>

<td style="border:none;"></td>

<td style="border:none;text-align:center;width:250px;">

Madiun,

{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

<br><br>

Mengetahui,

<br>

Laboran

<br><br><br><br><br>

<b>
(........................................)
</b>

</td>

</tr>

</table>

</body>

</html>