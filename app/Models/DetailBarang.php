<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    protected $fillable = [
        'produk_id',
        'kode_barang',
        'status',
        'gambar'
    ];

    protected $attributes = [
        'status' => 'tersedia'
    ];

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Relasi ke banyak peminjaman
    public function pinjams()
    {
        return $this->belongsToMany(
            Pinjam::class,
            'detail_barang_pinjam',
            'detail_barang_id',
            'pinjam_id'
        );
    }

    // Relasi ke Barang Rusak
    public function rusak()
    {
        return $this->hasOne(BarangRusak::class);
    }
}