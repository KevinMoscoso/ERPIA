<?php

declare(strict_types=1);

namespace Erpia\Core;

abstract class Controller
{
    protected function render(string $viewName, array $data = []): void
    {
        View::render($viewName, $data);
    }
}