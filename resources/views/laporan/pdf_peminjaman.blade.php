<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>

    <style>

    body{
        font-family: DejaVu Sans;
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

    .kop-text{
        font-size:11px;
        line-height:1.2;
    }

    .kop-text .besar{
        font-size:15px;
        font-weight:bold;
    }

    .kop-text .sedang{
        font-size:12px;
        font-weight:bold;
    }

    hr{
        margin:2px 0;
    }

    h2{
        text-align:center;
        margin-top:18px;
        margin-bottom:5px;
        font-size:16px;
    }

    p.subtitle{
        text-align:center;
        margin-bottom:18px;
        font-size:11px;
    }

    th{
        background:#e5e7eb;
    }

    table.data{
        border-collapse:collapse;
    }

    table.data,
    table.data th,
    table.data td{
        border:1px solid black;
    }

    table.data th,
    table.data td{
        padding:6px;
    }

    </style>

</head>

<body>

<!-- ========================= -->
<!-- KOP SURAT -->
<!-- ========================= -->

<table class="kop">

<tr>

<td width="75">

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

<hr style="border:1px solid black;">
<hr style="border:0.5px solid black;">

<!-- ========================= -->
<!-- JUDUL -->
<!-- ========================= -->

<h2>
LAPORAN PEMINJAMAN BARANG LABORATORIUM
</h2>

<p class="subtitle">
Program Studi D3 Teknik Informatika PSDKU Madiun
</p>

<!-- ========================= -->
<!-- TABEL -->
<!-- ========================= -->

<table class="data">

<thead>

<tr>

<th width="5%">No</th>

<th width="14%">
Tanggal Pinjam
</th>

<th width="14%">
Batas Pengembalian
</th>

<th width="20%">
Nama Barang
</th>

<th width="18%">
Peminjam
</th>

<th width="16%">
WhatsApp
</th>

<th width="13%">
Status
</th>

</tr>

</thead>

<tbody>

@forelse($data as $item)

<tr>

<td align="center">
{{ $loop->iteration }}
</td>

<td align="center">
{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}
</td>

<td align="center">
{{ \Carbon\Carbon::parse($item->batas_kembali)->translatedFormat('d M Y') }}
</td>

<td>
{{ $item->produk->nama ?? '-' }}
</td>

<td>
{{ $item->nama_peminjam }}
</td>

<td>
{{ $item->no_whatsapp }}
</td>

<td align="center">

@if($item->status=='dipinjam')

Dipinjam

@elseif($item->status=='dikembalikan')

Dikembalikan

@elseif($item->status=='terlambat')

Terlambat

@elseif($item->status=='menunggu')

Menunggu

@elseif($item->status=='ditolak')

Ditolak

@else

{{ ucfirst($item->status) }}

@endif

</td>

</tr>

@empty

<tr>

<td colspan="7" align="center">
Tidak ada data peminjaman.
</td>

</tr>

@endforelse

</tbody>

</table>

<br><br>

<table style="border:none;">

<tr style="border:none;">

<td style="border:none;"></td>

<td
style="border:none;width:230px;text-align:center;">

Madiun,
{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

<br><br>

Mengetahui,

<br>

Laboran

<br><br><br><br>

(........................................)

</td>

</tr>

</table>

</body>
</html>