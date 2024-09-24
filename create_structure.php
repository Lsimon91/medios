<?php

// Función para crear un directorio si no existe
function createDir($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

// Función para crear un archivo con contenido
function createFile($path, $content) {
    file_put_contents($path, $content);
}

// Estructura del proyecto
$structure = [
    'config' => [
        'database.php' => '<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" => $_ENV["DB_HOST"],
    "database" => $_ENV["DB_NAME"],
    "username" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASS"],
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix" => "",
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();',
        'app.php' => '<?php
return [
    "name" => "Gestión de Activos",
    "debug" => true,
    "url" => $_ENV["APP_URL"],
    "timezone" => "America/Mexico_City",
];'
    ],
    'src' => [
        'Controllers' => [
            'Controller.php' => '<?php
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
}',
            'AuthController.php' => '<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\Validator;
use App\Helpers\CsrfHelper;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    // Implementa los métodos de autenticación aquí
}',
            'EmpresaController.php' => '<?php
namespace App\Controllers;

use App\Models\Empresa;

class EmpresaController extends CrudController
{
    protected $viewPath = "empresas";

    protected function getModelClass()
    {
        return Empresa::class;
    }

    protected function getValidationRules()
    {
        return [
            "name" => "required"
        ];
    }
}',
            'SubEmpresaController.php' => '<?php
namespace App\Controllers;

use App\Models\SubEmpresa;
use App\Models\Empresa;

class SubEmpresaController extends CrudController
{
    protected $viewPath = "sub_empresas";

    protected function getModelClass()
    {
        return SubEmpresa::class;
    }

    protected function getValidationRules()
    {
        return [
            "name" => "required",
            "empresa_id" => "required|numeric"
        ];
    }

    // Implementa los métodos específicos aquí
}',
            'DepartamentoController.php' => '<?php
namespace App\Controllers;

use App\Models\Departamento;
use App\Models\SubEmpresa;

class DepartamentoController extends CrudController
{
    protected $viewPath = "departamentos";

    protected function getModelClass()
    {
        return Departamento::class;
    }

    protected function getValidationRules()
    {
        return [
            "name" => "required",
            "sub_empresa_id" => "required|numeric"
        ];
    }

    // Implementa los métodos específicos aquí
}',
            'EquipoController.php' => '<?php
namespace App\Controllers;

use App\Models\Equipo;
use App\Models\TipoEquipo;
use App\Models\Departamento;

class EquipoController extends CrudController
{
    protected $viewPath = "equipos";

    protected function getModelClass()
    {
        return Equipo::class;
    }

    protected function getValidationRules()
    {
        return [
            "tipo_equipo_id" => "required|numeric",
            "numero_serie" => "required",
            "modelo" => "required",
            "marca" => "required",
            "fecha_compra" => "required",
            "fecha_expiracion_garantia" => "required",
            "departamento_id" => "required|numeric",
            "estado" => "required",
            "numero_inventario" => "required",
            "numero_sello_garantia" => "required"
        ];
    }

    // Implementa los métodos específicos aquí
}',
            'CrudController.php' => '<?php
namespace App\Controllers;

use App\Core\Application;
use App\Helpers\CsrfHelper;
use App\Helpers\Validator;

abstract class CrudController extends Controller
{
    protected $model;
    protected $viewPath;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass();
    abstract protected function getValidationRules();

    // Implementa los métodos CRUD aquí
}',
        ],
        'Models' => [
            'User.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPermissions;

class User extends Model
{
    use HasPermissions;

    protected $fillable = ["name", "email", "password", "role_id", "sub_empresa_id"];

    // Implementa las relaciones y métodos aquí
}',
            'Role.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ["name"];

    // Implementa las relaciones y métodos aquí
}',
            'Permission.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ["name", "slug"];

    // Implementa las relaciones y métodos aquí
}',
            'Empresa.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ["name"];

    // Implementa las relaciones y métodos aquí
}',
            'SubEmpresa.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEmpresa extends Model
{
    protected $fillable = ["name", "empresa_id"];

    // Implementa las relaciones y métodos aquí
}',
            'Departamento.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ["name", "sub_empresa_id"];

    // Implementa las relaciones y métodos aquí
}',
            'TipoEquipo.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    protected $fillable = ["name"];

    // Implementa las relaciones y métodos aquí
}',
            'Equipo.php' => '<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        "tipo_equipo_id",
        "numero_serie",
        "modelo",
        "marca",
        "fecha_compra",
        "fecha_expiracion_garantia",
        "departamento_id",
        "estado",
        "numero_inventario",
        "numero_sello_garantia"
    ];

    // Implementa las relaciones y métodos aquí
}',
        ],
        'Views' => [
            'auth' => [
                'login.twig' => '{% extends "layout.twig" %}

{% block title %}Iniciar Sesión{% endblock %}

{% block content %}
<h2>Iniciar Sesión</h2>
<!-- Implementa el formulario de inicio de sesión aquí -->
{% endblock %}',
                'register.twig' => '{% extends "layout.twig" %}

{% block title %}Registro{% endblock %}

{% block content %}
<h2>Registro</h2>
<!-- Implementa el formulario de registro aquí -->
{% endblock %}',
                'forgot_password.twig' => '{% extends "layout.twig" %}

{% block title %}Recuperar Contraseña{% endblock %}

{% block content %}
<h2>Recuperar Contraseña</h2>
<!-- Implementa el formulario de recuperación de contraseña aquí -->
{% endblock %}',
                'reset_password.twig' => '{% extends "layout.twig" %}

{% block title %}Restablecer Contraseña{% endblock %}

{% block content %}
<h2>Restablecer Contraseña</h2>
<!-- Implementa el formulario de restablecimiento de contraseña aquí -->
{% endblock %}',
            ],
            'empresas' => [
                'index.twig' => '{% extends "layout.twig" %}

{% block title %}Empresas{% endblock %}

{% block content %}
<h2>Listado de Empresas</h2>
<!-- Implementa la lista de empresas aquí -->
{% endblock %}',
                'create.twig' => '{% extends "layout.twig" %}

{% block title %}Crear Empresa{% endblock %}

{% block content %}
<h2>Crear Nueva Empresa</h2>
<!-- Implementa el formulario de creación de empresa aquí -->
{% endblock %}',
                'edit.twig' => '{% extends "layout.twig" %}

{% block title %}Editar Empresa{% endblock %}

{% block content %}
<h2>Editar Empresa</h2>
<!-- Implementa el formulario de edición de empresa aquí -->
{% endblock %}',
            ],
            'sub_empresas' => [
                'index.twig' => '{% extends "layout.twig" %}

{% block title %}Sub-empresas{% endblock %}

{% block content %}
<h2>Listado de Sub-empresas</h2>
<!-- Implementa la lista de sub-empresas aquí -->
{% endblock %}',
                'create.twig' => '{% extends "layout.twig" %}

{% block title %}Crear Sub-empresa{% endblock %}

{% block content %}
<h2>Crear Nueva Sub-empresa</h2>
<!-- Implementa el formulario de creación de sub-empresa aquí -->
{% endblock %}',
                'edit.twig' => '{% extends "layout.twig" %}

{% block title %}Editar Sub-empresa{% endblock %}

{% block content %}
<h2>Editar Sub-empresa</h2>
<!-- Implementa el formulario de edición de sub-empresa aquí -->
{% endblock %}',
            ],
            'departamentos' => [
                'index.twig' => '{% extends "layout.twig" %}

{% block title %}Departamentos{% endblock %}

{% block content %}
<h2>Listado de Departamentos</h2>
<!-- Implementa la lista de departamentos aquí -->
{% endblock %}',
                'create.twig' => '{% extends "layout.twig" %}

{% block title %}Crear Departamento{% endblock %}

{% block content %}
<h2>Crear Nuevo Departamento</h2>
<!-- Implementa el formulario de creación de departamento aquí -->
{% endblock %}',
                'edit.twig' => '{% extends "layout.twig" %}

{% block title %}Editar Departamento{% endblock %}

{% block content %}
<h2>Editar Departamento</h2>
<!-- Implementa el formulario de edición de departamento aquí -->
{% endblock %}',
            ],
            'equipos' => [
                'index.twig' => '{% extends "layout.twig" %}

{% block title %}Equipos{% endblock %}

{% block content %}
<h2>Listado de Equipos</h2>
<!-- Implementa la lista de equipos aquí -->
{% endblock %}',
                'create.twig' => '{% extends "layout.twig" %}

{% block title %}Crear Equipo{% endblock %}

{% block content %}
<h2>Crear Nuevo Equipo</h2>
<!-- Implementa el formulario de creación de equipo aquí -->
{% endblock %}',
                'edit.twig' => '{% extends "layout.twig" %}

{% block title %}Editar Equipo{% endblock %}

{% block content %}
<h2>Editar Equipo</h2>
<!-- Implementa el formulario de edición de equipo aquí -->
{% endblock %}',
            ],
            'layout.twig' => '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block title %}Gestión de Activos{% endblock %}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Implementa la barra de navegación aquí -->
    </nav>

    <div class="container mt-4">
        {% block content %}{% endblock %}
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>',
        ],
        'Middleware' => [
            'AuthMiddleware.php' => '<?php
namespace App\Middleware;

use App\Models\User;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        // Implementa la lógica de autenticación aquí
    }
}',
            'PermissionMiddleware.php' => '<?php
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
}',
        ],
        'Helpers' => [
            'CsrfHelper.php' => '<?php
namespace App\Helpers;

class CsrfHelper
{
    public static function generateToken()
    {
        // Implementa la generación de token CSRF aquí
    }

    public static function verifyToken($token)
    {
        // Implementa la verificación de token CSRF aquí
    }
}',
            'Validator.php' => '<?php
namespace App\Helpers;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        // Implementa la lógica de validación aquí
    }

    public function getErrors()
    {
        return $this->errors;
    }
}',
        ],
        'Core' => [
            'Application.php' => '<?php
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
}',
            'Router.php' => '<?php
namespace App\Core;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            "method" => $method,
            "path" => $path,
            "handler" => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        // Implementa la lógica de enrutamiento aquí
    }
}',
        ],
        'Traits' => [
            'HasPermissions.php' => '<?php
namespace App\Traits;

trait HasPermissions
{
    public function hasPermission($permission)
    {
        // Implementa la lógica de verificación de permisos aquí
    }

    // Implementa otros métodos relacionados con permisos aquí
}',
        ],
    ],
    'public' => [
        'index.php' => '<?php
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

$router->dispatch($method, $uri);',
        '.htaccess' => 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]',
    ],
    'composer.json' => '{
    "require": {
        "php": "^7.4|^8.0",
        "illuminate/database": "^8.0",
        "twig/twig": "^3.0",
        "vlucas/phpdotenv": "^5.3",
        "phpmailer/phpmailer": "^6.5"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}',
    '.env.example' => 'APP_NAME="Gestión de Activos"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_activos
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"',
    'install.php' => '<?php
// Implementa el script de instalación aquí
// Este script debe crear la base de datos, las tablas y un usuario administrador inicial
',
];

// Crear la estructura de directorios y archivos
foreach ($structure as $dir => $content) {
    if (is_array($content)) {
        createDir($dir);
        foreach ($content as $file => $fileContent) {
            if (is_array($fileContent)) {
                createDir("$dir/$file");
                foreach ($fileContent as $subFile => $subFileContent) {
                    createFile("$dir/$file/$subFile", $subFileContent);
                }
            } else {
                createFile("$dir/$file", $fileContent);
            }
        }
    } else {
        createFile($dir, $content);
    }
}

echo "Estructura del proyecto creada exitosamente.\n";
