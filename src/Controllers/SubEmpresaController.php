<?php
namespace App\Controllers;

use App\Models\SubEmpresa;
use App\Models\Empresa;

class SubEmpresaController extends CrudController
{
    protected $viewPath = "sub_empresas";

    protected function getModelClass()
    {
        return SubEmpresa::class;
    }

    protected function getValidationRules()
    {
        return [
            "name" => "required",
            "empresa_id" => "required|numeric"
        ];
    }

    // Implementa los métodos específicos aquí
}