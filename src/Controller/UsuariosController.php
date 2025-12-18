<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Auth;
use Erpia\Model\Usuario;
use Erpia\Model\Rol;
use Erpia\Model\Auditoria;

class UsuariosController
{
    public function index(): void
    {
        Auth::can('usuarios.gestionar');

        // 🔍 búsqueda simple por nombre o email
        $q = trim((string)($_GET['q'] ?? ''));

        // ⚠️ IMPORTANTE:
        // - Si $q está vacío → pasamos NULL
        // - Si tiene contenido → pasamos el string
        $usuarios = Usuario::getAll($q !== '' ? $q : null);

        View::render('usuarios/index', [
            'usuarios' => $usuarios,
            'q'        => $q,
            'flash'    => $_GET['msg'] ?? null,
            'error'    => $_GET['error'] ?? null,
        ]);
    }

    public function crear(): void
    {
        Auth::can('usuarios.gestionar');

        $roles = Rol::getAll();

        View::render('usuarios/crear', [
            'roles' => $roles,
            'errors' => [],
            'old' => [
                'nombre' => '',
                'email' => '',
                'rol_id' => '',
                'activo' => '1',
            ],
        ]);
    }

    public function guardar(): void
    {
        Auth::can('usuarios.gestionar');

        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');
        $rolId = (int)($_POST['rol_id'] ?? 0);
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;

        $roles = Rol::getAll();

        $errors = [];
        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        } elseif (Usuario::emailExists($email, null)) {
            $errors['email'] = 'Este email ya está registrado.';
        }
        if (mb_strlen($password) < 8) {
            $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($rolId <= 0) {
            $errors['rol_id'] = 'Seleccione un rol.';
        }

        $old = [
            'nombre' => $nombre,
            'email' => $email,
            'rol_id' => (string)$rolId,
            'activo' => (string)($activo ? 1 : 0),
        ];

        if (!empty($errors)) {
            View::render('usuarios/crear', [
                'roles' => $roles,
                'errors' => $errors,
                'old' => $old,
            ]);
            return;
        }

        $ok = Usuario::create([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'rol_id' => $rolId,
            'activo' => $activo ? 1 : 0,
        ]);

        if (!$ok) {
            View::render('usuarios/crear', [
                'roles' => $roles,
                'errors' => ['form' => 'No se pudo crear el usuario.'],
                'old' => $old,
            ]);
            return;
        }

        if (class_exists(Auditoria::class) && Auth::user() && isset(Auth::user()['id'])) {
            Auditoria::registrar((int)Auth::user()['id'], 'crear_usuario', 'email:' . $email);
        }

        header('Location: /usuarios?msg=creado');
        exit;
    }

    public function editar(int $id): void
    {
        Auth::can('usuarios.gestionar');

        $usuario = Usuario::findById($id);
        if ($usuario === null) {
            header('Location: /usuarios?error=no_encontrado');
            exit;
        }

        $roles = Rol::getAll();

        View::render('usuarios/editar', [
            'usuario' => $usuario,
            'roles' => $roles,
            'errors' => [],
        ]);
    }

    public function actualizar(int $id): void
    {
        Auth::can('usuarios.gestionar');

        $usuario = Usuario::findById($id);
        if ($usuario === null) {
            header('Location: /usuarios?error=no_encontrado');
            exit;
        }

        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $rolId = (int)($_POST['rol_id'] ?? 0);
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;
        $password = (string)($_POST['password'] ?? '');

        $roles = Rol::getAll();

        $errors = [];
        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        } elseif (Usuario::emailExists($email, $id)) {
            $errors['email'] = 'Este email ya está registrado.';
        }
        if ($rolId <= 0) {
            $errors['rol_id'] = 'Seleccione un rol.';
        }
        if ($password !== '' && mb_strlen($password) < 8) {
            $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if (!empty($errors)) {
            $usuario['nombre'] = $nombre;
            $usuario['email'] = $email;
            $usuario['rol_id'] = $rolId;
            $usuario['activo'] = $activo ? 1 : 0;

            View::render('usuarios/editar', [
                'usuario' => $usuario,
                'roles' => $roles,
                'errors' => $errors,
            ]);
            return;
        }

        $ok = Usuario::update($id, [
            'nombre' => $nombre,
            'email' => $email,
            'rol_id' => $rolId,
            'activo' => $activo ? 1 : 0,
        ]);

        if (!$ok) {
            View::render('usuarios/editar', [
                'usuario' => $usuario,
                'roles' => $roles,
                'errors' => ['form' => 'No se pudo actualizar el usuario.'],
            ]);
            return;
        }

        if ($password !== '') {
            Usuario::updatePassword($id, $password);
        }

        if (class_exists(Auditoria::class) && Auth::user() && isset(Auth::user()['id'])) {
            Auditoria::registrar((int)Auth::user()['id'], 'editar_usuario', 'usuario:' . $id);
        }

        header('Location: /usuarios?msg=actualizado');
        exit;
    }

    public function toggle(int $id): void
    {
        Auth::can('usuarios.gestionar');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /usuarios?error=metodo');
            exit;
        }

        $usuario = Usuario::findById($id);
        if ($usuario === null) {
            header('Location: /usuarios?error=no_encontrado');
            exit;
        }

        $ok = Usuario::toggleActivo($id);
        if (!$ok) {
            header('Location: /usuarios?error=toggle');
            exit;
        }

        if (class_exists(Auditoria::class) && Auth::user() && isset(Auth::user()['id'])) {
            Auditoria::registrar((int)Auth::user()['id'], 'toggle_usuario', 'usuario:' . $id);
        }

        header('Location: /usuarios?msg=toggle');
        exit;
    }
}