<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
