<?php

use App\Http\Controllers\ProfileController;
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
Route::get('produk-export-pdf', [ProdukController::class, 'exportPdf'])->name('produk.exportPdf');

Route::resource('pinjam', PinjamController::class);
Route::resource('user', UserController::class);

Route::get('barang-rusak',
[BarangRusakController::class,'index'])
->name('barang-rusak.index');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
