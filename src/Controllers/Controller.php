<?php
namespace App\Controllers;

use App\Core\Application;
use App\Helpers\CsrfHelper;

class Controller
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function render($template, $data = [])
    {
        $data["csrf_token"] = CsrfHelper::generateToken();
        $this->app->render($template, $data);
    }
}