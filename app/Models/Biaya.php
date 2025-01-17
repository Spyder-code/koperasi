<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    //
    protected $fillable = [
        'name',
        'divisi_id',
        'jenis_biaya'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
