@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-xl">

<h2 class="text-2xl font-bold mb-6">
Upload Bukti Tanda Tangan
</h2>

<form
action="{{ route('pinjam.upload',$pinjam->id) }}"
method="POST"
enctype="multipart/form-data">

@csrf

<div class="mb-5">

<label class="block mb-2 font-semibold">

Upload Foto / Scan Bukti

</label>

<input
type="file"
name="bukti_ttd"
class="border rounded p-2 w-full"
required>

</div>

<button
class="bg-blue-600 text-white px-5 py-2 rounded">

Upload

</button>

</form>

</div>

@endsection