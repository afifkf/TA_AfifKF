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

    Route::put('/users.{user}.role', [UserController::class,'updateRole'])
        ->name('users.updateRole');

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

Route::get('/detail-barang/{id}', 
[DetailBarangController::class, 'show'])
    ->name('detail-barang.show');

Route::resource('produk', ProdukController::class);
Route::get('produk-export-pdf', [ProdukController::class, 'exportPdf'])
    ->name('produk.exportPdf');

Route::resource('pinjam', PinjamController::class);
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
});

require __DIR__.'/auth.php';
