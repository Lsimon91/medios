<?php
namespace App\Core;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            "method" => $method,
            "path" => $path,
            "handler" => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        // Implementa la lógica de enrutamiento aquí
    }
}