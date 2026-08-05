<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<style>

body{
    font-family: DejaVu Sans;
    font-size:12px;
    margin:30px;
    line-height:1.5;
}

table{
    width:100%;
    border-collapse:collapse;
}

.kop td{
    border:none;
    vertical-align:top;
}

.logo{
    width:80px;
}

.center{
    text-align:center;
}

.judul{
    text-align:center;
    margin-top:20px;
    margin-bottom:20px;
}

.judul h3{
    margin:0;
    font-size:18px;
}

.judul p{
    margin:5px 0 0;
}

.data td{
    border:none;
    padding:3px;
    vertical-align:top;
}

.ttd td{
    border:none;
    text-align:center;
    padding-top:60px;
}

</style>

</head>
@php
    $path = public_path('images/kop_surat_D3TI.jpg');
    $kop = base64_encode(file_get_contents($path));
@endphp
<body>

<!-- ======================= -->
<!-- KOP SURAT -->
<!-- ======================= -->

<div style="text-align:center; margin-bottom:15px;">

    <img
        src="data:images/jpg;base64,{{ $kop }}"
        style="width:100%;">

</div>


<!-- ======================= -->
<!-- JUDUL -->
<!-- ======================= -->

<div class="judul">

<h3>
<u>SURAT PEMINJAMAN BARANG LABORATORIUM</u>
</h3>

<!-- <p>
Nomor :
{{ $pinjam->nomor_surat }}
</p> -->

</div>

<!-- ======================= -->
<!-- PEMBUKA -->
<!-- ======================= -->

<p align="justify">

Yang bertanda tangan di bawah ini mengajukan permohonan
peminjaman barang Laboratorium Program Studi D3 Teknik
Informatika PSDKU Madiun dengan data sebagai berikut:

</p>

<!-- ======================= -->
<!-- IDENTITAS -->
<!-- ======================= -->

<table class="data">

<tr>

<td width="180">
Nama
</td>

<td width="10">
:
</td>

<td>
{{ $pinjam->nama_peminjam }}
</td>

</tr>

<tr>

<td>
NIM
</td>

<td>
:
</td>

<td>
{{ $pinjam->nim }}
</td>

</tr>

<tr>

<td>
No. WhatsApp
</td>

<td>
:
</td>

<td>
{{ $pinjam->no_whatsapp }}
</td>

</tr>

<tr>

<td>
Nama Barang
</td>

<td>
:
</td>

<td>
{{ $pinjam->produk->nama }}
</td>

</tr>

<tr>

<td>
Jumlah Barang
</td>

<td>
:
</td>

<td>
{{ $pinjam->jumlah }}
</td>

</tr>

<tr>

<td>
Tanggal Peminjaman
</td>

<td>
:
</td>

<td>

{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d F Y H:i') }}

</td>

</tr>

<tr>

<td>
Batas Pengembalian
</td>

<td>
:
</td>

<td>

{{ \Carbon\Carbon::parse($pinjam->batas_kembali)->translatedFormat('d F Y H:i') }}

</td>

</tr>

</table>

<br>

<p align="justify">

Saya menyatakan bersedia menggunakan barang laboratorium
sesuai dengan ketentuan yang berlaku, menjaga kondisi barang
selama masa peminjaman, serta bertanggung jawab atas segala
kerusakan maupun kehilangan yang terjadi selama barang berada
dalam tanggung jawab saya.

Apabila terjadi kerusakan atau kehilangan, saya bersedia
memenuhi ketentuan pertanggungjawaban sesuai dengan peraturan
Laboratorium Program Studi D3 Teknik Informatika PSDKU Madiun.

</p>

<p align="justify">

Demikian surat peminjaman barang ini dibuat dengan sebenar-benarnya.
Atas perhatian dan persetujuan Bapak/Ibu kami ucapkan terima kasih.

</p>

<!-- ======================= -->
<!-- TTD -->
<!-- ======================= -->

<table class="ttd">

<tr>

<td width="50%">

Mengetahui,

<br>

Laboran

<br><br><br><br><br>

(........................................)

</td>

<td width="50%">

Madiun,

{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

<br>

Mahasiswa,

<br><br><br><br><br>

<b>
{{ $pinjam->nama_peminjam }}
</b>

</td>

</tr>

</table>

</body>

</html>