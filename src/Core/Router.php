<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function addRoute($method, $uri, $controller, $action, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function dispatch($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $controller = new $route['controller']();
                $action = $route['action'];

                // Crear una función que ejecutará el controlador
                $executeController = function() use ($controller, $action) {
                    return $controller->$action();
                };

                // Aplicar middleware
                $middlewareStack = $route['middleware'];
                $next = $executeController;

                while ($middleware = array_pop($middlewareStack)) {
                    $middlewareInstance = new $middleware();
                    $next = function() use ($middlewareInstance, $next) {
                        return $middlewareInstance->handle(null, $next);
                    };
                }

                // Ejecutar la pila de middleware
                return $next();
            }
        }

        // Si no se encuentra la ruta, mostrar un error 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
