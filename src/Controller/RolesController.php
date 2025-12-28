<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Auth;
use Erpia\Model\Rol;
use Erpia\Model\Permiso;
use Erpia\Model\Auditoria;

class RolesController
{
    public function index(): void
    {
        Auth::can('roles.gestionar');

        $q = trim((string)($_GET['q'] ?? ''));
        $roles = Rol::getAll();

        if ($q !== '') {
            $roles = array_filter($roles, fn($r) =>
                stripos($r['nombre'], $q) !== false
            );
        }

        View::render('roles/index', [
            'roles' => $roles,
            'q' => $q,
        ]);
    }

    public function crear(): void
    {
        Auth::can('roles.gestionar');

        View::render('roles/crear', [
            'errors' => [],
            'old' => ['nombre' => ''],
        ]);
    }

    public function guardar(): void
    {
        Auth::can('roles.gestionar');

        $nombre = strtoupper(trim((string)($_POST['nombre'] ?? '')));
        if ($nombre === '') {
            View::render('roles/crear', [
                'errors' => ['nombre' => 'El nombre es obligatorio'],
                'old' => ['nombre' => $nombre],
            ]);
            return;
        }

        $rolId = Rol::create(['nombre' => $nombre]);
        if (!$rolId) {
            View::render('roles/crear', [
                'errors' => ['form' => 'No se pudo crear el rol'],
                'old' => ['nombre' => $nombre],
            ]);
            return;
        }

        Auditoria::registrar(Auth::user()['id'], 'crear_rol', 'rol:' . $rolId);
        header('Location: /roles');
        exit;
    }

    public function editar(int $id): void
    {
        Auth::can('roles.gestionar');

        $rol = Rol::findById($id);
        if (!$rol) {
            header('Location: /roles');
            exit;
        }

        View::render('roles/editar', [
            'rol' => $rol,
            'permisos' => Permiso::getAll(),
            'permisosRol' => Permiso::getByRol($id),
            'errors' => [],
        ]);
    }

    public function actualizar(int $id): void
    {
        Auth::can('roles.gestionar');

        $rol = Rol::findById($id);
        if (!$rol) {
            header('Location: /roles');
            exit;
        }

        $nombre = strtoupper(trim((string)($_POST['nombre'] ?? '')));
        $permisos = $_POST['permisos'] ?? [];

        if ($nombre === '') {
            View::render('roles/editar', [
                'rol' => $rol,
                'permisos' => Permiso::getAll(),
                'permisosRol' => Permiso::getByRol($id),
                'errors' => ['nombre' => 'El nombre es obligatorio'],
            ]);
            return;
        }

        Rol::update($id, ['nombre' => $nombre]);
        Permiso::syncRolPermisos($id, array_map('intval', $permisos));

        Auditoria::registrar(Auth::user()['id'], 'editar_rol', 'rol:' . $id);

        header('Location: /roles');
        exit;
    }
}