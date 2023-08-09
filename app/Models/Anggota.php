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
        'tgl_lahir',
        'tempat_lahir',
        'jabatan',
        'tgl_daftar',
        'status',
        'homebase'
    ];

    public function transaksi_harian_anggota()
    {
        return $this->hasMany(TransaksiHarianAnggota::class,'anggota_id');
    }

    public function user_anggota()
    {
        return $this->hasOne(UserAnggota::class);
    }
}
