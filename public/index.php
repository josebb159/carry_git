<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = '/home/carriroa/laravel_app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require '/home/carriroa/laravel_app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once '/home/carriroa/laravel_app/bootstrap/app.php')
    ->handleRequest(Request::capture());

