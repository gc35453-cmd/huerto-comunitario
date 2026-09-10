# Sistema de Gestión de Huerto Comunitario

## Descripción
Sistema web para registrar cultivos, controlar riegos y mostrar alertas de cosecha.

## Tecnologías
- PHP 8.3
- CodeIgniter 4
- MySQL
- Bootstrap 5
- Laragon

## Requisitos
- PHP 8.1 o superior
- Composer
- MySQL
- Laragon o XAMPP

## Instalación y configuración
1. Clonar el repositorio.
2. Ejecutar `composer install`.
3. Copiar `env` como `.env`.
4. Configurar la base `huerto_db`.
5. Ejecutar `php spark migrate`.
6. Ejecutar `php spark serve`.
7. Abrir `http://localhost:8080/`.

## Funcionalidades
- Alta de cultivos.
- Registro de riego.
- Cambio de estado.
- Eliminación de cultivos.
- Alertas de riego y cosecha.

## Estructura MVC
- Modelo: `CultivoModel`.
- Controlador: `Huerto`.
- Vistas: `app/Views/huerto` y `app/Views/layouts`.

## Comandos útiles
```bash
php spark migrate
php spark migrate:status
php spark serve
composer test
```