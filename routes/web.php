<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DetailBarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\KeuanganController;


Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/profil-admin', [ProfileController::class, 'index'])
    ->name('profil.index');

Route::put('/profil-admin', [ProfileController::class, 'update'])
    ->name('profil.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class,'index'])
        ->name('users.index');

    Route::get('/users/create-admin', [UserController::class, 'createAdmin'])
        ->name('users.createAdmin');

    Route::post('/users/store-admin', [UserController::class, 'storeAdmin'])
        ->name('users.storeAdmin');

    Route::put('/users/{user}.role', [UserController::class,'updateRole'])
        ->name('users.updateRole');

        Route::get('/users/{user}/edit-admin', [UserController::class, 'editAdmin'])
    ->name('users.editAdmin');

Route::put('/users/{user}/update-admin', [UserController::class, 'updateAdmin'])
    ->name('users.updateAdmin');

Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy');

});

Route::get('/laporan.peminjaman.pdf', [LaporanController::class, 'pdfPeminjaman'])
    ->name('laporan.peminjaman.pdf');

Route::get('/laporan.keuangan.pdf', [LaporanController::class, 'pdfKeuangan'])
    ->name('laporan.keuangan.pdf');

Route::get('/laporan.barangrusak.pdf', [LaporanController::class, 'pdfBarangRusak'])
    ->name('laporan.barangrusak.pdf');

Route::resource('keuangan', KeuanganController::class);
Route::resource('perawatan', PerawatanController::class);
Route::resource('detail-barang', DetailBarangController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



Route::get('/pinjam/kembali/{id}', 
[PinjamController::class, 'kembali'])
    ->name('pinjam.kembali');

Route::get('/pinjam/{id}/pilih-barang',
    [PinjamController::class, 'pilihBarang'])
    ->name('pinjam.pilihBarang');

Route::post('/pinjam/{id}/setujui',
    [PinjamController::class,'setujui'])
    ->name('pinjam.setujui');

Route::post('/pinjam/{id}/tolak',
    [PinjamController::class,'tolak'])
    ->name('pinjam.tolak');

Route::get('/pinjam/{id}/bukti-peminjaman',
    [PinjamController::class, 'buktiPeminjaman'])
    ->name('pinjam.bukti');

    Route::get('/pinjam/{id}/detail-barang',
    [PinjamController::class, 'detailBarang'])
    ->name('pinjam.detailBarang');

    Route::get('/pinjam/{id}/upload-bukti', [PinjamController::class, 'formUploadBukti'])
    ->name('pinjam.upload.form');

Route::post('/pinjam/{id}/upload-bukti', [PinjamController::class, 'uploadBukti'])
    ->name('pinjam.upload');
    Route::get(
    '/pinjam/{pinjam}/surat',
    [PinjamController::class, 'suratPdf']
)->name('pinjam.surat');

Route::get(
    '/pinjam/{id}/detail-peminjaman',
    [PinjamController::class,'detailPeminjaman']
)->name('pinjam.detail');

Route::get(
    '/pinjam/{id}/form-pengembalian',
    [PinjamController::class,'formPengembalian']
)->name('pinjam.formPengembalian');

Route::post(
    '/pinjam/{id}/pengembalian',
    [PinjamController::class,'prosesPengembalian']
)->name('pinjam.prosesPengembalian');

Route::post(
    '/pinjam/{id}/kembali',
    [PinjamController::class,'kembali']
)->name('pinjam.kembali');

Route::resource('pinjam', PinjamController::class);



Route::get('/detail-barang/{id}', 
[DetailBarangController::class, 'show'])
    ->name('detail-barang.show');

Route::resource('produk', ProdukController::class);
Route::get('produk-export-pdf', [ProdukController::class, 'exportPdf'])
    ->name('produk.exportPdf');

Route::resource('user', UserController::class);

Route::get('barang-rusak',
[BarangRusakController::class,'index'])
    ->name('barang-rusak.index');


Route::get('/laporan', [LaporanController::class, 'index'])
    ->name('laporan.index');
Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])
    ->name('laporan.pdf');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');








        Route::middleware('auth')->group(function () {

    Route::resource('pengajuan', App\Http\Controllers\PengajuanController::class);
});
});

require __DIR__.'/auth.php';
