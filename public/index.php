<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Application;
use App\Core\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

require_once __DIR__ . "/../config/database.php";

$app = new Application();
$router = new Router();

// Define tus rutas aquí
$router->addRoute("GET", "/", [App\Controllers\HomeController::class, "index"]);
$router->addRoute("GET", "/login", [App\Controllers\AuthController::class, "showLoginForm"]);
$router->addRoute("POST", "/login", [App\Controllers\AuthController::class, "login"]);
// Agrega más rutas según sea necesario

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router->dispatch($method, $uri);