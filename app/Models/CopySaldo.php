<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopySaldo extends Model
{
    protected $table = 'copy_saldos';

    protected $fillable = [
        'from_periode_id',
        'to_periode_id',
        'divisi_id',
        'status_saldo',
        'id'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }

    public function from_periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function to_periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
