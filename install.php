<?php

// Verificar que PHP CLI esté instalado
if (php_sapi_name() !== 'cli') {
    die("Este script debe ser ejecutado desde la línea de comandos.\n");
}

// Función para solicitar entrada del usuario
function prompt($message) {
    echo $message . ": ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim($line);
}

// Verificar requisitos
$requirements = [
    'PHP' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'PDO' => extension_loaded('pdo'),
    'MySQL' => extension_loaded('pdo_mysql'),
];

$allRequirementsMet = true;
foreach ($requirements as $requirement => $met) {
    if (!$met) {
        echo "Error: $requirement no está instalado o no cumple con los requisitos mínimos.\n";
        $allRequirementsMet = false;
    }
}

if (!$allRequirementsMet) {
    die("Por favor, instale los requisitos faltantes y vuelva a ejecutar este script.\n");
}

// Solicitar información de la base de datos
$dbHost = prompt("Ingrese el host de la base de datos (por defecto: localhost)") ?: 'localhost';
$dbName = prompt("Ingrese el nombre de la base de datos");
$dbUser = prompt("Ingrese el usuario de la base de datos");
$dbPass = prompt("Ingrese la contraseña de la base de datos");

// Crear archivo .env
$envContent = <<<EOT
APP_NAME="Gestión de Activos"
APP_ENV=local
APP_KEY=base64:${base64_encode(random_bytes(32))}
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=$dbHost
DB_PORT=3306
DB_DATABASE=$dbName
DB_USERNAME=$dbUser
DB_PASSWORD=$dbPass

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
EOT;

file_put_contents('.env', $envContent);

// Instalar dependencias con Composer
echo "Instalando dependencias con Composer...\n";
passthru('composer install');

// Crear tablas en la base de datos
echo "Creando tablas en la base de datos...\n";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents('database/schema.sql');
    $pdo->exec($sql);
    echo "Tablas creadas exitosamente.\n";

    // Insertar roles y permisos básicos
    $roles = [
        ['name' => 'Administrador'],
        ['name' => 'Operador'],
        ['name' => 'Usuario']
    ];

    $permissions = [
        ['name' => 'Ver empresas', 'slug' => 'view-empresas'],
        ['name' => 'Crear empresas', 'slug' => 'create-empresas'],
        ['name' => 'Editar empresas', 'slug' => 'edit-empresas'],
        ['name' => 'Eliminar empresas', 'slug' => 'delete-empresas'],
        ['name' => 'Ver sub-empresas', 'slug' => 'view-sub-empresas'],
        ['name' => 'Crear sub-empresas', 'slug' => 'create-sub-empresas'],
        ['name' => 'Editar sub-empresas', 'slug' => 'edit-sub-empresas'],
        ['name' => 'Eliminar sub-empresas', 'slug' => 'delete-sub-empresas'],
        ['name' => 'Ver departamentos', 'slug' => 'view-departamentos'],
        ['name' => 'Crear departamentos', 'slug' => 'create-departamentos'],
        ['name' => 'Editar departamentos', 'slug' => 'edit-departamentos'],
        ['name' => 'Eliminar departamentos', 'slug' => 'delete-departamentos'],
        ['name' => 'Ver equipos', 'slug' => 'view-equipos'],
        ['name' => 'Crear equipos', 'slug' => 'create-equipos'],
        ['name' => 'Editar equipos', 'slug' => 'edit-equipos'],
        ['name' => 'Eliminar equipos', 'slug' => 'delete-equipos']
    ];

    $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
    foreach ($roles as $role) {
        $stmt->execute($role);
    }

    $stmt = $pdo->prepare("INSERT INTO permissions (name, slug) VALUES (:name, :slug)");
    foreach ($permissions as $permission) {
        $stmt->execute($permission);
    }

    // Asignar todos los permisos al rol de Administrador
    $adminRoleId = $pdo->query("SELECT id FROM roles WHERE name = 'Administrador'")->fetchColumn();
    $permissionIds = $pdo->query("SELECT id FROM permissions")->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $pdo->prepare("INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)");
    foreach ($permissionIds as $permissionId) {
        $stmt->execute(['role_id' => $adminRoleId, 'permission_id' => $permissionId]);
    }

    echo "Roles y permisos básicos insertados exitosamente.\n";

    // Crear usuario administrador
    $adminName = prompt("Ingrese el nombre del usuario administrador");
    $adminEmail = prompt("Ingrese el correo electrónico del usuario administrador");
    $adminPassword = password_hash(prompt("Ingrese la contraseña del usuario administrador"), PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)");
    $stmt->execute([
        'name' => $adminName,
        'email' => $adminEmail,
        'password' => $adminPassword,
        'role_id' => $adminRoleId
    ]);

    echo "Usuario administrador creado exitosamente.\n";

} catch (PDOException $e) {
    die("Error al crear las tablas: " . $e->getMessage() . "\n");
}

echo "Instalación completada con éxito.\n";
