<?php

namespace App\Helpers;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleParts = explode('|', $rule);
            
            foreach ($ruleParts as $rulePart) {
                $this->applyRule($field, $value, $rulePart);
            }
        }

        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule)
    {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, 'El campo es requerido');
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'El correo electrónico no es válido');
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->addError($field, 'El campo debe ser numérico');
                }
                break;
            // Añadir más reglas según sea necesario
        }
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}