<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'nama', 
        'deskripsi', 
        'harga', 'stok', 
        'departemen',
        'jenis'
    ];

    public function detailBarang()
    {
        return $this->hasMany(DetailBarang::class);
    }

            public function pinjam()
{
    return $this->hasMany(Pinjam::class);
}
public function perawatan()
{
    return $this->hasMany(Perawatan::class);
}
}

