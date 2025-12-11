<?php

declare(strict_types=1);

namespace Erpia\Core;

class Router
{
    public function dispatch(): void
    {
        $controllerSegment = 'home';
        $actionSegment = 'index';
        $params = [];

        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = trim((string) $path, '/');

        if ($path !== '') {
            $segments = explode('/', $path);

            if (!empty($segments[0]) && $segments[0] === 'index.php') {
                array_shift($segments);
            }

            if (isset($segments[0]) && $segments[0] !== '') {
                $controllerSegment = $segments[0];
            }

            if (isset($segments[1]) && $segments[1] !== '') {
                $actionSegment = $segments[1];
            }

            if (count($segments) > 2) {
                $params = array_slice($segments, 2);
            }
        }

        $controllerName = ucfirst($controllerSegment) . 'Controller';
        $controllerClass = 'Erpia\\Controller\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException('Controller not found: ' . $controllerClass);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionSegment)) {
            throw new \RuntimeException('Action not found: ' . $actionSegment . ' in ' . $controllerClass);
        }

        call_user_func_array([$controller, $actionSegment], $params);
    }
}
