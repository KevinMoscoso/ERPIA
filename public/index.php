<?php

declare(strict_types=1);

use Erpia\Core\Router;

require dirname(__DIR__) . '/vendor/autoload.php';
session_start();

try {
    $router = new Router();
    $router->dispatch();
} catch (\Throwable $e) {
    http_response_code(500);
    echo 'Application error: ' . $e->getMessage();
}
