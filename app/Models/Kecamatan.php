<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $fillable = [
        'nama_kecamatan',
        'kode_kecamatan',
        'geojson',
        'latitude',
        'longitude',
        'total_keseluruhan',
        'total_2023',
        'total_2024',
        'total_2025',
    ];

    public function kriminalitas()
    {
        return $this->hasMany(Kriminalitas::class);
    }
}