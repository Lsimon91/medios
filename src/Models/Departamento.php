<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ["name", "sub_empresa_id"];

    // Implementa las relaciones y métodos aquí
}