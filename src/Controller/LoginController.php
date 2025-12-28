<?php

declare(strict_types=1);

namespace Erpia\Controller;

class LoginController
{
    public function index(): void
    {
        // /login  -> LoginController@index
        // Redirige a /auth/login
        header('Location: /auth/login');
        exit;
    }
}