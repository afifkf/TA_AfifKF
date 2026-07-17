<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<style>

body{
    font-family: DejaVu Sans;
    font-size:13px;
}

table{
    width:100%;
    border-collapse:collapse;
}

.judul{
    text-align:center;
}

.garis{
    border-bottom:2px solid black;
    margin-bottom:15px;
}

.ttd{
    margin-top:60px;
}

</style>

</head>

<body>

<div class="judul">

<h3>
UNIVERSITAS SEBELAS MARET PSDKU MADIUN
</h3>

<h4>
PROGRAM STUDI D3 TEKNIK INFORMATIKA
</h4>

<h4>
LABORATORIUM KOMPUTER
</h4>

<h3>
SURAT PEMINJAMAN BARANG
</h3>

</div>

<div class="garis"></div>

<table>

<tr>

<td width="180">
Nomor Surat
</td>

<td>
: {{ $pinjam->nomor_surat }}
</td>

</tr>

<tr>

<td>
Nama
</td>

<td>
: {{ $pinjam->nama_peminjam }}
</td>

</tr>

<tr>

<td>
NIM
</td>

<td>
: {{ $pinjam->nim }}
</td>

</tr>

<tr>

<td>
No WhatsApp
</td>

<td>
: {{ $pinjam->no_whatsapp }}
</td>

</tr>

<tr>

<td>
Barang
</td>

<td>
: {{ $pinjam->produk->nama }}
</td>

</tr>

<tr>

<td>
Jumlah
</td>

<td>
: {{ $pinjam->jumlah }}
</td>

</tr>

<tr>

<td>
Tanggal Pinjam
</td>

<td>
: {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d F Y H:i') }}
</td>

</tr>

<tr>

<td>
Batas Kembali
</td>

<td>
: {{ \Carbon\Carbon::parse($pinjam->batas_kembali)->translatedFormat('d F Y') }}
</td>

</tr>

</table>

<br>

<p align="justify">

Saya yang bertanda tangan di bawah ini menyatakan
bersedia menjaga barang laboratorium yang dipinjam,
menggunakan sesuai peruntukannya,
serta bertanggung jawab apabila terjadi kerusakan
atau kehilangan sesuai ketentuan Laboratorium
Teknik Informatika PSDKU Madiun.

</p>

<table class="ttd">

<tr>

<td align="center">

Mahasiswa

<br><br><br><br>

( {{ $pinjam->nama_peminjam }} )

</td>

<td align="center">

Admin Laboratorium

<br><br><br><br>

(...........................)

</td>

</tr>

</table>

</body>

</html>