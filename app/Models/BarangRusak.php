<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailBarang;
use App\Models\Pinjam;

class BarangRusak extends Model
{
    protected $table = 'barang_rusaks';

    protected $fillable = [
    'detail_barang_id',
    'pinjam_id',
    'keterangan',
    'tanggal_rusak',
    'status',
    'jenis_pertanggungjawaban',
    'status_pertanggungjawaban',
    'nominal_ganti',
    'keterangan_pertanggungjawaban',
];

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class);
    }

        public function pinjam()
    {
        return $this->belongsTo(
            Pinjam::class,
            'pinjam_id'
        );
    }

    public function perawatan()
    {
        return $this->hasOne(Perawatan::class);
    }
}