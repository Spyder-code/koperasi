<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    //

    protected $fillable = [
        'name',
        'open_date',
        'close_date',
        'status',
        'is_close'
    ];
}
