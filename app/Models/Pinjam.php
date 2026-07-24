<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailBarang;
use App\Models\BarangRusak;
use App\Models\User;
use App\Models\Produk;

class Pinjam extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'produk_id',
        'user_id',
        'admin_id',
        'nama_peminjam',
        'nim',
        'no_whatsapp',
        'jumlah',
        'tanggal_pinjam',
        'batas_kembali',
        'tanggal_dikembalikan',
        'tanggal_disetujui',
        'alasan_penolakan',
        'status',
        'bukti_ttd',
        'keterangan',
    ];

     public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi banyak detail barang
    public function detailBarangs()
    {
        return $this->belongsToMany(
            DetailBarang::class,
            'detail_barang_pinjam',
            'pinjam_id',
            'detail_barang_id'
        );
    }

    public function barangRusaks()
{
    return $this->hasMany(
        BarangRusak::class,
        'pinjam_id'
    );
}
}