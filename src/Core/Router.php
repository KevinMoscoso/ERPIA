<?php

declare(strict_types=1);

namespace Erpia\Core;

class Router
{
    public function dispatch(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = trim((string) parse_url($requestUri, PHP_URL_PATH), '/');

        $segments = $path === '' ? [] : explode('/', $path);

        $controllerSegment = $segments[0] ?? 'home';
        $actionSegment     = $segments[1] ?? 'index';
        $params            = array_slice($segments, 2);

        $controllerClass = 'Erpia\\Controller\\' . ucfirst($controllerSegment) . 'Controller';

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException('Controller not found: ' . $controllerClass);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionSegment)) {
            throw new \RuntimeException(
                'Action not found: ' . $actionSegment . ' in ' . $controllerClass
            );
        }

        call_user_func_array([$controller, $actionSegment], $params);
    }
}