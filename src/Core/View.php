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

        if (!empty($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        require $filePath;
    }
}
