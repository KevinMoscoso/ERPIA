<?php

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Auth;
use Erpia\Model\Usuario;
use Erpia\Model\Permiso;

class AuthController
{
    public function login(): void
    {
        View::render('auth/login');
    }

    public function authenticate(): void
    {
        $user = Usuario::findByEmail($_POST['email']);

        if (!$user || !password_verify($_POST['password'], $user['password'])) {
            View::render('auth/login', ['error' => 'Credenciales inválidas']);
            return;
        }

        $permisos = Permiso::getPermisosByRol($user['rol_id']);

        Auth::login([
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'rol_id' => $user['rol_id'],
            'permisos' => $permisos,
        ]);

        header('Location: /');
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /login');
    }
}