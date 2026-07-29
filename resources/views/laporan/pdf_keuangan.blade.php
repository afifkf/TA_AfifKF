<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>

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
            width:60px;
        }

        .center{
            text-align:center;
        }

        .header-text{
            line-height:1.2;
        }

        .header-text .kecil{
            font-size:10px;
        }

        .header-text .sedang{
            font-size:12px;
            font-weight:bold;
        }

        .header-text .besar{
            font-size:16px;
            font-weight:bold;
        }

        .garis1{
            border:1px solid #000;
            margin-top:8px;
            margin-bottom:1px;
        }

        .garis2{
            border:0.5px solid #000;
            margin-bottom:18px;
        }

        h2{
            text-align:center;
            margin:0;
            margin-bottom:5px;
            font-size:16px;
        }

        .periode{
            text-align:center;
            margin-bottom:18px;
            font-size:11px;
        }

        th, td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#f2f2f2;
            text-align:center;
        }

        tfoot td{
            font-weight:bold;
        }

    </style>

</head>

<body>

<!-- ======================= -->
<!-- KOP SURAT -->
<!-- ======================= -->

<table class="kop">

<tr>

<td width="70">

<img src="{{ public_path('logo-uns.png') }}" class="logo">

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

<div class="garis1"></div>
<div class="garis2"></div>

<!-- ======================= -->
<!-- JUDUL -->
<!-- ======================= -->

<h2>
LAPORAN KEUANGAN
</h2>

<div class="periode">
Sistem Informasi Laboratorium<br>
Program Studi D3 Teknik Informatika PSDKU Madiun
</div>

<!-- ======================= -->
<!-- TABEL -->
<!-- ======================= -->

<table>

<thead>

<tr>
    <th width="40">No</th>
    <th width="90">Tanggal</th>
    <th>Barang</th>
    <th>Keterangan</th>
    <th width="120">Nominal</th>
</tr>

</thead>

<tbody>

@forelse($keuangans as $item)

<tr>

<td align="center">
{{ $loop->iteration }}
</td>

<td align="center">
{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
</td>

<td>
{{ $item->perawatan->nama_barang }}
</td>

<td>
Perawatan {{ $item->perawatan->nama_barang }}
</td>

<td align="right">
Rp {{ number_format($item->nominal,0,',','.') }}
</td>

</tr>

@empty

<tr>

<td colspan="5" align="center">
Tidak ada data keuangan.
</td>

</tr>

@endforelse

</tbody>

<tfoot>

<tr>

<td colspan="4" align="center">
<b>Total Pengeluaran</b>
</td>

<td align="right">
<b>
Rp {{ number_format($totalPengeluaran,0,',','.') }}
</b>
</td>

</tr>

</tfoot>

</table>

<br><br>

<table style="width:100%; border:none;">

<tr style="border:none;">

<td style="border:none;"></td>

<td style="border:none; width:250px; text-align:center;">

Madiun,

{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

<br><br>

Mengetahui,

<br>

Laboran

<br><br><br><br><br>

(........................................)

</td>

</tr>

</table>

</body>
</html>