@extends('layouts.app')

@section('content')
<!-- <pre>{{ get_class($pinjam) }}</pre> -->
<div class="bg-white shadow rounded-2xl p-6">

    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Riwayat Peminjaman</h1>

        @if(Auth::user()->role != 'mahasiswa')

        <a href="{{ route('pinjam.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Tambah Peminjaman
        </a>

        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">No</th>
                    <th class="p-3 border text-left">Barang</th>
                    <th class="p-3 border text-left">Admin</th>
                    <th class="p-3 border text-left">Nama Peminjam</th>
                    <th class="p-3 border text-left">NIM</th>
                    <th class="p-3 border text-left">No WhatsApp</th>
                    <th class="p-3 border text-left">Jumlah</th>
                    <th class="p-3 border text-left">Tanggal Peminjaman</th>
                    <th class="p-3 border text-left">Status</th>
                    <th class="p-3 border text-left">Alasan</th>                   
                        <th class="p-3 border text-center">
                        Aksi
                        </th>
                </tr>
            </thead>

            <tbody>

                @forelse($pinjam as $p)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 border text center">
                        {{ $pinjam->firstItem() + $loop->index }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->produk->nama }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->admin?->name ?? '-' }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->nama_peminjam }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->nim }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->no_whatsapp }}
                    </td>

                    <td class="p-3 border">
                        {{ $p->jumlah }}
                    </td>

                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y H:i') }}
                    </td>

                    <td class="p-3 border">

                        <span class="px-2 py-1 rounded text-white

                        @if($p->status == 'menunggu')
                            bg-yellow-500
                        @elseif($p->status == 'dipinjam')
                            bg-blue-500
                        @elseif($p->status == 'dikembalikan')
                            bg-green-500
                        @elseif($p->status == 'ditolak')
                            bg-red-500
                        @else
                            bg-red-700
                        @endif
                        ">

                            {{ ucfirst($p->status) }}

                        </span>

                    </td>

                    <td class="p-3 border">

                        @if($p->status == 'ditolak')

                        <span class="text-red-600">
                        {{ $p->alasan_penolakan }}
                        </span>

                        @else

                        -

                        @endif

                    </td>



                    <td class="p-3 border">

                        <div class="flex gap-2 flex-wrap">

                            {{-- =========================
                            AKSI MAHASISWA
                            ========================= --}}
                            @if(Auth::user()->role == 'mahasiswa')

                                @if($p->status == 'menunggu')

                                    @if(!$p->bukti_ttd)

                                        <a
                                        href="{{ route('pinjam.surat',$p->id) }}"
                                        target="_blank"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                                            📄 Cetak Surat

                                        </a>

                                        <a
                                        href="{{ route('pinjam.upload.form',$p->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">

                                            ⬆ Upload Surat

                                        </a>

                                    @else

                                        <a
                                        href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                        target="_blank"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                            📄 Lihat Surat

                                        </a>

                                        <span
                                        class="bg-yellow-500 text-white px-3 py-1 rounded">

                                            Menunggu Persetujuan

                                        </span>

                                    @endif

                                @elseif($p->status == 'dipinjam')

                                    <a
                                        href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                        target="_blank"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                            📄 Lihat Surat

                                    </a>

                                    <button
                                        type="button"
                                        onclick="lihatDetail({{ $p->id }})"
                                        class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1 rounded">

                                            Detail Barang

                                    </button>


                                @elseif($p->status == 'dikembalikan')

                                    <a
                                    href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                    target="_blank"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                        📄 Lihat Surat

                                    </a>
                                    
                                    
                                    <button
                                        type="button"
                                        onclick="lihatDetail({{ $p->id }})"
                                        class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1 rounded">

                                            Detail Barang

                                    </button>


                                @endif

                            @endif

                            {{-- ADMIN --}}
                            @if(Auth::user()->role != 'mahasiswa')


                            <div class="flex gap-2 flex-wrap">

                                {{-- ==========================
                                STATUS MENUNGGU
                                ========================== --}}
                                @if($p->status == 'menunggu')

                                    {{-- Sudah upload surat --}}
                                    @if($p->bukti_ttd)

                                        <a
                                            href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                            target="_blank"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                            Lihat Surat

                                        </a>

                                        

                                        <button
                                            type="button"
                                            onclick="bukaModalSetujui({{ $p->id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                                            Setujui

                                        </button>

                                    @else

                                        <span
                                            class="bg-gray-500 text-white px-3 py-1 rounded">

                                            Belum Upload Surat

                                        </span>

                                    @endif

                                    <button
                                        type="button"
                                        onclick="bukaModal({{ $p->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                                        Tolak

                                    </button>

                                @endif


                                    {{-- ==========================
                                    STATUS DIPINJAM
                                    ========================== --}}
                                    @if($p->status == 'dipinjam')

                                    <a
                                        href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                        target="_blank"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                        Lihat Surat

                                    </a>

                                    <button
                                        type="button"
                                        onclick="lihatDetail({{ $p->id }})"
                                        class="bg-slate-700 text-white px-3 py-1 rounded">

                                        Detail Barang

                                    </button>

                                    <button
                                        type="button"
                                        onclick="bukaModalKembali({{ $p->id }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">

                                        Kembalikan

                                    </button>

                                @endif


                                    {{-- ==========================
                                    STATUS TERLAMBAT
                                    ========================== --}}
                                    @if($p->status == 'terlambat')

                                    <a
                                        href="{{ asset('storage/'.$p->bukti_ttd) }}"
                                        target="_blank"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                                        Lihat Surat

                                    </a>

                                    <a
    href="{{ asset('storage/'.$p->bukti_ttd) }}"
    download
    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

    ⬇ Download

</a>

                                    <button
                                        type="button"
                                        onclick="lihatDetail({{ $p->id }})"
                                        class="bg-slate-700 text-white px-3 py-1 rounded">

                                        Detail Barang

                                    </button>

                                    <button
                                        type="button"
                                        onclick="bukaModalKembali({{ $p->id }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">

                                        Kembalikan

                                    </button>

                                @endif


                                {{-- ==========================
STATUS DIKEMBALIKAN
========================== --}}
@if($p->status == 'dikembalikan')

    @if($p->bukti_ttd)

        {{-- Lihat Surat --}}
        <a
            href="{{ asset('storage/'.$p->bukti_ttd) }}"
            target="_blank"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

            📄 Lihat Surat

        </a>

        

    @endif

    {{-- Detail Barang --}}
    <button
        type="button"
        onclick="lihatDetail({{ $p->id }})"
        class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1 rounded">

        Detail Barang

    </button>

@endif


                                    {{-- ==========================
                                    HAPUS
                                    ========================== --}}
                                    @if($p->status != 'menunggu')

                                        <form
                                            action="{{ route('pinjam.destroy',$p->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                onclick="return confirm('Hapus data?')"
                                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">

                                                Hapus

                                            </button>

                                        </form>

                                    @endif

                                </div>
                            </div>

                        </td>

                    @endif
                </tr>

                @empty

                <tr>
                @if(Auth::user()->role == 'mahasiswa')
                    <td colspan="10" class="text-center p-6">
                @else
                    <td colspan="11" class="text-center p-6">
                @endif

                    Belum ada data peminjaman.

                </td>                
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $pinjam->links() }}
    </div>

</div>

<div id="modalTolak"
class="fixed inset-0 bg-black/40 hidden items-center justify-center">

<div class="bg-white rounded-xl p-6 w-[450px]">

<h2 class="text-xl font-bold mb-4">
Alasan Penolakan
</h2>

<form id="formTolak" method="POST">

@csrf

<div class="mb-4">

<label class="block mb-2">
Alasan
</label>

<textarea
name="alasan_penolakan"
rows="4"
class="w-full border rounded p-2"
required></textarea>

</div>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="tutupModal()"
class="bg-gray-500 text-white px-4 py-2 rounded">

Batal

</button>

<button
class="bg-red-600 text-white px-4 py-2 rounded">

Tolak

</button>

</div>

</form>

</div>

</div>
<!-- ===========================
MODAL SETUJUI
=========================== -->

    <div id="modalSetujui"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-5xl p-6">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-2xl font-bold">
                    Pilih Detail Barang
                </h2>

                <button
                    type="button"
                    onclick="tutupModalSetujui()"
                    class="text-red-600 text-xl">

                    ✕

                </button>

            </div>

            <div class="mb-4">

                <p>
                    Silakan pilih barang yang akan dipinjam.
                </p>

                <p class="font-semibold">

                    Jumlah yang harus dipilih :

                    <span id="jumlahHarusDipilih">
                        0
                    </span>

                </p>

            </div>

            <form
                id="formSetujui"
                method="POST">
                @csrf

                <div class="overflow-y-auto max-h-[400px]">

                    <table class="w-full border">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="border p-2">
                                    Pilih
                                </th>

                                <th class="border p-2">
                                    Kode Barang
                                </th>

                                <th class="border p-2">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody id="isiDetailBarang">

                            <tr>

                                <td colspan="3"
                                    class="text-center p-6">

                                    Memuat data...

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="flex justify-end gap-2 mt-5">

                    <button
                        type="button"
                        onclick="tutupModalSetujui()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">

                        Batal

                    </button>

                    <button
                        class="bg-green-600 text-white px-4 py-2 rounded">

                        Setujui

                    </button>

                </div>

            </form>

        </div>

    </div>

<!-- ===========================
MODAL Detail
=========================== -->
<div id="modalDetail"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-xl shadow-xl w-[700px] p-6">

<h2 class="text-2xl font-bold mb-4">

Detail Barang Dipinjam

</h2>

<table class="w-full border">

<thead>

<tr>

<th class="border p-2">Kode</th>
<th class="border p-2">Status</th>

</tr>

</thead>

<tbody id="isiDetailPinjam">

</tbody>

</table>

<div class="mt-5 text-right">

<button
type="button"
onclick="tutupDetail()"
class="bg-gray-600 text-white px-4 py-2 rounded">

Tutup

</button>

</div>

</div>

</div>

<!-- ===========================
MODAL Kembalikan
=========================== -->
<div id="modalKembali"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-xl shadow-xl w-11/12 max-w-5xl p-6">

<h2 class="text-2xl font-bold mb-5">

Pengembalian Barang

</h2>

<form
id="formKembali"
method="POST">

@csrf

<div id="isiPengembalian"
class="space-y-5 max-h-[450px] overflow-y-auto">

</div>

<div class="flex justify-end gap-2 mt-5">

<button
type="button"
onclick="tutupModalKembali()"
class="bg-gray-500 text-white px-4 py-2 rounded">

Batal

</button>

<button
class="bg-blue-600 text-white px-4 py-2 rounded">

Simpan

</button>

</div>

</form>

</div>

</div>
<script>

function bukaModal(id){

    document.getElementById('modalTolak').classList.remove('hidden');
    document.getElementById('modalTolak').classList.add('flex');

    document.getElementById('formTolak').action =
        "/pinjam/" + id + "/tolak";
}

function tutupModal(){

document.getElementById('modalTolak').classList.remove('flex');
document.getElementById('modalTolak').classList.add('hidden');

}
function bukaModalSetujui(id){

    document.getElementById('modalSetujui').classList.remove('hidden');
    document.getElementById('modalSetujui').classList.add('flex');

    fetch('/pinjam/' + id + '/detail-barang')
    .then(res => res.json())
    .then(data => {

        document.getElementById('jumlahHarusDipilih').innerHTML =
            data.pinjam.jumlah;

        document.getElementById('formSetujui').action =
            '/pinjam/' + id + '/setujui';

        let html = '';

        data.detail_barang.forEach(function(item){

            let warna = '';

            if(item.status == 'tersedia'){
                warna = 'text-green-600';
            }
            else if(item.status == 'dipinjam'){
                warna = 'text-yellow-600';
            }
            else{
                warna = 'text-red-600';
            }

            html += `
            <tr>

                <td class="border p-2 text-center">
            `;

            if(item.status == 'tersedia'){

                html += `
                <input
                    type="checkbox"
                    class="pilihBarang"
                    name="detail_barang_id[]"
                    value="${item.id}">
                `;

            }else{

                html += '-';

            }

            html += `
                </td>

                <td class="border p-2">
                    ${item.kode_barang}
                </td>

                <td class="border p-2 ${warna}">
                    ${item.status}
                </td>

            </tr>
            `;

        });

        document.getElementById('isiDetailBarang').innerHTML = html;

        batasiCheckbox(data.pinjam.jumlah);

    });

}
function tutupModalSetujui(){

    document.getElementById('modalSetujui').classList.remove('flex');
    document.getElementById('modalSetujui').classList.add('hidden');

}
function batasiCheckbox(jumlah){

    document.addEventListener('change',function(e){

        if(!e.target.classList.contains('pilihBarang')){
            return;
        }

        let checked =
            document.querySelectorAll('.pilihBarang:checked');

        if(checked.length > jumlah){

            e.target.checked = false;

            alert(
                'Maksimal memilih '
                + jumlah +
                ' barang.'
            );

        }

    });

}
function lihatDetail(id){

    document.getElementById('modalDetail')
        .classList.remove('hidden');

    document.getElementById('modalDetail')
        .classList.add('flex');

    fetch('/pinjam/' + id + '/detail-peminjaman')
    .then(res=>res.json())
    .then(data=>{

        let html='';

        data.detail_barang.forEach(function(item){

            html += `
            <tr>

                <td class="border p-2">
                    ${item.kode_barang}
                </td>

                <td class="border p-2">
                    ${item.status}
                </td>

            </tr>
            `;

        });

        document.getElementById('isiDetailPinjam')
            .innerHTML = html;

    });

}

function tutupDetail(){

    document.getElementById('modalDetail')
        .classList.remove('flex');

    document.getElementById('modalDetail')
        .classList.add('hidden');

}
function bukaModalKembali(id){

    document.getElementById('modalKembali')
        .classList.remove('hidden');

    document.getElementById('modalKembali')
        .classList.add('flex');

    document.getElementById('formKembali').action =
        "/pinjam/" + id + "/kembali";

    fetch('/pinjam/' + id + '/detail-peminjaman')
    .then(res => res.json())
    .then(data => {

        let html='';

        data.detail_barang.forEach(function(item){

            html += `

            <div class="border rounded-lg p-4">

                <div class="font-semibold mb-3">

                    ${item.kode_barang}

                </div>

                <div class="flex gap-6 mb-3">

                    <label>

                        <input
                        type="radio"
                        name="kondisi[${item.id}]"
                        value="baik"
                        checked>

                        Baik

                    </label>

                    <label>

                        <input
                        type="radio"
                        name="kondisi[${item.id}]"
                        value="rusak">

                        Rusak

                    </label>

                </div>

                <textarea

                    name="keterangan[${item.id}]"

                    class="border rounded p-2 w-full"

                    placeholder="Keterangan kerusakan (jika rusak)">

                </textarea>

            </div>

            `;

        });

        document.getElementById('isiPengembalian').innerHTML =
            html;

    });

}
function tutupModalKembali(){

    document.getElementById('modalKembali')
        .classList.remove('flex');

    document.getElementById('modalKembali')
        .classList.add('hidden');

}

</script>

@endsection