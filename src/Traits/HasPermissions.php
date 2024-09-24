<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('slug', $permission);
    }

    public function hasRole($role)
    {
        return $this->role->name === $role;
    }
}