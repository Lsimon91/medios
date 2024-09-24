<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['name', 'sub_empresa_id'];

    public function subEmpresa()
    {
        return $this->belongsTo(SubEmpresa::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}