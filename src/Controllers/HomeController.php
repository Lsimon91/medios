<?php

namespace App\Controllers;
use App\Models\Equipo;
use App\Models\Departamento;
use App\Models\Empresa;

class HomeController
{
    public function index()
    {
        $title = 'Inicio - Gestión de Activos';
        $content = $this->renderView('auth/login');
		$content = $this->renderView('Empresa');
        echo $this->renderLayout('layout', compact('title', 'content'));
    }

    private function renderView($view, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/$view.twig";
        return ob_get_clean();
    }

    private function renderLayout($layout, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/$layout.twig";
        return ob_get_clean();
    }
}