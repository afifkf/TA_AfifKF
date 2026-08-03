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

<!-- ======================= -->
<!-- KOP SURAT -->
<!-- ======================= -->

<div style="text-align:center; margin-bottom:15px;">

    <img src="{{ $kop }}" style="width:100%;">

</div>

<div class="garis1"></div>
<div class="garis2"></div>

<!-- ======================= -->
<!-- JUDUL -->
<!-- ======================= -->

<h2>
LAPORAN KEUANGAN
</h2>

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