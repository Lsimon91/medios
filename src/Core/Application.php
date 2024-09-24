<?php
namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Application
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . "/../Views");
        $this->twig = new Environment($loader);
    }

    public function render($template, $data = [])
    {
        echo $this->twig->render($template, $data);
    }

    // Implementa otros métodos de la aplicación aquí
}