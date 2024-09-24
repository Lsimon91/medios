<?php

namespace App\Middleware;

use App\Models\User;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user = User::find($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: /login');
            exit;
        }

        $request->setUser($user);

        return $next($request);
    }
}