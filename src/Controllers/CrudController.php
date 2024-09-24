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

    public function index()
    {
        $items = $this->model::all();
        $this->render($this->viewPath . '/index.twig', ['items' => $items]);
    }

    public function create()
    {
        $this->render($this->viewPath . '/create.twig');
    }

    public function store()
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = $this->getValidationRules();

            if ($validator->validate($_POST, $rules)) {
                $data = $this->sanitizeData($_POST);
                $item = $this->model::create($data);
                
                if ($item) {
                    header("Location: /{$this->viewPath}");
                    exit;
                } else {
                    throw new \Exception('Error al crear el elemento');
                }
            } else {
                $this->render($this->viewPath . '/create.twig', ['errors' => $validator->getErrors()]);
            }
        } catch (\Exception $e) {
            $this->render($this->viewPath . '/create.twig', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $this->render($this->viewPath . '/edit.twig', ['item' => $item]);
    }

    public function update($id)
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = $this->getValidationRules();

            if ($validator->validate($_POST, $rules)) {
                $item = $this->model::findOrFail($id);
                $data = $this->sanitizeData($_POST);
                
                if ($item->update($data)) {
                    header("Location: /{$this->viewPath}");
                    exit;
                } else {
                    throw new \Exception('Error al actualizar el elemento');
                }
            } else {
                $this->render($this->viewPath . '/edit.twig', ['errors' => $validator->getErrors(), 'item' => $this->model::findOrFail($id)]);
            }
        } catch (\Exception $e) {
            $this->render($this->viewPath . '/edit.twig', ['error' => $e->getMessage(), 'item' => $this->model::findOrFail($id)]);
        }
    }

    public function delete($id)
    {
        try {
            $item = $this->model::findOrFail($id);
            
            if ($item->delete()) {
                header("Location: /{$this->viewPath}");
                exit;
            } else {
                throw new \Exception('Error al eliminar el elemento');
            }
        } catch (\Exception $e) {
            $this->render($this->viewPath . '/index.twig', ['error' => $e->getMessage(), 'items' => $this->model::all()]);
        }
    }

    protected function sanitizeData($data)
    {
        $sanitizedData = [];
        foreach ($data as $key => $value) {
            if ($key !== 'csrf_token') {
                $sanitizedData[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }
        return $sanitizedData;
    }
}
