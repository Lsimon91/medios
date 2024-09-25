Resumen Extendido para Generación de Código de Aplicación de Gestión de Activos

Objetivo: Generar el código fuente completo de una aplicación web para la gestión de activos de una empresa, con enfoque en la seguridad y control de acceso mediante roles y permisos.

Funcionalidades Principales:

Autenticación:

Registro de usuarios con validación de datos (nombre, email, contraseña, rol, sub-empresa opcional).

Inicio de sesión seguro con verificación de credenciales.

Cierre de sesión.

Sistema de recuperación de contraseña.

Gestión de Entidades:

Empresas: CRUD (Crear, Leer, Actualizar, Eliminar) completo.

Sub-empresas: CRUD completo, asociadas a empresas.

Departamentos: CRUD completo, asociados a sub-empresas.

Equipos: CRUD completo, asociados a tipos de equipo y departamentos número de inventario y número de sello de garantia.

Roles y Permisos:

Tres roles predefinidos: Administrador, Operador y Usuario.

Permisos granulares a nivel de entidad y acción (crear, leer, editar, eliminar).

Control de acceso en controladores y vistas basado en los permisos del usuario actual.

Interfaz de Usuario:

Diseño web responsivo utilizando PHP, CSS.

Sistema de plantillas (se recomienda Twig) para facilitar el mantenimiento y la coherencia visual.

Formularios con validación del lado del cliente (HTML5, JavaScript) y del lado del servidor (PHP).

Mensajes de error y éxito claros para el usuario.

Tecnologías:

Lenguaje de programación: PHP.

Base de datos: MySQL.

Sistema de plantillas: Twig.

Librerías adicionales:

Para validación de datos.

Para gestión de roles y permisos.

Estructura de la Base de Datos:


Se requieren las siguientes tablas (con sus respectivos campos):

usuarios: id, nombre, email, contraseña, role_id, sub_empresa_id (opcional).

roles: id, nombre.

roles_permisos: id, role_id, permiso.

empresas: id, nombre.

sub_empresas: id, nombre, empresa_id.

departamentos: id, nombre, sub_empresa_id.

tipos_equipos: id, nombre.

equipos: id, tipo_equipo_id, numero_serie, modelo, marca, fecha_compra, fecha_expiracion_garantia, departamento_id, estado.

Consideraciones Adicionales:

Implementar un sistema de logs para registrar las acciones de los usuarios.

Utilizar un ORM (Object-Relational Mapper) para facilitar la interacción con la base de datos.

Implementar pruebas unitarias y de integración para garantizar la calidad del código.

Documentar el código para facilitar su mantenimiento puedes implementar todo lo que consideres necesario pero bien detallado e implementado 


Conclusión:

Este resumen extendido proporciona una guía detallada para que una IA pueda interpretar los requisitos y generar el código fuente completo de la aplicación de gestión de activos. Se recomienda que la IA tenga en cuenta las mejores prácticas de desarrollo web y seguridad para crear una aplicación robusta, segura y fácil de mantener.
