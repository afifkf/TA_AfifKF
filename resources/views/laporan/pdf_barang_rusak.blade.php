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

<body>

<!-- ======================== -->
<!-- KOP SURAT -->
<!-- ======================== -->

<table class="kop">

<tr>

<td width="70">

<img
src="{{ public_path('logo-uns.png') }}"
class="logo">

</td>

<td class="center">

    @php
    $departemen = $pinjam->produk->departemen ?? 'TI';
    @endphp

    <div style="font-size:11px;">

    <b>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,</b><br>
    <b>RISET, DAN TEKNOLOGI</b><br>

    <span style="font-size:15px;">
    <b>UNIVERSITAS SEBELAS MARET</b>
    </span><br>

    <b>SEKOLAH VOKASI</b><br>

    @if($departemen == 'TI')

    <b>PROGRAM STUDI D3 TEKNIK INFORMATIKA (MADIUN)</b><br>

    Jl. Imam Bonjol No.103 Madiun 63128<br>

    Telepon (0351) 4486943<br>

    Website :
    https://prodi.vokasi.uns.ac.id/psdku-tekinfo/<br>

    Email :
    d3ti.vokasiuns@gmail.com

    @elseif($departemen == 'AKUNTANSI')

    <b>PROGRAM STUDI D3 AKUNTANSI (MADIUN)</b><br>

    Jl. Imam Bonjol No.103 Madiun 63128<br>

    Telepon : ........................................<br>

    Website : ........................................<br>

    Email : ........................................

    @elseif($departemen == 'K3')

    <b>PROGRAM STUDI D3 KESELAMATAN DAN KESEHATAN KERJA (MADIUN)</b><br>

    Jl. Imam Bonjol No.103 Madiun 63128<br>

    Telepon : ........................................<br>

    Website : ........................................<br>

    Email : ........................................

    @elseif($departemen == 'REKAYASA_PANGAN')

    <b>PROGRAM STUDI D3 REKAYASA PANGAN (MADIUN)</b><br>

    Jl. Imam Bonjol No.103 Madiun 63128<br>

    Telepon : ........................................<br>

    Website : ........................................<br>

    Email : ........................................

    @elseif($departemen == 'TI&AI')

    <b>PROGRAM STUDI D3 TEKNOLOGI INFORMASI DAN KECERDASAN ARTIFISIAL (MADIUN)</b><br>

    Jl. Imam Bonjol No.103 Madiun 63128<br>

    Telepon : ........................................<br>

    Website : ........................................<br>

    Email : ........................................

    @endif

    </div>

</td>

</tr>

</table>

<hr style="border:1px solid black;margin:4px 0 1px;">
<hr style="border:0.5px solid black;margin:0 0 15px;">

<!-- ======================== -->
<!-- JUDUL LAPORAN -->
<!-- ======================== -->

<div class="judul">

<h3>
LAPORAN BARANG RUSAK
</h3>

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