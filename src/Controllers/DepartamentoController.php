<?php
namespace App\Controllers;

use App\Models\Departamento;
use App\Models\SubEmpresa;

class DepartamentoController extends CrudController
{
    protected $viewPath = "departamentos";

    protected function getModelClass()
    {
        return Departamento::class;
    }

    protected function getValidationRules()
    {
        return [
            "name" => "required",
            "sub_empresa_id" => "required|numeric"
        ];
    }

    // Implementa los métodos específicos aquí
}