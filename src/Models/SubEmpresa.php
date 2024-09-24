<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEmpresa extends Model
{
    protected $fillable = ['name', 'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}