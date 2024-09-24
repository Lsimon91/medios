<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Application
{
    protected $router;
    protected $twig;

    public function __construct()
    {
        $this->router = new Router();
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $this->router->dispatch($uri, $method);
    }

    public function render($template, $data = [])
    {
        echo $this->twig->render($template, $data);
    }

    public function getRouter()
    {
        return $this->router;
    }
}