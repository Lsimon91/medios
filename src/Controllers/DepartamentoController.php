<?php

namespace App\Controllers;

use App\Models\Departamento;
use App\Models\SubEmpresa;

class DepartamentoController extends CrudController
{
    protected $viewPath = 'departamentos';

    protected function getModelClass()
    {
        return Departamento::class;
    }

    protected function getValidationRules()
    {
        return [
            'name' => 'required',
            'sub_empresa_id' => 'required|numeric'
        ];
    }

    public function create()
    {
        $subEmpresas = SubEmpresa::all();
        $this->render($this->viewPath . '/create.twig', ['subEmpresas' => $subEmpresas]);
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $subEmpresas = SubEmpresa::all();
        $this->render($this->viewPath . '/edit.twig', ['item' => $item, 'subEmpresas' => $subEmpresas]);
    }
}
