<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $fillable = [
        'display_name'
    ];

    public function permission_role()
    {
        return $this->hasMany(PermissionRole::class);
    }
}
