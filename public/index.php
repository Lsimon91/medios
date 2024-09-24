<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require_once __DIR__ . '/../config/database.php';

$app = new Application();
$router = new Router();

// Define tus rutas aquí
$router->addRoute('GET', '/', 'App\Controllers\HomeController', 'index');
$router->addRoute('GET', '/login', 'App\Controllers\AuthController', 'showLoginForm');
$router->addRoute('POST', '/login', 'App\Controllers\AuthController', 'login');
// Agrega más rutas según sea necesario
// Rutas de autenticación
$router->addRoute('GET', '/forgot-password', 'App\Controllers\AuthController', 'forgotPassword');
$router->addRoute('POST', '/forgot-password', 'App\Controllers\AuthController', 'forgotPassword');
$router->addRoute('GET', '/reset-password/:token', 'App\Controllers\AuthController', 'resetPassword');
$router->addRoute('POST', '/reset-password/:token', 'App\Controllers\AuthController', 'resetPassword');

// Rutas de usuarios
$router->addRoute('GET', '/usuarios', 'App\Controllers\AuthController', 'index');
$router->addRoute('GET', '/usuarios/create', 'App\Controllers\AuthController', 'create');
$router->addRoute('POST', '/usuarios/create', 'App\Controllers\AuthController', 'create');
$router->addRoute('GET', '/usuarios/edit/:id', 'App\Controllers\AuthController', 'edit');
$router->addRoute('POST', '/usuarios/edit/:id', 'App\Controllers\AuthController', 'edit');
$router->addRoute('POST', '/usuarios/delete/:id', 'App\Controllers\AuthController', 'delete');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
