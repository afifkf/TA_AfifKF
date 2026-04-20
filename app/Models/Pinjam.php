<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjam extends Model
{
    protected $table = 'peminjamans'; // tambahkan ini

    protected $fillable = [
        'produk_id',
        'user_id',
        'nama_peminjam',
        'nim',
        'no_whatsapp',
        'jumlah',
        'tanggal_pinjam',
        'batas_kembali',
        'tanggal_dikembalikan',
        'status'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class);
    }


}