<?php

namespace Erpia\Core;

use Erpia\Model\Usuario;
use Erpia\Model\Permiso;

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function can(string $permiso, bool $abort = true): bool
    {
        self::check();

        $user = $_SESSION['user'] ?? [];
        $ok = in_array($permiso, $user['permisos'] ?? [], true);

        if (!$ok && $abort) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }

        return $ok;
    }

    public static function login(array $usuario): void
    {
        $_SESSION['user'] = $usuario;
    }

    public static function logout(): void
    {
        session_destroy();
    }
}