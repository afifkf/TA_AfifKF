<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailBarang;

class BarangRusak extends Model
{
    protected $table = 'barang_rusaks';

    protected $fillable = [
        'detail_barang_id',
        'keterangan',
        'tanggal_rusak',
        'status'
    ];

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class);
    }

    public function perawatan()
    {
        return $this->hasOne(Perawatan::class);
    }
}