<?php

namespace App\Controllers;

use App\Models\Empresa;

class EmpresaController extends CrudController
{
    protected $viewPath = 'empresas';

    protected function getModelClass()
    {
        return Empresa::class;
    }

    protected function getValidationRules()
    {
        return [
            'name' => 'required'
        ];
    }
}