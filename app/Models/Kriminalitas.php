<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriminalitas extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'alamat_detail',
        'rt',
        'rw',
        'kelurahan',
        'tahun',
        'jenis_kriminalitas',
        'jumlah_kasus',
        'tingkat_kerawanan',
        'latitude',
        'longitude',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}