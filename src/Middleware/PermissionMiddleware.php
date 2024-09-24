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
        $user = $request->getUser();

        if (!$user->hasPermission($this->permission)) {
            header('Location: /access-denied');
            exit;
        }

        return $next($request);
    }
}