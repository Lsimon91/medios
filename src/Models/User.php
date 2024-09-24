<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPermissions;

class User extends Model
{
    use HasPermissions;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'sub_empresa_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subEmpresa()
    {
        return $this->belongsTo(SubEmpresa::class);
    }
}
