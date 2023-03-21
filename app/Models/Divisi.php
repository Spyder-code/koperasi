<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function copy_saldo()
    {
        return $this->hasMany(CopySaldo::class);
    }
}
