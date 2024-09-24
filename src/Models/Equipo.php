<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'tipo_equipo_id',
        'numero_serie',
        'modelo',
        'marca',
        'fecha_compra',
        'fecha_expiracion_garantia',
        'departamento_id',
        'estado',
        'numero_inventario',
        'numero_sello_garantia'
    ];

    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}