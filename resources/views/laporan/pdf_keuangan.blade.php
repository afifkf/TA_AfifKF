<h2 style="text-align:center;">Laporan Keuangan</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">

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

    @foreach($keuangans as $item)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>

        <td>{{ $item->perawatan->nama_barang }}</td>

        <td>Perawatan {{ $item->perawatan->nama_barang }}</td>

        <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>

    </tr>

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <td colspan="4"><b>Total Pengeluaran</b></td>

        <td>
            <b>Rp {{ number_format($totalPengeluaran,0,',','.') }}</b>
        </td>

    </tr>

    </tfoot>

</table>