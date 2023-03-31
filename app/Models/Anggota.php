<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    //

    protected $fillable = [
        'nama',
        'nik',
        'inisial',
        'tgl_daftar',
        'status',
        'homebase'
    ];

    public function transaksi_harian_anggota()
    {
        return $this->hasMany(TransaksiHarianAnggota::class,'anggota_id');
    }
}
