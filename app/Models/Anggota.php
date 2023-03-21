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
}
