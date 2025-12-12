<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Proveedor;

class ProveedoresController extends Controller
{
    public function index(): void
    {
        $proveedores = Proveedor::getAll();

        View::render('proveedores/index', [
            'proveedores' => $proveedores,
        ]);
    }

    public function crear(): void
    {
        View::render('proveedores/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));

        $errors = [];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if (!empty($errors)) {
            View::render('proveedores/crear', [
                'errors' => $errors,
                'old' => [
                    'nombre' => $nombre,
                    'email' => $email,
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                ],
            ]);

            return;
        }

        Proveedor::create([
            'nombre' => $nombre,
            'email' => $email !== '' ? $email : null,
            'telefono' => $telefono !== '' ? $telefono : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        header('Location: /proveedores');
        exit;
    }

    public function editar($id): void
    {
        $id = (int) $id;

        $proveedor = Proveedor::findById($id);
        if ($proveedor === null) {
            throw new \RuntimeException('Proveedor no encontrado.');
        }

        View::render('proveedores/editar', [
            'proveedor' => $proveedor,
            'errors' => [],
        ]);
    }

    public function actualizar($id): void
    {
        $id = (int) $id;

        $proveedor = Proveedor::findById($id);
        if ($proveedor === null) {
            throw new \RuntimeException('Proveedor no encontrado.');
        }

        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));

        $errors = [];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if (!empty($errors)) {
            $proveedor['nombre'] = $nombre;
            $proveedor['email'] = $email;
            $proveedor['telefono'] = $telefono;
            $proveedor['direccion'] = $direccion;

            View::render('proveedores/editar', [
                'proveedor' => $proveedor,
                'errors' => $errors,
            ]);

            return;
        }

        Proveedor::update($id, [
            'nombre' => $nombre,
            'email' => $email !== '' ? $email : null,
            'telefono' => $telefono !== '' ? $telefono : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        header('Location: /proveedores');
        exit;
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        Proveedor::delete($id);

        header('Location: /proveedores');
        exit;
    }
}