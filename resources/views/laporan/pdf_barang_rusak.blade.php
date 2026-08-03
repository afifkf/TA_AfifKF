<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Laporan Barang Rusak</title>

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #000;
    padding:6px;
}

.kop td{
    border:none;
    padding:0;
    vertical-align:middle;
}

.logo{
    width:65px;
}

.center{
    text-align:center;
    line-height:1.2;
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

thead{
    background:#e5e5e5;
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

<!-- ======================== -->
<!-- KOP SURAT -->
<!-- ======================== -->

<div style="text-align:center; margin-bottom:15px;">

    <img src="{{ $kop }}" style="width:100%;">

</div>

<hr style="border:1px solid black;margin:4px 0 1px;">
<hr style="border:0.5px solid black;margin:0 0 15px;">

<!-- ======================== -->
<!-- JUDUL LAPORAN -->
<!-- ======================== -->

<div class="judul">

<h3>
LAPORAN BARANG RUSAK
</h3>

@php
    $programStudi = match ($departemen) {
        'TI' => 'Program Studi D3 Teknik Informatika PSDKU Madiun',
        'AKUNTANSI' => 'Program Studi D3 Akuntansi PSDKU Madiun',
        'K3' => 'Program Studi Sarjana Terapan Keselamatan dan Kesehatan Kerja PSDKU Madiun',
        'REKAYASA_PANGAN' => 'Program Studi Sarjana Terapan Teknologi Rekayasa Pangan PSDKU Madiun',
        'TI&AI' => 'Program Studi Sarjana Terapan Teknologi Informasi dan Kecerdasan Artifisial PSDKU Madiun',
        default => 'Program Studi PSDKU Madiun',
    };
@endphp

<p class="subtitle">
    {{ $programStudi }}
</p>

<p>

Tanggal Cetak :
{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

</p>

</div>

<!-- ======================== -->
<!-- TABEL -->
<!-- ======================== -->

<table>

<thead>

<tr>

<th width="5%">
No
</th>

<th width="27%">
Nama Barang
</th>

<th width="20%">
Kode Barang
</th>

<th width="15%">
Tanggal Rusak
</th>

<th width="18%">
Keterangan
</th>

<th width="15%">
Status
</th>

</tr>

</thead>

<tbody>

@forelse($barangRusaks as $item)

<tr>

<td align="center">
{{ $loop->iteration }}
</td>

<td>
{{ $item->detailBarang->produk->nama ?? '-' }}
</td>

<td align="center">
{{ $item->detailBarang->kode_barang ?? '-' }}
</td>

<td align="center">
{{ \Carbon\Carbon::parse($item->tanggal_rusak)->translatedFormat('d F Y') }}
</td>

<td>
{{ $item->keterangan ?? '-' }}
</td>

<td align="center">

@if($item->status == 'rusak')

Masih Rusak

@elseif($item->status == 'selesai')

Perbaikan Selesai

@else

{{ ucfirst($item->status) }}

@endif

</td>

</tr>

@empty

<tr>

<td colspan="6" align="center">

Tidak ada data barang rusak.

</td>

</tr>

@endforelse

</tbody>

</table>

</body>

</html>