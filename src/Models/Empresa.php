<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['name'];

    public function subEmpresas()
    {
        return $this->hasMany(SubEmpresa::class);
    }
}