-- Tabla de roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de permisos
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla pivote para la relación muchos a muchos entre roles y permisos
CREATE TABLE IF NOT EXISTS role_permission (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    sub_empresa_id INT,
    password_reset_token VARCHAR(255),
    password_reset_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);

-- Tabla de empresas
CREATE TABLE IF NOT EXISTS empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de sub-empresas
CREATE TABLE IF NOT EXISTS sub_empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    empresa_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

-- Tabla de departamentos
CREATE TABLE IF NOT EXISTS departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sub_empresa_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sub_empresa_id) REFERENCES sub_empresas(id) ON DELETE CASCADE
);

-- Tabla de tipos de equipo
CREATE TABLE IF NOT EXISTS tipos_equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de equipos
CREATE TABLE IF NOT EXISTS equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_equipo_id INT,
    numero_serie VARCHAR(255) NOT NULL,
    modelo VARCHAR(255) NOT NULL,
    marca VARCHAR(255) NOT NULL,
    fecha_compra DATE NOT NULL,
    fecha_expiracion_garantia DATE NOT NULL,
    departamento_id INT,
    estado ENUM('Activo', 'Inactivo', 'En mantenimiento') NOT NULL,
    numero_inventario VARCHAR(255) NOT NULL,
    numero_sello_garantia VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_equipo_id) REFERENCES tipos_equipo(id) ON DELETE SET NULL,
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL
);

-- Tabla de mantenimientos
CREATE TABLE IF NOT EXISTS mantenimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipo_id INT,
    fecha_mantenimiento DATE NOT NULL,
    descripcion TEXT,
    responsable VARCHAR(255) NOT NULL,
    costo DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);

-- Tabla de historial de asignaciones
CREATE TABLE IF NOT EXISTS historial_asignaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipo_id INT,
    departamento_id INT,
    fecha_asignacion DATE NOT NULL,
    fecha_desasignacion DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE,
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL
);

-- Índices para mejorar el rendimiento de las consultas
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_equipos_numero_serie ON equipos(numero_serie);
CREATE INDEX idx_equipos_numero_inventario ON equipos(numero_inventario);
CREATE INDEX idx_mantenimientos_fecha ON mantenimientos(fecha_mantenimiento);
CREATE INDEX idx_historial_asignaciones_fechas ON historial_asignaciones(fecha_asignacion, fecha_desasignacion);
