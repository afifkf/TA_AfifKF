<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailBarang;

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
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
}