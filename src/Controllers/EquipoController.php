<?php
namespace App\Controllers;

use App\Models\Equipo;
use App\Models\TipoEquipo;
use App\Models\Departamento;

class EquipoController extends CrudController
{
    protected $viewPath = "equipos";

    protected function getModelClass()
    {
        return Equipo::class;
    }

    protected function getValidationRules()
    {
        return [
            "tipo_equipo_id" => "required|numeric",
            "numero_serie" => "required",
            "modelo" => "required",
            "marca" => "required",
            "fecha_compra" => "required",
            "fecha_expiracion_garantia" => "required",
            "departamento_id" => "required|numeric",
            "estado" => "required",
            "numero_inventario" => "required",
            "numero_sello_garantia" => "required"
        ];
    }

    // Implementa los métodos específicos aquí
}