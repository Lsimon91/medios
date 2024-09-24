<?php
namespace App\Middleware;

class PermissionMiddleware
{
    private $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    public function handle($request, $next)
    {
        // Implementa la lógica de verificación de permisos aquí
    }
}