<h2 style="text-align:center;">Laporan Peminjaman</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">

    <thead>
    <tr>
        <th>No</th>
        <th>Tanggal Pinjam</th>
        <th>Batas Pengembalian</th>
        <th>Produk</th>
        <th>Peminjam</th>
        <th>WhatsApp</th>
        <th>Status</th>
    </tr>
    </thead>

    <tbody>

    @foreach($data as $item)

    <tr>
        <td>{{ $loop->iteration }}</td>

        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>

        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}</td>

        <td>{{ $item->produk->nama ?? '-' }}</td>

        <td>{{ $item->nama_peminjam }}</td>

        <td>{{ $item->no_whatsapp }}</td>

        <td>{{ $item->status }}</td>

    </tr>

    @endforeach

    </tbody>

</table>