<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    protected $fillable = [
    'barang_rusak_id',
    'nama_barang',
    'tanggal',
    'keterangan',
    'biaya',
    'status'
];


public function barangRusak()
{
    return $this->belongsTo(BarangRusak::class);
}
public function keuangan()
{
    return $this->hasOne(Keuangan::class);
}

}
