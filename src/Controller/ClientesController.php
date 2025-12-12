<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Cliente;

class ClientesController extends Controller
{
    public function index(): void
    {
        $clientes = Cliente::getAll();

        View::render('clientes/index', [
            'clientes' => $clientes,
        ]);
    }

    public function crear(): void
    {
        View::render('clientes/crear', [
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

        if ($email === '') {
            $errors['email'] = 'El email es obligatorio.';
        }

        if (!empty($errors)) {
            View::render('clientes/crear', [
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

        Cliente::create([
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono !== '' ? $telefono : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        header('Location: /clientes');
        exit;
    }

    public function editar($id): void
    {
        $id = (int) $id;
        $cliente = Cliente::findById($id);

        if ($cliente === null) {
            throw new \RuntimeException('Cliente no encontrado.');
        }

        View::render('clientes/editar', [
            'cliente' => $cliente,
            'errors' => [],
        ]);
    }

    public function actualizar($id): void
    {
        $id = (int) $id;
        $cliente = Cliente::findById($id);

        if ($cliente === null) {
            throw new \RuntimeException('Cliente no encontrado.');
        }

        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));

        $errors = [];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if ($email === '') {
            $errors['email'] = 'El email es obligatorio.';
        }

        if (!empty($errors)) {
            $cliente['nombre'] = $nombre;
            $cliente['email'] = $email;
            $cliente['telefono'] = $telefono;
            $cliente['direccion'] = $direccion;

            View::render('clientes/editar', [
                'cliente' => $cliente,
                'errors' => $errors,
            ]);

            return;
        }

        Cliente::update($id, [
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono !== '' ? $telefono : null,
            'direccion' => $direccion !== '' ? $direccion : null,
        ]);

        header('Location: /clientes');
        exit;
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        Cliente::delete($id);

        header('Location: /clientes');
        exit;
    }
}