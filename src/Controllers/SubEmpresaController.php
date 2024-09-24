<?php

namespace App\Controllers;

use App\Models\SubEmpresa;
use App\Models\Empresa;

class SubEmpresaController extends CrudController
{
    protected $viewPath = 'sub_empresas';

    protected function getModelClass()
    {
        return SubEmpresa::class;
    }

    protected function getValidationRules()
    {
        return [
            'name' => 'required',
            'empresa_id' => 'required|numeric'
        ];
    }

    public function create()
    {
        $empresas = Empresa::all();
        $this->render($this->viewPath . '/create.twig', ['empresas' => $empresas]);
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $empresas = Empresa::all();
        $this->render($this->viewPath . '/edit.twig', ['item' => $item, 'empresas' => $empresas]);
    }
}