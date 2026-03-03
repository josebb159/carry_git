<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// En producción (cPanel), web root y Laravel root están separados.
// LARAVEL_APP_PATH se define en .htaccess para apuntar a la carpeta correcta.
// En local/desarrollo usa el comportamiento estándar (__DIR__/../).
$laravelBase = getenv('LARAVEL_APP_PATH') ?: realpath(__DIR__ . '/..');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelBase . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelBase . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $laravelBase . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
