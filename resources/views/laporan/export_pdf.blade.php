<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px;}
        th, td { border: 1px solid black; padding: 8px; text-align: left;}
        th { background: #eee; }
    </style>
</head>
<body>

<h2>LAPORAN PEMINJAMAN</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Peminjam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pinjam as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
     
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}

            </td>
            <td>{{ $p->produk->nama ?? '-' }}</td>
            <td>{{ $p->nama_peminjam }}</td>
            <td>{{ $p->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


<h2>LAPORAN KEUANGAN</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($keuangan as $i => $k)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}

            </td>
            <td>{{ $k->perawatan->nama_barang ?? '-' }}</td>
            <td>{{ $k->keterangan }}</td>
            <td>Rp {{ number_format($k->nominal) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Total Pengeluaran: Rp {{ number_format($total) }}</h3>

</body>
</html>