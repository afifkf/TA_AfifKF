<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang Rusak</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table,th,td{
            border:1px solid #000;
        }

        th,td{
            padding:6px;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }
    </style>

</head>

<body>

<h2>Laporan Barang Rusak</h2>

<table>

    <thead>

    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Kode Barang</th>
        <th>Tanggal Rusak</th>
        <th>Keterangan</th>
        <th>Status</th>
    </tr>

    </thead>

    <tbody>

    @forelse($barangRusaks as $item)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>{{ $item->detailBarang->produk->nama ?? '-' }}</td>

        <td>{{ $item->detailBarang->kode_barang ?? '-' }}</td>

        <td>
            {{ \Carbon\Carbon::parse($item->tanggal_rusak)->translatedFormat('d F Y') }}
        </td>

        <td>{{ $item->keterangan }}</td>

        <td>
            @if($item->status == 'rusak')
                Masih Rusak
            @elseif($item->status == 'selesai')
                Perbaikan Selesai
            @else
                {{ $item->status }}
            @endif
        </td>

    </tr>

    @empty

    <tr>
        <td colspan="6" align="center">
            Tidak ada data
        </td>
    </tr>

    @endforelse

    </tbody>

</table>

</body>

</html>