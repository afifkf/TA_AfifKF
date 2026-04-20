<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = [
        'perawatan_id',
        'tanggal',
        'keterangan',
        'nominal'
    ];

    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class);
    }
}