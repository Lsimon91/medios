<?php
namespace App\Controllers;

use App\Core\Application;
use App\Helpers\CsrfHelper;
use App\Helpers\Validator;

abstract class CrudController extends Controller
{
    protected $model;
    protected $viewPath;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass();
    abstract protected function getValidationRules();

    // Implementa los métodos CRUD aquí
}