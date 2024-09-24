<?php
namespace App\Helpers;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        // Implementa la lógica de validación aquí
    }

    public function getErrors()
    {
        return $this->errors;
    }
}