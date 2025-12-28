<?php

declare(strict_types=1);

namespace Erpia\Core;

class View
{
    public static function render(string $viewName, array $data = []): void
    {
        $basePath = dirname(__DIR__) . '/View';
        $viewPath = trim($viewName, '/');
        $filePath = $basePath . '/' . $viewPath . '.php';

        if (!file_exists($filePath)) {
            throw new \RuntimeException('View not found: ' . $filePath);
        }   

        // Variables para la vista
        if (!empty($data)) {
            extract($data, EXTR_SKIP);
        }

        /**
        * Vistas que NO usan layout (login, auth, etc.)
        */
        $viewsSinLayout = [
            'auth/login',
        ];

        if (in_array($viewPath, $viewsSinLayout, true)) {
            require $filePath;
            return;
        }

        /**
        * Renderizar vista en buffer
        */
        ob_start();
        require $filePath;
        $content = ob_get_clean();

        /**
        * Cargar layout principal
        */
        $layoutPath = $basePath . '/layout/app.php';

        if (!file_exists($layoutPath)) {
            throw new \RuntimeException('Layout not found: ' . $layoutPath);
        }

        require $layoutPath;
    }
}