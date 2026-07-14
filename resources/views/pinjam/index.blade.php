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
                        @if(Auth::user()->role != 'mahasiswa')
                            <th class="p-3 border text-center">Aksi</th>
                        @endif
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
                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}
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

                    @if(Auth::user()->role != 'mahasiswa')

                    <td class="p-3 border">

                    <div class="flex gap-2 flex-wrap">

                        @if(
                            Auth::user()->role != 'mahasiswa'
                            && $p->status == 'menunggu'
                        )
                    <form action="{{ route('pinjam.setujui',$p->id) }}" method="POST">
                        @csrf

                        <button
                            onclick="return confirm('Setujui pengajuan ini?')"
                            class="bg-green-600 text-white px-3 py-1 rounded">
                            Setujui
                        </button>

                    </form>

                    <button
                        type="button"
                        onclick="bukaModal({{ $p->id }})"
                        class="bg-red-600 text-white px-3 py-1 rounded">

                        Tolak

                    </button>

                    @endif


                    @if(
                        Auth::user()->role != 'mahasiswa'
                        && $p->status == 'dipinjam'
                    )

                    <a href="{{ route('pinjam.kembali',$p->id) }}"
                    class="bg-blue-600 text-white px-3 py-1 rounded">

                    Kembalikan

                    </a>

                    @endif

                    

                    @if(Auth::user()->role != 'mahasiswa')
                    <form action="{{ route('pinjam.destroy',$p->id) }}" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                    onclick="return confirm('Hapus data?')"
                    class="bg-gray-600 text-white px-3 py-1 rounded">

                    Hapus

                    </button>

                    </form>
                    @endif

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

</script>

@endsection